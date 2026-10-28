@extends('layouts.app-layout')

@section('title', 'Role & Permission')
@section('subtitle', 'Kelola peran dan hak akses pengguna sistem')

@section('content')
  <!-- ============ VIEW: ROLE & PERMISSION ============ -->
  <section id="view-role-permission" class="view space-y-6">
    <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
      <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h3 class="font-bold text-lg text-gray-900 dark:text-white">Daftar Pengguna & Hak Akses</h3>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Daftar semua pengguna terdaftar dan konfigurasi peran dalam sistem</p>
        </div>
        <a href="{{ route('admin.dashboard') }}"
          class="inline-flex items-center gap-2 text-xs font-semibold px-3.5 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">
          <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Dashboard
        </a>
      </div>

      @if (session('success'))
        <div class="p-4 bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400 text-sm font-semibold border-b border-emerald-100 dark:border-emerald-900/40">
          {{ session('success') }}
        </div>
      @endif

      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
          <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-700 dark:text-gray-300 text-xs font-bold uppercase tracking-wider border-b border-gray-200 dark:border-gray-800">
            <tr>
              <th class="px-6 py-4 w-16 text-center">ID</th>
              <th class="px-6 py-4">Nama Pengguna</th>
              <th class="px-6 py-4">Email</th>
              <th class="px-6 py-4">Peran (Role)</th>
              <th class="px-6 py-4 text-center">AKSI</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50">
            @forelse ($users as $user)
              @php
                $roleName = $user->roles->first()?->name;
                $roleColor = match ($roleName) {
                    'super-admin' => 'bg-telkom-50 dark:bg-telkom-950/50 text-telkom-700 dark:text-telkom-400 border-telkom-200/60 dark:border-telkom-800',
                    'staff' => 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 border-emerald-200/60 dark:border-emerald-800',
                    'student-staff' => 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 border-amber-200/60 dark:border-amber-800',
                    default => 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700',
                };
              @endphp
              <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition">
                <td class="px-6 py-4 text-center text-gray-500">{{ $user->id }}</td>
                <td class="px-6 py-4">
                  <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                  @if ($user->username)
                    <div class="text-xs text-gray-400">{{ '@' . $user->username }}</div>
                  @endif
                </td>
                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $user->email ?? '-' }}</td>
                <td class="px-6 py-4">
                  <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $roleColor }} capitalize">
                    {{ $roleName ?? 'User' }}
                  </span>
                </td>
                <td class="px-6 py-4 text-center">
                  <x-button variant="table" icon="shield-check" href="{{ route('role-permission.edit', $user) }}">
                    Edit Akses
                  </x-button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-6 py-12 text-center text-gray-400">Tidak ada pengguna ditemukan.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </section>
@endsection
