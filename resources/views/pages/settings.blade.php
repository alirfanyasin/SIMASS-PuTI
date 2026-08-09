@extends('layouts.app-layout')

@section('title', 'Pengaturan')

@section('content')
<div class="px-6 py-8 mx-auto max-w-7xl h-full flex flex-col">
  <!-- Header -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
      <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">Pengaturan Sistem</h1>
      <p class="text-sm text-gray-500 mt-1 font-medium">Atur konfigurasi global untuk aplikasi presensi.</p>
    </div>
  </div>

  <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 md:p-8">
    <form action="{{ route('settings.update') }}" method="POST" class="max-w-2xl space-y-6">
      @csrf

      <div>
        <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">Pengaturan Lokasi Kantor (Geolocation)</h2>
        <p class="text-sm text-gray-500 mb-6">Tentukan titik pusat (Latitude & Longitude) kantor dan radius maksimal untuk membatasi lokasi presensi Student Staff.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Latitude</label>
            <input type="text" name="office_latitude" value="{{ old('office_latitude', $settings['office_latitude'] ?? '') }}" required
              class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl outline-none focus:ring-2 focus:ring-telkom-500">
            @error('office_latitude') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
          </div>
          
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Longitude</label>
            <input type="text" name="office_longitude" value="{{ old('office_longitude', $settings['office_longitude'] ?? '') }}" required
              class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl outline-none focus:ring-2 focus:ring-telkom-500">
            @error('office_longitude') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
          </div>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Radius Presensi (Meter)</label>
          <div class="relative">
            <input type="number" name="office_radius" value="{{ old('office_radius', $settings['office_radius'] ?? '100') }}" required min="1"
              class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl outline-none focus:ring-2 focus:ring-telkom-500 pr-16">
            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">Meter</span>
          </div>
          @error('office_radius') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      <div>
        <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">Pengaturan Waktu Operasional</h2>
        <p class="text-sm text-gray-500 mb-6">Tentukan jam masuk dan jam pulang untuk perhitungan keterlambatan dan jam kerja.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Jam Masuk</label>
            <input type="time" name="jam_masuk" value="{{ old('jam_masuk', $settings['jam_masuk'] ?? '08:30') }}" required
              class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl outline-none focus:ring-2 focus:ring-telkom-500">
            @error('jam_masuk') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
          </div>
          
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Jam Pulang</label>
            <input type="time" name="jam_pulang" value="{{ old('jam_pulang', $settings['jam_pulang'] ?? '16:30') }}" required
              class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl outline-none focus:ring-2 focus:ring-telkom-500">
            @error('jam_pulang') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
          </div>
        </div>
      </div>

      <div class="pt-4 border-t border-gray-100 dark:border-gray-800 flex justify-end">
        <button type="submit" class="px-6 py-2.5 gradient-telkom text-white font-bold rounded-xl shadow-lg shadow-telkom-600/30 hover:opacity-90 transition active:scale-95">
          Simpan Pengaturan
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
