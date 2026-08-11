@extends('layouts.app-layout')

@section('title', 'Riwayat Bulanan')
@section('subtitle', 'Rekapitulasi presensi student staff')



@section('content')
  <!-- View: Riwayat Bulanan -->
  <div id="view-list" class="{{ $viewMode === 'list' ? 'block' : 'hidden' }} space-y-6">
    <div class="mb-6">
      <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Riwayat Bulanan</h2>
      <p class="text-sm text-gray-500 mt-1">Rekapitulasi presensi student staff berdasarkan bulan</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach ($months as $m)
        <div
          class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 flex flex-col gap-6">

          <div
            class="w-12 h-12 rounded-xl bg-red-100/50 dark:bg-red-900/30 text-telkom-600 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round" viewBox="0 0 24 24">
              <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
              <line x1="16" x2="16" y1="2" y2="6" />
              <line x1="8" x2="8" y1="2" y2="6" />
              <line x1="3" x2="21" y1="10" y2="10" />
            </svg>
          </div>

          <div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $m['title'] }}</h3>
            <p class="text-xs font-semibold text-telkom-600 mt-1">{{ $m['period'] }}</p>
          </div>

          <div class="space-y-4 my-2">
            <div class="flex justify-between items-center text-sm">
              <span class="text-gray-500">Total Kehadiran</span>
              <span class="font-bold text-gray-900 dark:text-white">{{ $m['total'] }}</span>
            </div>
            <div class="flex justify-between items-center text-sm">
              <span class="text-gray-500">Student Staff Aktif</span>
              <span class="font-bold text-gray-900 dark:text-white">{{ $m['staff'] }}</span>
            </div>
          </div>

          <a href="{{ route('presence.history', ['period' => $m['id']]) }}"
            class="w-full mt-auto py-3 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold rounded-xl text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition flex items-center justify-center gap-2">
            Lihat Detail
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="m9 18 6-6-6-6" />
            </svg>
          </a>
        </div>
      @endforeach
    </div>
  </div>

  <!-- View: Detail Bulanan -->
  <div id="view-detail" class="{{ $viewMode === 'detail' ? 'block' : 'hidden' }} space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div class="flex items-center gap-4">
        <div>
          <h2 id="detail-title" class="text-2xl font-bold text-gray-900 dark:text-white">Presensi {{ $detailTitle }}</h2>
          <p id="detail-period" class="text-sm text-gray-500 mt-1">{{ $detailPeriod }}</p>
        </div>
      </div>
    </div>



    <!-- TAB CONTENT: TABEL -->
    <div id="content-tabel" class="block space-y-6">
      <!-- Filter -->
      <form action="{{ route('presence.history') }}" method="GET" class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        @if(request('period'))
          <input type="hidden" name="period" value="{{ request('period') }}">
        @endif
        <div class="p-4 lg:p-5 flex flex-col sm:flex-row items-end gap-4">
          <div class="w-full sm:w-64">
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Filter Nama</label>
            <select name="user_id"
              class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm outline-none focus:ring-2 focus:ring-telkom-500 transition">
              <option value="all">Semua Student Staff</option>
              @foreach($users as $u)
                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="flex gap-2 w-full sm:w-auto">
            <button type="submit"
              class="px-6 py-2.5 gradient-telkom text-white font-semibold rounded-xl text-sm hover:opacity-90 transition flex-1 sm:flex-none">Filter</button>
            <a href="{{ request('period') ? route('presence.history', ['period' => request('period')]) : route('presence.history') }}"
              class="px-6 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold rounded-xl text-sm hover:bg-gray-200 dark:hover:bg-gray-700 transition flex items-center justify-center flex-1 sm:flex-none">Reset</a>
            <button type="button" onclick="exportHistoryPdf()"
              class="px-4 py-2.5 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition flex items-center justify-center gap-2" title="Export PDF">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" /><polyline points="7 10 12 15 17 10" /><line x1="12" x2="12" y1="15" y2="3" /></svg>
            </button>
          </div>
        </div>
      </form>

      <!-- Table -->
      <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left">
            <thead
              class="bg-red-50/50 dark:bg-red-900/10 text-telkom-700 dark:text-telkom-400 text-xs font-bold uppercase tracking-wider border-b border-red-100 dark:border-red-900/30">
              <tr>
                <th class="px-5 py-4 w-16 text-center">No</th>
                <th class="px-5 py-4">Nama</th>
                <th class="px-5 py-4">Hari</th>
                <th class="px-5 py-4">Tanggal</th>
                <th class="px-5 py-4">Waktu</th>
                <th class="px-5 py-4">Total Jam</th>
                <th class="px-5 py-4 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
              @foreach ($detailData as $index => $d)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                  <td class="px-5 py-4 text-center text-gray-500">{{ $index + 1 }}</td>
                  <td class="px-5 py-4 font-semibold text-gray-900 dark:text-gray-100">
                    <div class="flex items-center gap-3">
                      <div
                        class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/30 text-telkom-600 dark:text-telkom-400 flex items-center justify-center font-bold text-xs uppercase shrink-0">
                        @php
                          $name = $d['nama'] ?? 'U';
                          $words = explode(' ', trim($name));
                          $initials = count($words) > 1 ? substr($words[0], 0, 1) . substr($words[1], 0, 1) : substr($name, 0, 2);
                        @endphp
                        {{ strtoupper($initials) }}
                      </div>
                      {{ $d['nama'] ?? '—' }}
                    </div>
                  </td>
                  <td class="px-5 py-4 font-medium text-gray-600 dark:text-gray-400">{{ $d['hari'] }}</td>
                  <td class="px-5 py-4 text-gray-600 dark:text-gray-400">{{ $d['tgl'] }}</td>
                  <td class="px-5 py-4 tabular-nums">{{ $d['waktu'] }}</td>
                  <td class="px-5 py-4 tabular-nums font-semibold text-green-600">{{ $d['jam'] }}</td>
                  <td class="px-5 py-4">
                    <div class="flex items-center justify-center gap-2">
                      <button type="button"
                        onclick="openProofModal('{{ addslashes($d['nama'] ?? '') }}', '{{ $d['tgl'] }}', '{{ $d['waktu'] }}', '{{ addslashes($d['pekerjaan'] ?? '') }}', '{{ $d['foto'] ?? '' }}')"
                        class="p-1.5 text-gray-400 hover:text-telkom-600 transition" title="Lihat Detail">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                          stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                          <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                          <circle cx="12" cy="12" r="3" />
                        </svg>
                      </button>
                      @if(Auth::user()->hasRole('super-admin'))
                        @php
                            $cin = explode(' - ', $d['waktu'])[0] ?? '';
                            $cout = explode(' - ', $d['waktu'])[1] ?? '';
                        @endphp
                        <button type="button" class="p-1.5 text-gray-400 hover:text-blue-600 transition" title="Edit"
                          onclick="openEditModal('{{ $d['id'] ?? '' }}', '{{ $cin }}', '{{ $cout }}', '{{ addslashes($d['pekerjaan'] ?? '') }}')">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                          </svg>
                        </button>
                        <button type="button" class="p-1.5 text-gray-400 hover:text-telkom-600 transition" title="Hapus"
                          onclick="confirmDelete('{{ $d['id'] ?? '' }}')">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <polyline points="3 6 5 6 21 6" />
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                            <line x1="10" y1="11" x2="10" y2="17" />
                            <line x1="14" y1="11" x2="14" y2="17" />
                          </svg>
                        </button>
                      @endif
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

  <!-- Modal Bukti Kehadiran -->
  <div id="proofModal"
    class="fixed inset-0 z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeProofModal()"></div>

    <!-- Modal Content -->
    <div id="proofModalContent"
      class="relative w-full max-w-lg bg-white dark:bg-gray-900 rounded-3xl shadow-2xl scale-95 transition-transform duration-300 overflow-hidden border border-gray-100 dark:border-gray-800 m-4">
      <div class="flex items-center justify-between p-4 md:p-5 border-b border-gray-100 dark:border-gray-800">
        <h3 class="font-bold text-lg">Bukti Kehadiran</h3>
        <button onclick="closeProofModal()"
          class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 bg-gray-100 dark:bg-gray-800 rounded-xl transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" viewBox="0 0 24 24">
            <line x1="18" y1="6" x2="6" y2="18" />
            <line x1="6" y1="6" x2="18" y2="18" />
          </svg>
        </button>
      </div>
      <div class="p-4 space-y-4">
        <!-- Image placeholder -->
        <div class="aspect-[16/9] bg-gray-100 dark:bg-gray-800 rounded-2xl overflow-hidden relative shadow-inner">
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
    </div>
  </div>

  <!-- Modal Alert / Warning -->
  <x-modal id="alertModal" size="sm" title="Perhatian" showCloseButton="true">
    <div class="flex flex-col items-center text-center p-4">
      <div class="w-12 h-12 bg-amber-50 dark:bg-amber-950/30 text-amber-500 rounded-full flex items-center justify-center mb-4">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
      </div>
      <p id="alertModalMessage" class="text-sm font-medium text-gray-600 dark:text-gray-400 leading-relaxed">
        Pesan alert.
      </p>
      <button type="button" onclick="closeModal('alertModal')" class="mt-6 w-full py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-950 rounded-xl text-sm font-bold active:scale-95 transition-all shadow-md">
        Mengerti
      </button>
    </div>
  </x-modal>

  @if(Auth::user()->hasRole('super-admin'))
  <!-- Edit Modal -->
  <div id="editModal"
    class="fixed inset-0 z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-gray-900/40 dark:bg-black/60 backdrop-blur-sm" onclick="closeEditModal()"></div>
    
    <!-- Modal Content -->
    <div id="editModalContent"
      class="relative bg-white dark:bg-gray-900 w-full max-w-sm rounded-3xl shadow-2xl overflow-hidden transform scale-95 transition-transform duration-300 border border-gray-100 dark:border-gray-800">
      
      <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
        <h3 class="text-sm font-bold text-gray-900 dark:text-white">Edit Presensi</h3>
        <button onclick="closeEditModal()" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-200 rounded-xl transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
      </div>
      
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
          <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">Batal</button>
          <button type="submit" class="px-4 py-2 text-sm font-semibold text-white gradient-telkom rounded-xl shadow-lg hover:opacity-90 transition">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Delete Form -->
  <form id="deleteForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
  </form>
  @endif

