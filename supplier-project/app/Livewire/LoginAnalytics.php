<?php

namespace App\Livewire;

use App\Models\LoginLog;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;

class LoginAnalytics extends Component
{
    public string $selectedDate   = '';
    public string $filterShift    = '';   // '', 'day', 'night', 'none'
    public string $filterRole     = '';
    public int    $perPage        = 15;
    public int    $currentPage    = 1;

    public function mount(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $this->selectedDate = now()->toDateString();
    }

    // ── Pagination ──
    public function nextPage(): void
    {
        $this->currentPage++;
    }

    public function prevPage(): void
    {
        if ($this->currentPage > 1) $this->currentPage--;
    }

    // ── Reset page on filter change ──
    public function updatedSelectedDate(): void  { $this->currentPage = 1; }
    public function updatedFilterShift(): void   { $this->currentPage = 1; }
    public function updatedFilterRole(): void    { $this->currentPage = 1; }

    // ── Stats ──
    private function baseQuery()
    {
        return LoginLog::query()
            ->with('user')
            ->whereDate('logged_in_at', $this->selectedDate)
            ->when($this->filterShift !== '', function ($q) {
                if ($this->filterShift === 'none') {
                    return $q->whereNull('shift');
                }
                return $q->where('shift', $this->filterShift);
            })
            ->when($this->filterRole !== '', fn($q) => $q->where('role', $this->filterRole));
    }

    public function render()
    {
        $date = Carbon::parse($this->selectedDate);

        // ── Summary Stats ──
        $allToday = LoginLog::whereDate('logged_in_at', $this->selectedDate);

        $stats = [
            'total'       => (clone $allToday)->count(),
            'day_shift'   => (clone $allToday)->where('shift', 'day')->count(),
            'night_shift' => (clone $allToday)->where('shift', 'night')->count(),
            'admins'      => (clone $allToday)->where('role', 'admin')->count(),
            'no_shift'    => (clone $allToday)->whereNull('shift')->count(),
        ];

        // ── Hourly breakdown (for the bar chart data) ──
        $hourly = LoginLog::whereDate('logged_in_at', $this->selectedDate)
            ->get()
            ->groupBy(fn($log) => Carbon::parse($log->logged_in_at)->timezone('Asia/Colombo')->format('H'))
            ->map->count()
            ->toArray();

        // ── Role breakdown ──
        $roleBreakdown = LoginLog::whereDate('logged_in_at', $this->selectedDate)
            ->selectRaw('role, count(*) as cnt')
            ->groupBy('role')
            ->pluck('cnt', 'role')
            ->toArray();

        // ── Filtered log rows (paginated) ──
        $query  = $this->baseQuery()->latest('logged_in_at');
        $total  = $query->count();
        $logs   = $query->skip(($this->currentPage - 1) * $this->perPage)
                        ->take($this->perPage)
                        ->get();

        $totalPages = (int) ceil($total / $this->perPage);

        // ── All distinct roles for filter dropdown ──
        $roles = LoginLog::distinct()->pluck('role')->filter()->sort()->values();

        return view('livewire.login-analytics', compact(
            'stats', 'hourly', 'roleBreakdown', 'logs', 'total', 'totalPages', 'roles', 'date'
        ))->layout('layouts.app');
    }
}