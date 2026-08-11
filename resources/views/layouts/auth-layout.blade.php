<!DOCTYPE html>
<html lang="id" class="">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — Portal PuTI</title>
  <link rel="icon" type="image/webp" href="{{ asset('logo-puti.webp') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
    rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-300 min-h-screen">

  <main class="min-h-screen grid lg:grid-cols-2">

    <section class="relative hidden lg:flex flex-col justify-between p-12 gradient-telkom text-white overflow-hidden">
      <!-- Background decorative -->
      <div class="absolute inset-0 dot-grid opacity-30"></div>
      <div class="absolute top-20 -right-20 w-96 h-96 rounded-full bg-white/10 pulse-slow"></div>
      <div class="absolute bottom-20 -left-20 w-80 h-80 rounded-full bg-white/5 pulse-slow"
        style="animation-delay: 2s;"></div>

      <!-- Logo -->
      <div class="relative z-10 flex items-center gap-3">
        <div class="w-11 h-11 rounded-xl bg-white p-1.5 flex items-center justify-center shrink-0 shadow-sm">
          <img src="{{ asset('logo-puti.webp') }}" class="w-full h-full object-contain" alt="PuTI Logo">
        </div>
        <div>
          <p class="font-bold text-base">PuTI</p>
          <p class="text-xs opacity-80">Portal Presensi &amp; Ticketing</p>
        </div>
      </div>

      <!-- Middle content -->
      <div class="relative z-10 max-w-md">
        <h1 class="text-4xl font-extrabold leading-tight">
          Kelola Presensi &amp; <br> Ticketing dengan Mudah
        </h1>
        <p class="mt-4 text-white/80 text-lg">
          Sistem presensi dan ticketing terintegrasi untuk student staf PuTI Telkom University. Cepat, aman, dan
          real-time.
        </p>

        <!-- Features -->
        <div class="mt-8 space-y-4">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-white/15 backdrop-blur flex items-center justify-center shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M9 11l3 3L22 4" />
                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
              </svg>
            </div>
            <div>
              <p class="font-semibold">Presensi Real-time</p>
              <p class="text-sm opacity-70">Catat kehadiran kapan saja</p>
            </div>
          </div>
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-white/15 backdrop-blur flex items-center justify-center shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" />
                <polyline points="12 6 12 12 16 14" />
              </svg>
            </div>
            <div>
              <p class="font-semibold">Pengajuan Lembur</p>
              <p class="text-sm opacity-70">Kelola overtime dengan sekali klik</p>
            </div>
          </div>
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-white/15 backdrop-blur flex items-center justify-center shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M3 3v5h5" />
                <path d="M3.05 13A9 9 0 1 0 6 5.3L3 8" />
                <path d="M12 7v5l4 2" />
              </svg>
            </div>
            <div>
              <p class="font-semibold">Riwayat Terorganisir</p>
              <p class="text-sm opacity-70">Lacak semua data kehadiranmu</p>
            </div>
          </div>
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-white/15 backdrop-blur flex items-center justify-center shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" viewBox="0 0 24 24">
                <path
                  d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                <path d="M13 5v14" stroke-dasharray="2 2" />
              </svg>
            </div>
            <div>
              <p class="font-semibold">Ticketing Support</p>
              <p class="text-sm opacity-70">Ajukan dan pantau tiket bantuan IT</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="relative z-10 flex items-center justify-between text-xs opacity-70">
        <div>
          <p><span class="font-bold">© 2026 PuTI.</span> All rights reserved.</p>
          <p>Version 3.8.2</p>
        </div>
        <div class="flex gap-4">
          <a href="#" class="hover:opacity-100 transition">Kebijakan Privasi</a>
          <a href="#" class="hover:opacity-100 transition">Syarat & Ketentuan</a>
        </div>
      </div>
    </section>

    <section class="relative flex flex-col justify-center items-center p-6 lg:p-12 bg-white dark:bg-gray-950">
      <!-- Dark mode toggle -->
      <button type="button" onclick="toggleDark()"
        class="absolute top-6 right-6 p-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition z-10 flex items-center justify-center">
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

      <!-- Mobile Logo -->
      <div class="lg:hidden absolute top-6 left-6 flex items-center gap-2 z-10">
        <div class="w-10 h-10 rounded-xl bg-white p-1.5 flex items-center justify-center shrink-0 shadow-sm">
          <img src="{{ asset('logo-puti.webp') }}" class="w-full h-full object-contain" alt="PuTI Logo">
        </div>
        <div>
          <div class="text-sm font-bold leading-tight">Portal PuTI</div>
          <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Kampus Surabaya</div>
        </div>
      </div>

      {{-- Content --}}
      @yield('content')
    </section>
  </main>

  @include('helpers.darkmode')
  @stack('scripts')
</body>

</html>
