@extends('layouts.app-layout')

@section('title', 'Admin Console')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Admin Console</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pusat kendali akun pengguna, hak akses, modul portal, data master, dan audit log.</p>
        </div>
        <a href="{{ route('portal') }}" class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Portal Hub
        </a>
    </div>

    {{-- Overview Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm">
            <div class="flex items-center justify-between text-gray-500 dark:text-gray-400">
                <span class="text-xs font-semibold">Total Pengguna</span>
                <i data-lucide="users" class="w-4 h-4"></i>
            </div>
            <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-2">{{ \App\Models\User::count() }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ \App\Models\User::where('is_active', true)->count() }} pengguna aktif</p>
        </div>

        <div class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm">
            <div class="flex items-center justify-between text-gray-500 dark:text-gray-400">
                <span class="text-xs font-semibold">Modul Portal</span>
                <i data-lucide="layout-grid" class="w-4 h-4"></i>
            </div>
            <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-2">{{ \App\Models\PortalModule::count() }}</p>
            <p class="text-xs text-gray-400 mt-1">Registry modul aktif</p>
        </div>

        <div class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm">
            <div class="flex items-center justify-between text-gray-500 dark:text-gray-400">
                <span class="text-xs font-semibold">Role Terdaftar</span>
                <i data-lucide="shield" class="w-4 h-4"></i>
            </div>
            <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-2">{{ \Spatie\Permission\Models\Role::count() }}</p>
            <p class="text-xs text-gray-400 mt-1">Spatie permissions</p>
        </div>

        <div class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm">
            <div class="flex items-center justify-between text-gray-500 dark:text-gray-400">
                <span class="text-xs font-semibold">Audit Logs</span>
                <i data-lucide="history" class="w-4 h-4"></i>
            </div>
            <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-2">{{ \App\Models\AuditLog::count() }}</p>
            <p class="text-xs text-gray-400 mt-1">Riwayat aksi tercatat</p>
        </div>
    </div>

    {{-- Quick Action Links --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.users.index') }}" class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-gray-900 dark:hover:border-gray-100 transition-all shadow-sm group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-900 dark:text-white group-hover:scale-105 transition-transform">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm">Manajemen Pengguna</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Kelola akun, unit & import CSV</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.master-data.index') }}" class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-gray-900 dark:hover:border-gray-100 transition-all shadow-sm group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-900 dark:text-white group-hover:scale-105 transition-transform">
                    <i data-lucide="database" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm">Data Master & SLA</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Unit, lokasi kampus & katalog SLA</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.audit-logs.index') }}" class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-gray-900 dark:hover:border-gray-100 transition-all shadow-sm group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-900 dark:text-white group-hover:scale-105 transition-transform">
                    <i data-lucide="history" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm">Audit Log</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Rekam jejak perubahan sistem</p>
                </div>
            </div>
        </a>

        <a href="{{ route('role-permission') }}" class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-gray-900 dark:hover:border-gray-100 transition-all shadow-sm group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-900 dark:text-white group-hover:scale-105 transition-transform">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm">Role & Permission</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Kelola hak akses pengguna</p>
                </div>
            </div>
        </a>

        <a href="{{ route('settings') }}" class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-gray-900 dark:hover:border-gray-100 transition-all shadow-sm group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-900 dark:text-white group-hover:scale-105 transition-transform">
                    <i data-lucide="sliders" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm">Pengaturan Sistem</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Geofence & jam operasional</p>
                </div>
            </div>
        </a>

        <a href="{{ route('holiday.index') }}" class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-gray-900 dark:hover:border-gray-100 transition-all shadow-sm group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-900 dark:text-white group-hover:scale-105 transition-transform">
                    <i data-lucide="calendar-off" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm">Hari Libur</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Kalender libur nasional</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
