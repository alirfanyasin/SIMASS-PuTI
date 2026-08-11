<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIMASS — Portal Presensi & Asisten Staff PuTI</title>
  <link rel="icon" type="image/webp" href="{{ asset('logo-puti.webp') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 text-gray-900 transition-colors duration-300 dark:bg-gray-950 dark:text-gray-100 flex flex-col justify-between overflow-x-hidden relative">

  <!-- Background Decorative Grid -->
  <div class="absolute inset-0 dot-grid opacity-40 pointer-events-none z-0"></div>

  <!-- Header / Navigation -->
  <header class="w-full border-b border-gray-200/50 dark:border-gray-800/50 bg-white/75 dark:bg-gray-950/75 backdrop-blur-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between">
      <!-- Logo Brand -->
      <a href="/" class="flex items-center gap-3 active:scale-95 transition-transform">
        <img src="{{ asset('logo-puti.webp') }}" alt="Logo PuTI" class="w-8 h-8 sm:w-10 sm:h-10 object-contain">
        <div>
          <span class="font-extrabold text-lg sm:text-xl tracking-tight block">Portal PuTI</span>
          <span class="text-[10px] sm:text-xs font-semibold text-gray-500 tracking-wide uppercase block -mt-1">Kampus Surabaya</span>
        </div>
      </a>

      <!-- Actions -->
      <div class="flex items-center gap-3">
        <!-- Dark Mode Toggle Button -->
        <button onclick="toggleDark()" class="p-2.5 rounded-2xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-900 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 active:scale-95 transition shadow-sm border border-gray-200/30 dark:border-gray-800/30" aria-label="Toggle Theme">
          <!-- Sun Icon (visible in light mode) -->
          <svg class="w-5 h-5 theme-toggle-sun" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M14 12a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          <!-- Moon Icon (visible in dark mode) -->
          <svg class="w-5 h-5 theme-toggle-moon" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
          </svg>
        </button>

        <!-- Main Auth Action -->
        @auth
          <a href="{{ route('presence.dashboard') }}" class="px-5 py-2.5 rounded-2xl gradient-telkom text-white font-bold text-sm shadow-lg shadow-telkom-600/20 active:scale-95 hover:opacity-95 transition">
            Dashboard
          </a>
        @else
          <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-2xl bg-gray-900 hover:bg-gray-800 dark:bg-white dark:hover:bg-gray-100 text-white dark:text-gray-950 font-bold text-sm shadow-lg active:scale-95 transition">
            Masuk Portal
          </a>
        @endauth
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <main class="flex-1 flex flex-col items-center justify-center py-12 sm:py-20 relative z-10">
    <div class="max-w-4xl mx-auto px-4 text-center space-y-6 sm:space-y-8">
      
      <!-- Announcement Pill -->
      <div class="inline-flex items-center gap-2 px-4 py-2 bg-telkom-50 dark:bg-telkom-950/40 border border-telkom-200/50 dark:border-telkom-900/50 rounded-full text-xs font-bold text-telkom-600 dark:text-telkom-400 shadow-sm animate-bounce">
        <span class="w-2 h-2 rounded-full bg-telkom-500 animate-pulse"></span>
        SIMASS v2.0 - Face Recognition AI
      </div>

      <!-- Main Headline -->
      <h1 class="text-4xl sm:text-6xl font-black tracking-tight leading-[1.1] text-gray-900 dark:text-white">
        Sistem Presensi & Manajemen <br class="hidden sm:inline">
        <span class="bg-gradient-to-r from-telkom-500 to-telkom-700 bg-clip-text text-transparent">Asisten Staff PuTI</span>
      </h1>

      <!-- Subheadline -->
      <p class="max-w-2xl mx-auto text-base sm:text-lg text-gray-500 dark:text-gray-400 font-medium leading-relaxed">
        Platform kehadiran cerdas yang mengintegrasikan pengenalan wajah (Face Recognition) bertenaga AI dan koordinat GPS presisi untuk asisten staff Direktorat Pusat Teknologi Informasi (PuTI) Kampus Surabaya.
      </p>

      <!-- CTA Buttons -->
      <div class="flex flex-col sm:flex-row items-center justify-center gap-4 max-w-md mx-auto pt-4">
        @auth
          <a href="{{ route('presence.dashboard') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl gradient-telkom text-white font-bold shadow-xl shadow-telkom-600/30 hover:opacity-95 active:scale-95 transition flex items-center justify-center gap-3">
            <span>Masuk Dashboard</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </a>
          <a href="{{ route('profile') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 font-bold border border-gray-200 dark:border-gray-800 shadow-md active:scale-95 transition">
            Profil Saya
          </a>
        @else
          <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl gradient-telkom text-white font-bold shadow-xl shadow-telkom-600/30 hover:opacity-95 active:scale-95 transition flex items-center justify-center gap-3">
            <span>Masuk Portal Presensi</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </a>
        @endauth
      </div>

      <!-- Features Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pt-12 text-left">
        <!-- Feature 1 -->
        <div class="bg-white dark:bg-gray-900 p-6 rounded-3xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm card-hover flex flex-col justify-between">
          <div class="w-12 h-12 rounded-2xl bg-telkom-50 dark:bg-telkom-950/50 text-telkom-600 dark:text-telkom-400 flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
          </div>
          <div>
            <h3 class="font-bold text-gray-900 dark:text-white mb-2">Face Recognition AI</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed font-medium">Verifikasi presensi aman berbasis model pengenalan wajah bertenaga AI langsung dari peramban.</p>
          </div>
        </div>

        <!-- Feature 2 -->
        <div class="bg-white dark:bg-gray-900 p-6 rounded-3xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm card-hover flex flex-col justify-between">
          <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1115 0z" />
            </svg>
          </div>
          <div>
            <h3 class="font-bold text-gray-900 dark:text-white mb-2">GPS Geolocation</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed font-medium">Membatasi presensi hanya di radius wilayah kantor yang telah ditentukan menggunakan koordinat GPS real-time.</p>
          </div>
        </div>

        <!-- Feature 3 -->
        <div class="bg-white dark:bg-gray-900 p-6 rounded-3xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm card-hover flex flex-col justify-between">
          <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <h3 class="font-bold text-gray-900 dark:text-white mb-2">Manajemen Overtime</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed font-medium">Pengajuan dan perekaman jam kerja lembur asisten secara otomatis dengan proses persetujuan admin.</p>
          </div>
        </div>

        <!-- Feature 4 -->
        <div class="bg-white dark:bg-gray-900 p-6 rounded-3xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm card-hover flex flex-col justify-between">
          <div class="w-12 h-12 rounded-2xl bg-green-50 dark:bg-green-950/50 text-green-600 dark:text-green-400 flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
            </svg>
          </div>
          <div>
            <h3 class="font-bold text-gray-900 dark:text-white mb-2">Luna AI & Support</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed font-medium">Asisten pintar Luna AI dan sistem pelaporan masalah/ticketing yang siap membantu aktivitas harian Anda.</p>
          </div>
        </div>
      </div>

    </div>
  </main>

  <!-- Footer -->
  <footer class="w-full py-6 border-t border-gray-200/30 dark:border-gray-800/30 bg-white/40 dark:bg-gray-950/40 backdrop-blur-sm text-center relative z-10">
    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">
      &copy; {{ date('Y') }} Portal PuTI Kampus Surabaya. Hak Cipta Dilindungi Undang-Undang.
    </p>
  </footer>

  <!-- Dark Mode Script -->
  @include('helpers.darkmode')

</body>

</html>
