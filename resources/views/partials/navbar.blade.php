<header
  class="sticky top-0 z-30 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border-b border-gray-200 dark:border-gray-800">
  <div class="flex items-center justify-between px-4 lg:px-8 h-16">
    <!-- Left -->
    <div class="flex items-center gap-3">
      <!-- Mobile logo -->
      <div class="lg:hidden flex items-center gap-2">
        <div class="w-9 h-9 rounded-lg bg-white p-1 flex items-center justify-center shrink-0 shadow-sm">
          <img src="{{ asset('logo-puti.webp') }}" class="w-full h-full object-contain" alt="PuTI Logo">
        </div>
        <div>
          <div class="text-sm">Portal PuTI</div>
          <div class="text-xs text-gray-500 dark:text-gray-400">Kampus Surabaya</div>
        </div>

      </div>
      <!-- Page title (desktop) -->
      <div class="hidden lg:block">
        <h1 id="pageTitle" class="text-xl font-bold">@yield('title', 'Dashboard')</h1>
        <p id="pageSubtitle" class="text-xs text-gray-500 dark:text-gray-400">@yield('subtitle', 'Selamat datang kembali, Andi')</p>
      </div>
    </div>

    <!-- Right -->
    <div class="flex items-center gap-2">
      <!-- Language Selector Dropdown -->
      <div class="relative" id="langDropdownContainer">
        <button type="button" onclick="toggleLangDropdown(event)"
          class="p-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition flex items-center gap-2 text-gray-700 dark:text-gray-300"
          title="Ubah Bahasa">
          <img id="currentLangFlag" src="https://flagcdn.com/w20/id.png" srcset="https://flagcdn.com/w40/id.png 2x"
            class="w-5 h-3.5 object-cover rounded-sm shadow-sm" alt="ID">
          <span id="currentLangLabel" class="text-xs font-bold hidden sm:inline">ID</span>
          <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5"
            stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <polyline points="6 9 12 15 18 9" />
          </svg>
        </button>

        <!-- Dropdown Menu -->
        <div id="langDropdownMenu"
          class="absolute right-0 mt-2 w-32 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl shadow-xl py-1.5 hidden opacity-0 scale-95 transition-all duration-200 z-50">
          <button
            onclick="changeLang('id', 'https://flagcdn.com/w20/id.png', 'https://flagcdn.com/w40/id.png 2x', 'ID')"
            class="w-full flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition font-semibold text-left">
            <img src="https://flagcdn.com/w20/id.png" class="w-5 h-3.5 object-cover rounded-sm shadow-sm"
              alt="ID">
            <span>ID</span>
          </button>
          <button
            onclick="changeLang('en', 'https://flagcdn.com/w20/gb.png', 'https://flagcdn.com/w40/gb.png 2x', 'EN')"
            class="w-full flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition font-semibold text-left">
            <img src="https://flagcdn.com/w20/gb.png" class="w-5 h-3.5 object-cover rounded-sm shadow-sm"
              alt="EN">
            <span>EN</span>
          </button>
        </div>
      </div>

      <!-- Dark mode -->
      <button type="button" onclick="toggleDark()"
        class="p-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition flex items-center justify-center">
        <svg class="w-5 h-5 theme-toggle-sun text-amber-500" fill="none" stroke="currentColor" stroke-width="2"
          stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
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
        <svg class="w-5 h-5 theme-toggle-moon text-indigo-400" fill="none" stroke="currentColor" stroke-width="2"
          stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
        </svg>
      </button>

      <!-- Notif -->
      <button
        class="relative p-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition flex items-center justify-center">
        <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" stroke-width="2"
          stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
          <path d="M13.73 21a2 2 0 0 1-3.46 0" />
        </svg>
        <span
          class="absolute top-2 right-2 w-2 h-2 bg-telkom-600 rounded-full ring-2 ring-white dark:ring-gray-900"></span>
      </button>

      <!-- Avatar (mobile) -->
      <a href="{{ route('profile') }}" class="lg:hidden">
        @php
          $userName = auth()->user()->name ?? 'User';
          $words = explode(' ', $userName);
          $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
        @endphp
        <div
          class="w-9 h-9 rounded-full ring-2 ring-telkom-100 dark:ring-telkom-900 flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-telkom-600 dark:text-telkom-400 font-bold text-sm">
          {{ $initials }}
        </div>
      </a>
    </div>
  </div>
</header>

@push('scripts')
  <script>
    function toggleLangDropdown(event) {
      event.stopPropagation();
      const dropdown = document.getElementById('langDropdownMenu');

      if (dropdown.classList.contains('hidden')) {
        dropdown.classList.remove('hidden');
        void dropdown.offsetWidth;
        dropdown.classList.remove('opacity-0', 'scale-95');
      } else {
        hideLangDropdown();
      }
    }

    function hideLangDropdown() {
      const dropdown = document.getElementById('langDropdownMenu');
      dropdown.classList.add('opacity-0', 'scale-95');
      setTimeout(() => {
        dropdown.classList.add('hidden');
      }, 200);
    }

    function changeLang(lang, flagSrc, flagSrcset, label) {
      const flagImg = document.getElementById('currentLangFlag');
      flagImg.src = flagSrc;
      flagImg.srcset = flagSrcset;
      document.getElementById('currentLangLabel').innerText = label;
      hideLangDropdown();
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
      const container = document.getElementById('langDropdownContainer');
      const dropdown = document.getElementById('langDropdownMenu');
      if (container && !container.contains(event.target) && !dropdown.classList.contains('hidden')) {
        hideLangDropdown();
      }
    });
  </script>
@endpush
