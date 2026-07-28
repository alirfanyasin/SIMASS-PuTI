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


        <div class="relative z-10 flex flex-col justify-between h-full min-h-[160px]">
          <div>
            <span class="inline-block px-3 py-1 bg-white/15 backdrop-blur-md rounded-full text-xs font-medium mb-3"
              id="greetingText">
              Selamat Pagi
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Andi Pratama</h2>
            <p class="text-white/80 text-xs sm:text-sm mt-1">Dosen • Fakultas Informatika</p>
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
            <span
              class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
              Hadir
            </span>
          </div>
          <div class="grid grid-cols-2 gap-4 py-2 border-y border-gray-100 dark:border-gray-800">
            <div>
              <p class="text-xs text-gray-400">Check In</p>
              <p class="text-base font-bold text-gray-900 dark:text-gray-100 mt-0.5">07:45</p>
            </div>
            <div>
              <p class="text-xs text-gray-400">Check Out</p>
              <p class="text-base font-bold text-gray-400 mt-0.5">—</p>
            </div>
          </div>
        </div>

        <a href="{{ route('app.presence') }}"
          class="mt-4 w-full py-2.5 gradient-telkom text-white rounded-xl text-sm font-semibold hover:opacity-90 transition flex items-center justify-center gap-2">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
          </svg>
          <span>Presensi Sekarang</span>
        </a>
      </div>
    </div>

    <!-- Quick Menu (Mobile) -->
    <div class="lg:hidden mb-6">
      <div class="flex items-center justify-between mb-3">
        <h3 class="font-semibold">Quick Menu</h3>
        <span class="text-xs text-gray-400">Geser →</span>
      </div>
      <div class="grid grid-cols-4 gap-3">
        <a href="{{ route('app.presence') }}"
          class="flex flex-col items-center gap-2 p-3 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 card-hover">
          <div class="w-11 h-11 rounded-xl gradient-telkom flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M9 11l3 3L22 4" />
              <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
            </svg>
          </div>
          <span class="text-xs font-medium">Presensi</span>
        </a>
        <a href="{{ route('app.presence-list') }}"
          class="flex flex-col items-center gap-2 p-3 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 card-hover">
          <div class="w-11 h-11 rounded-xl bg-blue-500 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
              <polyline points="14 2 14 8 20 8" />
            </svg>
          </div>
          <span class="text-xs font-medium">Daftar</span>
        </a>
        <a href="{{ route('app.overtime') }}"
          class="flex flex-col items-center gap-2 p-3 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 card-hover">
          <div class="w-11 h-11 rounded-xl bg-amber-500 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="10" />
              <polyline points="12 6 12 12 16 14" />
            </svg>
          </div>
          <span class="text-xs font-medium">Lembur</span>
        </a>
        <a href="{{ route('app.presence-history') }}"
          class="flex flex-col items-center gap-2 p-3 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 card-hover">
          <div class="w-11 h-11 rounded-xl bg-emerald-500 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M3 3v5h5" />
              <path d="M3.05 13A9 9 0 1 0 6 5.3L3 8" />
              <path d="M12 7v5l4 2" />
            </svg>
          </div>
          <span class="text-xs font-medium">History</span>
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
          <span class="text-xs font-semibold text-green-600">+12%</span>
        </div>
        <p class="mt-3 text-2xl lg:text-3xl font-bold">22</p>
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
          <span class="text-xs font-semibold text-telkom-600">+3%</span>
        </div>
        <p class="mt-3 text-2xl lg:text-3xl font-bold">20</p>
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
          <span class="text-xs font-semibold text-amber-600">-1%</span>
        </div>
        <p class="mt-3 text-2xl lg:text-3xl font-bold">2</p>
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
          <span class="text-xs font-semibold text-blue-600">—</span>
        </div>
        <p class="mt-3 text-2xl lg:text-3xl font-bold">1</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Izin / Sakit</p>
      </div>
    </div>

    <!-- Chart & Aktivitas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
      <!-- Chart Container -->
      <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h3 class="font-bold">Statistik Presensi</h3>
            <p class="text-xs text-gray-400">7 hari terakhir</p>
          </div>
          <div class="flex gap-1 bg-gray-100 dark:bg-gray-800 p-1 rounded-xl text-xs">
            <button class="px-3 py-1 rounded-lg bg-white dark:bg-gray-700 font-semibold shadow-sm">Mingguan</button>
            <button
              class="px-3 py-1 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">Bulanan</button>
          </div>
        </div>
        <div class="h-64 relative">
          <canvas id="presenceChart"></canvas>
        </div>
      </div>

      <!-- Aktivitas Terbaru -->
      <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
        <div class="flex items-center justify-between mb-4">
          <h3 class="font-bold">Aktivitas Terbaru</h3>
          <a href="{{ route('app.presence-history') }}"
            class="text-xs text-telkom-600 font-semibold hover:underline">Lihat semua</a>
        </div>
        <div class="space-y-3">
          <div class="flex gap-3">
            <div
              class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center shrink-0">
              <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <polyline points="20 6 9 17 4 12" />
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium">Check In berhasil</p>
              <p class="text-xs text-gray-500">07:45 • Gedung F</p>
            </div>
          </div>
          <div class="flex gap-3">
            <div
              class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center shrink-0">
              <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" />
                <polyline points="12 6 12 12 16 14" />
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium">Pengajuan lembur disetujui</p>
              <p class="text-xs text-gray-500">2 jam • Kemarin</p>
            </div>
          </div>
          <div class="flex gap-3">
            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
              <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                <circle cx="8.5" cy="7" r="4" />
                <polyline points="17 11 19 13 23 9" />
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium">Izin dikonfirmasi</p>
              <p class="text-xs text-gray-500">3 hari yang lalu</p>
            </div>
          </div>
          <div class="flex gap-3">
            <div
              class="w-8 h-8 rounded-full bg-telkom-100 dark:bg-telkom-900/30 flex items-center justify-center shrink-0">
              <svg class="w-4 h-4 text-telkom-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                <polyline points="7 10 12 15 17 10" />
                <line x1="12" y1="15" x2="12" y2="3" />
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium">Slip presensi diunduh</p>
              <p class="text-xs text-gray-500">5 hari yang lalu</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Script Chart.js & Live Clock -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
      const ctx = document.getElementById('presenceChart');
      if (!ctx) return;

      const isDark = document.documentElement.classList.contains('dark');
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
          datasets: [{
              label: 'Hadir',
              data: [8, 8, 8, 7, 8, 0, 0],
              backgroundColor: '#e60012',
              borderRadius: 6,
            },
            {
              label: 'Lembur',
              data: [2, 0, 3, 1, 0, 0, 0],
              backgroundColor: '#f59e0b',
              borderRadius: 6,
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                color: isDark ? '#9CA3AF' : '#4B5563',
                usePointStyle: true,
                pointStyle: 'circle',
                padding: 16,
                font: {
                  size: 12
                }
              }
            }
          },
          scales: {
            x: {
              grid: {
                display: false
              },
              ticks: {
                color: isDark ? '#9CA3AF' : '#6B7280'
              }
            },
            y: {
              grid: {
                color: isDark ? '#1F2937' : '#F3F4F6'
              },
              ticks: {
                color: isDark ? '#9CA3AF' : '#6B7280'
              }
            }
          }
        }
      });
    });
  </script>
@endsection
