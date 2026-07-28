@extends('layouts.app-layout')

@section('title', 'Student Staff')
@section('subtitle', 'Kelola data keanggotaan dan presensi wajah student staff PuTI')

@php
  $staffList = \App\Models\User::whereNotNull('position')->get()->map(function($staff) {
      return [
          'name' => $staff->name,
          'nim' => '-', 
          'email' => $staff->email ?? (strtolower(str_replace(' ', '', $staff->name)) . '@student.telkomuniversity.ac.id'),
          'avatar' => null,
          'role' => $staff->position ?? '-',
          'face_registered' => !is_null($staff->face_descriptor),
      ];
  });
@endphp

@section('content')
  <!-- ============ VIEW: STUDENT STAFF ============ -->
  <section id="view-student-staff" class="view space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
      <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 lg:p-5 card-hover">
        <div class="flex items-center justify-between">
          <div class="w-10 h-10 rounded-xl bg-telkom-100 dark:bg-telkom-900/30 flex items-center justify-center">
            <svg class="w-5 h-5 text-telkom-600 dark:text-telkom-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </div>
          <span class="text-xs font-semibold text-telkom-600">Total</span>
        </div>
        <p class="mt-3 text-2xl lg:text-3xl font-bold">18</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Total Student Staff</p>
      </div>

      <div
        class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 lg:p-5 card-hover">
        <div class="flex items-center justify-between">
          <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
          </div>
          <span class="text-xs font-semibold text-green-600">88%</span>
        </div>
        <p class="mt-3 text-2xl lg:text-3xl font-bold">16</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Status Aktif</p>
      </div>

      <div
        class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 lg:p-5 card-hover">
        <div class="flex items-center justify-between">
          <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><path d="M9 9h.01"/><path d="M15 9h.01"/></svg>
          </div>
          <span class="text-xs font-semibold text-purple-600">Registered</span>
        </div>
        <p class="mt-3 text-2xl lg:text-3xl font-bold">14</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Presensi Wajah Terdaftar</p>
      </div>

      <div
        class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 lg:p-5 card-hover">
        <div class="flex items-center justify-between">
          <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m18 16 4-4-4-4"/><path d="m6 8-4 4 4 4"/><path d="m14.5 4-5 16"/></svg>
          </div>
          <span class="text-xs font-semibold text-blue-600">Divisi</span>
        </div>
        <p class="mt-3 text-2xl lg:text-3xl font-bold">10 / 8</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Web Dev / Designer</p>
      </div>
    </div>

    <!-- Main Table Card -->
    <div
      class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
      <!-- Filter Bar -->
      <div
        class="p-4 lg:p-5 border-b border-gray-100 dark:border-gray-800 flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-3 flex-1 min-w-0">
          <div class="flex items-center gap-2 px-3 py-2 bg-gray-100 dark:bg-gray-800 rounded-xl flex-1 max-w-xs">
            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" placeholder="Cari nama, NIM, atau email..."
              class="bg-transparent text-sm outline-none flex-1 min-w-0">
          </div>
          <select class="px-3 py-2 bg-gray-100 dark:bg-gray-800 rounded-xl text-sm outline-none">
            <option value="">Semua Jabatan</option>
            <option value="Web Developer">Web Developer</option>
            <option value="Designer">Designer</option>
          </select>
          <select class="px-3 py-2 bg-gray-100 dark:bg-gray-800 rounded-xl text-sm outline-none">
            <option value="">Presensi Wajah</option>
            <option value="registered">Terdaftar</option>
            <option value="unregistered">Belum Terdaftar</option>
          </select>
        </div>

        <button
          class="px-4 py-2 gradient-telkom text-white rounded-xl text-sm font-semibold flex items-center gap-2 hover:opacity-90 transition shadow-md shadow-telkom-600/20">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
          <span>Tambah Staff</span>
        </button>
      </div>

      <!-- Table (Desktop View) -->
      <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 text-xs uppercase">
            <tr>
              <th class="px-5 py-3.5 text-left font-semibold">Foto & Profile</th>
              <th class="px-5 py-3.5 text-left font-semibold">Jabatan</th>
              <th class="px-5 py-3.5 text-left font-semibold">Presensi Wajah</th>
              <th class="px-5 py-3.5 text-center font-semibold">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
            @foreach ($staffList as $staff)
              <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                <!-- Foto Profile & Nama -->
                <td class="px-5 py-4">
                  <div class="flex items-center gap-3">
                    <img src="{{ $staff['avatar'] }}" alt="{{ $staff['name'] }}"
                      class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-100 dark:ring-gray-800">
                    <div>
                      <p class="font-semibold text-gray-900 dark:text-gray-100 leading-tight">{{ $staff['name'] }}</p>
                      <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $staff['nim'] }} •
                        {{ $staff['email'] }}</p>
                    </div>
                  </div>
                </td>

                <!-- Jabatan -->
                <td class="px-5 py-4">
                  @if ($staff['role'] === 'Web Developer')
                    <span
                      class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m18 16 4-4-4-4"/><path d="m6 8-4 4 4 4"/><path d="m14.5 4-5 16"/></svg>
                      {{ $staff['role'] }}
                    </span>
                  @else
                    <span
                      class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="13.5" cy="6.5" r=".5"/><circle cx="17.5" cy="10.5" r=".5"/><circle cx="8.5" cy="7.5" r=".5"/><circle cx="6.5" cy="12.5" r=".5"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/></svg>
                      {{ $staff['role'] }}
                    </span>
                  @endif
                </td>

                <!-- Presensi Wajah -->
                <td class="px-5 py-4">
                  @if ($staff['face_registered'])
                    <span
                      class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><path d="M9 9h.01"/><path d="M15 9h.01"/></svg>
                      Terdaftar
                    </span>
                  @else
                    <span
                      class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                      Belum Terdaftar
                    </span>
                  @endif
                </td>

                <!-- Aksi -->
                <td class="px-5 py-4 text-center">
                  <div class="flex items-center justify-center gap-1">
                    <button
                      class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg text-gray-500 hover:text-telkom-600 transition"
                      title="Edit Staff">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    </button>
                    <button
                      class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg text-gray-500 hover:text-purple-600 transition"
                      title="Registrasi Wajah">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><path d="M9 9h.01"/><path d="M15 9h.01"/></svg>
                    </button>
                    <button
                      class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg text-gray-500 hover:text-red-600 transition"
                      title="Hapus Staff">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                    </button>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Card List (Mobile View) -->
      <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-800">
        @foreach ($staffList as $staff)
          <div class="p-4 space-y-3">
            <div class="flex items-center gap-3">
              <img src="{{ $staff['avatar'] }}" alt="{{ $staff['name'] }}"
                class="w-12 h-12 rounded-full object-cover ring-2 ring-gray-100 dark:ring-gray-800">
              <div class="flex-1 min-w-0">
                <p class="font-semibold text-sm truncate">{{ $staff['name'] }}</p>
                <p class="text-xs text-gray-500 truncate">{{ $staff['nim'] }}</p>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-2 text-xs pt-1">
              <div>
                <p class="text-gray-400">Jabatan</p>
                <p class="font-medium text-gray-700 dark:text-gray-300 mt-0.5">{{ $staff['role'] }}</p>
              </div>
              <div>
                <p class="text-gray-400">Presensi Wajah</p>
                @if ($staff['face_registered'])
                  <p class="font-medium text-green-600 dark:text-green-400 mt-0.5">Terdaftar</p>
                @else
                  <p class="font-medium text-amber-600 dark:text-amber-400 mt-0.5">Belum Terdaftar</p>
                @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <!-- Pagination -->
      <div class="p-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between text-sm">
        <p class="text-gray-500 text-xs sm:text-sm">Menampilkan 7 dari 18 Student Staff</p>
        <div class="flex gap-1">
          <button
            class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
          </button>
          <button
            class="w-8 h-8 rounded-lg gradient-telkom text-white flex items-center justify-center font-semibold text-xs">1</button>
          <button
            class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-800 text-xs">2</button>
          <button
            class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-800 text-xs">3</button>
          <button
            class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
          </button>
        </div>
      </div>
    </div>
  </section>
@endsection
