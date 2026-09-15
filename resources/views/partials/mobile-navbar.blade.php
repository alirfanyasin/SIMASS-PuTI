<nav
    class="lg:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 dark:bg-gray-900/95 backdrop-blur-xl border-t border-gray-200 dark:border-gray-800">
    <div class="grid grid-cols-5 gap-1 px-2 py-2 safe-area">
        <a href="{{ route('presence.dashboard') }}"
            class="mobile-nav-item {{ request()->routeIs('presence.dashboard') ? 'active' : '' }} flex flex-col items-center gap-1 py-1.5 transition">
            <div class="nav-icon-wrap w-9 h-9 rounded-xl flex items-center justify-center transition">
                <i data-lucide="layout-grid" class="w-5 h-5"></i>
            </div>
            <span class="nav-label text-[10px]">Dashboard</span>
        </a>

        <a href="{{ route('presence.index') }}"
            class="mobile-nav-item {{ request()->routeIs('presence.index') ? 'active' : '' }} flex flex-col items-center gap-1 py-1.5 transition">
            <div class="nav-icon-wrap w-9 h-9 rounded-xl flex items-center justify-center transition">
                <i data-lucide="check-square" class="w-5 h-5"></i>
            </div>
            <span class="nav-label text-[10px]">Presensi</span>
        </a>

        <a href="{{ route('presence.list') }}"
            class="mobile-nav-item {{ request()->routeIs('presence.list') ? 'active' : '' }} flex flex-col items-center gap-1 py-1.5 transition">
            <div class="nav-icon-wrap w-9 h-9 rounded-xl flex items-center justify-center transition">
                <i data-lucide="file-text" class="w-5 h-5"></i>
            </div>
            <span class="nav-label text-[10px]">Daftar</span>
        </a>

        <a href="{{ route('presence.overtime') }}"
            class="mobile-nav-item {{ request()->routeIs('presence.overtime') ? 'active' : '' }} flex flex-col items-center gap-1 py-1.5 transition">
            <div class="nav-icon-wrap w-9 h-9 rounded-xl flex items-center justify-center transition">
                <i data-lucide="clock" class="w-5 h-5"></i>
            </div>
            <span class="nav-label text-[10px]">Lembur</span>
        </a>

        <a href="{{ route('presence.history') }}"
            class="mobile-nav-item {{ request()->routeIs('presence.history') ? 'active' : '' }} flex flex-col items-center gap-1 py-1.5 transition">
            <div class="nav-icon-wrap w-9 h-9 rounded-xl flex items-center justify-center transition">
                <i data-lucide="history" class="w-5 h-5"></i>
            </div>
            <span class="nav-label text-[10px]">History</span>
        </a>
    </div>
</nav>
