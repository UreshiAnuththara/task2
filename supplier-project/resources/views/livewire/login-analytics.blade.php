{{--
    resources/views/livewire/login-analytics.blade.php
    Login Analytics — Livewire page (no page reload)
--}}

<div class="p-6 space-y-6" style="font-family:'Figtree',sans-serif;">

    {{-- ══ Header ══ --}}
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div>
            <h1 style="font-size:22px;font-weight:800;color:#0f172a;margin:0;">Login Analytics</h1>
            <p style="font-size:13px;color:#64748b;margin:4px 0 0;">Daily login activity — shift & role breakdown</p>
        </div>
        <div style="font-size:12px;color:#94a3b8;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:6px 14px;">
            {{ $date->format('l, d M Y') }}
        </div>
    </div>

    {{-- ══ Filters ══ --}}
    <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">

        {{-- Date --}}
        <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.8px;margin-bottom:5px;">Date</label>
            <input wire:model.live="selectedDate" type="date"
                style="padding:8px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;color:#0f172a;background:#fff;outline:none;cursor:pointer;"
                onfocus="this.style.borderColor='#2563eb'" onblur="this.style.borderColor='#e2e8f0'">
        </div>

        {{-- Shift --}}
        <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.8px;margin-bottom:5px;">Shift</label>
            <select wire:model.live="filterShift"
                style="padding:8px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;color:#0f172a;background:#fff;outline:none;cursor:pointer;min-width:130px;">
                <option value="">All Shifts</option>
                <option value="day">Day Shift</option>
                <option value="night">Night Shift</option>
                <option value="none">No Restriction</option>
            </select>
        </div>

        {{-- Role --}}
        <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.8px;margin-bottom:5px;">Role</label>
            <select wire:model.live="filterRole"
                style="padding:8px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;color:#0f172a;background:#fff;outline:none;cursor:pointer;min-width:140px;">
                <option value="">All Roles</option>
                @foreach($roles as $r)
                    <option value="{{ $r }}">{{ ucfirst($r) }}</option>
                @endforeach
            </select>
        </div>

        {{-- Clear --}}
        @if($filterShift || $filterRole)
        <button wire:click="$set('filterShift', ''); $set('filterRole', '');"
            style="padding:8px 14px;background:#fee2e2;color:#dc2626;border:none;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;">
            ✕ Clear Filters
        </button>
        @endif

    </div>

    {{-- ══ Summary Cards ══ --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px;">

        @php
        $cards = [
            ['label'=>'Total Logins',    'value'=>$stats['total'],       'color'=>'#2563eb', 'bg'=>'#eff6ff', 'icon'=>'👤'],
            ['label'=>'Day Shift',        'value'=>$stats['day_shift'],   'color'=>'#d97706', 'bg'=>'#fffbeb', 'icon'=>'☀️'],
            ['label'=>'Night Shift',      'value'=>$stats['night_shift'], 'color'=>'#7c3aed', 'bg'=>'#f5f3ff', 'icon'=>'🌙'],
            ['label'=>'Admins',           'value'=>$stats['admins'],      'color'=>'#dc2626', 'bg'=>'#fef2f2', 'icon'=>'🛡️'],
            ['label'=>'No Restriction',   'value'=>$stats['no_shift'],    'color'=>'#059669', 'bg'=>'#ecfdf5', 'icon'=>'∞'],
        ];
        @endphp

        @foreach($cards as $c)
        <div style="background:{{ $c['bg'] }};border:1px solid color-mix(in srgb, {{ $c['color'] }} 20%, transparent);border-radius:12px;padding:16px 18px;">
            <div style="font-size:22px;margin-bottom:6px;">{{ $c['icon'] }}</div>
            <div style="font-size:26px;font-weight:800;color:{{ $c['color'] }};">{{ $c['value'] }}</div>
            <div style="font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.6px;margin-top:2px;">{{ $c['label'] }}</div>
        </div>
        @endforeach

    </div>

    {{-- ══ Charts Row ══ --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

        {{-- Hourly Bar Chart --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:20px;">
            <div style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:16px;">Logins by Hour</div>
            @php
                $maxHourly = max(array_values($hourly) ?: [1]);
            @endphp
            <div style="display:flex;align-items:flex-end;gap:4px;height:100px;">
                @for($h = 0; $h < 24; $h++)
                    @php
                        $hStr  = str_pad($h, 2, '0', STR_PAD_LEFT);
                        $count = $hourly[$hStr] ?? 0;
                        $pct   = $maxHourly > 0 ? round(($count / $maxHourly) * 100) : 0;
                        $isDayShift   = $h >= 8  && $h < 18;
                        $isNightShift = $h >= 18 || $h < 8;
                        $barColor = $isDayShift ? '#fbbf24' : '#818cf8';
                    @endphp
                    <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:2px;" title="{{ $hStr }}:00 — {{ $count }} login(s)">
                        <div style="width:100%;background:{{ $count > 0 ? $barColor : '#f1f5f9' }};border-radius:3px 3px 0 0;height:{{ max($pct, $count > 0 ? 6 : 2) }}px;transition:height .3s;"></div>
                        @if($h % 6 === 0)
                        <div style="font-size:9px;color:#94a3b8;">{{ $hStr }}</div>
                        @endif
                    </div>
                @endfor
            </div>
            <div style="display:flex;gap:14px;margin-top:10px;">
                <span style="font-size:10px;color:#64748b;display:flex;align-items:center;gap:4px;"><span style="width:10px;height:10px;background:#fbbf24;border-radius:2px;display:inline-block;"></span>Day (8–18)</span>
                <span style="font-size:10px;color:#64748b;display:flex;align-items:center;gap:4px;"><span style="width:10px;height:10px;background:#818cf8;border-radius:2px;display:inline-block;"></span>Night (18–8)</span>
            </div>
        </div>

        {{-- Role Breakdown --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:20px;">
            <div style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:16px;">Logins by Role</div>
            @php
                $roleColors = ['admin'=>'#dc2626','Production'=>'#2563eb','HR'=>'#7c3aed','Accounting'=>'#059669','Logistics'=>'#d97706','Sales'=>'#0891b2','IT'=>'#c026d3'];
                $maxRole = max(array_values($roleBreakdown) ?: [1]);
            @endphp
            @if(empty($roleBreakdown))
                <div style="color:#94a3b8;font-size:13px;text-align:center;padding:24px 0;">No logins recorded</div>
            @else
            <div style="display:flex;flex-direction:column;gap:10px;">
                @foreach($roleBreakdown as $role => $cnt)
                @php
                    $pct   = $maxRole > 0 ? round(($cnt / $maxRole) * 100) : 0;
                    $color = $roleColors[$role] ?? '#64748b';
                @endphp
                <div>
                    <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px;">
                        <span style="font-weight:600;color:#374151;">{{ ucfirst($role) }}</span>
                        <span style="color:{{ $color }};font-weight:700;">{{ $cnt }}</span>
                    </div>
                    <div style="height:8px;background:#f1f5f9;border-radius:99px;overflow:hidden;">
                        <div style="height:100%;width:{{ $pct }}%;background:{{ $color }};border-radius:99px;transition:width .4s;"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>

    {{-- ══ Detailed Log Table ══ --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">

        <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;">
            <div style="font-size:13px;font-weight:700;color:#0f172a;">Login Records</div>
            <div style="font-size:12px;color:#94a3b8;">{{ $total }} record(s)</div>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                        <th style="padding:10px 16px;text-align:left;font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.8px;">User</th>
                        <th style="padding:10px 16px;text-align:left;font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.8px;">Role</th>
                        <th style="padding:10px 16px;text-align:left;font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.8px;">Shift</th>
                        <th style="padding:10px 16px;text-align:left;font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.8px;">Logged In At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    @php
                        $shiftBadge = match($log->shift) {
                            'day'   => ['label'=>'Day Shift',  'bg'=>'#fffbeb','color'=>'#d97706'],
                            'night' => ['label'=>'Night Shift','bg'=>'#f5f3ff','color'=>'#7c3aed'],
                            default => ['label'=>'Unrestricted','bg'=>'#f0fdf4','color'=>'#059669'],
                        };
                        $roleColor = $roleColors[$log->role] ?? '#64748b';
                    @endphp
                    <tr style="border-bottom:1px solid #f8fafc;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:12px 16px;">
                            <div style="font-weight:600;color:#0f172a;">{{ $log->user?->name ?? '—' }}</div>
                            <div style="font-size:11px;color:#94a3b8;">{{ $log->user?->email ?? '' }}</div>
                        </td>
                        <td style="padding:12px 16px;">
                            <span style="padding:3px 10px;background:{{ $roleColor }}18;color:{{ $roleColor }};border-radius:99px;font-size:11px;font-weight:700;">
                                {{ ucfirst($log->role ?? '—') }}
                            </span>
                        </td>
                        <td style="padding:12px 16px;">
                            <span style="padding:3px 10px;background:{{ $shiftBadge['bg'] }};color:{{ $shiftBadge['color'] }};border-radius:99px;font-size:11px;font-weight:700;">
                                {{ $shiftBadge['label'] }}
                            </span>
                        </td>
                        <td style="padding:12px 16px;color:#374151;">
                            {{ \Carbon\Carbon::parse($log->logged_in_at)->timezone('Asia/Colombo')->format('h:i A') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding:40px;text-align:center;color:#94a3b8;">
                            No login records found for the selected filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($totalPages > 1)
        <div style="padding:14px 20px;border-top:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;">
            <button wire:click="prevPage" @if($currentPage <= 1) disabled @endif
                style="padding:6px 14px;background:#f1f5f9;color:#374151;border:none;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;opacity:{{ $currentPage <= 1 ? '0.4' : '1' }};">
                ← Prev
            </button>
            <span style="font-size:12px;color:#64748b;">Page {{ $currentPage }} of {{ $totalPages }}</span>
            <button wire:click="nextPage" @if($currentPage >= $totalPages) disabled @endif
                style="padding:6px 14px;background:#f1f5f9;color:#374151;border:none;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;opacity:{{ $currentPage >= $totalPages ? '0.4' : '1' }};">
                Next →
            </button>
        </div>
        @endif

    </div>

    {{-- Loading overlay --}}
    <div wire:loading.flex style="position:fixed;inset:0;background:rgba(255,255,255,0.5);z-index:50;align-items:center;justify-content:center;">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:20px 32px;display:flex;align-items:center;gap:12px;box-shadow:0 8px 24px rgba(0,0,0,0.08);">
            <div style="width:18px;height:18px;border:2.5px solid #e2e8f0;border-top-color:#2563eb;border-radius:50%;animation:spin 0.7s linear infinite;"></div>
            <span style="font-size:13px;font-weight:600;color:#374151;">Loading...</span>
        </div>
    </div>

    <style>@keyframes spin { to { transform: rotate(360deg); } }</style>

</div>