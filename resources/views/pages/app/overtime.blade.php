@extends('layouts.app-layout')

@section('title')
Overtime & Lembur <span class="bg-red-100 text-red-700 text-[10px] sm:text-xs px-2.5 py-1 rounded-full inline-flex items-center gap-1.5 align-middle ml-2 font-medium tracking-wide"><span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>Pengelolaan Jam Kerja</span>
@endsection
@section('subtitle', 'Kelola kelebihan jam kerja harian dan alokasikan untuk melengkapi hari kerja lainnya.')

@php
  $saldoLembur = [
      [
          'nama' => 'Fitriani Latifah',
          'tgl' => '24 Juli 2026',
          'durasi' => '3 Jam 9 Menit',
          'saldo' => '3 Jam 9 Menit',
          'status' => 'penuh',
      ],
      [
          'nama' => 'Irfan Yasin',
          'tgl' => '23 Juli 2026',
          'durasi' => '2 Jam 0 Menit',
          'saldo' => '1 Jam 8 Menit',
          'status' => 'sebagian',
      ],
      [
          'nama' => 'Fitriani Latifah',
          'tgl' => '23 Juli 2026',
          'durasi' => '4 Jam 24 Menit',
          'saldo' => '4 Jam 24 Menit',
          'status' => 'penuh',
      ],
  ];

  $riwayatAlokasi = [
      [
          'nama' => 'Irfan Yasin',
          'tgl' => '29 Jul 2026',
          'target' => '28 Jul 2026',
          'jumlah' => '0 Jam 52 Menit',
      ]
  ];

  $statusMap = [
      'penuh' => [
          'label' => 'Tersedia Penuh',
          'cls' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
      ],
      'sebagian' => [
          'label' => 'Terpakai Sebagian',
          'cls' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
      ],
  ];
@endphp

