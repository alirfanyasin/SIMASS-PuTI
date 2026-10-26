@extends('layouts.app-layout')

@section('title', 'Pengaturan Peran & Hak Akses — ' . $user->name)

@section('content')
    <div class="space-y-6 max-w-5xl mx-auto pb-28">
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Pengaturan Peran & Hak Akses</h1>
            </div>
            <a href="{{ route('role-permission') }}"
                class="inline-flex items-center gap-2 text-xs font-semibold px-3.5 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors shrink-0">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar Akses
            </a>
        </div>

        @if (session('error'))
            <div
                class="p-4 bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 text-xs font-semibold rounded-2xl border border-red-200 dark:border-red-900/60 flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- User Summary Card --}}
        <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4 min-w-0">
                    <div
                        class="w-12 h-12 rounded-full gradient-telkom text-white flex items-center justify-center font-bold text-lg aspect-square shadow-xs shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-base font-bold text-gray-900 dark:text-white truncate">
                            {{ $user->name }}
                            <small class="text-gray-500">
                                @if ($user->username)
                                    {{ '@' . $user->username }}
                                @endif
                            </small>
                        </h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate">
                            {{ $user->email ?? 'Tidak ada email' }}

                            @if ($user->nim)
                                <span class="mx-1">•</span>NIM/NIP: <span
                                    class="font-mono text-gray-700 dark:text-gray-300">{{ $user->nim }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto sm:ml-auto">
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800' : 'bg-gray-100 text-gray-600 border border-gray-200' }}">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-telkom-50 text-telkom-700 dark:bg-telkom-950/50 dark:text-telkom-400 border border-telkom-200/60 dark:border-telkom-900 capitalize">
                        {{ $currentRole }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Main Form --}}
        <form id="rolePermissionForm" method="POST" action="{{ route('role-permission.update', $user) }}"
            class="space-y-6">
            @csrf

            {{-- Section 1: Main Role Selection --}}
            <div
                class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="font-bold text-base text-gray-900 dark:text-white flex items-center gap-2">
                        <i data-lucide="badge-check" class="w-5 h-5 text-telkom-600"></i>
                        Pilih Peran Utama (Role)
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Pilih satu peran utama yang mendasari wewenang dasar akun pengguna ini.
                    </p>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach ($mainRoles as $rKey => $role)
                        @php
                            $isSelected = $currentRole === $rKey;
                        @endphp
                        <label
                            class="relative flex items-start gap-3.5 p-4 rounded-2xl border-2 cursor-pointer transition-all hover:border-telkom-400 dark:hover:border-telkom-600 group {{ $isSelected ? 'border-telkom-600 bg-telkom-50/20 dark:bg-telkom-950/20' : 'border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900' }}">
                            <input type="radio" name="user_role" value="{{ $rKey }}"
                                {{ $isSelected ? 'checked' : '' }} required
                                class="mt-0.5 w-4 h-4 text-telkom-600 border-gray-300 focus:ring-telkom-500 cursor-pointer shrink-0">
                            <div class="space-y-1">
                                <span
                                    class="font-bold text-sm text-gray-900 dark:text-white block group-hover:text-telkom-600 dark:group-hover:text-telkom-400 transition-colors">
                                    {{ $role['label'] }}
                                </span>
                                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                    {{ $role['desc'] }}
                                </p>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Section 2: App-Categorized Permissions --}}
            <div
                class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div
                    class="p-6 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-base text-gray-900 dark:text-white flex items-center gap-2">
                            <i data-lucide="layers" class="w-5 h-5 text-telkom-600"></i>
                            Hak Akses Modul Berdasarkan Aplikasi
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Atur izin spesifik per modul aplikasi untuk pengguna ini secara terstruktur.
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="toggleAllGlobal(true)"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-750 text-xs font-semibold text-gray-700 dark:text-gray-200 shadow-2xs transition">
                            <i data-lucide="check-square" class="w-3.5 h-3.5 text-telkom-600"></i> Centang Semua
                        </button>
                        <button type="button" onclick="toggleAllGlobal(false)"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-750 text-xs font-semibold text-gray-700 dark:text-gray-200 shadow-2xs transition">
                            <i data-lucide="square" class="w-3.5 h-3.5 text-gray-400"></i> Kosongkan Semua
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-8">
                    @foreach ($applications as $appKey => $app)
                        @php
                            $perms = $app['permissions'] ?? [];
                            $isComingSoon = ($app['status'] ?? 'active') === 'coming_soon';
                        @endphp
                        <div class="space-y-3" id="group-{{ $app['slug'] }}">
                            <div
                                class="flex items-center justify-between bg-gray-50 dark:bg-gray-800/50 px-4 py-2.5 rounded-2xl border border-gray-100 dark:border-gray-800">
                                <div class="flex items-center gap-2.5">
                                    <div
                                        class="w-8 h-8 rounded-xl bg-telkom-50 dark:bg-telkom-950/50 text-telkom-600 dark:text-telkom-400 flex items-center justify-center shrink-0">
                                        <i data-lucide="{{ $app['icon'] }}" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-gray-900 dark:text-white">{{ $app['name'] }}
                                        </h4>
                                        @if ($isComingSoon)
                                            <span class="text-[11px] text-amber-600 dark:text-amber-400 font-medium">Dalam
                                                Tahap Pengembangan</span>
                                        @else
                                            <span class="text-[11px] text-gray-400">{{ count($perms) }} hak akses
                                                tersedia</span>
                                        @endif
                                    </div>
                                </div>

                                @if ($isComingSoon)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-200/60 dark:border-amber-900/60">
                                        <i data-lucide="clock" class="w-3.5 h-3.5"></i> Segera Hadir
                                    </span>
                                @elseif (count($perms) > 0)
                                    <div class="flex items-center gap-1.5">
                                        <button type="button" onclick="toggleAppGroup('{{ $app['slug'] }}', true)"
                                            class="px-3 py-1 rounded-xl text-xs font-semibold bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-telkom-500 text-telkom-600 dark:text-telkom-400 transition shadow-2xs">
                                            Pilih Semua
                                        </button>
                                        <button type="button" onclick="toggleAppGroup('{{ $app['slug'] }}', false)"
                                            class="px-3 py-1 rounded-xl text-xs font-semibold bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 text-gray-600 dark:text-gray-400 transition shadow-2xs">
                                            Batal
                                        </button>
                                    </div>
                                @endif
                            </div>

                            @if ($isComingSoon)
                                <div
                                    class="rounded-2xl border border-dashed border-gray-200 dark:border-gray-800 p-6 text-center bg-gray-50/40 dark:bg-gray-900/40 flex flex-col items-center justify-center">
                                    <div
                                        class="w-10 h-10 rounded-full bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-2">
                                        <i data-lucide="sparkles" class="w-5 h-5"></i>
                                    </div>
                                    <h5 class="text-xs font-bold text-gray-900 dark:text-white">Modul {{ $app['name'] }}
                                        Sedang Disiapkan</h5>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-md">
                                        {{ $app['description'] }}</p>
                                    <div
                                        class="inline-flex items-center gap-2 mt-3.5 px-3 py-1 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-[11px] font-medium text-gray-500 dark:text-gray-400">
                                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                        Hak akses granular modul ini akan aktif otomatis setelah fitur dirilis
                                    </div>
                                </div>
                            @elseif (count($perms) === 0)
                                <div
                                    class="rounded-2xl border border-dashed border-gray-200 dark:border-gray-800 p-5 text-center bg-gray-50/40 dark:bg-gray-900/40 flex flex-col items-center justify-center">
                                    <div
                                        class="w-10 h-10 rounded-full bg-telkom-50 dark:bg-telkom-950/50 text-telkom-600 dark:text-telkom-400 flex items-center justify-center mb-2">
                                        <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                    </div>
                                    <h5 class="text-xs font-bold text-gray-900 dark:text-white">Akses Standar Terbuka</h5>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 max-w-md">
                                        {{ $app['description'] }}</p>
                                    <p class="text-[11px] text-gray-400 mt-2">Semua pengguna terotentikasi dapat mengakses
                                        pembuatan tiket dan layanan Luna AI.</p>
                                </div>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($perms as $perm)
                                        @php
                                            $isGranted = in_array($perm['name'], $userAllPermissions);
                                        @endphp
                                        <div
                                            class="flex items-center justify-between p-4 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl hover:border-gray-300 dark:hover:border-gray-700 transition-colors">
                                            <div class="pr-3 min-w-0">
                                                <p class="text-xs font-bold text-gray-900 dark:text-white truncate">
                                                    {{ $perm['label'] }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 leading-snug">
                                                    {{ $perm['desc'] }}</p>
                                                <span
                                                    class="text-[10px] text-gray-400 dark:text-gray-500 font-mono mt-0.5 block truncate">{{ $perm['name'] }}</span>
                                            </div>
                                            <label
                                                class="relative inline-flex items-center cursor-pointer select-none shrink-0">
                                                <input type="checkbox" name="user_permissions[]"
                                                    value="{{ $perm['name'] }}" data-app="{{ $app['slug'] }}"
                                                    {{ $isGranted ? 'checked' : '' }} class="sr-only peer perm-checkbox">
                                                <div
                                                    class="w-9 h-5 bg-gray-200 dark:bg-gray-800 rounded-full peer peer-checked:after:translate-x-4 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-telkom-600">
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </form>

        {{-- Floating Action Bar --}}
        <div class="fixed bottom-6 inset-x-0 z-40 flex justify-center px-4 pointer-events-none">
            <div
                class="bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border border-gray-200 dark:border-gray-800 shadow-2xl rounded-2xl px-5 py-3.5 flex items-center justify-between gap-6 pointer-events-auto max-w-xl w-full">
                <div class="flex items-center gap-2.5 text-xs text-gray-600 dark:text-gray-300 min-w-0">
                    <i data-lucide="shield-check" class="w-4 h-4 text-telkom-600 shrink-0"></i>
                    <span class="truncate">Konfigurasi hak akses <strong
                            class="text-gray-900 dark:text-white">{{ $user->name }}</strong></span>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('role-permission') }}"
                        class="px-4 py-2 text-xs font-semibold rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition">
                        Batal
                    </a>
                    <button type="submit" form="rolePermissionForm"
                        class="inline-flex items-center gap-1.5 px-5 py-2 text-xs font-semibold rounded-xl bg-telkom-600 hover:bg-telkom-700 text-white transition shadow-sm">
                        <i data-lucide="check" class="w-4 h-4"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleAppGroup(appSlug, state) {
                const checkboxes = document.querySelectorAll(`input[data-app="${appSlug}"]`);
                checkboxes.forEach(chk => {
                    chk.checked = state;
                });
            }

            function toggleAllGlobal(state) {
                const checkboxes = document.querySelectorAll('input.perm-checkbox');
                checkboxes.forEach(chk => {
                    chk.checked = state;
                });
            }
        </script>
    @endpush
@endsection
