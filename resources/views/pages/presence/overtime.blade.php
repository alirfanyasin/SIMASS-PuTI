@extends('layouts.app-layout')

@section('title')
  Overtime & Lembur <span
    class="bg-red-100 text-red-700 text-[10px] sm:text-xs px-2.5 py-1 rounded-full inline-flex items-center gap-1.5 align-middle ml-2 font-medium tracking-wide"><span
      class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>Pengelolaan Jam Kerja</span>
@endsection
@section('subtitle', 'Kelola kelebihan jam kerja harian dan alokasikan untuk melengkapi hari kerja lainnya.')



@section('content')
  <!-- ============ VIEW: OVERTIME ============ -->
  <section id="view-overtime" class="view space-y-6">
    <!-- Rekap Overtime -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div
        class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" />
            <polyline points="12 6 12 12 16 14" />
          </svg>
        </div>
        <div>
          <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">TOTAL AKUMULASI LEMBUR</p>
          <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">
            {{ number_format($totalAkumulasiMenit / 60, 1) }} Jam</p>
          <p class="text-xs text-gray-500 mt-1">Total {{ $totalAkumulasiMenit }} menit terdeteksi</p>
        </div>
      </div>
      <div
        class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
            <polyline points="22 4 12 14.01 9 11.01" />
          </svg>
        </div>
        <div>
          <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">SALDO LEMBUR AKTIF</p>
          <p class="text-xl sm:text-2xl font-bold text-green-600">{{ number_format($totalSaldoMenit / 60, 1) }} Jam</p>
          <p class="text-xs text-gray-500 mt-1">Tersedia {{ $totalSaldoMenit }} menit untuk ditransfer</p>
        </div>
      </div>
      <div
        class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M8 3 4 7l4 4" />
            <path d="M4 7h16" />
            <path d="m16 21 4-4-4-4" />
            <path d="M20 17H4" />
          </svg>
        </div>
        <div>
          <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">LEMBUR DIALOKASIKAN</p>
          <p class="text-xl sm:text-2xl font-bold text-indigo-600">{{ number_format($totalAlokasiMenit / 60, 1) }} Jam</p>
          <p class="text-xs text-gray-500 mt-1">Sebanyak {{ $totalAlokasiMenit }} menit dialihkan</p>
        </div>
      </div>
    </div>

    <!-- Filter -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
      <form method="GET" action="{{ route('presence.overtime') }}"
        class="p-4 lg:p-5 border-b border-gray-100 dark:border-gray-800 flex flex-col gap-5">
        <div class="flex flex-col lg:flex-row items-end gap-4 w-full">
          @can('manage-presence')
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
          @endcan

          <div class="w-full lg:w-[25%]">
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Tanggal Mulai</label>
            <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
              class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm outline-none focus:ring-2 focus:ring-telkom-500 transition text-gray-600 dark:text-gray-300">
          </div>

          <div class="w-full lg:w-[45%] flex flex-col sm:flex-row items-start sm:items-end gap-4">
            <div class="w-full sm:flex-1">
              <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Tanggal Selesai</label>
              <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}"
                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm outline-none focus:ring-2 focus:ring-telkom-500 transition text-gray-600 dark:text-gray-300">
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
              <button type="submit"
                class="px-6 py-2.5 gradient-telkom text-white font-semibold rounded-xl text-sm hover:opacity-90 transition flex-1 sm:flex-none">
                Filter
              </button>
              <a href="{{ route('presence.overtime') }}"
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
      </form>

      <!-- Info banner -->
      <div
        class="flex items-center gap-3 px-4 lg:px-5 py-3 bg-red-50/80 dark:bg-red-900/20 text-red-700 dark:text-red-400 text-sm border-b border-red-100 dark:border-red-900/30">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
          stroke-linejoin="round" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10" />
          <line x1="12" y1="16" x2="12" y2="12" />
          <line x1="12" y1="8" x2="12.01" y2="8" />
        </svg>
        <p>Menampilkan data overtime dari tanggal <strong>{{ $startDate->format('d M Y') }}</strong> sampai
          <strong>{{ $endDate->format('d M Y') }}</strong>.
        </p>
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
          <thead
            class="bg-red-50/50 dark:bg-red-900/10 text-telkom-700 dark:text-telkom-400 text-xs font-bold uppercase tracking-wider border-b border-red-100 dark:border-red-900/30">
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
                <td class="px-5 py-4 font-medium text-gray-600 dark:text-gray-400">{{ $d['tgl'] }}</td>
                <td class="px-5 py-4 tabular-nums">{{ $d['durasi'] }}</td>
                <td class="px-5 py-4 tabular-nums font-bold text-gray-900 dark:text-gray-100">{{ $d['saldo'] }}</td>
                <td class="px-5 py-4 text-center">
                  <span
                    class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $s['cls'] }}">{{ $s['label'] }}</span>
                </td>
                <td class="px-5 py-4">
                  <div class="flex items-center justify-center gap-3">
                    <button type="button" onclick="openAlokasiModal({{ $d['id'] }}, {{ $d['user_id'] }}, '{{ addslashes($d['nama']) }}', '{{ $d['tgl'] }}', {{ $d['raw_saldo'] }}, '{{ $d['saldo'] }}')"
                      class="flex items-center gap-2 px-3 py-1.5 gradient-telkom text-white text-xs font-semibold rounded-lg hover:opacity-90 transition"
                      @if($d['raw_saldo'] <= 0) disabled class="opacity-50 cursor-not-allowed grayscale" @endif>
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M8 3 4 7l4 4" />
                        <path d="M4 7h16" />
                        <path d="m16 21 4-4-4-4" />
                        <path d="M20 17H4" />
                      </svg>
                      Alihkan Saldo
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
          <thead
            class="bg-red-50/50 dark:bg-red-900/10 text-telkom-700 dark:text-telkom-400 text-xs font-bold uppercase tracking-wider border-b border-red-100 dark:border-red-900/30">
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
  <x-modal id="alokasiModal" title="Alokasikan Saldo Lembur" bodyClass="space-y-4 text-sm">
    <x-slot:header>
      <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
        <svg class="w-5 h-5 text-telkom-600" fill="none" stroke="currentColor" stroke-width="2"
          stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M8 3 4 7l4 4" />
          <path d="M4 7h16" />
          <path d="m16 21 4-4-4-4" />
          <path d="M20 17H4" />
        </svg>
        Alokasikan Saldo Lembur
      </h3>
    </x-slot:header>

    <form method="POST" action="{{ route('presence.overtime.transfer') }}" id="formAlokasiLembur">
      @csrf
      <input type="hidden" name="overtime_id" id="modal_overtime_id">
      
      <div class="space-y-4 text-sm px-1">
        <div>
          <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Student Staff</label>
          <input type="text" id="modal_user_name" disabled
            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 text-gray-500 border border-gray-200 dark:border-gray-700 rounded-xl outline-none">
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Sumber Kelebihan Lembur</label>
          <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900">
            <p class="text-sm text-gray-600 dark:text-gray-400">Tanggal: <strong class="text-gray-900 dark:text-gray-100" id="modal_sumber_tgl"></strong></p>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Saldo Tersedia: <strong class="text-telkom-600" id="modal_sumber_saldo"></strong></p>
          </div>
        </div>

        <label class="flex items-center gap-2 cursor-pointer mt-2 w-max">
          <input type="checkbox" name="is_wfh" id="wfhCheck" value="1"
            class="w-4 h-4 text-telkom-600 rounded border-gray-300 focus:ring-telkom-500" onchange="toggleWfhFields()">
          <span class="font-semibold text-gray-700 dark:text-gray-300">Alokasikan ke WFH / Tidak Masuk</span>
        </label>

        <!-- Default Fields (If NOT WFH) -->
        <div id="defaultFields" class="space-y-4">
          <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Presensi Hari Target (Kurang dari 8 Jam)</label>
            <select name="presence_id" id="modal_presence_id" onchange="updateTargetInfo()"
              class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border border-telkom-600 text-gray-900 dark:text-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-telkom-500 transition">
              <option value="">-- Pilih Hari Target --</option>
            </select>
          </div>
          <div id="targetInfoBox" class="hidden p-4 bg-blue-50/50 dark:bg-blue-900/10 rounded-xl text-blue-800 dark:text-blue-300 space-y-1">
            <p>Durasi Asli Target: <strong id="info_durasi"></strong></p>
            <p>Kekurangan Jam: <strong id="info_kurang"></strong></p>
          </div>
        </div>

        <!-- WFH Fields -->
        <div id="wfhFields" class="space-y-4 hidden">
          <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Pilih Hari WFH / Tidak Masuk Kerja</label>
            <select name="tanggal_wfh" id="modal_tanggal_wfh"
              class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-telkom-500 transition">
              <option value="">-- Pilih Hari Tidak Masuk --</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Deskripsi Pekerjaan <span class="text-telkom-600">(wajib)</span></label>
            <textarea name="keterangan_wfh" rows="3" placeholder="Tuliskan deskripsi pekerjaan alokasi..."
              class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl outline-none focus:ring-2 focus:ring-telkom-500 transition"></textarea>
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Jumlah Menit yang Dialihkan</label>
          <div class="relative">
            <input type="number" name="durasi_menit" id="modal_durasi_menit" required
              class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl outline-none focus:ring-2 focus:ring-telkom-500 transition pr-16 text-gray-900 dark:text-gray-100">
            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Menit</span>
          </div>
          <div class="flex justify-between items-center mt-2 text-xs">
            <span class="text-gray-500" id="modal_durasi_format">Setara dengan: 0 Menit</span>
            <button type="button" onclick="setMaksimal()" class="font-bold text-telkom-600 hover:underline">Set Maksimal</button>
          </div>
        </div>
      </div>
      
      <div class="mt-8 flex items-center gap-3 w-full">
        <button type="button" onclick="closeAlokasiModal()"
          class="flex-1 px-4 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold rounded-xl text-sm hover:bg-gray-200 dark:hover:bg-gray-700 transition">
          Batal
        </button>
        <button type="submit"
          class="flex-1 px-4 py-2.5 gradient-telkom text-white font-semibold rounded-xl text-sm hover:opacity-90 transition">
          Proses Alokasi
        </button>
      </div>
    </form>
  </x-modal>

  <script>
    const eligiblePresences = @json($eligiblePresences);
    const absentDays = @json($absentDays);
    let currentSaldo = 0;
    
    function formatMenit(menit) {
        let jam = Math.floor(menit / 60);
        let sisa = menit % 60;
        return `${jam} Jam ${sisa} Menit`;
    }

    function openAlokasiModal(id, userId, userName, tgl, rawSaldo, strSaldo) {
      document.getElementById('modal_overtime_id').value = id;
      document.getElementById('modal_user_name').value = userName;
      document.getElementById('modal_sumber_tgl').textContent = tgl;
      document.getElementById('modal_sumber_saldo').textContent = strSaldo;
      currentSaldo = rawSaldo;
      
      const presenceSelect = document.getElementById('modal_presence_id');
      presenceSelect.innerHTML = '<option value="">-- Pilih Hari Target --</option>';
      
      if (eligiblePresences[userId]) {
        eligiblePresences[userId].forEach(p => {
          let option = document.createElement('option');
          option.value = p.id;
          option.text = `${p.tanggal} (Kurang ${p.kurang_format})`;
          option.dataset.durasi = p.durasi;
          option.dataset.kurang = p.kurang_menit;
          option.dataset.kurangFormat = p.kurang_format;
          presenceSelect.appendChild(option);
        });
      }

      // Populate WFH select
      const absentSelect = document.getElementById('modal_tanggal_wfh');
      absentSelect.innerHTML = '<option value="">-- Pilih Hari Tidak Masuk --</option>';
      if (absentDays[userId]) {
        absentDays[userId].forEach(d => {
          let option = document.createElement('option');
          option.value = d.val;
          option.text = d.label;
          absentSelect.appendChild(option);
        });
      }
      
      document.getElementById('modal_durasi_menit').value = '';
      document.getElementById('modal_durasi_format').textContent = 'Setara dengan: 0 Menit';
      updateTargetInfo();
      openModal('alokasiModal');
    }

    document.getElementById('modal_durasi_menit').addEventListener('input', function() {
        let val = parseInt(this.value) || 0;
        if(val > currentSaldo) {
            this.value = currentSaldo;
            val = currentSaldo;
        }
        
        if(document.getElementById('wfhCheck').checked) {
            let maxWfh = Math.min(240, currentSaldo);
            if(val > maxWfh) {
                this.value = maxWfh;
                val = maxWfh;
            }
        } else {
            let select = document.getElementById('modal_presence_id');
            if (select.selectedIndex > 0) {
                let option = select.options[select.selectedIndex];
                let kurang = parseInt(option.dataset.kurang) || 0;
                if(val > kurang) {
                    this.value = kurang;
                    val = kurang;
                }
            }
        }
        
        document.getElementById('modal_durasi_format').textContent = 'Setara dengan: ' + formatMenit(val);
    });

    function setMaksimal() {
        let val = currentSaldo;
        
        if(document.getElementById('wfhCheck').checked) {
            val = Math.min(240, currentSaldo);
        } else {
            let select = document.getElementById('modal_presence_id');
            if (select.selectedIndex > 0) {
                let option = select.options[select.selectedIndex];
                let kurang = parseInt(option.dataset.kurang) || 0;
                if(val > kurang) {
                    val = kurang;
                }
            } else {
                alert('Pilih Hari Target terlebih dahulu!');
                return;
            }
        }
        
        let input = document.getElementById('modal_durasi_menit');
        input.value = val;
        // Trigger event listener manually
        input.dispatchEvent(new Event('input'));
    }

    function updateTargetInfo() {
        let select = document.getElementById('modal_presence_id');
        let infoBox = document.getElementById('targetInfoBox');
        
        if (select.selectedIndex > 0) {
            let option = select.options[select.selectedIndex];
            document.getElementById('info_durasi').textContent = option.dataset.durasi;
            document.getElementById('info_kurang').textContent = option.dataset.kurangFormat;
            infoBox.classList.remove('hidden');
        } else {
            infoBox.classList.add('hidden');
        }
    }

    function closeAlokasiModal() {
      closeModal('alokasiModal');
    }

    function toggleWfhFields() {
      const check = document.getElementById('wfhCheck').checked;
      const defFields = document.getElementById('defaultFields');
      const wfhFields = document.getElementById('wfhFields');
      const presenceSelect = document.getElementById('modal_presence_id');
      const absentSelect = document.getElementById('modal_tanggal_wfh');

      if (check) {
        defFields.classList.add('hidden');
        wfhFields.classList.remove('hidden');
        presenceSelect.removeAttribute('required');
        absentSelect.setAttribute('required', 'required');
      } else {
        defFields.classList.remove('hidden');
        wfhFields.classList.add('hidden');
        presenceSelect.setAttribute('required', 'required');
        absentSelect.removeAttribute('required');
      }
      
      // Reset input duration
      const input = document.getElementById('modal_durasi_menit');
      input.value = '';
      document.getElementById('modal_durasi_format').textContent = 'Setara dengan: 0 Menit';
    }
  </script>
@endsection
