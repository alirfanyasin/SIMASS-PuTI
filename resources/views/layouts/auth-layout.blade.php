<!DOCTYPE html>
<html lang="id" class="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SIMASS</title>
    <link rel="icon" type="image/webp" href="{{ asset('logo-puti.webp') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-300 min-h-screen">

    <main class="min-h-screen grid lg:grid-cols-2">

        <section
            class="relative hidden lg:flex flex-col justify-between p-12 gradient-telkom text-white overflow-hidden">
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
                    SIMASS <br> <small class="text-md">Sistem Monitoring Aktifitas Staff dan Student-staff</small>
                </h1>
                <p class="mt-4 text-white/80 text-lg">
                    Aplikasi one-gate yang memuat semua aplikasi yang dibutuhkan oleh tim PuTI untuk bekerja harian
                </p>

                <!-- Features -->
                <div class="mt-8 space-y-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-lg bg-white/15 backdrop-blur flex items-center justify-center shrink-0">
                            <i data-lucide="clock-check" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Presensi</p>
                            <p class="text-sm opacity-70">Catat kehadiran kapan saja</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-lg bg-white/15 backdrop-blur flex items-center justify-center shrink-0">
                            <i data-lucide="ticket" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="font-semibold">e-Ticket</p>
                            <p class="text-sm opacity-70">Aplikasi tiket terintegrasi AI</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-lg bg-white/15 backdrop-blur flex items-center justify-center shrink-0">
                            <i data-lucide="globe-code" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Webosistant</p>
                            <p class="text-sm opacity-70">Reporting Webo-army dan instrumen pendukung</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-lg bg-white/15 backdrop-blur flex items-center justify-center shrink-0">
                            <i data-lucide="shelving-unit" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Inventaris PuTI</p>
                            <p class="text-sm opacity-70">Inventaris produk fisik dan digital</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-lg bg-white/15 backdrop-blur flex items-center justify-center shrink-0">
                            <i data-lucide="radio-tower" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Neing</p>
                            <p class="text-sm opacity-70">Network monitoring untuk network dan qramas</p>
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
                <i data-lucide="sun" class="w-5 h-5 theme-toggle-sun text-amber-500"></i>
                <i data-lucide="moon" class="w-5 h-5 theme-toggle-moon text-indigo-400"></i>
            </button>

            <!-- Mobile Logo -->
            <div class="lg:hidden absolute top-6 left-6 flex items-center gap-2 z-10">
                <div class="w-10 h-10 rounded-xl bg-white p-1.5 flex items-center justify-center shrink-0 shadow-sm">
                    <img src="{{ asset('logo-puti.webp') }}" class="w-full h-full object-contain" alt="PuTI Logo">
                </div>
                <div>
                    <div class="text-sm font-bold leading-tight">SIMASS</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Kampus Surabaya</div>
                </div>
            </div>

            {{-- Content --}}
            @yield('content')
        </section>
    </main>

    @include('helpers.darkmode')
    @stack('scripts')

    @production
        {{-- Anti-Inspect Protection --}}
        <script>
            (function() {
                document.addEventListener('contextmenu', function(e) {
                    e.preventDefault();
                    return false;
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'F12') {
                        e.preventDefault();
                        return false;
                    }
                    if ((e.ctrlKey || e.metaKey) && e.shiftKey && (e.key === 'I' || e.key === 'i')) {
                        e.preventDefault();
                        return false;
                    }
                    if ((e.ctrlKey || e.metaKey) && e.shiftKey && (e.key === 'J' || e.key === 'j')) {
                        e.preventDefault();
                        return false;
                    }
                    if ((e.ctrlKey || e.metaKey) && e.shiftKey && (e.key === 'C' || e.key === 'c')) {
                        e.preventDefault();
                        return false;
                    }
                    if ((e.ctrlKey || e.metaKey) && (e.key === 'U' || e.key === 'u')) {
                        e.preventDefault();
                        return false;
                    }
                    if ((e.ctrlKey || e.metaKey) && (e.key === 'S' || e.key === 's')) {
                        e.preventDefault();
                        return false;
                    }
                });

                var devToolsOpen = false;
                var threshold = 160;
                setInterval(function() {
                    var start = new Date();
                    // eslint-disable-next-line no-debugger
                    debugger;
                    var elapsed = new Date() - start;
                    if (elapsed > threshold && !devToolsOpen) {
                        devToolsOpen = true;
                        document.body.innerHTML =
                            '<div style="display:flex;align-items:center;justify-content:center;min-height:100vh;flex-direction:column;font-family:sans-serif;background:#111;color:#fff;">' +
                            '<i data-lucide="alert-triangle" style="width:64px;height:64px;color:#ef4444;margin-bottom:16px;"></i>' +
                            '<h1 style="font-size:1.5rem;font-weight:700;margin-bottom:8px">Akses Ditolak</h1>' +
                            '<p style="color:#9ca3af;text-align:center;max-width:320px">Developer Tools terdeteksi. Tutup DevTools dan muat ulang halaman.</p>' +
                            '<button onclick="location.reload()" style="margin-top:24px;padding:10px 24px;background:#ef4444;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer">Muat Ulang</button>' +
                            '</div>';
                        if (window.createIcons) {
                            window.createIcons();
                        }
                    }
                    if (elapsed <= threshold && devToolsOpen) {
                        devToolsOpen = false;
                        location.reload();
                    }
                }, 1000);
            })
            ();
        </script>
    @endproduction
    <x-toast />
</body>

</html>
