@extends('layouts.app-layout')

@section('title', 'Dashboard')
@section('subtitle', 'Selamat datang kembali, Andi')

@section('content')
  <!-- ============ VIEW: DASHBOARD ============ -->
  <section id="view-dashboard" class="view">
    <!-- Header Banner & Card Presensi Hari Ini -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
      <!-- Banner Selamat Pagi -->
      <div
        class="lg:col-span-2 relative overflow-hidden rounded-3xl gradient-telkom p-6 sm:p-8 text-white shadow-xl shadow-telkom-600/20 dot-grid">
        <!-- Background decorative / Bubbles -->
        <div class="absolute top-10 -right-10 w-64 h-64 rounded-full bg-white/10 pulse-slow"></div>


        <div class="relative z-10 flex flex-col justify-between h-full min-h-40">
          <div>
            <span class="inline-block px-3 py-1 bg-white/15 backdrop-blur-md rounded-full text-xs font-medium mb-3"
              id="greetingText">
              Selamat Pagi
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">{{ auth()->user()->name }}</h2>
            <p class="text-white/80 text-xs sm:text-sm mt-1">{{ auth()->user()->position ?? ucfirst(auth()->user()->getRoleNames()->first() ?? 'Staff') }}</p>
          </div>
          <div class="flex flex-wrap items-center gap-4 mt-6">
            <div class="flex items-center gap-2 px-3.5 py-2 bg-white/15 backdrop-blur-md rounded-2xl text-xs">
              <span class="text-white/70">Tanggal</span>
              <span class="font-bold" id="todayDate">Senin, 18 Nov 2024</span>
            </div>
            <div class="flex items-center gap-2 px-3.5 py-2 bg-white/15 backdrop-blur-md rounded-2xl text-xs">
              <span class="text-white/70">Jam</span>
              <span class="font-bold tabular-nums" id="liveClock">07:45:12</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Card Presensi Hari Ini -->
      <div
        class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-6 flex flex-col justify-between">
        <div>
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-sm">Status Hari Ini</h3>
            @if(!$todayPresence)
              <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400">Belum Hadir</span>
            @elseif(!$todayPresence->jam_pulang)
              <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">Sedang Bekerja</span>
            @else
              <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Selesai</span>
            @endif
          </div>
          <div class="grid grid-cols-2 gap-4 py-2 border-y border-gray-100 dark:border-gray-800">
            <div>
              <p class="text-xs text-gray-400">Check In</p>
              <p class="text-base font-bold text-gray-900 dark:text-gray-100 mt-0.5">{{ $todayPresence->jam_masuk ?? '—' }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-400">Check Out</p>
              <p class="text-base font-bold text-gray-400 mt-0.5">{{ $todayPresence->jam_pulang ?? '—' }}</p>
            </div>
          </div>
        </div>

        @can('create-presence')
        <a href="{{ route('presence.index') }}"
          class="mt-4 w-full py-2.5 gradient-telkom text-white rounded-xl text-sm font-semibold hover:opacity-90 transition flex items-center justify-center gap-2">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
          </svg>
          <span>Presensi Sekarang</span>
        </a>
        @else
        <div class="mt-4 w-full py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-500 rounded-xl text-sm font-semibold flex items-center justify-center">
          Tidak Ada Jadwal Presensi
        </div>
        @endcan
      </div>
    </div>

    <!-- Quick Menu (Mobile) -->
    <div class="lg:hidden mb-6">
      <div class="flex items-center justify-between mb-3">
        <h3 class="font-semibold">Quick Menu</h3>
        <span class="text-xs text-gray-400">Geser →</span>
      </div>
      <div class="flex gap-3 overflow-x-auto no-scrollbar pb-2">
        <a href="{{ route('presence.index') }}"
          class="flex flex-col items-center gap-2 p-3 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 card-hover w-20 shrink-0">
          <div class="w-11 h-11 rounded-xl gradient-telkom flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M9 11l3 3L22 4" />
              <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
            </svg>
          </div>
          <span class="text-[10px] font-medium truncate w-full text-center">Presensi</span>
        </a>
        <a href="{{ route('presence.list') }}"
          class="flex flex-col items-center gap-2 p-3 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 card-hover w-20 shrink-0">
          <div class="w-11 h-11 rounded-xl bg-blue-500 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
              <polyline points="14 2 14 8 20 8" />
            </svg>
          </div>
          <span class="text-[10px] font-medium truncate w-full text-center">Daftar</span>
        </a>
        <a href="{{ route('presence.overtime') }}"
          class="flex flex-col items-center gap-2 p-3 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 card-hover w-20 shrink-0">
          <div class="w-11 h-11 rounded-xl bg-amber-500 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="10" />
              <polyline points="12 6 12 12 16 14" />
            </svg>
          </div>
          <span class="text-[10px] font-medium truncate w-full text-center">Lembur</span>
        </a>
        <a href="{{ route('presence.history') }}"
          class="flex flex-col items-center gap-2 p-3 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 card-hover w-20 shrink-0">
          <div class="w-11 h-11 rounded-xl bg-emerald-500 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M3 3v5h5" />
              <path d="M3.05 13A9 9 0 1 0 6 5.3L3 8" />
              <path d="M12 7v5l4 2" />
            </svg>
          </div>
          <span class="text-[10px] font-medium truncate w-full text-center">History</span>
        </a>
        <a href="{{ route('ticket.index') }}"
          class="flex flex-col items-center gap-2 p-3 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 card-hover w-20 shrink-0">
          <div class="w-11 h-11 rounded-xl bg-purple-500 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path
                d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
              <path d="M13 5v14" stroke-dasharray="2 2" />
            </svg>
          </div>
          <span class="text-[10px] font-medium truncate w-full text-center">Daftar Tiket</span>
        </a>
        <a href="{{ route('ticket.create') }}"
          class="flex flex-col items-center gap-2 p-3 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 card-hover w-20 shrink-0">
          <div class="w-11 h-11 rounded-xl bg-indigo-500 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M12 5v14M5 12h14" />
            </svg>
          </div>
          <span class="text-[10px] font-medium truncate w-full text-center">Buat Tiket</span>
        </a>
        <a href="{{ route('ticket.my-tickets') }}"
          class="flex flex-col items-center gap-2 p-3 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 card-hover w-20 shrink-0">
          <div class="w-11 h-11 rounded-xl bg-pink-500 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
          </div>
          <span class="text-[10px] font-medium truncate w-full text-center">Tiket Saya</span>
        </a>
        <a href="{{ route('ticket.luna') }}"
          class="flex flex-col items-center gap-2 p-3 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 card-hover w-20 shrink-0">
          <div class="w-11 h-11 rounded-xl bg-teal-500 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M12 2a10 10 0 0 1 10 10c0 5.523-4.477 10-10 10S2 17.523 2 12c0-2.4 1-4.8 2.75-6.5" />
              <path d="M12 10a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z" />
              <path d="m17 7-5 5" />
            </svg>
          </div>
          <span class="text-[10px] font-medium truncate w-full text-center">Luna AI</span>
        </a>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6">
      <div
        class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 lg:p-5 card-hover">
        <div class="flex items-center justify-between">
          <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-green-600 dark:text-green-400 shrink-0" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
              <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
          </div>
        </div>
        <p class="mt-3 text-2xl lg:text-3xl font-bold">{{ $stats['hadir'] }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Hadir Bulan Ini</p>
      </div>

      <div
        class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 lg:p-5 card-hover">
        <div class="flex items-center justify-between">
          <div
            class="w-10 h-10 rounded-xl bg-telkom-100 dark:bg-telkom-900/30 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-telkom-600 dark:text-telkom-400 shrink-0" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="10" />
              <polyline points="12 6 12 12 16 14" />
            </svg>
          </div>
        </div>
        <p class="mt-3 text-2xl lg:text-3xl font-bold">{{ $stats['tepat_waktu'] }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Tepat Waktu</p>
      </div>

      <div
        class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 lg:p-5 card-hover">
        <div class="flex items-center justify-between">
          <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
              <line x1="12" y1="9" x2="12" y2="13" />
              <line x1="12" y1="17" x2="12.01" y2="17" />
            </svg>
          </div>
        </div>
        <p class="mt-3 text-2xl lg:text-3xl font-bold">{{ $stats['terlambat'] }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Terlambat</p>
      </div>

      <div
        class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 lg:p-5 card-hover">
        <div class="flex items-center justify-between">
          <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 shrink-0" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
              <circle cx="8.5" cy="7" r="4" />
              <polyline points="17 11 19 13 23 9" />
            </svg>
          </div>
        </div>
        <p class="mt-3 text-2xl lg:text-3xl font-bold">{{ $overtimeSaldo }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Saldo Lembur (Menit)</p>
      </div>
    </div>

    <!-- Chart & Aktivitas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
      <!-- Chart Container -->
      <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 flex flex-col">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h3 class="font-bold">Statistik Presensi</h3>
            <p class="text-xs text-gray-400" id="chartPeriodLabel">Minggu ini</p>
          </div>
          <div class="flex gap-1 bg-gray-100 dark:bg-gray-800 p-1 rounded-xl text-xs">
            <button id="btnChartWeekly" class="px-3 py-1 rounded-lg bg-white dark:bg-gray-700 font-semibold shadow-sm transition">Mingguan</button>
            <button id="btnChartMonthly" class="px-3 py-1 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">Bulanan</button>
          </div>
        </div>
        <div class="flex-1 w-full min-h-[250px]">
          <div id="presenceChart" class="h-full"></div>
        </div>
      </div>

      <!-- Aktivitas Terbaru -->
      <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
        <div class="flex items-center justify-between mb-4">
          <h3 class="font-bold">Aktivitas Terbaru</h3>
          <a href="{{ route('presence.history') }}" class="text-xs text-telkom-600 font-semibold hover:underline">Lihat
            semua</a>
        </div>
        <div class="space-y-3">
          @forelse($recentActivity as $activity)
          <div class="flex gap-3">
            <div
              class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center shrink-0">
              <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <polyline points="20 6 9 17 4 12" />
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium">Check In ({{ $activity->tanggal }})</p>
              <p class="text-xs text-gray-500">{{ $activity->jam_masuk }} • PUK @if(auth()->user()->hasRole('student-staff')) <span class="font-bold text-gray-700 dark:text-gray-300">• {{ $activity->user->name }}</span> @endif</p>
            </div>
          </div>
          @if($activity->jam_pulang)
          <div class="flex gap-3 mt-2">
            <div
              class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
              <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <polyline points="20 6 9 17 4 12" />
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium">Check Out ({{ $activity->tanggal }})</p>
              <p class="text-xs text-gray-500">{{ $activity->jam_pulang }} • PUK @if(auth()->user()->hasRole('student-staff')) <span class="font-bold text-gray-700 dark:text-gray-300">• {{ $activity->user->name }}</span> @endif</p>
            </div>
          </div>
          @endif
          @empty
          <p class="text-sm text-gray-500 text-center py-4">Belum ada aktivitas.</p>
          @endforelse
        </div>
      </div>
    </div>
  </section>

  <!-- Script Chart.js & Live Clock -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script>
    function updateLiveClock() {
      const now = new Date();
      const h = String(now.getHours()).padStart(2, '0');
      const m = String(now.getMinutes()).padStart(2, '0');
      const s = String(now.getSeconds()).padStart(2, '0');
      const liveClock = document.getElementById('liveClock');
      if (liveClock) liveClock.textContent = `${h}:${m}:${s}`;

      const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
      const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober',
        'November', 'Desember'
      ];
      const dateStr = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
      const todayDate = document.getElementById('todayDate');
      if (todayDate) todayDate.textContent = dateStr;

      const hour = now.getHours();
      const greet = hour < 11 ? 'Selamat pagi' : hour < 15 ? 'Selamat siang' : hour < 18 ? 'Selamat sore' :
        'Selamat malam';
      const g = document.getElementById('greetingText');
      if (g) g.textContent = greet;
    }
    setInterval(updateLiveClock, 1000);
    updateLiveClock();

    document.addEventListener('DOMContentLoaded', function() {
      const chartEl = document.querySelector("#presenceChart");
      if (!chartEl) return;

      const isDark = document.documentElement.classList.contains('dark');
      
      const chartData = @json($chartData);
      
      const options = {
        series: [{
          name: 'Hadir',
          data: chartData.weekly.hadir
        }, {
          name: 'Lembur',
          data: chartData.weekly.lembur
        }],
        chart: {
          type: 'bar',
          height: '100%',
          toolbar: {
            show: false
          },
          fontFamily: 'inherit',
          foreColor: isDark ? '#9CA3AF' : '#6B7280'
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            borderRadius: 6,
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: chartData.weekly.labels,
          axisBorder: { show: false },
          axisTicks: { show: false }
        },
        yaxis: {
          title: {
            text: ''
          }
        },
        fill: {
          opacity: 1,
          colors: ['#e60012', '#f59e0b']
        },
        legend: {
          position: 'bottom',
          markers: {
            radius: 12
          }
        },
        grid: {
          borderColor: isDark ? '#1F2937' : '#F3F4F6',
          strokeDashArray: 4,
          yaxis: {
            lines: {
              show: true
            }
          }
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + " jam"
            }
          }
        }
      };

      const chart = new ApexCharts(chartEl, options);
      chart.render();

      // Interactions
      const btnWeekly = document.getElementById('btnChartWeekly');
      const btnMonthly = document.getElementById('btnChartMonthly');
      const label = document.getElementById('chartPeriodLabel');

      btnWeekly.addEventListener('click', () => {
        btnWeekly.classList.add('bg-white', 'dark:bg-gray-700', 'font-semibold', 'shadow-sm', 'text-gray-900', 'dark:text-white');
        btnWeekly.classList.remove('text-gray-400', 'hover:text-gray-600', 'dark:hover:text-gray-200');
        
        btnMonthly.classList.remove('bg-white', 'dark:bg-gray-700', 'font-semibold', 'shadow-sm', 'text-gray-900', 'dark:text-white');
        btnMonthly.classList.add('text-gray-400', 'hover:text-gray-600', 'dark:hover:text-gray-200');
        
        label.textContent = 'Minggu ini';
        
        chart.updateSeries([{
          data: chartData.weekly.hadir
        }, {
          data: chartData.weekly.lembur
        }]);
        chart.updateOptions({
          xaxis: { categories: chartData.weekly.labels }
        });
      });

      btnMonthly.addEventListener('click', () => {
        btnMonthly.classList.add('bg-white', 'dark:bg-gray-700', 'font-semibold', 'shadow-sm', 'text-gray-900', 'dark:text-white');
        btnMonthly.classList.remove('text-gray-400', 'hover:text-gray-600', 'dark:hover:text-gray-200');
        
        btnWeekly.classList.remove('bg-white', 'dark:bg-gray-700', 'font-semibold', 'shadow-sm', 'text-gray-900', 'dark:text-white');
        btnWeekly.classList.add('text-gray-400', 'hover:text-gray-600', 'dark:hover:text-gray-200');
        
        label.textContent = 'Bulan ini';

        chart.updateSeries([{
          data: chartData.monthly.hadir
        }, {
          data: chartData.monthly.lembur
        }]);
        chart.updateOptions({
          xaxis: { categories: chartData.monthly.labels }
        });
      });
    });
  </script>
@endsection
