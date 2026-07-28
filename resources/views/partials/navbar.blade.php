<header
  class="sticky top-0 z-30 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border-b border-gray-200 dark:border-gray-800">
  <div class="flex items-center justify-between px-4 lg:px-8 h-16">
    <!-- Left -->
    <div class="flex items-center gap-3">
      <!-- Mobile logo -->
      <div class="lg:hidden flex items-center gap-2">
        <div class="w-9 h-9 rounded-lg gradient-telkom flex items-center justify-center">
          <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2L2 7l10 5 10-5-10-5z" />
            <path d="M2 17l10 5 10-5" />
            <path d="M2 12l10 5 10-5" />
          </svg>
        </div>
        <span class="font-bold text-sm">Presensi</span>
      </div>
      <!-- Page title (desktop) -->
      <div class="hidden lg:block">
        <h1 id="pageTitle" class="text-xl font-bold">@yield('title', 'Dashboard')</h1>
        <p id="pageSubtitle" class="text-xs text-gray-500 dark:text-gray-400">@yield('subtitle', 'Selamat datang kembali, Andi')</p>
      </div>
    </div>

    <!-- Right -->
    <div class="flex items-center gap-2">
      <!-- Dark mode -->
      <button type="button" onclick="toggleDark()"
        class="p-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition flex items-center justify-center">
        <svg class="w-5 h-5 theme-toggle-sun text-amber-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="5" />
          <line x1="12" y1="1" x2="12" y2="3" />
          <line x1="12" y1="21" x2="12" y2="23" />
          <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
          <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
          <line x1="1" y1="12" x2="3" y2="12" />
          <line x1="21" y1="12" x2="23" y2="12" />
          <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
          <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
        </svg>
        <svg class="w-5 h-5 theme-toggle-moon text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
        </svg>
      </button>

      <!-- Notif -->
      <button
        class="relative p-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition flex items-center justify-center">
        <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
          <path d="M13.73 21a2 2 0 0 1-3.46 0" />
        </svg>
        <span
          class="absolute top-2 right-2 w-2 h-2 bg-telkom-600 rounded-full ring-2 ring-white dark:ring-gray-900"></span>
      </button>

      <!-- Avatar (mobile) -->
      <a href="{{ route('app.profile') }}" class="lg:hidden">
        <img src="https://i.pravatar.cc/80?img=12"
          class="w-9 h-9 rounded-full ring-2 ring-telkom-100 dark:ring-telkom-900" alt="">
      </a>
    </div>
  </div>
</header>
