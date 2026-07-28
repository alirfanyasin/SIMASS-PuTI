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
      @foreach($months as $m)
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 flex flex-col gap-6">
          
          <div class="w-12 h-12 rounded-xl bg-red-100/50 dark:bg-red-900/30 text-telkom-600 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
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

          <a href="{{ route('app.presence-history', ['period' => $m['id']]) }}" class="w-full mt-auto py-3 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold rounded-xl text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition flex items-center justify-center gap-2">
            Lihat Detail 
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
          </a>
        </div>
      @endforeach
    </div>
  </div>

  <!-- View: Detail Bulanan -->
  <div id="view-detail" class="{{ $viewMode === 'detail' ? 'block' : 'hidden' }} space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div class="flex items-center gap-4">
        <a href="{{ route('app.presence-history') }}" class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-700 transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <div>
          <h2 id="detail-title" class="text-2xl font-bold text-gray-900 dark:text-white">Presensi {{ $detailTitle }}</h2>
          <p id="detail-period" class="text-sm text-gray-500 mt-1">{{ $detailPeriod }}</p>
        </div>
      </div>
    </div>

    <!-- Tabs: Tabel & Kalender -->
    <div class="flex items-center gap-4 border-b border-gray-200 dark:border-gray-800">
      <button id="tab-tabel" onclick="switchTab('tabel')" class="px-4 py-2.5 border-b-2 border-telkom-600 text-telkom-600 font-semibold text-sm transition">Tabel Presensi</button>
      <button id="tab-kalender" onclick="switchTab('kalender')" class="px-4 py-2.5 border-b-2 border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-semibold text-sm transition">Kalender</button>
    </div>

    <!-- TAB CONTENT: TABEL -->
    <div id="content-tabel" class="block space-y-6">
      <!-- Filter -->
      <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        <div class="p-4 lg:p-5 flex flex-col sm:flex-row items-end gap-4">
          <div class="w-full sm:w-64">
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Filter Nama</label>
            <select class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm outline-none focus:ring-2 focus:ring-telkom-500 transition">
              <option>Semua Student Staff</option>
              <option>Irfan Yasin</option>
              <option>Fitriani Latifah</option>
            </select>
          </div>
          <div class="flex gap-2 w-full sm:w-auto">
            <button class="px-6 py-2.5 gradient-telkom text-white font-semibold rounded-xl text-sm hover:opacity-90 transition flex-1 sm:flex-none">Filter</button>
            <button class="px-6 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold rounded-xl text-sm hover:bg-gray-200 dark:hover:bg-gray-700 transition flex-1 sm:flex-none">Reset</button>
          </div>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left">
            <thead class="bg-red-50/50 dark:bg-red-900/10 text-telkom-700 dark:text-telkom-400 text-xs font-bold uppercase tracking-wider border-b border-red-100 dark:border-red-900/30">
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
                      <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/30 text-telkom-600 dark:text-telkom-400 flex items-center justify-center font-bold text-xs uppercase shrink-0">
                        {{ substr($d['nama'] ?? 'U', 0, 1) }}
                      </div>
                      {{ $d['nama'] ?? '—' }}
                    </div>
                  </td>
                  <td class="px-5 py-4 font-medium text-gray-600 dark:text-gray-400">{{ $d['hari'] }}</td>
                  <td class="px-5 py-4 text-gray-600 dark:text-gray-400">{{ $d['tgl'] }}</td>
                  <td class="px-5 py-4 tabular-nums">{{ $d['waktu'] }}</td>
                  <td class="px-5 py-4 tabular-nums font-semibold text-green-600">{{ $d['jam'] }}</td>
                  <td class="px-5 py-4">
                    <div class="flex items-center justify-center">
                      <button type="button" class="p-1.5 text-gray-400 hover:text-telkom-600 transition" title="Lihat Detail">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                      </button>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- TAB CONTENT: KALENDER -->
    <div id="content-kalender" class="hidden space-y-6">
      <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
        <h3 class="font-bold text-lg mb-4 text-gray-900 dark:text-white text-center">Kalender Presensi (Siklus Tgl 16 - 15)</h3>
        
        <!-- Days Header -->
        <div class="grid grid-cols-7 gap-2 text-center font-semibold text-xs text-gray-500 mb-2">
          <div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div><div>Min</div>
        </div>
        
        <!-- Calendar Grid -->
        <div class="grid grid-cols-7 gap-2 sm:gap-3">
          <!-- Offset for visual (example starts at Wednesday = 3rd col) -->
          <div class="aspect-square bg-transparent"></div>
          <div class="aspect-square bg-transparent"></div>
          
          <!-- Generating days from 16 prev month to 15 current month (approx 30 days) -->
          @php 
            $calendarDays = array_merge(range(16, 31), range(1, 15)); 
          @endphp

          @foreach ($calendarDays as $day)
            <div class="aspect-square bg-gray-50 hover:bg-gray-100 dark:bg-gray-800/50 dark:hover:bg-gray-800 rounded-xl flex flex-col items-center justify-center cursor-pointer transition border border-gray-200 dark:border-gray-700 relative">
              <span class="text-sm sm:text-base font-semibold {{ $day == 16 || $day == 15 ? 'text-telkom-600' : 'text-gray-700 dark:text-gray-300' }}">{{ $day }}</span>
              <!-- status dot -->
              @if ($day % 7 !== 6 && $day % 7 !== 5)
                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mt-1"></span>
              @endif
            </div>
          @endforeach
        </div>
        <div class="mt-6 flex items-center justify-center gap-6 text-xs text-gray-500">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-500"></span> Hadir
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span> Terlambat
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-500"></span> Izin/Cuti
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-gray-300"></span> Libur
            </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function switchTab(tab) {
      const tabTabel = document.getElementById('tab-tabel');
      const tabKalender = document.getElementById('tab-kalender');
      const contentTabel = document.getElementById('content-tabel');
      const contentKalender = document.getElementById('content-kalender');

      if (tab === 'tabel') {
        tabTabel.className = 'px-4 py-2.5 border-b-2 border-telkom-600 text-telkom-600 font-semibold text-sm transition';
        tabKalender.className = 'px-4 py-2.5 border-b-2 border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-semibold text-sm transition';
        contentTabel.classList.replace('hidden', 'block');
        contentKalender.classList.replace('block', 'hidden');
      } else {
        tabKalender.className = 'px-4 py-2.5 border-b-2 border-telkom-600 text-telkom-600 font-semibold text-sm transition';
        tabTabel.className = 'px-4 py-2.5 border-b-2 border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-semibold text-sm transition';
        contentKalender.classList.replace('hidden', 'block');
        contentTabel.classList.replace('block', 'hidden');
      }
    }
  </script>
@endsection