@section('content')
  <!-- ============ VIEW: OVERTIME ============ -->
  <section id="view-overtime" class="view space-y-6">
    <!-- Rekap Overtime -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div>
          <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">TOTAL AKUMULASI LEMBUR</p>
          <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">174.0 Jam</p>
          <p class="text-xs text-gray-500 mt-1">Total 10441 menit terdeteksi</p>
        </div>
      </div>
      <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div>
          <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">SALDO LEMBUR AKTIF</p>
          <p class="text-xl sm:text-2xl font-bold text-green-600">128.3 Jam</p>
          <p class="text-xs text-gray-500 mt-1">Tersedia 7698 menit untuk ditransfer</p>
        </div>
      </div>
      <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M8 3 4 7l4 4"/><path d="M4 7h16"/><path d="m16 21 4-4-4-4"/><path d="M20 17H4"/></svg>
        </div>
        <div>
          <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">LEMBUR DIALOKASIKAN</p>
          <p class="text-xl sm:text-2xl font-bold text-indigo-600">45.7 Jam</p>
          <p class="text-xs text-gray-500 mt-1">Sebanyak 2743 menit dialihkan</p>
        </div>
      </div>
    </div>

    <!-- Filter -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
      <div class="p-4 lg:p-5 flex flex-col gap-5">
        <div class="flex flex-col lg:flex-row items-end gap-4 w-full">
          <div class="w-full lg:w-[30%]">
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Filter Nama</label>
            <select class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm outline-none focus:ring-2 focus:ring-telkom-500 transition">
              <option>Semua Student Staff</option>
              <option>Irfan Yasin</option>
              <option>Fitriani Latifah</option>
            </select>
          </div>
          
          <div class="w-full lg:w-[25%]">
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Tanggal Mulai</label>
            <input type="date" value="2026-07-16" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm outline-none focus:ring-2 focus:ring-telkom-500 transition text-gray-600 dark:text-gray-300">
          </div>

          <div class="w-full lg:w-[45%] flex flex-col sm:flex-row items-start sm:items-end gap-4">
            <div class="w-full sm:flex-1">
              <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Tanggal Selesai</label>
              <input type="date" value="2026-08-14" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm outline-none focus:ring-2 focus:ring-telkom-500 transition text-gray-600 dark:text-gray-300">
            </div>
            
            <div class="flex items-center gap-2 w-full sm:w-auto">
              <button class="px-6 py-2.5 gradient-telkom text-white font-semibold rounded-xl text-sm hover:opacity-90 transition flex-1 sm:flex-none">
                Filter
              </button>
              <button class="px-6 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold rounded-xl text-sm hover:bg-gray-200 dark:hover:bg-gray-700 transition flex-1 sm:flex-none">
                Reset
              </button>
            </div>
          </div>
        </div>

        <div class="flex items-center gap-3 px-4 py-3 bg-red-50/80 dark:bg-red-900/20 text-red-700 dark:text-red-400 rounded-xl text-sm border border-red-100 dark:border-red-900/30">
          <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
          <p>Menampilkan data <strong>semua student staff</strong> , periode <strong>16 Jul 2026</strong> sampai <strong>14 Agt 2026</strong></p>
        </div>
      </div>
    </div>

    <!-- Table 1 -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
      <div class="p-5 border-b border-gray-100 dark:border-gray-800">
        <h3 class="font-bold text-lg text-gray-900 dark:text-white">Daftar Saldo Lembur Student Staff</h3>
        <p class="text-sm text-gray-500 mt-1">Kelebihan waktu di atas 8 jam per hari yang tercatat otomatis</p>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
          <thead class="bg-red-50/50 dark:bg-red-900/10 text-telkom-700 dark:text-telkom-400 text-xs font-bold uppercase tracking-wider border-b border-red-100 dark:border-red-900/30">
            <tr>
              <th class="px-5 py-4 w-16 text-center">No</th>
              <th class="px-5 py-4">Nama Student Staff</th>
              <th class="px-5 py-4">Tanggal Lembur</th>
              <th class="px-5 py-4">Durasi Lembur</th>
              <th class="px-5 py-4">Saldo Tersisa</th>
              <th class="px-5 py-4 text-center">Status</th>
              <th class="px-5 py-4 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
            @foreach ($saldoLembur as $index => $d)
              @php $s = $statusMap[$d['status']]; @endphp
              <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                <td class="px-5 py-4 text-center text-gray-500">{{ $index + 1 }}</td>
                <td class="px-5 py-4 font-semibold text-gray-900 dark:text-gray-100">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/30 text-telkom-600 dark:text-telkom-400 flex items-center justify-center font-bold text-xs uppercase shrink-0">
                      {{ substr($d['nama'] ?? 'U', 0, 1) }}
                    </div>
                    {{ $d['nama'] ?? '—' }}
                  </div>
                </td>
                <td class="px-5 py-4 font-medium text-gray-600 dark:text-gray-400">{{ $d['tgl'] }}</td>
                <td class="px-5 py-4 tabular-nums">{{ $d['durasi'] }}</td>
                <td class="px-5 py-4 tabular-nums font-bold text-gray-900 dark:text-gray-100">{{ $d['saldo'] }}</td>
                <td class="px-5 py-4 text-center">
                  <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $s['cls'] }}">{{ $s['label'] }}</span>
                </td>
                <td class="px-5 py-4">
                  <div class="flex items-center justify-center gap-3">
                    <button type="button" onclick="openAlokasiModal()" class="flex items-center gap-2 px-3 py-1.5 gradient-telkom text-white text-xs font-semibold rounded-lg hover:opacity-90 transition">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M8 3 4 7l4 4"/><path d="M4 7h16"/><path d="m16 21 4-4-4-4"/><path d="M20 17H4"/></svg>
                      Alihkan Saldo
                    </button>
                    <button type="button" class="p-1 text-gray-400 hover:text-blue-600 transition" title="Edit">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    </button>
                    <button type="button" class="p-1 text-gray-400 hover:text-telkom-600 transition" title="Hapus">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                    </button>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Table 2 -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
      <div class="p-5 border-b border-gray-100 dark:border-gray-800">
        <h3 class="font-bold text-lg text-gray-900 dark:text-white">Riwayat Alokasi & Pengalihan Lembur</h3>
        <p class="text-sm text-gray-500 mt-1">Jejak audit alokasi jam lembur student staff ke presensi hari lain</p>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
          <thead class="bg-red-50/50 dark:bg-red-900/10 text-telkom-700 dark:text-telkom-400 text-xs font-bold uppercase tracking-wider border-b border-red-100 dark:border-red-900/30">
            <tr>
              <th class="px-5 py-4 w-16 text-center">No</th>
              <th class="px-5 py-4">Nama Student Staff</th>
              <th class="px-5 py-4">Tanggal Alokasi</th>
              <th class="px-5 py-4 text-center">Target Presensi</th>
              <th class="px-5 py-4 text-center">Jumlah Dialihkan</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
            @foreach ($riwayatAlokasi as $index => $r)
              <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                <td class="px-5 py-4 text-center text-gray-500">{{ $index + 1 }}</td>
                <td class="px-5 py-4 font-semibold text-gray-900 dark:text-gray-100">{{ $r['nama'] }}</td>
                <td class="px-5 py-4 font-medium text-gray-600 dark:text-gray-400">{{ $r['tgl'] }}</td>
                <td class="px-5 py-4 tabular-nums text-center font-medium">{{ $r['target'] }}</td>
                <td class="px-5 py-4 tabular-nums font-semibold text-indigo-600 text-center">{{ $r['jumlah'] }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- Modal Alokasi Lembur -->
  <div id="alokasiModal"
    class="fixed inset-0 z-[100] flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAlokasiModal()"></div>
    <div
      class="relative bg-white dark:bg-gray-900 rounded-2xl w-full max-w-lg mx-4 overflow-hidden transform scale-95 transition-transform duration-300"
      id="alokasiModalContent">
      <div class="p-5 sm:p-6 flex flex-col h-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <svg class="w-5 h-5 text-telkom-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M8 3 4 7l4 4"/><path d="M4 7h16"/><path d="m16 21 4-4-4-4"/><path d="M20 17H4"/></svg>
            Alokasikan Saldo Lembur
          </h3>
          <button onclick="closeAlokasiModal()"
            class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M18 6 6 18" />
              <path d="m6 6 12 12" />
            </svg>
          </button>
        </div>

        <div class="space-y-4 text-sm">
          <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Student Staff</label>
            <input type="text" value="Irfan Yasin" disabled
              class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 text-gray-500 border border-gray-200 dark:border-gray-700 rounded-xl outline-none">
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Sumber Kelebihan Lembur</label>
            <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900">
              <p class="text-sm text-gray-600 dark:text-gray-400">Tanggal: <strong class="text-gray-900 dark:text-gray-100">Kamis, 23 Juli 2026</strong></p>
              <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Saldo Tersedia: <strong class="text-telkom-600">1 Jam 8 Menit</strong></p>
            </div>
          </div>

          <label class="flex items-center gap-2 cursor-pointer mt-2 w-max">
            <input type="checkbox" id="wfhCheck" class="w-4 h-4 text-telkom-600 rounded border-gray-300 focus:ring-telkom-500" onchange="toggleWfhFields()">
            <span class="font-semibold text-gray-700 dark:text-gray-300">Alokasikan ke WFH / Tidak Masuk</span>
          </label>

          <!-- Default Fields (If NOT WFH) -->
          <div id="defaultFields" class="space-y-4">
            <div>
              <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Presensi Hari Target (Kurang dari 8 Jam)</label>
              <select class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border border-telkom-600 text-gray-900 dark:text-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-telkom-500 transition">
                <option>Senin, 27 Jul 2026 (5 Jam 47 Menit)</option>
                <option>-- Pilih Hari Target --</option>
              </select>
            </div>
            <div class="p-4 bg-blue-50/50 dark:bg-blue-900/10 rounded-xl text-blue-800 dark:text-blue-300 space-y-1">
              <p>Durasi Asli Target: <strong>5 Jam 47 Menit</strong></p>
              <p>Saldo Tambahan Saat Ini: <strong>0 Menit</strong></p>
              <p>Kekurangan Jam: <strong>2 Jam 13 Menit</strong></p>
            </div>
          </div>

          <!-- WFH Fields -->
          <div id="wfhFields" class="space-y-4 hidden">
            <div>
              <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Pilih Hari WFH / Tidak Masuk Kerja</label>
              <select class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl outline-none focus:ring-2 focus:ring-telkom-500 transition">
                <option>-- Pilih Tanggal WFH / Tidak Masuk --</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Deskripsi Pekerjaan <span class="text-telkom-600">(wajib)</span></label>
              <textarea rows="3" placeholder="Tuliskan deskripsi pekerjaan alokasi..." class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl outline-none focus:ring-2 focus:ring-telkom-500 transition"></textarea>
            </div>
            <div>
              <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Bukti Foto <span class="text-telkom-600">(wajib)</span></label>
              <div class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl p-6 flex flex-col items-center justify-center text-center hover:bg-gray-50 dark:hover:bg-gray-800 transition cursor-pointer">
                <svg class="w-6 h-6 text-gray-400 mb-2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                <p class="text-xs text-gray-500">Klik untuk unggah foto bukti alokasi</p>
              </div>
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Jumlah Menit yang Dialihkan</label>
            <div class="relative">
              <input type="number" value="68" class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl outline-none focus:ring-2 focus:ring-telkom-500 transition pr-16 text-gray-900 dark:text-gray-100">
              <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Menit</span>
            </div>
            <div class="flex justify-between items-center mt-2 text-xs">
              <span class="text-gray-500">Setara dengan: 1 Jam 8 Menit</span>
              <button type="button" class="font-bold text-telkom-600 hover:underline">Set Maksimal</button>
            </div>
          </div>
        </div>

        <div class="mt-8 flex items-center gap-3">
          <button type="button" onclick="closeAlokasiModal()"
            class="flex-1 px-4 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold rounded-xl text-sm hover:bg-gray-200 dark:hover:bg-gray-700 transition">
            Batal
          </button>
          <button type="button" onclick="closeAlokasiModal(); showToast('Alokasi berhasil diproses!', 'success')"
            class="flex-1 px-4 py-2.5 gradient-telkom text-white font-semibold rounded-xl text-sm hover:opacity-90 transition">
            Proses Alokasi
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    function openAlokasiModal() {
      const modal = document.getElementById('alokasiModal');
      const content = document.getElementById('alokasiModalContent');
      modal.classList.remove('hidden');
      setTimeout(() => {
        modal.classList.remove('opacity-0');
        content.classList.remove('scale-95');
      }, 10);
    }

    function closeAlokasiModal() {
      const modal = document.getElementById('alokasiModal');
      const content = document.getElementById('alokasiModalContent');
      modal.classList.add('opacity-0');
      content.classList.add('scale-95');
      setTimeout(() => {
        modal.classList.add('hidden');
      }, 300);
    }
    
    function toggleWfhFields() {
      const check = document.getElementById('wfhCheck').checked;
      const defFields = document.getElementById('defaultFields');
      const wfhFields = document.getElementById('wfhFields');
      
      if (check) {
        defFields.classList.add('hidden');
        wfhFields.classList.remove('hidden');
      } else {
        defFields.classList.remove('hidden');
        wfhFields.classList.add('hidden');
      }
    }

    function showToast(msg, type = 'success') {
      const c = document.getElementById('toastContainer') || createToastContainer();
      const t = document.createElement('div');
      t.className =
        `toast bg-green-600 text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-2 text-sm font-medium max-w-xs`;
      t.innerHTML = `<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg><span>${msg}</span>`;
      c.appendChild(t);
      setTimeout(() => {
        t.remove();
      }, 3000);
    }

    function createToastContainer() {
      const c = document.createElement('div');
      c.id = 'toastContainer';
      c.className = 'fixed bottom-20 right-4 lg:bottom-6 lg:right-6 z-50 flex flex-col gap-2';
      document.body.appendChild(c);
      return c;
    }
  </script>
@endsection