@endsection

@push('scripts')
  <script>
    function openProofModal(nama, tgl, jam, desc, foto) {
      if (!foto) {
        document.getElementById('alertModalMessage').textContent = 'Bukti kehadiran masih belum ada.';
        openModal('alertModal');
        return;
      }
      const modal = document.getElementById('proofModal');
      const content = document.getElementById('proofModalContent');

      document.getElementById('proofDate').innerText = `${tgl || '—'} - ${jam || '—'}`;
      document.getElementById('proofDesc').innerText = desc || 'Tidak ada deskripsi pekerjaan.';

      const imgEl = document.getElementById('proofImage');
      imgEl.src = foto.startsWith('http') || foto.startsWith('/') ? foto : '/storage/' + foto;

      modal.classList.remove('hidden');
      // trigger reflow
      void modal.offsetWidth;
      modal.classList.remove('opacity-0');
      content.classList.remove('scale-95');
    }

    function closeProofModal() {
      const modal = document.getElementById('proofModal');
      const content = document.getElementById('proofModalContent');

      modal.classList.add('opacity-0');
      content.classList.add('scale-95');

      setTimeout(() => {
        modal.classList.add('hidden');
      }, 300);
    }

    function exportHistoryPdf() {
      const startDate = '{{ $startDate ?? "" }}';
      const endDate = '{{ $endDate ?? "" }}';
      const userSelect = document.querySelector('select[name="user_id"]');
      const userId = userSelect ? userSelect.value : 'all';
      window.open(`/export-pdf?filterNama=${userId}&startDate=${startDate}&endDate=${endDate}`, '_blank');
    }

    @if(Auth::user()->hasRole('super-admin'))
    function openEditModal(id, cin, cout, pekerjaan) {
      if(!id) return;
      const modal = document.getElementById('editModal');
      const content = document.getElementById('editModalContent');
      const form = document.getElementById('editForm');
      
      form.action = `/presence/${id}`;
      document.getElementById('editJamMasuk').value = cin && cin !== '-' ? cin.substring(0, 5) : '';
      document.getElementById('editJamPulang').value = cout && cout !== '-' ? cout.substring(0, 5) : '';
      document.getElementById('editPekerjaan').value = pekerjaan;

      modal.classList.remove('hidden');
      void modal.offsetWidth;
      modal.classList.remove('opacity-0');
      content.classList.remove('scale-95');
    }

    function closeEditModal() {
      const modal = document.getElementById('editModal');
      const content = document.getElementById('editModalContent');

      modal.classList.add('opacity-0');
      content.classList.add('scale-95');

      setTimeout(() => {
        modal.classList.add('hidden');
      }, 300);
    }

    function confirmDelete(id) {
      if(!id) return;
      if(confirm('Apakah Anda yakin ingin menghapus data presensi ini?')) {
        const form = document.getElementById('deleteForm');
        form.action = `/presence/${id}`;
        form.submit();
      }
    }
    @endif
  </script>
@endpush
