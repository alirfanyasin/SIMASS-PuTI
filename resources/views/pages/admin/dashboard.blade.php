@extends('layouts.app-layout')

@section('title', 'Admin Console')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Admin Console</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pusat kendali akun pengguna, hak akses, modul
                    portal, data master, dan audit log.</p>
            </div>
            <a href="{{ route('portal') }}"
                class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Portal Hub
            </a>
        </div>

        {{-- Overview Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.index') }}"
                class="group block p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm hover:border-gray-400 dark:hover:border-gray-600 transition">
                <div class="flex items-center justify-between text-gray-500 dark:text-gray-400">
                    <span class="text-xs font-semibold">Total Pengguna</span>
                    <i data-lucide="users"
                        class="w-4 h-4 text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transition"></i>
                </div>
                <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-2">{{ \App\Models\User::count() }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ \App\Models\User::where('is_active', true)->count() }} pengguna
                    aktif</p>
            </a>

            <a href="{{ route('admin.modules.index') }}"
                class="group block p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm hover:border-telkom-500 dark:hover:border-telkom-500 transition">
                <div class="flex items-center justify-between text-gray-500 dark:text-gray-400">
                    <span class="text-xs font-semibold">Modul Portal</span>
                    <i data-lucide="layout-grid"
                        class="w-4 h-4 text-gray-400 group-hover:text-telkom-600 dark:group-hover:text-telkom-400 transition"></i>
                </div>
                <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-2">
                    {{ \App\Models\PortalModule::count() }}</p>
                <p class="text-xs text-telkom-600 dark:text-telkom-400 font-medium mt-1">Kelola kartu & kredit &rarr;</p>
            </a>

            <a href="{{ route('role-permission') }}"
                class="group block p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm hover:border-gray-400 dark:hover:border-gray-600 transition">
                <div class="flex items-center justify-between text-gray-500 dark:text-gray-400">
                    <span class="text-xs font-semibold">Role Terdaftar</span>
                    <i data-lucide="shield"
                        class="w-4 h-4 text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transition"></i>
                </div>
                <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-2">
                    {{ \Spatie\Permission\Models\Role::count() }}</p>
                <p class="text-xs text-gray-400 mt-1">Spatie permissions</p>
            </a>

            <a href="{{ route('admin.audit-logs.index') }}"
                class="group block p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm hover:border-gray-400 dark:hover:border-gray-600 transition">
                <div class="flex items-center justify-between text-gray-500 dark:text-gray-400">
                    <span class="text-xs font-semibold">Audit Logs</span>
                    <i data-lucide="history"
                        class="w-4 h-4 text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transition"></i>
                </div>
                <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-2">{{ \App\Models\AuditLog::count() }}
                </p>
                <p class="text-xs text-gray-400 mt-1">Riwayat aksi tercatat</p>
            </a>
        </div>

        {{-- Quick Management Shortcuts --}}
        <div>
            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-3">Fitur Administrasi</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('admin.modules.index') }}"
                    class="group p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-xs hover:border-telkom-500 dark:hover:border-telkom-500 transition flex items-start gap-4">
                    <div
                        class="w-11 h-11 rounded-xl bg-telkom-50 dark:bg-telkom-950 text-telkom-700 dark:text-telkom-400 flex items-center justify-center shrink-0">
                        <i data-lucide="layout-grid" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3
                            class="font-bold text-sm text-gray-900 dark:text-white group-hover:text-telkom-600 dark:group-hover:text-telkom-400 transition">
                            Manajemen Modul Portal</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">Kelola kartu modul
                            aplikasi, credit developer, ikon, status, dan urutan kartu.</p>
                    </div>
                </a>

                <a href="{{ route('admin.users.index') }}"
                    class="group p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-xs hover:border-gray-400 dark:hover:border-gray-600 transition flex items-start gap-4">
                    <div
                        class="w-11 h-11 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3
                            class="font-bold text-sm text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                            Manajemen Pengguna</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">Tambah pengguna baru, impor
                            file CSV, dan aktif/nonaktifkan akun sivitas.</p>
                    </div>
                </a>

                <a href="{{ route('admin.master-data.index') }}"
                    class="group p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-xs hover:border-gray-400 dark:hover:border-gray-600 transition flex items-start gap-4">
                    <div
                        class="w-11 h-11 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0">
                        <i data-lucide="database" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3
                            class="font-bold text-sm text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition">
                            Data Master & SLA</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">Konfigurasi unit & divisi
                            kerja, lokasi kampus, dan target jam resolusi SLA.</p>
                    </div>
                </a>

                <a href="{{ route('role-permission') }}"
                    class="group p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-xs hover:border-gray-400 dark:hover:border-gray-600 transition flex items-start gap-4">
                    <div
                        class="w-11 h-11 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3
                            class="font-bold text-sm text-gray-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition">
                            Role & Hak Akses</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">Atur role pengguna (student
                            staff, staff, superadmin) dan izin fitur per modul.</p>
                    </div>
                </a>

                <a href="{{ route('admin.audit-logs.index') }}"
                    class="group p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-xs hover:border-gray-400 dark:hover:border-gray-600 transition flex items-start gap-4">
                    <div
                        class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                        <i data-lucide="history" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3
                            class="font-bold text-sm text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition">
                            Audit Log Aktivitas</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">Catatan jejak aktivitas
                            admin, perubahan master data, dan aksi pengguna.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
