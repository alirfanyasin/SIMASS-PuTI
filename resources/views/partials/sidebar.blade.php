@php
  $dataMenuItems = [
      [
          'icon' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <rect x="3" y="3" width="7" height="7" rx="1" />
            <rect x="14" y="3" width="7" height="7" rx="1" />
            <rect x="14" y="14" width="7" height="7" rx="1" />
            <rect x="3" y="14" width="7" height="7" rx="1" />
          </svg>',
          'item' => 'Dashboard',
          'route' => 'app.dashboard',
      ],
      [
          'icon' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M9 11l3 3L22 4" />
            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
          </svg>',
          'item' => 'Presensi',
          'route' => 'app.presence',
      ],
      [
          'icon' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
            <polyline points="14 2 14 8 20 8" />
            <line x1="9" y1="13" x2="15" y2="13" />
            <line x1="9" y1="17" x2="15" y2="17" />
          </svg>',
          'item' => 'Daftar Presensi',
          'route' => 'app.presence-list',
      ],
      [
          'icon' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" />
            <polyline points="12 6 12 12 16 14" />
          </svg>',
          'item' => 'Overtime',
          'route' => 'app.overtime',
      ],
      [
          'icon' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M3 3v5h5" />
            <path d="M3.05 13A9 9 0 1 0 6 5.3L3 8" />
            <path d="M12 7v5l4 2" />
          </svg>',
          'item' => 'History Presensi',
          'route' => 'app.presence-history',
      ],
      [
          'icon' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
          </svg>',
          'item' => 'Student Staff',
          'route' => 'app.student-staff',
      ],
  ];
@endphp

<aside id="sidebar"
  class="hidden lg:flex fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 flex-col z-40">
  <!-- Logo -->
  <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-xl gradient-telkom flex items-center justify-center shrink-0 shadow-lg shadow-telkom-600/30">
        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 2L2 7l10 5 10-5-10-5z" />
          <path d="M2 17l10 5 10-5" />
          <path d="M2 12l10 5 10-5" />
        </svg>
      </div>
      <div>
        <p class="font-bold text-sm leading-tight">Telkom University</p>
        <p class="text-xs text-gray-500 dark:text-gray-400">Portal Presensi</p>
      </div>
    </div>
  </div>

  <!-- Menu -->
  <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto no-scrollbar">
    <p class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu Utama</p>
    @foreach ($dataMenuItems as $item)
      <a href="{{ route($item['route']) }}"
        class="nav-item {{ request()->routeIs($item['route']) ? 'active' : '' }} w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
        {!! $item['icon'] !!}
        <span>{{ $item['item'] }}</span>
      </a>
    @endforeach

    <p class="px-3 py-2 mt-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Lainnya</p>
    <a href="{{ route('app.profile') }}"
      class="nav-item {{ request()->routeIs('app.profile') ? 'active' : '' }} w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
      <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
        <circle cx="12" cy="7" r="4" />
      </svg>
      <span>Profil</span>
    </a>
    <a href="{{ route('app.settings') }}"
      class="nav-item {{ request()->routeIs('app.settings') ? 'active' : '' }} w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
      <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="3" />
        <path
          d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
      </svg>
      <span>Pengaturan</span>
    </a>
  </nav>

  <!-- User -->
  <div class="p-3 border-t border-gray-100 dark:border-gray-800">
    <a href="{{ route('app.profile') }}"
      class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition cursor-pointer">
      <img src="https://i.pravatar.cc/80?img=12"
        class="w-9 h-9 rounded-full ring-2 ring-telkom-100 dark:ring-telkom-900 shrink-0" alt="">
      <div class="flex-1 min-w-0">
        <p class="text-sm font-semibold truncate leading-tight">Andi Pratama</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">Dosen - FIT</p>
      </div>
      <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <polyline points="9 18 15 12 9 6" />
      </svg>
    </a>
  </div>
</aside>
