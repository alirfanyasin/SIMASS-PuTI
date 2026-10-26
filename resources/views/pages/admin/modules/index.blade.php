@extends('layouts.app-layout')

@section('title', 'Manajemen Kartu Modul Portal — Admin Console')

@section('content')
    <div class="space-y-6" x-data="{
        createModal: false,
        editModal: false,
        createLinkType: 'route',
        moduleToEdit: {
            id: null,
            name: '',
            code: '',
            description: '',
            credit: '',
            icon: 'layout-grid',
            status: 'active',
            order: 1,
            link_type: 'route',
            route_name: '',
            url: '',
            allowed_roles: []
        },
        openEdit(mod) {
            let lType = 'none';
            if (mod.url && mod.url.trim() !== '') {
                lType = 'url';
            } else if (mod.route_name && mod.route_name.trim() !== '') {
                lType = 'route';
            }

            this.moduleToEdit = {
                id: mod.id,
                name: mod.name || '',
                code: mod.code || '',
                description: mod.description || '',
                credit: mod.credit || '',
                icon: mod.icon || 'layout-grid',
                status: mod.status || 'active',
                order: mod.order ?? 1,
                link_type: lType,
                route_name: mod.route_name || '',
                url: mod.url || '',
                allowed_roles: Array.isArray(mod.allowed_roles) ? [...mod.allowed_roles] : []
            };
            this.editModal = true;
            this.$nextTick(() => {
                if (window.lucide) lucide.createIcons();
                if (window.initSelect2Auto) window.initSelect2Auto();
                $('#edit_module_status').val(this.moduleToEdit.status).trigger('change.select2');
            });
        },
        toggleRole(role) {
            const idx = this.moduleToEdit.allowed_roles.indexOf(role);
            if (idx > -1) {
                this.moduleToEdit.allowed_roles.splice(idx, 1);
            } else {
                this.moduleToEdit.allowed_roles.push(role);
            }
        }
    }">

        {{-- Header Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Manajemen Kartu Modul Portal</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola informasi kartu portal, nama modul, credit developer, ikon, status, dan jenis tautan (Route / Outer Link).</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('portal') }}" target="_blank"
                    class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 text-xs font-semibold text-gray-700 dark:text-gray-200 transition shadow-xs">
                    <i data-lucide="external-link" class="w-4 h-4"></i> Preview Portal
                </a>
                <a href="{{ route('admin.dashboard') }}"
                    class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-xs font-semibold text-gray-700 dark:text-gray-300 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Dashboard
                </a>
                <button type="button" @click="createModal = true; $nextTick(() => { if (window.initSelect2Auto) window.initSelect2Auto(); })"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-telkom-600 hover:bg-telkom-700 text-white text-xs font-semibold transition shadow-xs">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Modul
                </button>
            </div>
        </div>

        {{-- Status Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-4 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-400">Total Modul</span>
                    <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $modules->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-300">
                    <i data-lucide="layout-grid" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="p-4 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Modul Aktif</span>
                    <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $modules->where('status', 'active')->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="p-4 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-amber-600 dark:text-amber-400">Segera Hadir / Maintenance</span>
                    <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $modules->where('status', '!=', 'active')->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/60 flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                </div>
            </div>
        </div>

        {{-- Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse ($modules as $mod)
                <div class="group relative flex flex-col justify-between bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-6 shadow-xs hover:border-gray-300 dark:hover:border-gray-700 transition">
                    <div>
                        {{-- Top Meta: Order & Status Badge --}}
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                    #{{ $mod->order }}
                                </span>
                                <span class="text-xs font-mono text-gray-400">
                                    {{ $mod->code }}
                                </span>
                            </div>
                            @if ($mod->status === 'active')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                </span>
                            @elseif ($mod->status === 'coming_soon')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Segera Hadir
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-950 dark:text-amber-400 border border-amber-200 dark:border-amber-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Maintenance
                                </span>
                            @endif
                        </div>

                        {{-- Card Icon & Title --}}
                        <div class="flex items-start gap-3.5 mb-3">
                            <div class="w-12 h-12 rounded-2xl bg-telkom-50 dark:bg-telkom-950 text-telkom-700 dark:text-telkom-400 flex items-center justify-center shrink-0">
                                <i data-lucide="{{ $mod->icon }}" class="w-6 h-6"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="font-bold text-lg text-gray-900 dark:text-white truncate">
                                    {{ $mod->name }}
                                </h3>

                                {{-- Link Preview Badge --}}
                                <div class="mt-1">
                                    @if ($mod->url)
                                        <a href="{{ $mod->url }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1 text-[11px] font-mono text-blue-600 dark:text-blue-400 hover:underline max-w-full truncate">
                                            <i data-lucide="external-link" class="w-3 h-3 shrink-0"></i>
                                            <span class="truncate">{{ $mod->url }}</span>
                                        </a>
                                    @elseif ($mod->route_name)
                                        <span class="inline-flex items-center gap-1 text-[11px] font-mono text-purple-600 dark:text-purple-400">
                                            <i data-lucide="compass" class="w-3 h-3 shrink-0"></i>
                                            <span class="truncate">{{ $mod->route_name }}</span>
                                        </span>
                                    @else
                                        <span class="text-[11px] text-gray-400 italic">Tanpa tautan</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Description --}}
                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed line-clamp-2 mb-3">
                            {{ $mod->description ?: 'Tidak ada deskripsi modul.' }}
                        </p>

                        {{-- Credit --}}
                        <div class="rounded-xl bg-gray-50 dark:bg-gray-800/60 p-2.5 border border-gray-100 dark:border-gray-800/80 mb-3">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Credit / Pengembang:</span>
                            <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mt-0.5 truncate">
                                {{ $mod->credit ?: '-' }}
                            </p>
                        </div>

                        {{-- Allowed Roles --}}
                        <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                            <i data-lucide="shield" class="w-3.5 h-3.5 shrink-0 text-gray-400"></i>
                            <span class="truncate">
                                @if (empty($mod->allowed_roles))
                                    <span class="text-gray-500 dark:text-gray-400">Semua Role</span>
                                @else
                                    <span class="font-semibold text-telkom-700 dark:text-telkom-400">{{ implode(', ', $mod->allowed_roles) }}</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-5 pt-3.5 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between gap-2">
                        <button type="button" @click="openEdit({{ json_encode($mod) }})"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition">
                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit Kartu
                        </button>
                        <button type="button"
                            onclick="confirmDelete('{{ route('admin.modules.destroy', $mod) }}', 'modul {{ addslashes($mod->name) }}')"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-xl text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40 transition">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 text-gray-400">
                    <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-2 text-gray-300 dark:text-gray-600"></i>
                    <p class="font-medium text-sm">Belum ada modul portal yang terdaftar.</p>
                </div>
            @endforelse
        </div>

        {{-- CREATE MODAL --}}
        <div x-show="createModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
            x-cloak
            style="display: none;">
            <div @click.away="createModal = false"
                class="bg-white dark:bg-gray-900 rounded-3xl max-w-xl w-full p-6 shadow-2xl border border-gray-200 dark:border-gray-800 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800">
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-base">Tambah Modul Portal</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Daftarkan kartu aplikasi baru pada Portal Hub PuTI.</p>
                    </div>
                    <button @click="createModal = false" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.modules.store') }}" class="space-y-4 mt-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Modul *</label>
                            <input type="text" name="name" required placeholder="Contoh: SIMASS Presensi"
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Kode Unik *</label>
                            <input type="text" name="code" required placeholder="contoh: presensi"
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5 font-mono">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Status *</label>
                            <select name="status" id="create_module_status" required
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                                <option value="active">Aktif</option>
                                <option value="coming_soon">Segera Hadir</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Urutan *</label>
                            <input type="number" name="order" value="{{ $modules->max('order') + 1 }}" min="0" required
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Ikon Lucide *</label>
                            <input type="text" name="icon" value="layout-grid" required placeholder="user-check, ticket, bot..."
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5 font-mono">
                        </div>
                    </div>

                    {{-- Link Type Option (Route vs Outer Link) --}}
                    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 p-3.5 bg-gray-50/50 dark:bg-gray-950/50 space-y-3">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">Pilihan Tautan / Navigasi Kartu:</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="flex items-center gap-2 p-2 rounded-xl border text-xs font-medium cursor-pointer transition"
                                :class="createLinkType === 'route' ? 'border-telkom-600 bg-telkom-50/60 dark:bg-telkom-950/60 text-telkom-700 dark:text-telkom-400 font-bold' : 'border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300'">
                                <input type="radio" name="link_type" value="route" x-model="createLinkType" class="text-telkom-600 focus:ring-telkom-500">
                                <span>Route Name</span>
                            </label>
                            <label class="flex items-center gap-2 p-2 rounded-xl border text-xs font-medium cursor-pointer transition"
                                :class="createLinkType === 'url' ? 'border-telkom-600 bg-telkom-50/60 dark:bg-telkom-950/60 text-telkom-700 dark:text-telkom-400 font-bold' : 'border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300'">
                                <input type="radio" name="link_type" value="url" x-model="createLinkType" class="text-telkom-600 focus:ring-telkom-500">
                                <span>Outer Link</span>
                            </label>
                            <label class="flex items-center gap-2 p-2 rounded-xl border text-xs font-medium cursor-pointer transition"
                                :class="createLinkType === 'none' ? 'border-telkom-600 bg-telkom-50/60 dark:bg-telkom-950/60 text-telkom-700 dark:text-telkom-400 font-bold' : 'border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300'">
                                <input type="radio" name="link_type" value="none" x-model="createLinkType" class="text-telkom-600 focus:ring-telkom-500">
                                <span>Tanpa Tautan</span>
                            </label>
                        </div>

                        {{-- Route Input --}}
                        <div x-show="createLinkType === 'route'" class="space-y-1">
                            <label class="block text-[11px] font-semibold text-gray-600 dark:text-gray-400">Route Name Internal:</label>
                            <input type="text" name="route_name" placeholder="presence.dashboard / ticket.index"
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-2.5 font-mono">
                            <p class="text-[11px] text-gray-400">Gunakan route name Laravel terdaftar dalam aplikasi.</p>
                        </div>

                        {{-- Outer Link Input --}}
                        <div x-show="createLinkType === 'url'" class="space-y-1">
                            <label class="block text-[11px] font-semibold text-gray-600 dark:text-gray-400">Outer Link (URL Eksternal):</label>
                            <input type="url" name="url" placeholder="https://example.com atau https://t.me/..."
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-2.5 font-mono">
                            <p class="text-[11px] text-gray-400">Kartu akan otomatis membuka tab baru dengan URL eksternal.</p>
                        </div>

                        {{-- None Note --}}
                        <div x-show="createLinkType === 'none'">
                            <p class="text-[11px] text-gray-400 italic">Kartu tidak dapat diklik atau menuju ke halaman lain (cocok untuk status Segera Hadir).</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Credit / Kontributor (Opsional)</label>
                        <input type="text" name="credit" placeholder="Courtesy to Irfan Yasin S.Kom (Student Staff PuTI Gen. 2023)"
                            class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Deskripsi Modul</label>
                        <textarea name="description" rows="3" placeholder="Jelaskan fungsi aplikasi ini..."
                            class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Batasi Hak Akses Role (Opsional)</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            @foreach ($roles as $r)
                                <label class="flex items-center gap-2 p-2 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 text-xs cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-850">
                                    <input type="checkbox" name="allowed_roles[]" value="{{ $r }}"
                                        class="rounded border-gray-300 text-telkom-600 focus:ring-telkom-500">
                                    <span class="text-gray-700 dark:text-gray-300">{{ $r }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1">Jika tidak ada role yang dicentang, modul dapat diakses oleh semua pengguna.</p>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-800">
                        <button type="button" @click="createModal = false"
                            class="px-4 py-2 text-xs font-semibold text-gray-600 dark:text-gray-400 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-xs font-semibold bg-telkom-600 hover:bg-telkom-700 text-white rounded-xl shadow-xs">
                            Simpan Modul
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- EDIT MODAL --}}
        <div x-show="editModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
            x-cloak
            style="display: none;">
            <div @click.away="editModal = false"
                class="bg-white dark:bg-gray-900 rounded-3xl max-w-xl w-full p-6 shadow-2xl border border-gray-200 dark:border-gray-800 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800">
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-base">Edit Kartu Modul</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Perbarui informasi tampilan kartu portal, tautan, dan hak akses.</p>
                    </div>
                    <button @click="editModal = false" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form method="POST" :action="`{{ url('admin/modules') }}/${moduleToEdit.id}`" class="space-y-4 mt-4">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Modul *</label>
                            <input type="text" name="name" x-model="moduleToEdit.name" required
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Kode Unik *</label>
                            <input type="text" name="code" x-model="moduleToEdit.code" required
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5 font-mono">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Status *</label>
                            <select name="status" id="edit_module_status" x-model="moduleToEdit.status" required
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                                <option value="active">Aktif</option>
                                <option value="coming_soon">Segera Hadir</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Urutan *</label>
                            <input type="number" name="order" x-model="moduleToEdit.order" min="0" required
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Ikon Lucide *</label>
                            <input type="text" name="icon" x-model="moduleToEdit.icon" required
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5 font-mono">
                        </div>
                    </div>

                    {{-- Preset Icon Pills --}}
                    <div>
                        <span class="block text-[11px] font-semibold text-gray-400 mb-1.5">Preset Ikon Populer:</span>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach (['layout-grid', 'user-check', 'ticket', 'bot', 'sliders-horizontal', 'package', 'globe', 'calendar', 'users', 'activity', 'shield'] as $preset)
                                <button type="button" @click="moduleToEdit.icon = '{{ $preset }}'"
                                    :class="moduleToEdit.icon === '{{ $preset }}' ? 'bg-telkom-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300'"
                                    class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[11px] transition">
                                    <i data-lucide="{{ $preset }}" class="w-3 h-3"></i>
                                    <span>{{ $preset }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Link Type Option (Route vs Outer Link) --}}
                    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 p-3.5 bg-gray-50/50 dark:bg-gray-950/50 space-y-3">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">Pilihan Tautan / Navigasi Kartu:</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="flex items-center gap-2 p-2 rounded-xl border text-xs font-medium cursor-pointer transition"
                                :class="moduleToEdit.link_type === 'route' ? 'border-telkom-600 bg-telkom-50/60 dark:bg-telkom-950/60 text-telkom-700 dark:text-telkom-400 font-bold' : 'border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300'">
                                <input type="radio" name="link_type" value="route" x-model="moduleToEdit.link_type" class="text-telkom-600 focus:ring-telkom-500">
                                <span>Route Name</span>
                            </label>
                            <label class="flex items-center gap-2 p-2 rounded-xl border text-xs font-medium cursor-pointer transition"
                                :class="moduleToEdit.link_type === 'url' ? 'border-telkom-600 bg-telkom-50/60 dark:bg-telkom-950/60 text-telkom-700 dark:text-telkom-400 font-bold' : 'border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300'">
                                <input type="radio" name="link_type" value="url" x-model="moduleToEdit.link_type" class="text-telkom-600 focus:ring-telkom-500">
                                <span>Outer Link</span>
                            </label>
                            <label class="flex items-center gap-2 p-2 rounded-xl border text-xs font-medium cursor-pointer transition"
                                :class="moduleToEdit.link_type === 'none' ? 'border-telkom-600 bg-telkom-50/60 dark:bg-telkom-950/60 text-telkom-700 dark:text-telkom-400 font-bold' : 'border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300'">
                                <input type="radio" name="link_type" value="none" x-model="moduleToEdit.link_type" class="text-telkom-600 focus:ring-telkom-500">
                                <span>Tanpa Tautan</span>
                            </label>
                        </div>

                        {{-- Route Input --}}
                        <div x-show="moduleToEdit.link_type === 'route'" class="space-y-1">
                            <label class="block text-[11px] font-semibold text-gray-600 dark:text-gray-400">Route Name Internal:</label>
                            <input type="text" name="route_name" x-model="moduleToEdit.route_name"
                                placeholder="presence.dashboard / ticket.index"
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-2.5 font-mono">
                            <p class="text-[11px] text-gray-400">Gunakan route name Laravel terdaftar dalam aplikasi.</p>
                        </div>

                        {{-- Outer Link Input --}}
                        <div x-show="moduleToEdit.link_type === 'url'" class="space-y-1">
                            <label class="block text-[11px] font-semibold text-gray-600 dark:text-gray-400">Outer Link (URL Eksternal):</label>
                            <input type="url" name="url" x-model="moduleToEdit.url"
                                placeholder="https://example.com atau https://t.me/..."
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-2.5 font-mono">
                            <p class="text-[11px] text-gray-400">Kartu akan otomatis membuka tab baru dengan URL eksternal.</p>
                        </div>

                        {{-- None Note --}}
                        <div x-show="moduleToEdit.link_type === 'none'">
                            <p class="text-[11px] text-gray-400 italic">Kartu tidak dapat diklik atau menuju ke halaman lain (cocok untuk status Segera Hadir).</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Credit / Kontributor (Opsional)</label>
                        <input type="text" name="credit" x-model="moduleToEdit.credit"
                            placeholder="Courtesy to Irfan Yasin S.Kom (Student Staff PuTI Gen. 2023)"
                            class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Deskripsi Modul</label>
                        <textarea name="description" rows="3" x-model="moduleToEdit.description"
                            class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Batasi Hak Akses Role (Opsional)</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            @foreach ($roles as $r)
                                <label class="flex items-center gap-2 p-2 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 text-xs cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-850">
                                    <input type="checkbox" name="allowed_roles[]" value="{{ $r }}"
                                        :checked="moduleToEdit.allowed_roles.includes('{{ $r }}')"
                                        @change="toggleRole('{{ $r }}')"
                                        class="rounded border-gray-300 text-telkom-600 focus:ring-telkom-500">
                                    <span class="text-gray-700 dark:text-gray-300">{{ $r }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1">Jika tidak ada role yang dicentang, modul dapat diakses oleh semua pengguna.</p>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-800">
                        <button type="button" @click="editModal = false"
                            class="px-4 py-2 text-xs font-semibold text-gray-600 dark:text-gray-400 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-xs font-semibold bg-telkom-600 hover:bg-telkom-700 text-white rounded-xl shadow-xs">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
