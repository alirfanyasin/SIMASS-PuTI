@extends('layouts.app-layout')

@section('title', 'Presensi')
@section('subtitle', 'Lakukan Check In / Check Out presensi harian')

@section('content')
  <!-- ============ VIEW: PRESENSI ============ -->
  <section id="view-presensi" class="view">
    <div class="max-w-3xl mx-auto space-y-6">

      <!-- Header: Clock & Date -->
      <div
        class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-6 sm:p-8 text-center shadow-sm">
        <h2 class="text-4xl sm:text-5xl font-black tracking-tight tabular-nums text-gray-900 dark:text-gray-100"
          id="bigClock">07:45:12</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-medium" id="presensiDate">Rabu, 29 Juli 2026</p>
      </div>

      <!-- Location Verification Status -->
      <div
        class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-900/50 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
          <div
            class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
              <circle cx="12" cy="10" r="3" />
            </svg>
          </div>
          <div>
            <h3 class="font-bold text-emerald-900 dark:text-emerald-300 text-base">Lokasi Sesuai (Radius 15m)</h3>
            <p class="text-xs sm:text-sm text-emerald-700 dark:text-emerald-400 mt-0.5">Gedung F - Telkom University</p>
          </div>
        </div>
        <div
          class="flex items-center gap-2 px-3 py-1.5 bg-emerald-100 dark:bg-emerald-900/40 rounded-full text-xs font-bold text-emerald-700 dark:text-emerald-400 shrink-0">
          <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
          GPS Akurat
        </div>
      </div>

      <!-- Tabs / Method Switcher -->
      <div class="bg-gray-100 dark:bg-gray-900 p-1.5 rounded-2xl flex items-center gap-1">
        <button onclick="switchTab('face')" id="tabBtn-face"
          class="flex-1 py-2.5 rounded-xl text-sm font-bold bg-white dark:bg-gray-800 text-telkom-600 dark:text-telkom-400 shadow-sm transition-all">
          Face Recognition
        </button>
        <button onclick="switchTab('manual')" id="tabBtn-manual"
          class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-all">
          Presensi Manual
        </button>
      </div>

      <!-- Presensi Content Container -->
      <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-6 sm:p-8">

        <!-- Tab 1: Face Recognition -->
        <div id="tabContent-face" class="space-y-6 block fade-in-up">
          <div class="text-center space-y-1">
            <h3 class="font-bold text-lg">Presensi Wajah</h3>
            <p class="text-xs text-gray-500">Posisikan wajah Anda di dalam bingkai untuk verifikasi.</p>
          </div>

          <!-- Camera Viewfinder Mockup -->
          <div
            class="relative w-full max-w-sm mx-auto aspect-3/4 bg-gray-100 dark:bg-gray-950 rounded-4xl overflow-hidden flex items-center justify-center border-4 border-dashed border-gray-300 dark:border-gray-700">
            <!-- Simulated Camera Feed -->
            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=600&auto=format&fit=crop"
              class="absolute inset-0 w-full h-full object-cover opacity-80 mix-blend-luminosity hover:mix-blend-normal transition duration-500"
              alt="Camera Feed">

            <!-- UI Overlay for Face Guide -->
            <div class="absolute inset-0 pointer-events-none flex flex-col justify-between items-center py-6">
              <span
                class="px-3 py-1 bg-black/50 text-white text-[10px] font-bold rounded-full backdrop-blur-md">Mendeteksi
                wajah...</span>
              <!-- Scanning line -->
              <div class="w-3/4 h-1 bg-telkom-500 rounded-full shadow-[0_0_15px_rgba(242,59,59,0.8)] animate-pulse"></div>
            </div>

            <!-- Face Frame -->
            <div
              class="absolute inset-x-8 inset-y-16 border-2 border-white/60 rounded-[3rem] shadow-[0_0_0_9999px_rgba(0,0,0,0.6)] pointer-events-none">
            </div>
          </div>

          <button onclick="showToast('Presensi Wajah Berhasil!', 'success')"
            class="w-full max-w-sm mx-auto flex items-center justify-center gap-3 py-3.5 gradient-telkom text-white rounded-xl font-bold shadow-xl shadow-telkom-600/30 hover:opacity-90 transition active:scale-95">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M3 7V5a2 2 0 0 1 2-2h2" />
              <path d="M17 3h2a2 2 0 0 1 2 2v2" />
              <path d="M21 17v2a2 2 0 0 1-2 2h-2" />
              <path d="M7 21H5a2 2 0 0 1-2-2v-2" />
              <rect x="8" y="8" width="8" height="8" rx="2" />
            </svg>
            <span>Scan & Check In</span>
          </button>
        </div>

        <!-- Tab 2: Presensi Manual -->
        <div id="tabContent-manual" class="space-y-6 hidden fade-in-up">
          <div class="text-center space-y-1 mb-6">
            <h3 class="font-bold text-lg">Presensi Manual</h3>
            <p class="text-xs text-gray-500">Gunakan opsi ini jika pengenalan wajah bermasalah.</p>
          </div>

          <form action="" method="POST" class="max-w-md mx-auto space-y-5">
            <div>
              <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Tipe Presensi</label>
              <select id="presenceType"
                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl outline-none focus:ring-2 focus:ring-telkom-500 text-sm">
                <option value="hadir">Hadir (Check In)</option>
                <option value="pulang">Pulang (Check Out)</option>
              </select>
            </div>

            <div id="jobDescription">
              <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Catatan Pekerjaan
                (Opsional)</label>
              <textarea rows="3" placeholder="Tulis catatan jika ada..."
                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl outline-none focus:ring-2 focus:ring-telkom-500 text-sm"></textarea>
            </div>

            <div id="evidence">
              <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Foto Bukti (Selfie)</label>
              <div
                class="w-full border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl p-6 flex flex-col items-center justify-center text-center hover:bg-gray-50 dark:hover:bg-gray-800 transition cursor-pointer">
                <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" stroke-width="2"
                  stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                  <circle cx="8.5" cy="8.5" r="1.5" />
                  <polyline points="21 15 16 10 5 21" />
                </svg>
                <p class="text-sm font-semibold text-gray-600 dark:text-gray-300">Klik untuk unggah foto</p>
                <p class="text-xs text-gray-400 mt-1">Format JPG/PNG maksimal 2MB</p>
              </div>
            </div>

            <button type="submit"
              class="w-full mt-2 flex items-center justify-center gap-2 py-3.5 gradient-telkom text-white rounded-xl font-bold shadow-lg shadow-telkom-600/30 hover:opacity-90 transition">
              <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
              </svg>
              <span>Simpan Presensi Manual</span>
            </button>
          </form>
        </div>

      </div>

    </div>
  </section>

  <script>
    const jobDescription = document.getElementById("job-description")
    const presenceType = document.getElementById("presenceType")

    presenceType.addEventListener('change', function() {
      console.log(presenceType.value)
    })


    function switchTab(tabId) {
      // Buttons
      const btnFace = document.getElementById('tabBtn-face');
      const btnManual = document.getElementById('tabBtn-manual');

      // Contents
      const contentFace = document.getElementById('tabContent-face');
      const contentManual = document.getElementById('tabContent-manual');

      // Reset classes
      const activeClass = ['bg-white', 'dark:bg-gray-800', 'text-telkom-600', 'dark:text-telkom-400', 'shadow-sm',
        'font-bold'
      ];
      const inactiveClass = ['text-gray-500', 'hover:text-gray-700', 'dark:hover:text-gray-300', 'font-semibold',
        'bg-transparent'
      ];

      if (tabId === 'face') {
        btnFace.classList.remove(...inactiveClass);
        btnFace.classList.add(...activeClass);

        btnManual.classList.remove(...activeClass);
        btnManual.classList.add(...inactiveClass);

        contentFace.classList.remove('hidden');
        contentFace.classList.add('block');

        contentManual.classList.remove('block');
        contentManual.classList.add('hidden');
      } else {
        btnManual.classList.remove(...inactiveClass);
        btnManual.classList.add(...activeClass);

        btnFace.classList.remove(...activeClass);
        btnFace.classList.add(...inactiveClass);

        contentManual.classList.remove('hidden');
        contentManual.classList.add('block');

        contentFace.classList.remove('block');
        contentFace.classList.add('hidden');
      }
    }

    function updateClock() {
      const now = new Date();
      const h = String(now.getHours()).padStart(2, '0');
      const m = String(now.getMinutes()).padStart(2, '0');
      const s = String(now.getSeconds()).padStart(2, '0');
      const bigClock = document.getElementById('bigClock');
      if (bigClock) bigClock.textContent = `${h}:${m}:${s}`;

      const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
      const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober',
        'November', 'Desember'
      ];
      const dateStr = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
      const presensiDate = document.getElementById('presensiDate');
      if (presensiDate) presensiDate.textContent = dateStr;
    }
    setInterval(updateClock, 1000);
    updateClock();
  </script>
@endsection
