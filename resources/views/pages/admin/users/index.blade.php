@extends('layouts.app-layout')

@section('title', 'Manajemen Pengguna — Admin Console')

@section('content')
    <div class="space-y-6" x-data="{
        createModal: false,
        editModal: false,
        importModal: false,
        editUser: { id: null, name: '', username: '', email: '', nim: '', phone: '', type: 'Staff', position: '', unit_id: '', role: 'staff' },
        openEdit(user) {
            this.editUser = { ...user };
            this.editModal = true;
        }
    }">

        {{-- Top Action Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Manajemen Akun Pengguna</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola data seluruh akun sivitas, staf, teknisi,
                    dan student staff PuTI.</p>
            </div>
            <div class="flex items-center gap-2.5">
                <x-button variant="secondary" icon="file-up" @click="importModal = true">
                    Impor CSV
                </x-button>
                <x-button variant="primary" icon="user-plus" @click="createModal = true">
                    Tambah Pengguna
                </x-button>
            </div>
        </div>

        {{-- Filter & Search Bar --}}
        <div
            class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 bg-white dark:bg-gray-900 p-4 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm">
            <div class="flex items-center gap-1.5 overflow-x-auto text-xs font-semibold">
                <a href="{{ route('admin.users.index', array_merge(request()->query(), ['status' => 'all'])) }}"
                    class="px-3 py-1.5 rounded-lg transition {{ $status === 'all' ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    Semua
                </a>
                <a href="{{ route('admin.users.index', array_merge(request()->query(), ['status' => 'active'])) }}"
                    class="px-3 py-1.5 rounded-lg transition {{ $status === 'active' ? 'bg-emerald-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    Aktif
                </a>
                <a href="{{ route('admin.users.index', array_merge(request()->query(), ['status' => 'inactive'])) }}"
                    class="px-3 py-1.5 rounded-lg transition {{ $status === 'inactive' ? 'bg-red-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    Nonaktif
                </a>
            </div>

            <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="status" value="{{ $status }}">
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, NIM, email..."
                        class="pl-9 pr-3 py-1.5 text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 focus:outline-none focus:border-gray-900 dark:focus:border-white w-48 sm:w-64">
                </div>
                <x-button type="submit" variant="secondary" size="sm" icon="search">
                    Filter
                </x-button>
            </form>
        </div>

        {{-- Users Table --}}
        <div
            class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-600 dark:text-gray-400">
                    <thead
                        class="bg-gray-50 dark:bg-gray-800/50 text-gray-700 dark:text-gray-300 font-bold uppercase tracking-wider border-b border-gray-200 dark:border-gray-800">
                        <tr>
                            <th class="px-5 py-3.5">Pengguna</th>
                            <th class="px-5 py-3.5">Kontak</th>
                            <th class="px-5 py-3.5">Role</th>
                            <th class="px-5 py-3.5">Unit / Jabatan</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-bold flex items-center justify-center shrink-0 text-xs">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $user->name }}
                                            </p>
                                            <p class="text-[11px] text-gray-400">@<span>{{ $user->username }}</span>
                                                {{ $user->nim ? '· NIM: ' . $user->nim : '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="text-gray-700 dark:text-gray-300">{{ $user->email ?? '-' }}</p>
                                    <p class="text-[11px] text-gray-400">{{ $user->phone ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    @php
                                        $roleName = $user->roles->first()?->name ?? 'user';
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold
                                    {{ $roleName === 'super-admin'
                                        ? 'bg-purple-50 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300 border border-purple-200 dark:border-purple-800'
                                        : ($roleName === 'unit-head' || $roleName === 'unit_head'
                                            ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800'
                                            : ($roleName === 'staff'
                                                ? 'bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-800'
                                                : ($roleName === 'student-staff'
                                                    ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800'
                                                    : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700'))) }}">
                                        {{ $roleName }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="text-gray-700 dark:text-gray-300 font-medium">
                                        {{ $user->unit?->name ?? ($user->type ?? '-') }}</p>
                                    <p class="text-[11px] text-gray-400">{{ $user->position ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold transition
                                        {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 hover:bg-emerald-100' : 'bg-red-50 text-red-700 dark:bg-red-950/60 dark:text-red-400 hover:bg-red-100' }}">
                                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <x-button variant="table" icon="edit-3"
                                            @click="openEdit({
                                                id: {{ $user->id }},
                                                name: '{{ addslashes($user->name) }}',
                                                username: '{{ addslashes($user->username) }}',
                                                email: '{{ addslashes($user->email ?? '') }}',
                                                nim: '{{ addslashes($user->nim ?? '') }}',
                                                phone: '{{ addslashes($user->phone ?? '') }}',
                                                type: '{{ addslashes($user->type ?? 'Staff') }}',
                                                position: '{{ addslashes($user->position ?? '') }}',
                                                unit_id: '{{ $user->unit_id ?? '' }}',
                                                role: '{{ $user->roles->first()?->name ?? 'staff' }}'
                                            })" title="Edit Pengguna">
                                            Edit
                                        </x-button>

                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                            onsubmit="return confirm('Anda akan menghapus pengguna {{ $user->name }}, aksi ini tidak bisa dibatalkan?')"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <x-button type="submit" variant="table-danger" icon="trash-2" title="Hapus Pengguna">
                                                Hapus
                                            </x-button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                                    <i data-lucide="users" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                                    <p>Tidak ada pengguna yang sesuai kriteria.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-800">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        {{-- Create User Modal --}}
        <div x-show="createModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
            style="display: none;">
            <div @click.away="createModal = false"
                class="bg-white dark:bg-gray-900 rounded-2xl max-w-lg w-full p-6 shadow-xl border border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="font-bold text-gray-900 dark:text-white text-base">Tambah Pengguna Baru</h3>
                    <button @click="createModal = false" class="text-gray-400 hover:text-gray-600"><i data-lucide="x"
                            class="w-5 h-5"></i></button>
                </div>
                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4 mt-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap
                                *</label>
                            <input type="text" name="name" required
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Username
                                *</label>
                            <input type="text" name="username" required
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" name="email"
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">NIM /
                                NIP</label>
                            <input type="text" name="nim"
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Role *</label>
                            <select name="role" required
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                                @foreach ($roles as $r)
                                    <option value="{{ $r }}">{{ $r }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Unit</label>
                            <select name="unit_id"
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                                <option value="">-- Tanpa Unit --</option>
                                @foreach ($units as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Posisi /
                                Jabatan</label>
                            <input type="text" name="position" placeholder="e.g. IT Support"
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Kata Sandi
                                (Opsional)</label>
                            <input type="password" name="password" placeholder="Dibuat otomatis jika kosong"
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-800">
                        <button type="button" @click="createModal = false"
                            class="px-4 py-2 text-xs font-semibold text-gray-600 rounded-xl hover:bg-gray-100">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-xs font-semibold bg-gray-900 text-white dark:bg-white dark:text-gray-900 rounded-xl hover:bg-black">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Edit User Modal --}}
        <div x-show="editModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
            style="display: none;">
            <div @click.away="editModal = false"
                class="bg-white dark:bg-gray-900 rounded-2xl max-w-lg w-full p-6 shadow-xl border border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="font-bold text-gray-900 dark:text-white text-base">Ubah Data Pengguna</h3>
                    <button @click="editModal = false" class="text-gray-400 hover:text-gray-600"><i data-lucide="x"
                            class="w-5 h-5"></i></button>
                </div>
                <form method="POST" :action="`{{ url('admin/users') }}/${editUser.id}`" class="space-y-4 mt-4">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap
                                *</label>
                            <input type="text" name="name" x-model="editUser.name" required
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Username
                                *</label>
                            <input type="text" name="username" x-model="editUser.username" required
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" name="email" x-model="editUser.email"
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">NIM /
                                NIP</label>
                            <input type="text" name="nim" x-model="editUser.nim"
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Role *</label>
                            <select name="role" x-model="editUser.role" required
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                                @foreach ($roles as $r)
                                    <option value="{{ $r }}">{{ $r }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Unit</label>
                            <select name="unit_id" x-model="editUser.unit_id"
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                                <option value="">-- Tanpa Unit --</option>
                                @foreach ($units as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Posisi /
                                Jabatan</label>
                            <input type="text" name="position" x-model="editUser.position"
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Reset Sandi
                                (Opsional)</label>
                            <input type="password" name="password" placeholder="Biarkan kosong jika tidak diubah"
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-800">
                        <button type="button" @click="editModal = false"
                            class="px-4 py-2 text-xs font-semibold text-gray-600 rounded-xl hover:bg-gray-100">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-xs font-semibold bg-gray-900 text-white dark:bg-white dark:text-gray-900 rounded-xl hover:bg-black">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Import CSV Modal --}}
        <div x-show="importModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
            style="display: none;">
            <div @click.away="importModal = false"
                class="bg-white dark:bg-gray-900 rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="font-bold text-gray-900 dark:text-white text-base">Impor Pengguna via CSV</h3>
                    <button @click="importModal = false" class="text-gray-400 hover:text-gray-600"><i data-lucide="x"
                            class="w-5 h-5"></i></button>
                </div>
                <form method="POST" action="{{ route('admin.users.import-csv') }}" enctype="multipart/form-data"
                    class="space-y-4 mt-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Pilih Berkas
                            CSV</label>
                        <input type="file" name="csv_file" accept=".csv,text/csv" required
                            class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                        <p class="text-[11px] text-gray-400 mt-1">Kolom wajib: <code>name</code>, <code>username</code>.
                            Opsional: <code>email</code>, <code>nim</code>, <code>phone</code>, <code>role</code>,
                            <code>position</code>.
                        </p>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-800">
                        <button type="button" @click="importModal = false"
                            class="px-4 py-2 text-xs font-semibold text-gray-600 rounded-xl hover:bg-gray-100">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-xs font-semibold bg-gray-900 text-white dark:bg-white dark:text-gray-900 rounded-xl hover:bg-black">Unggah
                            & Impor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
