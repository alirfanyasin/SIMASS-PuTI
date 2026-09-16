@php
    if (request()->routeIs('presence.*') || request()->routeIs('holiday.*') || request()->routeIs('export-pdf')) {
        session(['active_module' => 'Presensi']);
    } elseif (request()->routeIs('ticket.*')) {
        session(['active_module' => 'Ticketing']);
    } elseif (request()->routeIs('inventory.*')) {
        session(['active_module' => 'Inventaris']);
    }

    $currentApp = session('active_module', 'Presensi');

    $menuGroups = [
        'Presensi' => [
            ['icon' => 'layout-grid', 'item' => 'Dashboard', 'route' => 'presence.dashboard', 'permission' => 'view-presence'],
            ['icon' => 'check-square', 'item' => 'Presensi', 'route' => 'presence.index', 'permission' => 'create-presence'],
            ['icon' => 'file-text', 'item' => 'Daftar Presensi', 'route' => 'presence.list', 'permission' => 'view-presence'],
            ['icon' => 'clock', 'item' => 'Overtime', 'route' => 'presence.overtime', 'permission' => 'manage-overtime'],
            ['icon' => 'history', 'item' => 'History Presensi', 'route' => 'presence.history', 'permission' => 'view-presence-history'],
            ['icon' => 'users', 'item' => 'Student Staff', 'route' => 'presence.student-staff', 'permission' => 'manage-presence'],
            ['icon' => 'calendar', 'item' => 'Kalender', 'route' => 'presence.calendar', 'permission' => 'view-presence'],
            ['icon' => 'calendar-off', 'item' => 'Hari Libur', 'route' => 'holiday.index', 'permission' => 'manage-holiday'],
        ],
        'Ticketing' => [
            ['icon' => 'ticket', 'item' => 'Daftar Tiket', 'route' => 'ticket.index'],
            ['icon' => 'plus-circle', 'item' => 'Pengajuan Tiket', 'route' => 'ticket.create'],
            ['icon' => 'user-check', 'item' => 'Tiket Saya', 'route' => 'ticket.my-tickets'],
            ['icon' => 'clipboard-list', 'item' => 'Task Management', 'route' => 'ticket.tasks'],
            ['icon' => 'history', 'item' => 'History Tiket', 'route' => 'ticket.history'],
            ['icon' => 'bot', 'item' => 'Luna AI', 'route' => 'ticket.luna'],
        ],
        'Lainnya' => [
            ['icon' => 'shield-check', 'item' => 'Role & Permission', 'route' => 'role-permission', 'permission' => 'manage-roles'],
            ['icon' => 'user', 'item' => 'Profil', 'route' => 'profile'],
            ['icon' => 'settings', 'item' => 'Pengaturan', 'route' => 'settings', 'permission' => 'manage-roles'],
        ],
    ];
@endphp

<aside id="sidebar"
    class="hidden lg:flex fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 flex-col z-40">
    <!-- Logo -->
    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white p-1.5 flex items-center justify-center shrink-0 shadow-sm">
                <img src="{{ asset('logo-puti.webp') }}" class="w-full h-full object-contain" alt="PuTI Logo">
            </div>
            <div>
                <p class="font-bold text-sm leading-tight">
                    {{ $currentApp ? ($currentApp === 'Presensi' ? 'SIMASS Presensi' : ($currentApp === 'Ticketing' ? 'e-Ticket PuTI' : 'Inventaris PuTI')) : 'Portal PuTI' }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Kampus Surabaya</p>
            </div>
        </div>
    </div>

    <!-- Kembali ke Portal -->
    <div class="px-3 pt-3 pb-1">
        <a href="{{ route('portal') }}"
            class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-gray-600 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40 dark:text-gray-400 dark:hover:text-red-400 border border-dashed border-gray-200 dark:border-gray-800 transition">
            <i data-lucide="arrow-left" class="w-4 h-4 shrink-0"></i>
            <span>Kembali ke Portal Hub</span>
        </a>
    </div>

    <!-- Menu -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto no-scrollbar">
        @foreach ($menuGroups as $groupLabel => $items)
            @if ($currentApp && $groupLabel !== $currentApp && $groupLabel !== 'Lainnya')
                @continue
            @endif
            <p class="px-3 py-2 {{ $loop->first ? '' : 'mt-4' }} text-xs font-semibold text-gray-400 uppercase tracking-wider">
                {{ $groupLabel }}
            </p>
            @foreach ($items as $item)
                @if (!isset($item['permission']) || auth()->user()?->can($item['permission']))
                    <a href="{{ route($item['route']) }}"
                        class="nav-item {{ request()->routeIs($item['route']) ? 'active' : '' }} w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5 shrink-0"></i>
                        <span>{{ $item['item'] }}</span>
                    </a>
                @endif
            @endforeach
        @endforeach
    </nav>

    <!-- User -->
    <div class="p-3 border-t border-gray-100 dark:border-gray-800 space-y-1">
        <a href="{{ route('profile') }}"
            class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition cursor-pointer">
            @php
                $userName = auth()->user()->name;
                $words = explode(' ', $userName);
                $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
            @endphp
            <div
                class="w-9 h-9 rounded-full ring-2 ring-telkom-100 dark:ring-telkom-900 shrink-0 flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-telkom-600 font-bold text-sm">
                {{ $initials }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold truncate leading-tight">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                    {{ auth()->user()->position ?? (auth()->user()->getRoleNames()->first() ?? '-') }}</p>
            </div>
            <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 shrink-0"></i>
        </a>
    </div>
</aside>
