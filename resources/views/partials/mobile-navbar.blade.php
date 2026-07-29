<nav
  class="lg:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 dark:bg-gray-900/95 backdrop-blur-xl border-t border-gray-200 dark:border-gray-800">
  <div class="grid grid-cols-5 gap-1 px-2 py-2 safe-area">
    <a href="{{ route('presence.dashboard') }}"
      class="mobile-nav-item {{ request()->routeIs('presence.dashboard') ? 'active' : '' }} flex flex-col items-center gap-1 py-1.5 transition">
      <div class="nav-icon-wrap w-9 h-9 rounded-xl flex items-center justify-center transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
          stroke-linejoin="round" viewBox="0 0 24 24">
          <rect x="3" y="3" width="7" height="7" rx="1" />
          <rect x="14" y="3" width="7" height="7" rx="1" />
          <rect x="14" y="14" width="7" height="7" rx="1" />
          <rect x="3" y="14" width="7" height="7" rx="1" />
        </svg>
      </div>
      <span class="nav-label text-[10px]">Dashboard</span>
    </a>

    <a href="{{ route('presence.index') }}"
      class="mobile-nav-item {{ request()->routeIs('presence.index') ? 'active' : '' }} flex flex-col items-center gap-1 py-1.5 transition">
      <div class="nav-icon-wrap w-9 h-9 rounded-xl flex items-center justify-center transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
          stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M9 11l3 3L22 4" />
          <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
        </svg>
      </div>
      <span class="nav-label text-[10px]">Presensi</span>
    </a>

    <a href="{{ route('presence.list') }}"
      class="mobile-nav-item {{ request()->routeIs('presence.list') ? 'active' : '' }} flex flex-col items-center gap-1 py-1.5 transition">
      <div class="nav-icon-wrap w-9 h-9 rounded-xl flex items-center justify-center transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
          stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
          <polyline points="14 2 14 8 20 8" />
        </svg>
      </div>
      <span class="nav-label text-[10px]">Daftar</span>
    </a>

    <a href="{{ route('presence.overtime') }}"
      class="mobile-nav-item {{ request()->routeIs('presence.overtime') ? 'active' : '' }} flex flex-col items-center gap-1 py-1.5 transition">
      <div class="nav-icon-wrap w-9 h-9 rounded-xl flex items-center justify-center transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
          stroke-linejoin="round" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10" />
          <polyline points="12 6 12 12 16 14" />
        </svg>
      </div>
      <span class="nav-label text-[10px]">Lembur</span>
    </a>

    <a href="{{ route('presence.history') }}"
      class="mobile-nav-item {{ request()->routeIs('presence.history') ? 'active' : '' }} flex flex-col items-center gap-1 py-1.5 transition">
      <div class="nav-icon-wrap w-9 h-9 rounded-xl flex items-center justify-center transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
          stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M3 3v5h5" />
          <path d="M3.05 13A9 9 0 1 0 6 5.3L3 8" />
          <path d="M12 7v5l4 2" />
        </svg>
      </div>
      <span class="nav-label text-[10px]">History</span>
    </a>
  </div>
</nav>
