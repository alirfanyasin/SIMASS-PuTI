<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal PuTI — Direktorat Pusat Teknologi Informasi Telkom University Surabaya</title>
    <link rel="icon" type="image/webp" href="{{ asset('logo-puti.webp') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="min-h-screen bg-gray-50/70 dark:bg-gray-950 text-gray-900 dark:text-gray-100 antialiased selection:bg-telkom-100 selection:text-telkom-800 transition-colors duration-200"
    style="font-family:'Plus Jakarta Sans',sans-serif">

    <!-- Top Sticky Navigation Bar -->
    <header
        class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 px-6 py-4 flex items-center justify-between">
        <!-- Brand -->
        <div class="flex items-center gap-3">
            <img src="{{ asset('logo-puti.webp') }}" class="w-8 h-8 object-contain" alt="PuTI">
            <span class="font-bold text-lg text-gray-900 dark:text-white">SIMASS</span>
        </div>

        <!-- Right Controls -->
        <div class="flex items-center gap-4">
            <!-- Dark Mode Switcher -->
            <button type="button" onclick="toggleDark()" title="Beralih tema"
                class="p-2 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-750 text-gray-600 dark:text-gray-400 transition flex items-center justify-center">
                <i data-lucide="sun" class="w-4 h-4 theme-toggle-sun text-amber-500"></i>
                <i data-lucide="moon" class="w-4 h-4 theme-toggle-moon text-indigo-400"></i>
            </button>

            <span class="text-sm text-gray-600 dark:text-gray-400 font-medium">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="text-sm text-red-500 hover:text-red-700 font-medium transition">Logout</button>
            </form>
        </div>
    </header>

    <!-- Main View Content -->
    <main class="max-w-6xl mx-auto p-5 sm:p-8 lg:p-10">
        @yield('content')
    </main>

    @include('helpers.darkmode')
    <x-confirm-delete />
    <x-toast />
    @stack('scripts')
</body>

</html>
