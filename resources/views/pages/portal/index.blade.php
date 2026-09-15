@extends('layouts.portal-layout')

@section('content')
    <div class="text-center mb-10 pt-4">
        <div
            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 text-xs font-semibold uppercase tracking-wider mb-4 border border-red-100 dark:border-red-900/30">
            <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
            Sistem Informasi Manajemen Layanan PuTI
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Selamat Datang, {{ auth()->user()->name }}</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-2.5 max-w-lg mx-auto text-sm sm:text-base">
            Pilih modul aplikasi yang ingin Anda akses di lingkungan Direktorat PuTI Telkom University Surabaya.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        {{-- 1. SIMASS Presensi (Active) --}}
        <a href="{{ route('presence.dashboard') }}"
            class="group relative flex flex-col justify-between bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-7 hover:shadow-xl hover:shadow-red-500/5 hover:border-red-400 dark:hover:border-red-700 transition-all duration-300 hover:-translate-y-1">
            <div>
                <div class="flex items-center justify-between mb-5">
                    <div
                        class="w-13 h-13 rounded-2xl bg-red-50 dark:bg-red-950/50 flex items-center justify-center text-red-600 dark:text-red-400 group-hover:scale-105 group-hover:bg-red-600 group-hover:text-white transition-all duration-200">
                        <i data-lucide="calendar-check" class="w-6 h-6"></i>
                    </div>
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/40">
                        Aktif
                    </span>
                </div>
                <h3
                    class="font-bold text-xl text-gray-900 dark:text-white group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">
                    SIMASS Presensi
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">
                    Pencatatan absensi, pengajuan lembur, transfer poin, dan jadwal kehadiran kerja staff PuTI.
                </p>
            </div>
            <div
                class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800/60 flex items-center justify-between text-sm font-semibold text-red-600 dark:text-red-400">
                <span>Buka Presensi</span>
                <i data-lucide="arrow-right" class="w-4 h-4 transform group-hover:translate-x-1.5 transition-transform"></i>
            </div>
        </a>

        {{-- 2. e-Ticket PuTI (Phase 3) --}}
        <a href="{{ route('ticket.index') }}"
            class="group relative flex flex-col justify-between bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-7 hover:shadow-xl hover:shadow-amber-500/5 hover:border-amber-400 dark:hover:border-amber-700 transition-all duration-300 hover:-translate-y-1">
            <div>
                <div class="flex items-center justify-between mb-5">
                    <div
                        class="w-13 h-13 rounded-2xl bg-amber-50 dark:bg-amber-950/50 flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:scale-105 group-hover:bg-amber-600 group-hover:text-white transition-all duration-200">
                        <i data-lucide="ticket" class="w-6 h-6"></i>
                    </div>
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200 dark:border-amber-800/40">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        on-development
                    </span>
                </div>
                <h3
                    class="font-bold text-xl text-gray-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">
                    e-Ticket PuTI
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">
                    Pusat bantuan helpdesk, pelaporan kendala jaringan/fasilitas IT, serta integrasi AI Chatbot LUNA.
                </p>
            </div>
            <div
                class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800/60 flex items-center justify-between text-sm font-semibold text-amber-600 dark:text-amber-400">
                <span>Buka Ticketing</span>
                <i data-lucide="arrow-right" class="w-4 h-4 transform group-hover:translate-x-1.5 transition-transform"></i>
            </div>
        </a>

        {{-- 3. Inventaris PuTI (Phase 4 - Coming Soon) --}}
        <div
            class="relative flex flex-col justify-between bg-gray-50/70 dark:bg-gray-900/40 rounded-3xl border border-dashed border-gray-300 dark:border-gray-800 p-7 opacity-80">
            <div>
                <div class="flex items-center justify-between mb-5">
                    <div
                        class="w-13 h-13 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 dark:text-gray-500">
                        <i data-lucide="box" class="w-6 h-6"></i>
                    </div>
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-200/70 text-gray-600 dark:bg-gray-800 dark:text-gray-400">
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
                class="mt-6 pt-4 border-t border-gray-200/60 dark:border-gray-800/60 flex items-center justify-between text-sm text-gray-400">
                <span>Segera Hadir</span>
                <span class="text-xs px-2 py-0.5 rounded bg-gray-200 dark:bg-gray-800">Coming Soon</span>
            </div>
        </div>

    </div>
@endsection
