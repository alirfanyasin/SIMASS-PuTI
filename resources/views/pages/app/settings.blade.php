@extends('layouts.app-layout')

@section('title', 'Pengaturan')
@section('subtitle', 'Kelola preferensi dan keamanan akun')

@section('content')
  <!-- ============ VIEW: PENGATURAN ============ -->
  <section id="view-settings" class="view space-y-6">
    <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-6 space-y-6">
      <h3 class="font-bold text-lg border-b border-gray-100 dark:border-gray-800 pb-3">Pengaturan Aplikasi</h3>

      <div class="space-y-4">
        <div class="flex items-center justify-between py-2">
          <div>
            <p class="font-semibold text-sm">Notifikasi Presensi</p>
            <p class="text-xs text-gray-500">Kirim pengingat sebelum jam masuk & pulang</p>
          </div>
          <input type="checkbox" checked class="toggle accent-telkom-600 w-5 h-5 rounded cursor-pointer">
        </div>

        <div class="flex items-center justify-between py-2 border-t border-gray-100 dark:border-gray-800">
          <div>
            <p class="font-semibold text-sm">Mode Gelap (Dark Mode)</p>
            <p class="text-xs text-gray-500">Sesuaikan tampilan dengan preferensi Anda</p>
          </div>
          <button onclick="toggleDark()"
            class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 rounded-xl text-xs font-semibold hover:bg-gray-200 dark:hover:bg-gray-700 transition">
            Beralih Mode
          </button>
        </div>

        <div class="flex items-center justify-between py-2 border-t border-gray-100 dark:border-gray-800">
          <div>
            <p class="font-semibold text-sm">Ganti Kata Sandi</p>
            <p class="text-xs text-gray-500">Perbarui kata sandi akun secara berkala</p>
          </div>
          <button
            class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 rounded-xl text-xs font-semibold hover:bg-gray-200 dark:hover:bg-gray-700 transition">
            Ubah Password
          </button>
        </div>
      </div>
    </div>
  </section>
@endsection
