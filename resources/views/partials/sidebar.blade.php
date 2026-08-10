@php
  $menuGroups = [
      'Presensi' => [
          [
              'icon' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <rect x="3" y="3" width="7" height="7" rx="1" />
                <rect x="14" y="3" width="7" height="7" rx="1" />
                <rect x="14" y="14" width="7" height="7" rx="1" />
                <rect x="3" y="14" width="7" height="7" rx="1" />
              </svg>',
              'item' => 'Dashboard',
              'route' => 'presence.dashboard',
              'permission' => 'view-presence',
          ],
          [
              'icon' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M9 11l3 3L22 4" />
                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
              </svg>',
              'item' => 'Presensi',
              'route' => 'presence.index',
              'permission' => 'create-presence',
          ],
          [
              'icon' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <polyline points="14 2 14 8 20 8" />
                <line x1="9" y1="13" x2="15" y2="13" />
                <line x1="9" y1="17" x2="15" y2="17" />
              </svg>',
              'item' => 'Daftar Presensi',
              'route' => 'presence.list',
              'permission' => 'view-presence',
          ],
          [
              'icon' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" />
                <polyline points="12 6 12 12 16 14" />
              </svg>',
              'item' => 'Overtime',
              'route' => 'presence.overtime',
              'permission' => 'manage-overtime',
          ],
          [
              'icon' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M3 3v5h5" />
                <path d="M3.05 13A9 9 0 1 0 6 5.3L3 8" />
                <path d="M12 7v5l4 2" />
              </svg>',
              'item' => 'History Presensi',
              'route' => 'presence.history',
              'permission' => 'view-presence-history',
          ],
          [
              'icon' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
              </svg>',
              'item' => 'Student Staff',
              'route' => 'presence.student-staff',
              'permission' => 'manage-presence',
          ],
          [
              'icon' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                <line x1="16" x2="16" y1="2" y2="6"/>
                <line x1="8" x2="8" y1="2" y2="6"/>
                <line x1="3" x2="21" y1="10" y2="10"/>
              </svg>',
              'item' => 'Kalender',
              'route' => 'presence.calendar',
              'permission' => 'view-presence',
          ],
      ],
      'Ticketing' => [
          [
              'icon' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                <path d="M13 5v14" stroke-dasharray="2 2" />
              </svg>',
              'item' => 'Daftar Tiket',
              'route' => 'ticket.index',
          ],
          [
              'icon' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M12 5v14M5 12h14" />
              </svg>',
              'item' => 'Pengajuan Tiket',
              'route' => 'ticket.create',
          ],
          [
              'icon' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
              </svg>',
              'item' => 'Tiket Saya',
              'route' => 'ticket.my-tickets',
          ],
          [
              'icon' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <rect x="8" y="2" width="8" height="4" rx="1" ry="1" />
                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                <path d="m9 14 2 2 4-4" />
              </svg>',
              'item' => 'Task Management',
              'route' => 'ticket.tasks',
          ],
          [
              'icon' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" />
                <polyline points="12 6 12 12 16 14" />
              </svg>',
              'item' => 'History Tiket',
              'route' => 'ticket.history',
          ],
          [
              'icon' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M12 2a10 10 0 0 1 10 10c0 5.523-4.477 10-10 10S2 17.523 2 12c0-2.4 1-4.8 2.75-6.5" />
                <path d="M12 10a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z" />
                <path d="m17 7-5 5" />
              </svg>',
              'item' => 'Luna AI',
              'route' => 'ticket.luna',
          ],
      ],
      'Lainnya' => [
          [
              'icon' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>
              </svg>',
              'item' => 'Hari Libur',
              'route' => 'holiday.index',
              'permission' => 'manage-holiday',
          ],
          [
              'icon' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
              </svg>',
              'item' => 'Role & Permission',
              'route' => 'role-permission',
              'permission' => 'manage-roles',
          ],
          [
              'icon' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
              </svg>',
              'item' => 'Profil',
              'route' => 'profile',
          ],
          [
              'icon' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="3" />
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
              </svg>',
              'item' => 'Pengaturan',
              'route' => 'settings',
              'permission' => 'manage-roles',
          ],
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
        <p class="font-bold text-sm leading-tight">Portal PuTI</p>
        <p class="text-xs text-gray-500 dark:text-gray-400">Kampus Surabaya</p>
      </div>
    </div>
  </div>

  <!-- Menu -->
  <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto no-scrollbar">
    @foreach ($menuGroups as $groupLabel => $items)
      <p
        class="px-3 py-2 {{ $loop->first ? '' : 'mt-4' }} text-xs font-semibold text-gray-400 uppercase tracking-wider">
        {{ $groupLabel }}
      </p>
      @foreach ($items as $item)
        @if (!isset($item['permission']) || auth()->user()?->can($item['permission']))
          <a href="{{ route($item['route']) }}"
            class="nav-item {{ request()->routeIs($item['route']) ? 'active' : '' }} w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
            {!! $item['icon'] !!}
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
      <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <polyline points="9 18 15 12 9 6" />
      </svg>
    </a>
  </div>
</aside>
