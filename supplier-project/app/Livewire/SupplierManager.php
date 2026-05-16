<?php

namespace App\Livewire;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;

class SupplierManager extends Component
{
    use WithPagination;

    // UI state
    public bool $showModal      = false;
    public bool $isEditing      = false;
    public ?int $editingId      = null;
    public bool $showPrint      = false;

    // Delete Modal state
    public bool    $showDeleteModal       = false;
    public ?int    $deletingId            = null;
    public string  $deletingSupplierName  = '';

    // Search & Form fields
    public string $search  = '';
    public string $name    = '';
    public string $email   = '';
    public string $phone   = '';
    public string $address = '';

    protected string $paginationTheme = 'tailwind';

    // ── Validation Rules ──────────────────────────────────────────────────────
    protected function rules(): array
    {
        return [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:suppliers,email,' . $this->editingId,
            'phone'   => 'required|string|max:20',
            'address' => 'required|string',
        ];
    }

    protected array $messages = [
        'email.unique' => 'This email is already registered.',
        'email.email'  => 'Please enter a valid email address.',
    ];

    // ── Lifecycle ─────────────────────────────────────────────────────────────
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // ── CRUD Modal ────────────────────────────────────────────────────────────
    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $s = Supplier::findOrFail($id);
        $this->editingId = $s->id;
        $this->name      = $s->name;
        $this->email     = $s->email;
        $this->phone     = $s->phone;
        $this->address   = $s->address;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate();

        Supplier::updateOrCreate(['id' => $this->editingId], [
            'name'    => $this->name,
            'email'   => $this->email,
            'phone'   => $this->phone,
            'address' => $this->address,
        ]);

        session()->flash('success', $this->isEditing
            ? 'Supplier updated successfully.'
            : 'Supplier created successfully.');

        $this->closeModal();
    }

    // ── Delete Modal ──────────────────────────────────────────────────────────
    public function openDeleteModal(int $id): void
    {
        $supplier = Supplier::findOrFail($id);
        $this->deletingId           = $supplier->id;
        $this->deletingSupplierName = $supplier->name;
        $this->showDeleteModal      = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal      = false;
        $this->deletingId           = null;
        $this->deletingSupplierName = '';
    }

    public function confirmDelete(): void
    {
        if ($this->deletingId) {
            Supplier::findOrFail($this->deletingId)->delete();
            session()->flash('success', 'Supplier deleted successfully.');
            $this->closeDeleteModal();
        }
    }

    // ── Print (Task 04) ───────────────────────────────────────────────────────
    /**
     * Livewire sets $showPrint = true so the print overlay renders,
     * then dispatches the 'open-print-dialog' browser event.
     * The blade listener calls window.print() once Livewire finishes the DOM update.
     */
    public function openPrint(): void
    {
        $this->showPrint = true;
        $this->dispatch('open-print-dialog');
    }

    public function closePrint(): void
    {
        $this->showPrint = false;
    }

    // ── Render ────────────────────────────────────────────────────────────────
    public function render()
    {
        $suppliers = Supplier::query()
            ->when($this->search, fn($q) =>
                $q->where('name',  'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
            )
            ->latest()
            ->paginate(10);

        // All records for the print overlay
        $allSuppliers = $this->showPrint
            ? Supplier::orderBy('name')->get()
            : collect();

        return view('livewire.supplier-manager', [
            'suppliers'    => $suppliers,
            'allSuppliers' => $allSuppliers,
            'printedBy'    => auth()->user()->name ?? 'Guest User',
            'printedAt'    => now()->format('d M Y, h:i A'),
        ])->layout('layouts.app');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────
    private function resetForm(): void
    {
        $this->name      = '';
        $this->email     = '';
        $this->phone     = '';
        $this->address   = '';
        $this->editingId = null;
        $this->isEditing = false;
    }
}