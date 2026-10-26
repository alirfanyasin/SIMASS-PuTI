<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMASS — Portal Presensi & Asisten Staff PuTI</title>
    <link rel="icon" type="image/webp" href="{{ asset('logo-puti.webp') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="min-h-screen bg-gray-50 text-gray-900 transition-colors duration-300 dark:bg-gray-950 dark:text-gray-100 flex flex-col justify-between overflow-x-hidden relative">

    <!-- Background Decorative Grid -->
    <div class="absolute inset-0 dot-grid opacity-40 pointer-events-none z-0"></div>

    <!-- Header / Navigation -->
    <header
        class="w-full border-b border-gray-200/50 dark:border-gray-800/50 bg-white/75 dark:bg-gray-950/75 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between">
            <!-- Logo Brand -->
            <a href="/" class="flex items-center gap-3 active:scale-95 transition-transform">
                <img src="{{ asset('logo-puti.webp') }}" alt="Logo PuTI" class="w-8 h-8 sm:w-10 sm:h-10 object-contain">
                <div>
                    <span class="font-extrabold text-lg sm:text-xl tracking-tight block">SIMASS</span>
                    <span
                        class="text-[10px] sm:text-xs font-semibold text-gray-500 tracking-wide uppercase block -mt-1">Kampus
                        Surabaya</span>
                </div>
            </a>

            <!-- Actions -->
            <div class="flex items-center gap-3">
                <!-- Dark Mode Toggle Button -->
                <button onclick="toggleDark()"
                    class="p-2.5 rounded-2xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-900 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 active:scale-95 transition shadow-sm border border-gray-200/30 dark:border-gray-800/30"
                    aria-label="Toggle Theme">
                    <!-- Sun Icon (visible in light mode) -->
                    <i data-lucide="sun" class="w-5 h-5 theme-toggle-sun"></i>
                    <!-- Moon Icon (visible in dark mode) -->
                    <i data-lucide="moon" class="w-5 h-5 theme-toggle-moon"></i>
                </button>

                <!-- Main Auth Action -->
                @auth
                    <a href="{{ route('presence.dashboard') }}"
                        class="px-5 py-2.5 rounded-2xl gradient-telkom text-white font-bold text-sm shadow-lg shadow-telkom-600/20 active:scale-95 hover:opacity-95 transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="px-5 py-2.5 rounded-2xl bg-gray-900 hover:bg-gray-800 dark:bg-white dark:hover:bg-gray-100 text-white dark:text-gray-950 font-bold text-sm shadow-lg active:scale-95 transition">
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
            <div
                class="inline-flex items-center px-4 py-2 bg-telkom-50 dark:bg-telkom-950/40 border border-telkom-200/50 dark:border-telkom-900/50 rounded-full text-xs font-bold text-telkom-600 dark:text-telkom-400 shadow-sm animate-bounce">
                SIMASS v2.0 - Face Recognition AI
            </div>

            <!-- Main Headline -->
            <h1 class="text-4xl sm:text-6xl font-black tracking-tight leading-[1.1] text-gray-900 dark:text-white">
                Sistem Presensi & Manajemen <br class="hidden sm:inline">
                <span class="text-telkom-600 dark:text-telkom-500">Asisten Staff PuTI</span>
            </h1>

            <!-- Subheadline -->
            <p
                class="max-w-2xl mx-auto text-base sm:text-lg text-gray-500 dark:text-gray-400 font-medium leading-relaxed">
                Platform kehadiran cerdas yang mengintegrasikan pengenalan wajah (Face Recognition) bertenaga AI dan
                koordinat GPS presisi untuk asisten staff Direktorat Pusat Teknologi Informasi (PuTI) Kampus Surabaya.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 max-w-md mx-auto pt-4">
                @auth
                    <a href="{{ route('presence.dashboard') }}"
                        class="w-full sm:w-auto px-8 py-4 rounded-2xl gradient-telkom text-white font-bold shadow-xl shadow-telkom-600/30 hover:opacity-95 active:scale-95 transition flex items-center justify-center gap-3">
                        <span>Masuk Dashboard</span>
                        <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </a>
                    <a href="{{ route('profile') }}"
                        class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 font-bold border border-gray-200 dark:border-gray-800 shadow-md active:scale-95 transition">
                        Profil Saya
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="w-full sm:w-auto px-8 py-4 rounded-2xl gradient-telkom text-white font-bold shadow-xl shadow-telkom-600/30 hover:opacity-95 active:scale-95 transition flex items-center justify-center gap-3">
                        <span>Masuk Portal Presensi</span>
                        <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </a>
                @endauth
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pt-12 text-left">
                <!-- Feature 1 -->
                <div
                    class="bg-white dark:bg-gray-900 p-6 rounded-3xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm card-hover flex flex-col justify-between">
                    <div
                        class="w-12 h-12 rounded-2xl bg-telkom-50 dark:bg-telkom-950/50 text-telkom-600 dark:text-telkom-400 flex items-center justify-center mb-4">
                        <i data-lucide="scan-face" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white mb-2">Face Recognition AI</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed font-medium">Verifikasi
                            presensi aman berbasis model pengenalan wajah bertenaga AI langsung dari peramban.</p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div
                    class="bg-white dark:bg-gray-900 p-6 rounded-3xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm card-hover flex flex-col justify-between">
                    <div
                        class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-4">
                        <i data-lucide="map-pin" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white mb-2">GPS Geolocation</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed font-medium">Membatasi
                            presensi hanya di radius wilayah kantor yang telah ditentukan menggunakan koordinat GPS
                            real-time.</p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div
                    class="bg-white dark:bg-gray-900 p-6 rounded-3xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm card-hover flex flex-col justify-between">
                    <div
                        class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-4">
                        <i data-lucide="clock" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white mb-2">Manajemen Overtime</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed font-medium">Pengajuan dan
                            perekaman jam kerja lembur asisten secara otomatis dengan proses persetujuan admin.</p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div
                    class="bg-white dark:bg-gray-900 p-6 rounded-3xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm card-hover flex flex-col justify-between">
                    <div
                        class="w-12 h-12 rounded-2xl bg-green-50 dark:bg-green-950/50 text-green-600 dark:text-green-400 flex items-center justify-center mb-4">
                        <i data-lucide="bot" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white mb-2">Luna AI & Support</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed font-medium">Asisten pintar
                            Luna AI dan sistem pelaporan masalah/ticketing yang siap membantu aktivitas harian Anda.</p>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer
        class="w-full py-6 border-t border-gray-200/30 dark:border-gray-800/30 bg-white/40 dark:bg-gray-950/40 backdrop-blur-sm text-center relative z-10">
        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">
            &copy; {{ date('Y') }} SIMASS Kampus Surabaya. Hak Cipta Dilindungi Undang-Undang.
        </p>
    </footer>

    <!-- Dark Mode Script -->
    @include('helpers.darkmode')

</body>

</html>
