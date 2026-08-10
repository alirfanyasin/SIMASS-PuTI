@extends('layouts.app-layout')

@section('title', 'Student Staff')
@section('subtitle', 'Kelola data keanggotaan dan presensi wajah student staff PuTI')
@section('content')
  @if (session('status'))
    <div class="p-4 mb-4 text-sm text-green-800 rounded-2xl bg-green-50 dark:bg-green-900/30 dark:text-green-400 border border-green-100 dark:border-green-800/30">
      {{ session('status') }}
    </div>
  @endif

  @if ($errors->any())
    <div class="p-4 mb-4 text-sm text-red-800 rounded-2xl bg-red-50 dark:bg-red-900/30 dark:text-red-400 border border-red-100 dark:border-red-800/30">
      <ul class="list-disc pl-5 space-y-1">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

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
        <p class="mt-3 text-2xl lg:text-3xl font-bold">{{ $totalStudentStaff }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Total Student Staff</p>
      </div>

      <div
        class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 lg:p-5 card-hover">
        <div class="flex items-center justify-between">
          <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
          </div>
          <span class="text-xs font-semibold text-green-600">{{ $activePercentage }}%</span>
        </div>
        <p class="mt-3 text-2xl lg:text-3xl font-bold">{{ $activeCount }}</p>
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
        <p class="mt-3 text-2xl lg:text-3xl font-bold">{{ $totalFaceRegistered }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Presensi Wajah Terdaftar</p>
      </div>

      <div
        class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 lg:p-5 card-hover">
        <div class="flex items-center justify-between">
          <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </div>
          <span class="text-xs font-semibold text-blue-600">Staff</span>
        </div>
        <p class="mt-3 text-2xl lg:text-3xl font-bold">{{ $totalStaff }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Total Staff</p>
      </div>
    </div>

    <!-- Main Table Card -->
    <div
      class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
      <!-- Filter Bar -->
      <div
        class="p-4 lg:p-5 border-b border-gray-100 dark:border-gray-800 flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-3 flex-1 min-w-0">
          <form method="GET" action="{{ route('presence.student-staff') }}" class="flex items-center gap-2 px-3 py-2 bg-gray-100 dark:bg-gray-800 rounded-xl flex-1 max-w-xs">
            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, NIM, atau email..."
              class="bg-transparent text-sm outline-none flex-1 min-w-0">
            @if($search)
              <a href="{{ route('presence.student-staff') }}" class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">Reset</a>
            @endif
          </form>
        </div>

        <button onclick="openTambahStaffModal()"
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
              <th class="px-5 py-3.5 text-left font-semibold">Kategori</th>
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
                    @php
                      $words = explode(' ', $staff['name']);
                      $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                    @endphp
                    <div class="w-10 h-10 rounded-full bg-telkom-100 dark:bg-telkom-900/30 text-telkom-600 flex items-center justify-center font-bold text-sm uppercase shrink-0 ring-2 ring-gray-100 dark:ring-gray-800">
                      {{ $initials }}
                    </div>
                    <div>
                      <p class="font-semibold text-gray-900 dark:text-gray-100 leading-tight">{{ $staff['name'] }}</p>
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

                <!-- Kategori -->
                <td class="px-5 py-4">
                  @if ($staff['type'] === 'Student Staff')
                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                      Student Staff
                    </span>
                  @else
                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                      Staf
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
                      onclick="openEditStaffModal('{{ $staff['id'] }}', '{{ addslashes($staff['name']) }}', '{{ addslashes($staff['username']) }}', '{{ addslashes($staff['nim']) }}', '{{ addslashes($staff['phone']) }}', '{{ addslashes($staff['role']) }}', '{{ addslashes($staff['email']) }}', '{{ $staff['type'] }}')"
                      class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg text-gray-500 hover:text-telkom-600 transition"
                      title="Edit Staff">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    </button>
                    <button
                      onclick="confirmDelete('{{ route('presence.student-staff.destroy', $staff['id']) }}', '{{ addslashes($staff['name']) }}')"
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
              @php
                $words = explode(' ', $staff['name']);
                $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
              @endphp
              <div class="w-12 h-12 rounded-full bg-telkom-100 dark:bg-telkom-900/30 text-telkom-600 flex items-center justify-center font-bold text-base uppercase shrink-0 ring-2 ring-gray-100 dark:ring-gray-800">
                {{ $initials }}
              </div>
              <div class="flex-1 min-w-0">
                <p class="font-semibold text-sm truncate">{{ $staff['name'] }}</p>
              </div>
            </div>

            <div class="grid grid-cols-3 gap-2 text-xs pt-1">
              <div>
                <p class="text-gray-400">Jabatan</p>
                <p class="font-medium text-gray-700 dark:text-gray-300 mt-0.5">{{ $staff['role'] }}</p>
              </div>
              <div>
                <p class="text-gray-400">Kategori</p>
                <p class="font-medium text-gray-700 dark:text-gray-300 mt-0.5">{{ $staff['type'] }}</p>
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
        <p class="text-gray-500 text-xs sm:text-sm">
          Menampilkan {{ $paginator->firstItem() ?? 0 }} - {{ $paginator->lastItem() ?? 0 }} dari {{ $paginator->total() }} Staff
        </p>
        @if ($paginator->hasPages())
          <div class="flex gap-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
              <button disabled class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-300 dark:text-gray-600 cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
              </button>
            @else
              <a href="{{ $paginator->previousPageUrl() }}" class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
              </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
              @if ($page == $paginator->currentPage())
                <button class="w-8 h-8 rounded-lg gradient-telkom text-white flex items-center justify-center font-semibold text-xs">{{ $page }}</button>
              @else
                <a href="{{ $url }}" class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold text-xs">{{ $page }}</a>
              @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
              <a href="{{ $paginator->nextPageUrl() }}" class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
              </a>
            @else
              <button disabled class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-300 dark:text-gray-600 cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
              </button>
            @endif
          </div>
        @endif
      </div>
    </div>
  </section>

  <!-- Modal Tambah Staff -->
  <x-modal id="tambahStaffModal" size="sm" title="Tambah Staff">
    <form method="POST" action="{{ route('presence.student-staff.store') }}" class="space-y-4">
      @csrf
      <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">Nama Lengkap</label>
        <input type="text" name="name" required placeholder="Nama Lengkap"
          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
      </div>
      <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">Username (NIM / NIP)</label>
        <input type="text" name="username" required placeholder="username"
          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
      </div>
      <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">NIM / NIP</label>
        <input type="text" name="nim" placeholder="NIM / NIP"
          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
      </div>
      <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">No. WA / Telepon</label>
        <input type="text" name="phone" placeholder="No. WA / Telepon"
          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
      </div>
      <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">Jabatan / Posisi</label>
        <input type="text" name="position" required placeholder="Jabatan / Posisi"
          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
      </div>
      <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">Email</label>
        <input type="email" name="email" required placeholder="email@domain.com"
          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
      </div>
      <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">Kategori Akun</label>
        <select name="type" required
          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
          <option value="Student Staff">Student Staff</option>
          <option value="Staf">Staf</option>
        </select>
      </div>
      <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">Password</label>
        <input type="password" name="password" placeholder="Default: password"
          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
      </div>
      <div class="pt-2">
        <button type="submit"
          class="w-full py-2.5 gradient-telkom text-white rounded-xl text-sm font-semibold hover:opacity-90 transition">Tambah Staff</button>
      </div>
    </form>
  </x-modal>

  <!-- Modal Edit Staff -->
  <x-modal id="editStaffModal" size="sm" title="Edit Staff">
    <form id="editStaffForm" method="POST" class="space-y-4">
      @csrf
      @method('PUT')
      <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">Nama Lengkap</label>
        <input type="text" id="edit_name" name="name" required
          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
      </div>
      <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">Username (NIM / NIP)</label>
        <input type="text" id="edit_username" name="username" required
          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
      </div>
      <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">NIM / NIP</label>
        <input type="text" id="edit_nim" name="nim"
          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
      </div>
      <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">No. WA / Telepon</label>
        <input type="text" id="edit_phone" name="phone"
          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
      </div>
      <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">Jabatan / Posisi</label>
        <input type="text" id="edit_position" name="position" required
          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
      </div>
      <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">Email</label>
        <input type="email" id="edit_email" name="email" required
          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
      </div>
      <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">Kategori Akun</label>
        <select id="edit_type" name="type" required
          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
          <option value="Student Staff">Student Staff</option>
          <option value="Staf">Staf</option>
        </select>
      </div>
      <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">Password Baru (Kosongkan jika tidak diubah)</label>
        <input type="password" name="password" autocomplete="new-password"
          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
      </div>
      <div class="pt-2">
        <button type="submit"
          class="w-full py-2.5 gradient-telkom text-white rounded-xl text-sm font-semibold hover:opacity-90 transition">Simpan Perubahan</button>
      </div>
    </form>
  </x-modal>
@endsection

@push('scripts')
  <script>
    function openTambahStaffModal() {
      openModal('tambahStaffModal');
    }

    function openEditStaffModal(id, name, username, nim, phone, position, email, type) {
      document.getElementById('editStaffForm').action = `/presence/student-staff/${id}`;
      document.getElementById('edit_name').value = name;
      document.getElementById('edit_username').value = username;
      document.getElementById('edit_nim').value = nim;
      document.getElementById('edit_phone').value = phone;
      document.getElementById('edit_position').value = position;
      document.getElementById('edit_email').value = email;
      document.getElementById('edit_type').value = type;
      openModal('editStaffModal');
    }
  </script>
@endpush
