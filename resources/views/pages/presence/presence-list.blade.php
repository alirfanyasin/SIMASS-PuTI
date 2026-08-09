@extends('layouts.app-layout')

@section('title')
  Daftar Kehadiran
@endsection
@section('subtitle', 'Riwayat presensi seluruh student staff secara real-time')



@section('content')
  <!-- ============ VIEW: DAFTAR PRESENSI ============ -->
  <section id="view-daftar" class="view space-y-4">

    {{-- ── CARD FILTER ── --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
      <form method="GET" action="{{ route('presence.list') }}" class="p-4 lg:p-5 flex flex-col gap-5">
        <div class="flex flex-col lg:flex-row items-end gap-4 w-full">
          <div class="w-full lg:w-[30%]">
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Filter Nama</label>
            <select name="user_id" onchange="this.form.submit()"
              class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm outline-none focus:ring-2 focus:ring-telkom-500 transition">
              <option value="all">Semua Student Staff</option>
              @foreach ($users as $u)
                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                  {{ $u->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="w-full lg:w-[25%]">
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Tanggal Mulai</label>
            <input type="date" name="start_date" value="{{ request('start_date', $startDate) }}"
              class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm outline-none focus:ring-2 focus:ring-telkom-500 transition text-gray-600 dark:text-gray-300">
          </div>

          <div class="w-full lg:w-[45%] flex flex-col sm:flex-row items-start sm:items-end gap-4">
            <div class="w-full sm:flex-1">
              <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Tanggal Selesai</label>
              <input type="date" name="end_date" value="{{ request('end_date', $endDate) }}"
                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm outline-none focus:ring-2 focus:ring-telkom-500 transition text-gray-600 dark:text-gray-300">
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
              <button type="submit"
                class="px-6 py-2.5 gradient-telkom text-white font-semibold rounded-xl text-sm hover:opacity-90 transition flex-1 sm:flex-none">
                Filter
              </button>
              <a href="{{ route('presence.list') }}"
                class="px-6 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold rounded-xl text-sm hover:bg-gray-200 dark:hover:bg-gray-700 transition flex-1 sm:flex-none text-center">
                Reset
              </a>
              <button type="button"
                class="px-4 py-2.5 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition flex items-center justify-center gap-2"
                title="Export">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round" viewBox="0 0 24 24">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                  <polyline points="7 10 12 15 17 10" />
                  <line x1="12" x2="12" y1="15" y2="3" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        {{-- Info banner --}}
        <div
          class="flex items-center gap-3 px-4 py-3 bg-red-50/80 dark:bg-red-900/20 text-red-700 dark:text-red-400 rounded-xl text-sm border border-red-100 dark:border-red-900/30">
          <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="16" x2="12" y2="12" />
            <line x1="12" y1="8" x2="12.01" y2="8" />
          </svg>
          <p>Menampilkan data presensi dari tanggal <strong>16 Juli 2026</strong> sampai <strong>14 Agustus 2026</strong>.
          </p>
        </div>
      </form>
    </div>

    {{-- ── CARD TABLE ── --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">

      {{-- Table header / title --}}
      <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800">
        <h2 class="font-bold text-base text-gray-900 dark:text-gray-100">Daftar Presensi Student Staff</h2>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Riwayat presensi berdasarkan rentang tanggal yang
          dipilih</p>
      </div>

      <!-- Table (desktop) -->
      <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm text-left">
          <thead
            class="bg-red-50/50 dark:bg-red-900/10 text-telkom-700 dark:text-telkom-400 text-xs font-bold uppercase tracking-wider border-b border-red-100 dark:border-red-900/30">
            <tr>
              <th class="px-5 py-4 w-16 text-center">No</th>
              <th class="px-5 py-4">Nama</th>
              <th class="px-5 py-4">Tanggal</th>
              <th class="px-5 py-4">Waktu</th>
              <th class="px-5 py-4">Durasi</th>
              <th class="px-5 py-4 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-800" id="tableBody">
            @foreach ($presensiData as $index => $d)
              <tr
                class="border-b border-gray-50 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                <td class="px-5 py-3.5 text-center text-gray-500">{{ $index + 1 }}</td>
                <td class="px-5 py-3.5 font-semibold text-gray-900 dark:text-gray-100">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/30 text-telkom-600 dark:text-telkom-400 flex items-center justify-center font-bold text-xs uppercase shrink-0">
                      {{ substr($d['nama'] ?? 'U', 0, 1) }}
                    </div>
                    {{ $d['nama'] ?? '—' }}
                  </div>
                </td>
                <td class="px-5 py-3.5 font-medium text-gray-600 dark:text-gray-400">{{ $d['tgl'] }}</td>
                <td class="px-5 py-3.5 tabular-nums font-semibold">
                  <span class="text-green-600">{{ $d['cin'] }}</span> <span class="text-gray-400">-</span> <span
                    class="text-telkom-600">{{ $d['cout'] }}</span>
                </td>
                <td class="px-5 py-3.5 tabular-nums font-semibold text-green-600">{{ $d['durasi'] }}</td>
                <td class="px-5 py-3.5">
                  <div class="flex items-center justify-center gap-2">
                    <button type="button"
                      onclick="openProofModal('{{ addslashes($d['nama'] ?? '') }}', '{{ $d['tgl'] }}', '{{ $d['cin'] }}', '{{ addslashes($d['pekerjaan'] ?? '') }}', '{{ $d['foto'] ?? '' }}')"
                      class="p-1 text-gray-500 hover:text-telkom-600 transition" title="Lihat Bukti">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                        <circle cx="12" cy="12" r="3" />
                      </svg>
                    </button>
                    <button type="button" class="p-1 text-gray-500 hover:text-blue-600 transition" title="Edit"
                      onclick="openEditModal({{ $d['id'] }}, '{{ $d['raw_cin'] }}', '{{ $d['raw_cout'] }}', '{{ addslashes($d['pekerjaan'] ?? '') }}')">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M12 20h9" />
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                      </svg>
                    </button>
                    <button type="button" class="p-1 text-gray-500 hover:text-telkom-600 transition" title="Hapus"
                      onclick="confirmDelete({{ $d['id'] }})">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <polyline points="3 6 5 6 21 6" />
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                        <line x1="10" y1="11" x2="10" y2="17" />
                        <line x1="14" y1="11" x2="14" y2="17" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Card list (mobile) -->
      <div class="md:hidden p-4 space-y-4 bg-gray-50 dark:bg-gray-800/20" id="cardList">
        @foreach ($presensiData as $d)
          @php $s = $statusMap[$d['status']]; @endphp
          <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm">
            <div class="flex items-center justify-between mb-2">
              <div>
                <p class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ $d['nama'] ?? '—' }}</p>
                <p class="font-medium text-xs text-gray-500 mt-0.5">{{ $d['tgl'] }}</p>
              </div>
              <span
                class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $s['cls'] }}">{{ $s['label'] }}</span>
            </div>
            <div class="grid grid-cols-3 gap-2 text-xs mt-3">
              <div>
                <p class="text-gray-400">Masuk</p>
                <p class="font-semibold tabular-nums">{{ $d['cin'] }}</p>
              </div>
              <div>
                <p class="text-gray-400">Pulang</p>
                <p class="font-semibold tabular-nums">{{ $d['cout'] }}</p>
              </div>
              <div class="flex items-end justify-between">
                <div>
                  <p class="text-gray-400">Durasi</p>
                  <p class="font-semibold tabular-nums">{{ $d['durasi'] }}</p>
                </div>
                <div class="flex items-center gap-1">
                  <button type="button"
                    onclick="openProofModal('{{ addslashes($d['nama'] ?? '') }}', '{{ $d['tgl'] }}', '{{ $d['cin'] }}', '{{ addslashes($d['pekerjaan'] ?? '') }}', '{{ $d['foto'] ?? '' }}')"
                    class="p-1.5 text-gray-500 hover:text-telkom-600 bg-gray-100 dark:bg-gray-800 hover:bg-telkom-50 rounded-lg transition shrink-0"
                    title="Lihat Bukti">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                      stroke-linejoin="round" viewBox="0 0 24 24">
                      <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                      <circle cx="12" cy="12" r="3" />
                    </svg>
                  </button>
                  <button type="button"
                    onclick="openEditModal({{ $d['id'] }}, '{{ $d['raw_cin'] }}', '{{ $d['raw_cout'] }}', '{{ addslashes($d['pekerjaan'] ?? '') }}')"
                    class="p-1.5 text-gray-500 hover:text-blue-600 bg-gray-100 dark:bg-gray-800 hover:bg-blue-50 rounded-lg transition shrink-0"
                    title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                      <path d="M12 20h9" />
                      <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                    </svg>
                  </button>
                  <button type="button"
                    onclick="confirmDelete({{ $d['id'] }})"
                    class="p-1.5 text-gray-500 hover:text-telkom-600 bg-gray-100 dark:bg-gray-800 hover:bg-telkom-50 rounded-lg transition shrink-0"
                    title="Hapus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                      <polyline points="3 6 5 6 21 6" />
                      <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                      <line x1="10" y1="11" x2="10" y2="17" />
                      <line x1="14" y1="11" x2="14" y2="17" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

    </div>
  </section>

  <!-- Modal Bukti Kehadiran -->
  <x-modal id="proofModal" size="sm" title="Bukti Kehadiran" bodyClass="!p-0" headerClass="p-4 border-b border-gray-100 dark:border-gray-800">
    <div class="p-4 space-y-4">
      <!-- Image placeholder -->
      <div class="aspect-video bg-gray-100 dark:bg-gray-800 rounded-2xl overflow-hidden relative shadow-inner">
        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=600&auto=format&fit=crop"
          alt="Bukti Kehadiran" class="w-full h-full object-cover" id="proofImage">
        <div
          class="absolute bottom-3 left-3 bg-black/60 backdrop-blur-md text-white px-3 py-1.5 rounded-xl text-xs font-medium border border-white/10 shadow-lg">
          <span id="proofDate">Tanggal</span>
        </div>
      </div>
      <div class="text-sm bg-gray-50 dark:bg-gray-800/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-800">
        <div>
          <span class="text-gray-500 block mb-1">Deskripsi Pekerjaan</span>
          <p class="font-medium text-gray-900 dark:text-gray-100 text-xs leading-relaxed" id="proofDesc">Deskripsi</p>
        </div>
      </div>
    </div>
  </x-modal>

  <!-- Edit Modal -->
  <x-modal id="editModal" size="sm" title="Edit Presensi" bodyClass="!p-0" headerClass="p-4 border-b border-gray-100 dark:border-gray-800">
    <form id="editForm" method="POST">
      @csrf
      @method('PUT')
      <div class="p-5 space-y-4">
        <div>
          <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Jam Masuk</label>
          <input type="time" name="jam_masuk" id="editJamMasuk" required class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm outline-none focus:ring-2 focus:ring-telkom-500">
        </div>
        <div>
          <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Jam Pulang</label>
          <input type="time" name="jam_pulang" id="editJamPulang" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm outline-none focus:ring-2 focus:ring-telkom-500">
        </div>
        <div>
          <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Pekerjaan</label>
          <textarea name="pekerjaan" id="editPekerjaan" rows="3" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm outline-none focus:ring-2 focus:ring-telkom-500"></textarea>
        </div>
      </div>
      <div class="p-4 border-t border-gray-100 dark:border-gray-800 flex justify-end gap-2">
        <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">Batal</button>
        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white gradient-telkom rounded-xl shadow-lg hover:opacity-90 transition">Simpan Perubahan</button>
      </div>
    </form>
  </x-modal>

  <!-- Delete Form -->
  <form id="deleteForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
  </form>
@endsection

@push('scripts')
  <script>
    function openProofModal(nama, tgl, jam, desc, foto) {
      document.getElementById('proofDate').innerText = `${tgl || '—'} - ${jam || '—'}`;
      document.getElementById('proofDesc').innerText = desc || 'Tidak ada deskripsi pekerjaan.';

      const imgEl = document.getElementById('proofImage');
      if (foto) {
        // Handle path rendering correctly
        imgEl.src = foto.startsWith('http') || foto.startsWith('/') ? foto : '/storage/' + foto;
      } else {
        imgEl.src = "https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=600&auto=format&fit=crop";
      }

      openModal('proofModal');
    }

    function openEditModal(id, cin, cout, pekerjaan) {
      const form = document.getElementById('editForm');
      
      form.action = `/presence/${id}`;
      document.getElementById('editJamMasuk').value = cin ? cin.substring(0, 5) : '';
      document.getElementById('editJamPulang').value = cout ? cout.substring(0, 5) : '';
      document.getElementById('editPekerjaan').value = pekerjaan;

      openModal('editModal');
    }

    function confirmDelete(id) {
      if(confirm('Apakah Anda yakin ingin menghapus data presensi ini?')) {
        const form = document.getElementById('deleteForm');
        form.action = `/presence/${id}`;
        form.submit();
      }
    }
  </script>
@endpush
