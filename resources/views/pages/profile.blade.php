@extends('layouts.app-layout')

@section('title', 'Profil Saya')
@section('subtitle', 'Informasi akun dan data pribadi')

@section('content')
  <!-- ============ VIEW: PROFIL ============ -->
  <section id="view-profile" class="view space-y-6">
    <!-- Card Utama Profil -->
    <div class="gradient-telkom rounded-3xl border border-telkom-800 overflow-hidden shadow-xl text-white relative">
      <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
      <div class="p-6 relative flex flex-col sm:flex-row items-center gap-5">
        <img src="https://i.pravatar.cc/150?img=12" alt="Avatar"
          class="w-28 h-28 rounded-full ring-4 ring-white/30 shadow-xl object-cover">
        <div class="flex-1 text-center sm:text-left">
          <h2 class="text-2xl font-bold">Andi Pratama</h2>
          <p class="text-xs text-white/80">Dosen • Fakultas Informatika</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap justify-center sm:justify-end">
          <button onclick="openEditProfileModal()"
            class="px-4 py-2 bg-white/10 rounded-xl text-sm font-semibold hover:bg-white/20 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="18" cy="15" r="3"/><circle cx="9" cy="7" r="4"/><path d="M10 15H6a4 4 0 0 0-4 4v2"/><path d="m21.7 16.4-.9-.3"/><path d="m15.2 13.9-.9-.3"/><path d="m16.6 18.7.3-.9"/><path d="m19.1 12.2.3-.9"/><path d="m19.6 18.7-.4-1"/><path d="m16.8 12.3-.4-1"/><path d="m14.3 16.6 1-.4"/><path d="m20.7 13.8 1-.4"/></svg>
            <span>Edit Profil</span>
          </button>
          <a href="#"
            class="px-4 py-2 bg-red-500/30 text-white rounded-xl text-sm font-semibold hover:bg-red-500/50 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            <span>Logout</span>
          </a>
        </div>
      </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Data Pribadi -->
      <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 space-y-4">
        <h3 class="font-bold text-base border-b border-gray-100 dark:border-gray-800 pb-3">Informasi Pribadi</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
          <div>
            <p class="text-xs text-gray-400">NIP / NUP</p>
            <p class="font-semibold text-gray-900 dark:text-gray-100 mt-0.5">19920815202001</p>
          </div>
          <div>
            <p class="text-xs text-gray-400">Email</p>
            <p class="font-semibold text-gray-900 dark:text-gray-100 mt-0.5">andipratama@telkomuniversity.ac.id</p>
          </div>
          <div>
            <p class="text-xs text-gray-400">No. Telepon</p>
            <p class="font-semibold text-gray-900 dark:text-gray-100 mt-0.5">+62 812-3456-7890</p>
          </div>
          <div>
            <p class="text-xs text-gray-400">Fakultas / Unit</p>
            <p class="font-semibold text-gray-900 dark:text-gray-100 mt-0.5">Informatika (FIT)</p>
          </div>
        </div>
      </div>

      <!-- Presensi Wajah Status -->
      <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 space-y-4">
        <h3 class="font-bold text-base border-b border-gray-100 dark:border-gray-800 pb-3">Status Presensi Wajah</h3>
        <div class="flex items-center gap-4 p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl border border-emerald-100 dark:border-emerald-900/40">
          <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><path d="M9 9h.01"/><path d="M15 9h.01"/></svg>
          </div>
          <div>
            <p class="font-bold text-sm text-emerald-900 dark:text-emerald-300">Wajah Terdaftar</p>
            <p class="text-xs text-emerald-700 dark:text-emerald-400 mt-0.5">Data biometrik Anda sudah aktif dan terverifikasi untuk presensi wajah.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Modal Edit Profile -->
  <div id="editProfileModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 dark:bg-gray-950/80 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
    <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 w-full max-w-md overflow-hidden shadow-2xl transform scale-95 transition-transform duration-300" id="editProfileModalContent">
      <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <h3 class="font-bold text-lg">Edit Profil</h3>
        <button onclick="closeEditProfileModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 bg-gray-100 dark:bg-gray-800 rounded-xl transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <form class="p-5 space-y-4">
        <div>
          <label class="block text-xs font-semibold text-gray-500 mb-1">Email</label>
          <input type="email" value="andipratama@telkomuniversity.ac.id" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
        </div>
        <div>
          <label class="block text-xs font-semibold text-gray-500 mb-1">No. Telepon</label>
          <input type="text" value="+62 812-3456-7890" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
        </div>
        <div>
          <label class="block text-xs font-semibold text-gray-500 mb-1">Fakultas / Unit</label>
          <input type="text" value="Informatika (FIT)" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
        </div>
        <div class="pt-2">
          <button type="button" onclick="closeEditProfileModal()" class="w-full py-2.5 gradient-telkom text-white rounded-xl text-sm font-semibold hover:opacity-90 transition">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  function openEditProfileModal() {
    const modal = document.getElementById('editProfileModal');
    const content = document.getElementById('editProfileModalContent');
    
    modal.classList.remove('hidden');
    void modal.offsetWidth;
    modal.classList.remove('opacity-0');
    content.classList.remove('scale-95');
  }

  function closeEditProfileModal() {
    const modal = document.getElementById('editProfileModal');
    const content = document.getElementById('editProfileModalContent');
    
    modal.classList.add('opacity-0');
    content.classList.add('scale-95');
    
    setTimeout(() => {
      modal.classList.add('hidden');
    }, 300);
  }
</script>
@endpush
