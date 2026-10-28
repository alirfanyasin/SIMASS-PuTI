@extends('layouts.app-layout')

@section('title', 'Data Master — Admin Console')

@section('content')
    <div class="space-y-6" x-data="{ tab: '{{ request()->query('tab', 'units') }}', unitModal: false, locationModal: false, serviceModal: false }" id="masterDataTabs">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Data Master & SLA</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola data unit kerja, lokasi kampus, dan target
                    SLA layanan PuTI.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Dashboard
            </a>
        </div>

        {{-- Tabs --}}
        <div class="flex items-center gap-2 border-b border-gray-200 dark:border-gray-800 text-xs font-bold">
            <button type="button" @click="tab = 'units'; switchTab('units')" id="btn-tab-units"
                :class="tab === 'units' ? 'border-telkom-600 text-telkom-700 dark:border-telkom-500 dark:text-telkom-400' :
                    'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'"
                class="pb-3 border-b-2 transition">
                Unit & Divisi ({{ $units->count() }})
            </button>
            <button type="button" @click="tab = 'locations'; switchTab('locations')" id="btn-tab-locations"
                :class="tab === 'locations' ? 'border-telkom-600 text-telkom-700 dark:border-telkom-500 dark:text-telkom-400' :
                    'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'"
                class="pb-3 border-b-2 transition ml-4">
                Lokasi Kampus ({{ $locations->count() }})
            </button>
            <button type="button" @click="tab = 'services'; switchTab('services')" id="btn-tab-services"
                :class="tab === 'services' ? 'border-telkom-600 text-telkom-700 dark:border-telkom-500 dark:text-telkom-400' :
                    'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'"
                class="pb-3 border-b-2 transition ml-4">
                Katalog Layanan & SLA ({{ $services->count() }})
            </button>
        </div>

        {{-- TAB 1: UNITS --}}
        <div x-show="tab === 'units'" id="pane-tab-units" class="space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="font-bold text-sm text-gray-900 dark:text-white">Daftar Unit & Divisi Kerja</h3>
                <x-button variant="primary" icon="plus" @click="unitModal = true">
                    Tambah Unit
                </x-button>
            </div>

            <div
                class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <table class="w-full text-left text-xs text-gray-600 dark:text-gray-400">
                    <thead
                        class="bg-gray-50 dark:bg-gray-800/50 text-gray-700 dark:text-gray-300 font-bold uppercase border-b border-gray-200 dark:border-gray-800">
                        <tr>
                            <th class="px-5 py-3.5">Nama Unit</th>
                            <th class="px-5 py-3.5">Kode</th>
                            <th class="px-5 py-3.5">Jumlah Akun</th>
                            <th class="px-5 py-3.5 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($units as $u)
                            <tr>
                                <td class="px-5 py-3.5 font-bold text-gray-900 dark:text-white">{{ $u->name }}</td>
                                <td class="px-5 py-3.5"><code>{{ $u->code ?? '-' }}</code></td>
                                <td class="px-5 py-3.5">{{ $u->users_count }} pengguna</td>
                                <td class="px-5 py-3.5 text-center">
                                    <x-button type="button" variant="table-danger" icon="trash-2"
                                        onclick="confirmDelete('{{ route('admin.units.destroy', $u) }}', 'unit {{ addslashes($u->name) }}')">
                                        Hapus
                                    </x-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-gray-400">Belum ada unit kerja
                                    terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB 2: LOCATIONS --}}
        <div x-cloak x-show="tab === 'locations'" id="pane-tab-locations" class="space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="font-bold text-sm text-gray-900 dark:text-white">Hierarki Lokasi Kampus</h3>
                <x-button variant="primary" icon="plus" @click="locationModal = true">
                    Tambah Lokasi
                </x-button>
            </div>

            <div
                class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <table class="w-full text-left text-xs text-gray-600 dark:text-gray-400">
                    <thead
                        class="bg-gray-50 dark:bg-gray-800/50 text-gray-700 dark:text-gray-300 font-bold uppercase border-b border-gray-200 dark:border-gray-800">
                        <tr>
                            <th class="px-5 py-3.5">Tipe</th>
                            <th class="px-5 py-3.5">Nama Lokasi</th>
                            <th class="px-5 py-3.5">Induk</th>
                            <th class="px-5 py-3.5">Kode</th>
                            <th class="px-5 py-3.5 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($locations as $l)
                            <tr>
                                <td class="px-5 py-3.5">
                                    <span
                                        class="px-2 py-0.5 rounded text-[11px] font-semibold uppercase {{ $l->type === 'building' ? 'bg-blue-50 text-blue-700' : ($l->type === 'floor' ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700') }}">
                                        {{ $l->type }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 font-bold text-gray-900 dark:text-white">{{ $l->name }}</td>
                                <td class="px-5 py-3.5">{{ $l->parent?->name ?? '-' }}</td>
                                <td class="px-5 py-3.5"><code>{{ $l->code ?? '-' }}</code></td>
                                <td class="px-5 py-3.5 text-center">
                                    <x-button type="button" variant="table-danger" icon="trash-2"
                                        onclick="confirmDelete('{{ route('admin.locations.destroy', $l) }}', 'lokasi {{ addslashes($l->name) }}')">
                                        Hapus
                                    </x-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-gray-400">Belum ada lokasi kampus
                                    terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB 3: SERVICES & SLA --}}
        <div x-cloak x-show="tab === 'services'" id="pane-tab-services" class="space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="font-bold text-sm text-gray-900 dark:text-white">Katalog Layanan & Target Resolusi SLA</h3>
                <x-button variant="primary" icon="plus" @click="serviceModal = true">
                    Tambah Layanan
                </x-button>
            </div>

            <div
                class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <table class="w-full text-left text-xs text-gray-600 dark:text-gray-400">
                    <thead
                        class="bg-gray-50 dark:bg-gray-800/50 text-gray-700 dark:text-gray-300 font-bold uppercase border-b border-gray-200 dark:border-gray-800">
                        <tr>
                            <th class="px-5 py-3.5">Kategori</th>
                            <th class="px-5 py-3.5">Layanan</th>
                            <th class="px-5 py-3.5">Target SLA</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($services as $s)
                            <tr>
                                <td class="px-5 py-3.5 font-semibold text-gray-700 dark:text-gray-300">{{ $s->category }}
                                </td>
                                <td class="px-5 py-3.5 font-bold text-gray-900 dark:text-white">{{ $s->name }}</td>
                                <td class="px-5 py-3.5"><span
                                        class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 font-semibold">{{ $s->sla_resolution_hours }}
                                        Jam</span></td>
                                <td class="px-5 py-3.5">
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700">Aktif</span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <x-button type="button" variant="table-danger" icon="trash-2"
                                        onclick="confirmDelete('{{ route('admin.services.destroy', $s) }}', 'layanan {{ addslashes($s->name) }}')">
                                        Hapus
                                    </x-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-gray-400">Belum ada katalog layanan
                                    terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Create Unit Modal --}}
        <div x-show="unitModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
            style="display: none;">
            <div @click.away="unitModal = false"
                class="bg-white dark:bg-gray-900 rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="font-bold text-gray-900 dark:text-white text-base">Tambah Unit & Divisi</h3>
                    <button type="button" @click="unitModal = false"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.units.store') }}" class="space-y-4 mt-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Unit
                            *</label>
                        <input type="text" name="name" required placeholder="Contoh: Fakultas Rekayasa Industri"
                            class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Kode Unit</label>
                        <input type="text" name="code" placeholder="Contoh: FRI"
                            class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5 text-gray-900 dark:text-white">
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="unitModal = false"
                            class="px-4 py-2 text-xs font-semibold rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-xs font-semibold rounded-xl bg-telkom-600 hover:bg-telkom-700 text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Create Location Modal --}}
        <div x-show="locationModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
            style="display: none;">
            <div @click.away="locationModal = false"
                class="bg-white dark:bg-gray-900 rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="font-bold text-gray-900 dark:text-white text-base">Tambah Lokasi Kampus</h3>
                    <button type="button" @click="locationModal = false"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.locations.store') }}" class="space-y-4 mt-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Tipe Lokasi
                            *</label>
                        <select name="type" required
                            class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5 text-gray-900 dark:text-white">
                            <option value="building">Gedung</option>
                            <option value="floor">Lantai</option>
                            <option value="room">Ruang</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Induk Lokasi
                            (Opsional)</label>
                        <select name="parent_id"
                            class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5 text-gray-900 dark:text-white">
                            <option value="">-- Tanpa Induk (Root / Gedung Utama) --</option>
                            @foreach ($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->type }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Lokasi
                            *</label>
                        <input type="text" name="name" required placeholder="Contoh: Gedung Deli / Ruang 201"
                            class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Kode
                            Lokasi</label>
                        <input type="text" name="code" placeholder="Contoh: PA-101"
                            class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5 text-gray-900 dark:text-white">
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="locationModal = false"
                            class="px-4 py-2 text-xs font-semibold rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-xs font-semibold rounded-xl bg-telkom-600 hover:bg-telkom-700 text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Create Service Modal --}}
        <div x-show="serviceModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
            style="display: none;">
            <div @click.away="serviceModal = false"
                class="bg-white dark:bg-gray-900 rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="font-bold text-gray-900 dark:text-white text-base">Tambah Katalog Layanan & SLA</h3>
                    <button type="button" @click="serviceModal = false"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.services.store') }}" class="space-y-4 mt-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Kategori Layanan
                            *</label>
                        <input type="text" name="category" required placeholder="Contoh: Jaringan, Aplikasi, Hardware"
                            class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Layanan
                            *</label>
                        <input type="text" name="name" required placeholder="Contoh: Gangguan Akses Wi-Fi Kampus"
                            class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Target SLA
                            Resolusi (Jam) *</label>
                        <input type="number" name="sla_resolution_hours" required placeholder="Contoh: 4"
                            min="1"
                            class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-2.5 text-gray-900 dark:text-white">
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="serviceModal = false"
                            class="px-4 py-2 text-xs font-semibold rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-xs font-semibold rounded-xl bg-telkom-600 hover:bg-telkom-700 text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function switchTab(name) {
                ['units', 'locations', 'services'].forEach(t => {
                    const pane = document.getElementById('pane-tab-' + t);
                    const btn = document.getElementById('btn-tab-' + t);
                    if (pane) {
                        if (t === name) {
                            pane.style.removeProperty('display');
                            pane.classList.remove('hidden');
                        } else {
                            pane.style.display = 'none';
                        }
                    }
                    if (btn) {
                        if (t === name) {
                            btn.classList.add('border-telkom-600', 'text-telkom-700', 'dark:border-telkom-500',
                                'dark:text-telkom-400');
                            btn.classList.remove('border-gray-900', 'text-gray-900', 'dark:border-white',
                                'dark:text-white', 'border-transparent', 'text-gray-500');
                        } else {
                            btn.classList.remove('border-telkom-600', 'text-telkom-700', 'dark:border-telkom-500',
                                'dark:text-telkom-400', 'border-gray-900', 'text-gray-900', 'dark:border-white',
                                'dark:text-white');
                            btn.classList.add('border-transparent', 'text-gray-500');
                        }
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                const urlParams = new URLSearchParams(window.location.search);
                const initialTab = urlParams.get('tab') || 'units';
                switchTab(initialTab);
            });
        </script>
    @endpush
@endsection
