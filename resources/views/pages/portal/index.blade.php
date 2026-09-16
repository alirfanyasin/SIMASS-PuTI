@extends('layouts.portal-layout')

@section('content')
    <div class="text-center mb-10 pt-4">
        <div
            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-telkom-50 dark:bg-telkom-950 text-telkom-700 dark:text-telkom-400 text-xs font-semibold uppercase tracking-wider mb-4 border border-telkom-200 dark:border-telkom-900">
            Portal Utama PuTI
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Selamat Datang, {{ auth()->user()->name }}</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-2.5 max-w-lg mx-auto text-sm sm:text-base">
            Pilih modul aplikasi yang ingin Anda akses di lingkungan Direktorat PuTI Telkom University Surabaya.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        {{-- 1. SIMASS Presensi (Active) --}}
        <a href="{{ route('presence.dashboard') }}"
            class="group relative flex flex-col justify-between bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-7 hover:border-telkom-600 dark:hover:border-telkom-600 hover:shadow-md transition-all duration-150 cursor-pointer select-none">
            <div class="pointer-events-none">
                <div class="flex items-center justify-between mb-5">
                    <div
                        class="w-13 h-13 rounded-2xl bg-telkom-50 dark:bg-telkom-950 text-telkom-700 dark:text-telkom-400 flex items-center justify-center">
                        <i data-lucide="calendar-check" class="w-6 h-6"></i>
                    </div>
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                        Aktif
                    </span>
                </div>
                <h3
                    class="font-bold text-xl text-gray-900 dark:text-white group-hover:text-telkom-700 dark:group-hover:text-telkom-400 transition-colors">
                    SIMASS Presensi
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">
                    Pencatatan absensi, pengajuan lembur, transfer poin, dan jadwal kehadiran kerja staff PuTI.
                </p>
            </div>
            <div
                class="pointer-events-none mt-6 pt-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between text-sm font-semibold text-telkom-700 dark:text-telkom-400">
                <span>Buka Presensi</span>
                <i data-lucide="arrow-right" class="w-4 h-4 transform group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>

        {{-- 2. e-Ticket PuTI (Phase 3) --}}
        <a href="{{ route('ticket.index') }}"
            class="group relative flex flex-col justify-between bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-7 hover:border-amber-500 dark:hover:border-amber-600 hover:shadow-md transition-all duration-150 cursor-pointer select-none">
            <div class="pointer-events-none">
                <div class="flex items-center justify-between mb-5">
                    <div
                        class="w-13 h-13 rounded-2xl bg-amber-50 dark:bg-amber-950 text-amber-700 dark:text-amber-400 flex items-center justify-center">
                        <i data-lucide="ticket" class="w-6 h-6"></i>
                    </div>
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-950 dark:text-amber-400 border border-amber-200 dark:border-amber-800">
                        on-development
                    </span>
                </div>
                <h3
                    class="font-bold text-xl text-gray-900 dark:text-white group-hover:text-amber-700 dark:group-hover:text-amber-400 transition-colors">
                    e-Ticket PuTI
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">
                    Pusat bantuan helpdesk, pelaporan kendala jaringan/fasilitas IT, serta integrasi AI Chatbot LUNA.
                </p>
            </div>
            <div
                class="pointer-events-none mt-6 pt-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between text-sm font-semibold text-amber-700 dark:text-amber-400">
                <span>Buka Ticketing</span>
                <i data-lucide="arrow-right" class="w-4 h-4 transform group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>

        {{-- 3. Inventaris PuTI (Phase 4 - Coming Soon) --}}
        <div
            class="relative flex flex-col justify-between bg-gray-50 dark:bg-gray-900 rounded-3xl border border-dashed border-gray-300 dark:border-gray-800 p-7 select-none">
            <div>
                <div class="flex items-center justify-between mb-5">
                    <div
                        class="w-13 h-13 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 dark:text-gray-500">
                        <i data-lucide="box" class="w-6 h-6"></i>
                    </div>
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                        coming-soon
                    </span>
                </div>
                <h3 class="font-bold text-xl text-gray-700 dark:text-gray-300">
                    Inventaris PuTI
                </h3>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-2 leading-relaxed">
                    Pencatatan aset lisensi software, SSL, server fisik, dan perangkat jaringan PuTI Surabaya.
                </p>
            </div>
            <div
                class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-800 flex items-center justify-between text-sm text-gray-400">
                <span>Segera Hadir</span>
                <span class="text-xs px-2 py-0.5 rounded bg-gray-200 dark:bg-gray-800 text-gray-600 dark:text-gray-400">Coming Soon</span>
            </div>
        </div>

    </div>
@endsection
