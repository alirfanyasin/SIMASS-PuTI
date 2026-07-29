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
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Daftar semua pengguna terdaftar dan perannya dalam sistem</p>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
          <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-700 dark:text-gray-300 text-xs font-bold uppercase tracking-wider border-b border-gray-200 dark:border-gray-800">
            <tr>
              <th class="px-6 py-4 w-16 text-center">ID</th>
              <th class="px-6 py-4">Nama</th>
              <th class="px-6 py-4">Email</th>
              <th class="px-6 py-4">Peran (Role)</th>
              <th class="px-6 py-4 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50">
            @foreach ($users as $user)
              <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition">
                <td class="px-6 py-4 text-center text-gray-500">{{ $user->id }}</td>
                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                <td class="px-6 py-4">
                  <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-telkom-50 dark:bg-telkom-950/50 text-telkom-600 dark:text-telkom-400 capitalize">
                    {{ $user->position ?? ($user->type ?? 'User') }}
                  </span>
                </td>
                <td class="px-6 py-4 text-center">
                  <button type="button" 
                    onclick="openEditRoleModal({{ json_encode($user) }})"
                    class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-semibold hover:bg-telkom-600 hover:text-white dark:hover:bg-telkom-600 dark:hover:text-white transition">
                    Edit Akses
                  </button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- Modal Edit Hak Akses -->
  <div id="roleModal" class="fixed inset-0 z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeEditRoleModal()"></div>
    
    <!-- Modal Content -->
    <div id="roleModalContent" class="relative w-full max-w-2xl bg-white dark:bg-gray-900 rounded-3xl shadow-2xl scale-95 transition-transform duration-300 overflow-hidden border border-gray-100 dark:border-gray-800 m-4 flex flex-col max-h-[85vh]">
      <!-- Header -->
      <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-gray-800 shrink-0">
        <div>
          <h3 class="font-bold text-lg text-gray-900 dark:text-white">Pengaturan Peran & Permission</h3>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Konfigurasi hak akses pengguna secara dinamis</p>
        </div>
        <button onclick="closeEditRoleModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 bg-gray-100 dark:bg-gray-800 rounded-xl transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>

      <!-- Body (Scrollable) -->
      <div class="p-6 space-y-6 overflow-y-auto flex-1">
        <!-- User Info -->
        <div class="bg-gray-50 dark:bg-gray-800/40 p-4 rounded-2xl border border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
          <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama Pengguna</p>
            <p id="modalUserName" class="text-base font-bold text-gray-900 dark:text-white mt-0.5">—</p>
          </div>
          <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Email</p>
            <p id="modalUserEmail" class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-0.5">—</p>
          </div>
        </div>

        <!-- Role Selection -->
        <div class="space-y-3">
          <h4 class="font-bold text-sm text-gray-900 dark:text-white">Pilih Peran Utama (Role)</h4>
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach ($roles as $role)
              <label class="flex items-center gap-3 p-4 bg-gray-55 dark:bg-gray-800/30 border border-gray-100 dark:border-gray-800/60 rounded-2xl cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition group">
                <input type="radio" name="user_role" value="{{ $role }}" class="accent-telkom-600 w-4 h-4 cursor-pointer">
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white">{{ $role }}</span>
              </label>
            @endforeach
          </div>
        </div>

        <!-- Permission Selection -->
        <div class="space-y-4">
          <h4 class="font-bold text-sm text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800/55 pb-2">Hak Akses Modul (Permissions)</h4>
          
          @foreach ($permissions as $module => $modulePerms)
            <div class="space-y-2">
              <h5 class="text-xs font-bold text-telkom-600 dark:text-telkom-400 uppercase tracking-wider">{{ $module }}</h5>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach ($modulePerms as $perm)
                  <div class="flex items-center justify-between p-4 bg-gray-50/30 dark:bg-gray-800/10 border border-gray-100 dark:border-gray-800/40 rounded-2xl">
                    <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">{{ $perm }}</span>
                    <label class="relative inline-flex items-center cursor-pointer select-none">
                      <input type="checkbox" name="user_permissions[]" value="{{ $perm }}" class="sr-only peer">
                      <div class="w-9 h-5 bg-gray-200 dark:bg-gray-800 rounded-full peer peer-checked:after:translate-x-4 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-telkom-600"></div>
                    </label>
                  </div>
                @endforeach
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Footer -->
      <div class="p-5 border-t border-gray-100 dark:border-gray-800 flex items-center justify-end gap-3 shrink-0 bg-gray-50/50 dark:bg-gray-900">
        <button type="button" onclick="closeEditRoleModal()" class="px-5 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold rounded-xl text-sm hover:bg-gray-250 dark:hover:bg-gray-700 transition">Batal</button>
        <button type="button" onclick="savePermissions()" class="px-6 py-2.5 gradient-telkom text-white font-semibold rounded-xl text-sm hover:opacity-90 transition">Simpan Perubahan</button>
      </div>
    </div>
  </div>

  <!-- Toast Notification -->
  <div id="toastNotification" class="fixed bottom-5 right-5 z-50 flex items-center gap-3 px-5 py-4 bg-emerald-500 text-white rounded-2xl shadow-xl translate-y-20 opacity-0 transition-all duration-300 max-w-sm">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
      <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
      <polyline points="22 4 12 14.01 9 11.01"></polyline>
    </svg>
    <div class="text-sm font-semibold">Perubahan hak akses berhasil disimpan!</div>
  </div>
@endsection

@push('scripts')
<script>
  function openEditRoleModal(user) {
    const modal = document.getElementById('roleModal');
    const content = document.getElementById('roleModalContent');
    
    // Set user info
    document.getElementById('modalUserName').innerText = user.name;
    document.getElementById('modalUserEmail').innerText = user.email;

    // Match role (radio selection)
    const roleRadios = document.getElementsByName('user_role');
    const userRoleStr = (user.position || user.type || '').toLowerCase();
    
    roleRadios.forEach(radio => {
      const radioVal = radio.value.toLowerCase();
      if (userRoleStr.includes(radioVal) || 
          (radioVal === 'student staff' && userRoleStr.includes('student')) ||
          (radioVal === 'user' && !userRoleStr.includes('admin') && !userRoleStr.includes('staff'))) {
        radio.checked = true;
      } else {
        radio.checked = false;
      }
    });

    // Randomize checking of some permissions for demo purposes
    const checkboxes = document.getElementsByName('user_permissions[]');
    checkboxes.forEach(chk => {
      chk.checked = Math.random() > 0.4;
    });

    // Open modal animation
    modal.classList.remove('hidden');
    void modal.offsetWidth;
    modal.classList.remove('opacity-0');
    content.classList.remove('scale-95');
  }

  function closeEditRoleModal() {
    const modal = document.getElementById('roleModal');
    const content = document.getElementById('roleModalContent');
    
    modal.classList.add('opacity-0');
    content.classList.add('scale-95');
    
    setTimeout(() => {
      modal.classList.add('hidden');
    }, 300);
  }

  function savePermissions() {
    closeEditRoleModal();
    
    // Show toast
    const toast = document.getElementById('toastNotification');
    toast.classList.remove('translate-y-20', 'opacity-0');
    
    setTimeout(() => {
      toast.classList.add('translate-y-20', 'opacity-0');
    }, 3000);
  }
</script>
@endpush
