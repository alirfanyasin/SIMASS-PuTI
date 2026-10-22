@extends('layouts.portal-layout')

@section('content')
    <div class="text-center mb-10 pt-4">
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">Halo
            {{ auth()->user()->name }},</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-2.5 max-w-lg mx-auto text-sm sm:text-base">
            <b>Selamat Datang di Portal PuTI Telkom University Surabaya</b>
            <br>
            Silahkan pilih modul aplikasi yang ingin Anda akses.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($modules as $mod)
            @if ($mod->status === 'active')
                @php
                    $isDark = $mod->code === 'admin';
                    $isRed = $mod->code === 'presensi';
                    $isGreen = $mod->code === 'eticket';
                @endphp
                <a href="{{ $mod->route_name ? route($mod->route_name) : '#' }}"
                    class="group relative flex flex-col justify-between bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-7 {{ $isDark ? 'hover:border-gray-900 dark:hover:border-gray-100' : ($isGreen ? 'hover:border-emerald-600 dark:hover:border-emerald-500' : 'hover:border-telkom-600 dark:hover:border-telkom-600') }} hover:shadow-md transition-all duration-150 cursor-pointer select-none">
                    <div class="pointer-events-none">
                        <div class="flex items-center justify-between mb-5">
                            <div
                                class="w-13 h-13 rounded-2xl flex items-center justify-center shadow-xs {{ $isDark ? 'bg-gray-900 text-white dark:bg-gray-100 dark:text-gray-900' : ($isGreen ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400' : 'bg-telkom-50 dark:bg-telkom-950 text-telkom-700 dark:text-telkom-400') }}">
                                <i data-lucide="{{ $mod->icon }}" class="w-6 h-6"></i>
                            </div>
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $isDark ? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-700' : ($isGreen ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800' : 'bg-telkom-50 text-telkom-700 dark:bg-telkom-950 dark:text-telkom-400 border border-telkom-200 dark:border-telkom-800') }}">
                                Aktif
                            </span>
                        </div>
                        <h3
                            class="font-bold text-xl text-gray-900 dark:text-white {{ $isDark ? 'group-hover:text-gray-900 dark:group-hover:text-gray-100' : ($isGreen ? 'group-hover:text-emerald-600 dark:group-hover:text-emerald-400' : 'group-hover:text-telkom-700 dark:group-hover:text-telkom-400') }} transition-colors">
                            {{ $mod->name }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">
                            {{ $mod->description }}
                        </p>
                        @if ($mod->code === 'admin')
                            <p class="text-xs text-gray-500 dark:text-gray-700 mt-4">Kontrol Terpusat PuTI Surabaya</p>
                        @elseif ($mod->code === 'presensi')
                            <p class="text-xs text-gray-500 dark:text-gray-700 mt-4">Courtesy to Irfan Yasin S.Kom (Student
                                Staff PuTI Gen. 2023)</p>
                        @elseif ($mod->code === 'eticket')
                            <p class="text-xs text-gray-500 dark:text-gray-700 mt-4">Courtesy to Reynanda Shaquile S.Kom
                                (TLH PuTI Gen. 2024)</p>
                        @endif
                    </div>
                    <div
                        class="pointer-events-none mt-6 pt-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between text-sm font-semibold {{ $isDark ? 'text-gray-900 dark:text-gray-100' : ($isGreen ? 'text-emerald-600 dark:text-emerald-400' : 'text-telkom-700 dark:text-telkom-400') }}">
                        <span>Buka {{ $mod->name }}</span>
                        <i data-lucide="arrow-right"
                            class="w-4 h-4 transform group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>
            @else
                @if ($mod->code === 'ning')
                    <div
                        class="relative flex flex-col justify-between bg-white dark:bg-gray-900 rounded-3xl border border-blue-200 dark:border-blue-900/50 p-7 shadow-sm select-none">
                        <div>
                            <div class="flex items-center justify-between mb-5">
                                <div
                                    class="w-13 h-13 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                                    <i data-lucide="activity" class="w-6 h-6"></i>
                                </div>
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                    Aplikasi Eksisting
                                </span>
                            </div>
                            <h3 class="font-bold text-xl text-gray-900 dark:text-white">
                                {{ $mod->name }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">
                                {{ $mod->description }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-700 mt-4">Courtesy to Reza Eka Firmansyah S.Kom
                                (TLH PuTI Gen. 2024)</p>
                        </div>
                        <div
                            class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between text-sm text-blue-600 dark:text-blue-400 font-semibold">
                            <span
                                class="inline-flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400 font-normal">
                                <i data-lucide="webhook" class="w-3.5 h-3.5"></i> Integrasi Webhook
                            </span>
                            <span
                                class="text-xs px-2 py-0.5 rounded bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300 font-semibold">Fase
                                6</span>
                        </div>
                    </div>
                @else
                    <div
                        class="relative flex flex-col justify-between bg-gray-50 dark:bg-gray-900 rounded-3xl border border-dashed border-gray-300 dark:border-gray-800 p-7 select-none">
                        <div>
                            <div class="flex items-center justify-between mb-5">
                                <div
                                    class="w-13 h-13 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 dark:text-gray-500">
                                    <i data-lucide="{{ $mod->icon }}" class="w-6 h-6"></i>
                                </div>
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                    coming-soon
                                </span>
                            </div>
                            <h3 class="font-bold text-xl text-gray-700 dark:text-gray-300">
                                {{ $mod->name }}
                            </h3>
                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-2 leading-relaxed">
                                {{ $mod->description }}
                            </p>
                        </div>
                        <div
                            class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-800 flex items-center justify-between text-sm text-gray-400">
                            <span>Segera Hadir</span>
                            <span
                                class="text-xs px-2 py-0.5 rounded bg-gray-200 dark:bg-gray-800 text-gray-600 dark:text-gray-400">
                                {{ $mod->code === 'webosistant' ? 'Fase 4' : 'Fase 5' }}
                            </span>
                        </div>
                    </div>
                @endif
            @endif
        @endforeach
    </div>
@endsection
