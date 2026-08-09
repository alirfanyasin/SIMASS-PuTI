@extends('layouts.app-layout')

@section('title', 'Manajemen Hari Libur')
@section('subtitle', 'Kelola daftar hari libur nasional dan cuti')

@section('content')
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold">Hari Libur</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola daftar hari libur untuk periode aktif</p>
      </div>
      <!-- Year Filter -->
      <form method="GET" action="{{ route('holiday.index') }}" class="flex items-center gap-2">
        <select name="year" onchange="this.form.submit()"
          class="px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
          @foreach ($years as $y)
            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
          @endforeach
        </select>
      </form>
    </div>

    <!-- Flash -->
    @if (session('status'))
      <div class="p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 text-sm flex items-center gap-3">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
        {{ session('status') }}
      </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Add Holiday Form -->
      <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
          <h3 class="font-semibold text-base mb-4">Tambah Hari Libur</h3>
          <form method="POST" action="{{ route('holiday.store') }}" class="space-y-4">
            @csrf
            <div>
              <label class="block text-xs font-semibold text-gray-500 mb-1">Tanggal</label>
              <input type="date" name="date" required value="{{ old('date') }}"
                class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
              @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="block text-xs font-semibold text-gray-500 mb-1">Nama Hari Libur</label>
              <input type="text" name="name" required value="{{ old('name') }}" placeholder="Contoh: Hari Kemerdekaan"
                class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
              @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center gap-2">
              <input type="checkbox" name="is_national" id="is_national" value="1" {{ old('is_national', true) ? 'checked' : '' }}
                class="rounded border-gray-300 text-telkom-600 focus:ring-telkom-500">
              <label for="is_national" class="text-sm text-gray-600 dark:text-gray-400">Libur Nasional</label>
            </div>
            <button type="submit"
              class="w-full py-2.5 gradient-telkom text-white rounded-xl text-sm font-semibold hover:opacity-90 transition">
              Tambahkan
            </button>
          </form>
        </div>
      </div>

      <!-- Holiday List -->
      <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <h3 class="font-semibold text-base">Daftar Hari Libur {{ $year }}</h3>
            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $holidays->count() }} hari libur</span>
          </div>

          @if ($holidays->isEmpty())
            <div class="py-16 text-center text-gray-400 dark:text-gray-600">
              <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>
              </svg>
              <p class="text-sm">Belum ada hari libur untuk tahun {{ $year }}</p>
            </div>
          @else
            <div class="divide-y divide-gray-100 dark:divide-gray-800">
              @foreach ($holidays as $holiday)
                <div class="px-6 py-4 flex items-center justify-between gap-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition group">
                  <div class="flex items-center gap-4 min-w-0">
                    <div class="w-10 h-10 rounded-xl {{ $holiday->is_national ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' }} flex items-center justify-center shrink-0 font-bold text-sm">
                      {{ $holiday->date->format('d') }}
                    </div>
                    <div class="min-w-0">
                      <p class="font-medium text-sm truncate">{{ $holiday->name }}</p>
                      <p class="text-xs text-gray-500 dark:text-gray-400">{{ $holiday->date->translatedFormat('l, d F Y') }}</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-2 shrink-0">
                    <span class="text-xs px-2 py-1 rounded-lg {{ $holiday->is_national ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' }}">
                      {{ $holiday->is_national ? 'Nasional' : 'Lokal' }}
                    </span>
                    <!-- Delete -->
                    <form method="POST" action="{{ route('holiday.destroy', $holiday) }}"
                      onsubmit="return confirm('Hapus hari libur ini?')" class="inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit"
                        class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition opacity-0 group-hover:opacity-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                          <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/>
                        </svg>
                      </button>
                    </form>
                  </div>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection
