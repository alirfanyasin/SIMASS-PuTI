@extends('layouts.app-layout')

@section('title', 'Profil Saya')
@section('subtitle', 'Informasi akun dan data pribadi')

@section('content')
  <!-- ============ VIEW: PROFIL ============ -->
  <section id="view-profile" class="view space-y-6">
    <!-- Card Utama Profil -->
    <div class="gradient-telkom rounded-3xl border border-telkom-800 overflow-hidden shadow-xl text-white relative">
      <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
      @php
        $user = auth()->user();
        $email = $user->email ?? '';

        $isStudent = str_contains($email, '@student.');
        $isTelkom = str_contains($email, '@telkomuniversity.ac.id');
        $isStaff = !$isStudent && $isTelkom;
        $isGeneral = !$isStudent && !$isStaff;

        $words = explode(' ', $user->name);
        $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
      @endphp
      <div class="p-6 relative flex flex-col sm:flex-row items-center gap-5">
        <div class="w-28 h-28 rounded-full ring-4 ring-white/30 shadow-xl flex items-center justify-center bg-white/20 text-white font-bold text-4xl tracking-widest backdrop-blur-sm">
          {{ $initials }}
        </div>
        <div class="flex-1 text-center sm:text-left">
          <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
          @if (!$isGeneral)
            <p class="text-xs text-white/80">{{ $user->position ?? ($isStudent ? 'Mahasiswa' : 'Staff / Dosen') }}</p>
          @endif
        </div>
        <div class="flex items-center gap-2 flex-wrap justify-center sm:justify-end">
          <button onclick="openEditProfileModal()"
            class="px-4 py-2 bg-white/10 rounded-xl text-sm font-semibold hover:bg-white/20 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round" viewBox="0 0 24 24">
              <circle cx="18" cy="15" r="3" />
              <circle cx="9" cy="7" r="4" />
              <path d="M10 15H6a4 4 0 0 0-4 4v2" />
              <path d="m21.7 16.4-.9-.3" />
              <path d="m15.2 13.9-.9-.3" />
              <path d="m16.6 18.7.3-.9" />
              <path d="m19.1 12.2.3-.9" />
              <path d="m19.6 18.7-.4-1" />
              <path d="m16.8 12.3-.4-1" />
              <path d="m14.3 16.6 1-.4" />
              <path d="m20.7 13.8 1-.4" />
            </svg>
            <span>Edit Profil</span>
          </button>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
              class="px-4 py-2 bg-red-500/30 text-white rounded-xl text-sm font-semibold hover:bg-red-500/50 transition flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                <polyline points="16 17 21 12 16 7" />
                <line x1="21" y1="12" x2="9" y2="12" />
              </svg>
              <span>Logout</span>
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Data Pribadi -->
      <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 space-y-4">
        <h3 class="font-bold text-base border-b border-gray-100 dark:border-gray-800 pb-3">Informasi Pribadi</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
          @if (!$isGeneral)
            <div>
              <p class="text-xs text-gray-400">{{ $isStudent ? 'NIM' : 'NIP / NUP' }}</p>
              <p class="font-semibold text-gray-900 dark:text-gray-100 mt-0.5">{{ $user->username ?? '-' }}</p>
            </div>
          @endif
          <div>
            <p class="text-xs text-gray-400">Email</p>
            <p class="font-semibold text-gray-900 dark:text-gray-100 mt-0.5 break-all">{{ $user->email ?? '-' }}</p>
          </div>
          <div>
            <p class="text-xs text-gray-400">No. Telepon</p>
            <p class="font-semibold text-gray-900 dark:text-gray-100 mt-0.5">-</p>
          </div>
          @if (!$isGeneral)
            <div>
              <p class="text-xs text-gray-400">Fakultas / Unit</p>
              <p class="font-semibold text-gray-900 dark:text-gray-100 mt-0.5">Informatika (FIT)</p>
            </div>
          @endif
        </div>
      </div>

      <!-- Presensi Wajah Status -->
      <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 space-y-4">
        <h3 class="font-bold text-base border-b border-gray-100 dark:border-gray-800 pb-3">Status Presensi Wajah</h3>

        @if (auth()->user()->face_descriptor)
          <div
            class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl border border-emerald-100 dark:border-emerald-900/40">
            <div class="flex items-center gap-4">
              <div
                class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round" viewBox="0 0 24 24">
                  <path d="M3 7V5a2 2 0 0 1 2-2h2" />
                  <path d="M17 3h2a2 2 0 0 1 2 2v2" />
                  <path d="M21 17v2a2 2 0 0 1-2 2h-2" />
                  <path d="M7 21H5a2 2 0 0 1-2-2v-2" />
                  <path d="M8 14s1.5 2 4 2 4-2 4-2" />
                  <path d="M9 9h.01" />
                  <path d="M15 9h.01" />
                </svg>
              </div>
              <div>
                <p class="font-bold text-sm text-emerald-900 dark:text-emerald-300">Wajah Terdaftar</p>
                <p class="text-xs text-emerald-700 dark:text-emerald-400 mt-0.5">Data biometrik Anda sudah aktif dan
                  terverifikasi untuk presensi wajah.</p>
              </div>
            </div>
            <button type="button"
              onclick="confirmDelete('{{ route('presence.remove-face') }}', 'Data Wajah Anda')"
              class="px-4 py-2 bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/40 dark:text-red-400 dark:hover:bg-red-900/60 rounded-xl text-xs font-bold transition w-full sm:w-auto text-center">
              Hapus Data Wajah
            </button>
          </div>
        @else
          <div
            class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-4">
              <div
                class="w-12 h-12 rounded-xl bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round" viewBox="0 0 24 24">
                  <path d="M3 7V5a2 2 0 0 1 2-2h2" />
                  <path d="M17 3h2a2 2 0 0 1 2 2v2" />
                  <path d="M21 17v2a2 2 0 0 1-2 2h-2" />
                  <path d="M7 21H5a2 2 0 0 1-2-2v-2" />
                  <path d="M8 14s1.5 2 4 2 4-2 4-2" />
                  <path d="M9 9h.01" />
                  <path d="M15 9h.01" />
                </svg>
              </div>
              <div>
                <p class="font-bold text-sm text-gray-900 dark:text-gray-100">Wajah Belum Terdaftar</p>
                <p class="text-xs text-gray-500 mt-0.5">Anda belum mendaftarkan data wajah. Buka menu presensi untuk
                  mendaftar.</p>
              </div>
            </div>
            <a href="{{ route('presence.index') }}"
              class="px-4 py-2 gradient-telkom text-white rounded-xl text-xs font-bold transition w-full sm:w-auto text-center shadow-lg shadow-telkom-500/20">
              Daftar Sekarang
            </a>
          </div>
        @endif
      </div>
    </div>
  </section>

  <!-- Modal Edit Profile -->
  <x-modal id="editProfileModal" size="sm" title="Edit Profil">
    <form class="space-y-4">
      <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">Nama Lengkap</label>
        <input type="text" value="{{ $user->name }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
      </div>
      <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">Email</label>
        <input type="email" value="{{ $user->email }}" readonly
          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-sm focus:outline-none text-gray-500"
          title="Email tidak dapat diubah">
      </div>
      <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">No. Telepon</label>
        <input type="text" value="-"
          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
      </div>
      @if (!$isGeneral)
        <div>
          <label class="block text-xs font-semibold text-gray-500 mb-1">Fakultas / Unit</label>
          <input type="text" value="Informatika (FIT)" readonly
            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-sm focus:outline-none text-gray-500">
        </div>
      @endif
      <div class="pt-2">
        <button type="button" onclick="closeModal('editProfileModal')"
          class="w-full py-2.5 gradient-telkom text-white rounded-xl text-sm font-semibold hover:opacity-90 transition">Simpan
          Perubahan</button>
      </div>
    </form>
  </x-modal>
@endsection

@push('scripts')
  <script>
    function openEditProfileModal() {
      openModal('editProfileModal');
    }
  </script>
@endpush
