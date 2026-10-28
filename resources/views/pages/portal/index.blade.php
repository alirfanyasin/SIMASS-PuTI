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
            @php
                $theme = $mod->getColorTheme();
            @endphp
            @if ($mod->status === 'active')
                <a href="{{ $mod->getTargetUrl() }}"
                    @if ($mod->isExternalLink()) target="_blank" rel="noopener noreferrer" @endif
                    class="group relative flex flex-col justify-between bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-7 {{ $theme['border_hover'] }} hover:shadow-md transition-all duration-150 cursor-pointer select-none">
                    <div class="pointer-events-none">
                        <div class="flex items-center justify-between mb-5">
                            <div
                                class="w-13 h-13 rounded-2xl flex items-center justify-center shadow-xs {{ $theme['bg_icon'] }}">
                                <i data-lucide="{{ $mod->icon }}" class="w-6 h-6"></i>
                            </div>
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $theme['badge'] }}">
                                Aktif
                            </span>
                        </div>
                        <h3
                            class="font-bold text-xl text-gray-900 dark:text-white transition-colors">
                            {{ $mod->name }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">
                            {{ $mod->description }}
                        </p>
                        @if ($mod->credit)
                            <p class="text-xs text-gray-500 dark:text-gray-700 mt-4">{{ $mod->credit }}</p>
                        @endif
                    </div>
                    <div
                        class="pointer-events-none mt-6 pt-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between text-sm font-semibold {{ $theme['text_accent'] }}">
                        <span>Buka {{ $mod->name }}</span>
                        <i data-lucide="{{ $mod->isExternalLink() ? 'external-link' : 'arrow-right' }}"
                            class="w-4 h-4 transform group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>
            @else
                <div
                    class="relative flex flex-col justify-between bg-white dark:bg-gray-900 rounded-3xl border border-dashed border-gray-300 dark:border-gray-800 p-7 select-none">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div
                                class="w-13 h-13 rounded-2xl {{ $theme['bg_icon'] }} flex items-center justify-center">
                                <i data-lucide="{{ $mod->icon }}" class="w-6 h-6"></i>
                            </div>
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $theme['badge'] }}">
                                {{ $mod->status === 'coming_soon' ? 'Segera Hadir' : 'Maintenance' }}
                            </span>
                        </div>
                        <h3 class="font-bold text-xl text-gray-900 dark:text-white">
                            {{ $mod->name }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">
                            {{ $mod->description }}
                        </p>
                        @if ($mod->credit)
                            <p class="text-xs text-gray-500 dark:text-gray-700 mt-4">{{ $mod->credit }}</p>
                        @endif
                    </div>
                    <div
                        class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between text-sm {{ $theme['text_accent'] }} font-semibold">
                        <span class="inline-flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400 font-normal">
                            {{ $mod->status === 'coming_soon' ? 'Segera Hadir' : 'Dalam Pemeliharaan' }}
                        </span>
                        <span class="text-xs px-2 py-0.5 rounded {{ $theme['badge'] }} font-semibold">
                            #{{ $mod->order }}
                        </span>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endsection
