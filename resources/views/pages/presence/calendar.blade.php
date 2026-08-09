@extends('layouts.app-layout')

@section('title', 'Kalender')
@section('subtitle', 'Jadwal hari kerja dan hari libur')

@section('content')
  <div class="space-y-6">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Kalender Hari Kerja</h2>
        <p class="text-sm text-gray-500 mt-1">Daftar hari libur nasional dan akhir pekan</p>
      </div>

      <!-- Navigasi Bulan -->
      <div
        class="flex items-center gap-4 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-2 shadow-sm">
        <a href="{{ route('presence.calendar', ['month' => $prevMonthDate->month, 'year' => $prevMonthDate->year]) }}"
          class="p-2 text-gray-500 hover:text-telkom-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="m15 18-6-6 6-6" />
          </svg>
        </a>
        <div class="w-40 text-center font-bold text-gray-800 dark:text-gray-200">
          {{ $currentMonthName }} {{ $year }}
        </div>
        <a href="{{ route('presence.calendar', ['month' => $nextMonthDate->month, 'year' => $nextMonthDate->year]) }}"
          class="p-2 text-gray-500 hover:text-telkom-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="m9 18 6-6-6-6" />
          </svg>
        </a>
      </div>
    </div>

    <!-- Kalender -->
    <div
      class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
      <!-- Header Hari -->
      <div class="grid grid-cols-7 border-b border-gray-200 dark:border-gray-800">
        @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $dayName)
          <div
            class="px-2 sm:px-4 py-3 text-center text-xs sm:text-sm font-semibold uppercase tracking-wider {{ in_array($dayName, ['Sabtu', 'Minggu']) ? 'text-red-500' : 'text-gray-500' }}">
            <span class="hidden sm:inline">{{ $dayName }}</span>
            <span class="sm:hidden">{{ substr($dayName, 0, 3) }}</span>
          </div>
        @endforeach
      </div>

      <!-- Grid Tanggal -->
      <div class="grid grid-cols-7 auto-rows-fr">
        @foreach ($calendarData as $data)
          @if ($data === null)
            <div
              class="min-h-[100px] sm:min-h-[120px] p-2 sm:p-4 border-r border-b border-gray-100 dark:border-gray-800/50 bg-gray-50/50 dark:bg-gray-800/20">
            </div>
          @else
            @php
              $isOff = $data['is_off'];
              $isToday = $data['is_today'];
            @endphp
            <div
              class="min-h-[80px] sm:min-h-[120px] p-1.5 sm:p-3 border-r border-b border-gray-100 dark:border-gray-800/50 transition relative group {{ $isOff ? 'bg-red-50/30 dark:bg-red-900/10' : 'hover:bg-gray-50 dark:hover:bg-gray-800/50' }}">
              <div class="flex flex-col sm:flex-row items-center sm:items-start justify-between">
                <span
                  class="inline-flex items-center justify-center w-6 h-6 sm:w-8 sm:h-8 rounded-full text-xs sm:text-sm font-semibold {{ $isToday ? 'bg-telkom-600 text-white' : ($isOff ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-300 group-hover:text-telkom-600') }}">
                  {{ $data['day'] }}
                </span>

                @if (!$isOff && !$isToday)
                  <span class="w-1 h-1 sm:w-1.5 sm:h-1.5 rounded-full bg-green-500 mt-1 sm:mt-2 mr-0 sm:mr-1"></span>
                @endif
              </div>

              @if ($data['holiday'])
                <div
                  class="mt-1 sm:mt-2 text-[9px] sm:text-xs font-medium text-red-600 dark:text-red-400 leading-tight text-center sm:text-left line-clamp-2">
                  {{ $data['holiday'] }}
                </div>
              @endif

              @if ($isOff && !$data['holiday'])
                <div
                  class="mt-1 sm:mt-2 text-[9px] sm:text-xs font-medium text-red-400 dark:text-red-500/70 leading-tight text-center sm:text-left">
                  Akhir Pekan
                </div>
              @endif

              @if ($data['presence_count'] > 0)
                <div class="mt-1.5 sm:mt-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-[9px] sm:text-xs font-semibold px-1 sm:px-1.5 py-0.5 rounded flex items-center justify-center sm:justify-start gap-1 w-max mx-auto sm:mx-0">
                  <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><polyline points="17 11 19 13 23 9"/></svg>
                  <span class="hidden sm:inline">{{ $data['presence_count'] }} Hadir</span>
                  <span class="sm:hidden">{{ $data['presence_count'] }}</span>
                </div>
              @endif
            </div>
          @endif
        @endforeach
      </div>
    </div>

    <!-- Legend -->
    <div
      class="flex flex-wrap gap-4 sm:gap-6 text-xs sm:text-sm text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-900 p-4 rounded-xl border border-gray-200 dark:border-gray-800">
      <div class="flex items-center gap-2">
        <span class="w-3 h-3 rounded-full bg-green-500"></span> Hari Kerja
      </div>
      <div class="flex items-center gap-2">
        <span
          class="w-3 h-3 rounded-full bg-red-100 border border-red-200 dark:bg-red-900/30 dark:border-red-900/50"></span>
        Hari Libur / Akhir Pekan
      </div>
      <div class="flex items-center gap-2">
        <span class="w-3 h-3 rounded-full bg-telkom-600"></span> Hari Ini
      </div>
    </div>
  </div>
@endsection
