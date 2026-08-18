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
      <div id="locationBanner"
        class="bg-gray-50 dark:bg-gray-900/20 border border-gray-200 dark:border-gray-900/50 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
          <div id="locationIconBg"
            class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-500 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
              <circle cx="12" cy="10" r="3" />
            </svg>
          </div>
          <div>
            <h3 id="locationTitle" class="font-bold text-gray-900 dark:text-gray-300 text-base">Mengecek Lokasi...</h3>
            <p id="locationDesc" class="text-xs sm:text-sm text-gray-500 mt-0.5">Mendapatkan koordinat GPS</p>
          </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
          <button id="btnRequestGPS" onclick="window.requestGPSPermission()"
            class="hidden px-3.5 py-1.5 bg-telkom-600 hover:bg-telkom-700 text-white text-xs font-bold rounded-xl active:scale-95 transition-all shadow-sm">
            Izinkan GPS
          </button>
          <div id="locationTag"
            class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 dark:bg-gray-800 rounded-full text-xs font-bold text-gray-500">
            <span class="w-2 h-2 rounded-full bg-gray-400 animate-pulse"></span>
            Menunggu GPS
          </div>
        </div>
      </div>

      <!-- Map Container -->
      <div id="mapContainer"
        class="hidden w-full h-48 sm:h-64 bg-gray-100 dark:bg-gray-800 rounded-3xl overflow-hidden border border-gray-200 dark:border-gray-800 relative z-0 shadow-sm">
        <div id="map" class="w-full h-full z-0"></div>
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

          @if ($todayPresence && $todayPresence->jam_masuk && $todayPresence->jam_pulang)
            <div
              class="p-6 bg-green-50 dark:bg-green-900/20 rounded-2xl text-center border border-green-200 dark:border-green-900/50">
              <svg class="w-12 h-12 text-green-500 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <h4 class="font-bold text-green-700 dark:text-green-400">Presensi Selesai</h4>
              <p class="text-sm text-green-600 dark:text-green-500 mt-1">Anda sudah menyelesaikan jam kerja hari ini.</p>
            </div>
          @else
            <!-- Camera Selection (Hidden if only 1 camera) -->
            <div id="cameraSelectContainer" class="hidden max-w-sm mx-auto mb-4 fade-in-up">
              <select id="cameraSelect" onchange="switchCamera(this.value)"
                class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 outline-none focus:ring-2 focus:ring-telkom-500 appearance-none cursor-pointer">
                <!-- Options populated by JS -->
              </select>
            </div>

            <!-- Camera Viewfinder -->
            <div
              class="relative w-full max-w-sm mx-auto aspect-[3/4] bg-gray-100 dark:bg-gray-950 rounded-4xl overflow-hidden flex items-center justify-center border-4 border-dashed border-gray-300 dark:border-gray-700">
              <!-- Loading State -->
              <div id="cameraLoading"
                class="absolute inset-0 flex flex-col items-center justify-center bg-gray-100 dark:bg-gray-900 z-20">
                <div id="cameraSpinner"
                  class="w-8 h-8 border-4 border-telkom-200 border-t-telkom-600 rounded-full animate-spin"></div>
                <p class="text-xs font-semibold mt-3 text-gray-500 text-center px-4" id="loadingText">Memuat Model AI...
                </p>
                <button type="button" id="btnRequestCamera" onclick="window.retryCameraAccess()"
                  class="hidden mt-4 px-4 py-2 bg-telkom-600 hover:bg-telkom-700 text-white rounded-xl text-xs font-bold shadow-md active:scale-95 transition-all">
                  Izinkan Kamera
                </button>
              </div>

              <video id="videoFeed" autoplay muted playsinline
                class="absolute inset-0 w-full h-full object-cover z-10"></video>
              <canvas id="faceCanvas" class="absolute inset-0 w-full h-full pointer-events-none z-20"></canvas>

              <!-- Face Frame -->
              <div id="faceFrame"
                class="absolute inset-x-8 inset-y-16 border-2 border-white/60 rounded-[3rem] shadow-[0_0_0_9999px_rgba(0,0,0,0.6)] pointer-events-none z-10 overflow-hidden">
                <!-- Scanning Line -->
                <div
                  class="absolute left-0 right-0 h-1 bg-telkom-500 opacity-80 shadow-[0_0_20px_5px_rgba(235,59,90,0.5)]"
                  style="animation: scan 2.5s cubic-bezier(0.4, 0, 0.2, 1) infinite;"></div>
              </div>

              <!-- Success Match Indicator (Hidden by default) -->
              <div id="faceSuccessCheck"
                class="absolute inset-0 bg-green-50/90 dark:bg-green-900/90 backdrop-blur-sm z-30 hidden flex-col items-center justify-center">
                <div
                  class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center text-white shadow-lg animate-bounce mb-3">
                  <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                <p class="text-green-800 dark:text-green-200 font-bold px-4 text-center">Identitas Terverifikasi</p>
                <p id="verifiedNameDisplay"
                  class="text-green-700 dark:text-green-300 font-medium px-4 text-center text-sm mt-1"></p>
              </div>
            </div>

            <!-- Actions -->
            <div id="actionContainer" class="max-w-sm mx-auto hidden">
              <!-- Registration state -->
              <div id="registerState" class="space-y-3 hidden">
                <p id="registerStatusText" class="text-sm text-center text-gray-600 dark:text-gray-400 font-medium">
                  Wajah Anda belum terdaftar di sistem.</p>
                <button type="button" id="btnRegisterFace" disabled
                  class="w-full flex items-center justify-center gap-3 py-3.5 bg-gray-800 disabled:opacity-50 text-white rounded-xl font-bold shadow-xl transition">
                  Daftarkan Wajah Sekarang
                </button>
              </div>

              <!-- Presence state -->
              <div id="presenceState" class="space-y-3 hidden">
                <p id="matchStatusText" class="text-sm text-center font-bold text-gray-500">Mencari kecocokan wajah...
                </p>

                <form action="{{ !$todayPresence ? route('presence.check-in') : route('presence.check-out') }}"
                  method="POST" enctype="multipart/form-data" id="formFacePresensi">
                  @csrf
                  <input type="hidden" name="latitude" class="geo-lat">
                  <input type="hidden" name="longitude" class="geo-lng">
                  <input type="hidden" name="location_token" class="geo-token">

                  @if ($todayPresence && !$todayPresence->jam_pulang)
                    <div class="space-y-4 mb-4 text-left">
                      <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Catatan Pekerjaan
                          (Wajib)</label>
                        <textarea name="pekerjaan" rows="2" placeholder="Tulis aktivitas hari ini..." required
                          class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl outline-none focus:ring-2 focus:ring-telkom-500 text-sm"></textarea>
                      </div>
                      <div id="evidence_face" class="w-full">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Foto Bukti /
                          Selfie (Wajib)</label>
                        <input type="hidden" name="foto_base64" id="fotoBase64_face" required>
                        <div id="selfieContainer_face"
                          class="relative w-full aspect-video bg-gray-900 rounded-xl overflow-hidden hidden mb-3">
                          <video id="selfieVideo_face" autoplay muted playsinline
                            class="w-full h-full object-cover"></video>
                          <button type="button" onclick="captureSelfie('face')"
                            class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white text-gray-900 px-4 py-2 rounded-full text-sm font-bold shadow-lg flex items-center gap-2 hover:bg-gray-100 transition z-10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                              </path>
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Ambil Foto
                          </button>
                        </div>
                        <div id="selfiePreviewContainer_face"
                          class="relative w-full aspect-video bg-gray-100 dark:bg-gray-800 rounded-xl overflow-hidden hidden mb-3 border-2 border-gray-200 dark:border-gray-700">
                          <img id="selfiePreview_face" class="w-full h-full object-cover">
                          <button type="button" onclick="retakeSelfie('face')"
                            class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-gray-900 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg flex items-center gap-2 hover:bg-gray-800 transition z-10">
                            Ulangi
                          </button>
                        </div>
                        <button type="button" id="btnStartSelfie_face" onclick="openSelfieCamera('face')"
                          class="w-full py-3 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition flex items-center justify-center gap-2">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          </svg>
                          Buka Kamera Selfie
                        </button>
                      </div>
                    </div>
                  @endif
                  <button type="submit" id="btnSubmitFace" disabled
                    class="w-full flex items-center justify-center gap-3 py-3.5 gradient-telkom disabled:opacity-50 text-white rounded-xl font-bold shadow-xl shadow-telkom-600/30 transition active:scale-95">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                      <path d="M3 7V5a2 2 0 0 1 2-2h2" />
                      <path d="M17 3h2a2 2 0 0 1 2 2v2" />
                      <path d="M21 17v2a2 2 0 0 1-2 2h-2" />
                      <path d="M7 21H5a2 2 0 0 1-2-2v-2" />
                      <rect x="8" y="8" width="8" height="8" rx="2" />
                    </svg>
                    <span>{{ !$todayPresence ? 'Check In Sekarang' : 'Check Out Sekarang' }}</span>
                  </button>
                </form>
              </div>
            </div>
          @endif
        </div>

        <!-- Tab 2: Presensi Manual -->
        <div id="tabContent-manual" class="space-y-6 hidden fade-in-up">
          <div class="text-center space-y-1 mb-6">
            <h3 class="font-bold text-lg">Presensi Manual</h3>
            <p class="text-xs text-gray-500">Gunakan opsi ini jika pengenalan wajah bermasalah.</p>
          </div>

          @if ($todayPresence && $todayPresence->jam_masuk && $todayPresence->jam_pulang)
            <div
              class="p-6 bg-green-50 dark:bg-green-900/20 rounded-2xl text-center border border-green-200 dark:border-green-900/50">
              <svg class="w-12 h-12 text-green-500 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <h4 class="font-bold text-green-700 dark:text-green-400">Presensi Selesai</h4>
              <p class="text-sm text-green-600 dark:text-green-500 mt-1">Anda sudah menyelesaikan jam kerja hari ini.</p>
            </div>
          @else
            <form action="{{ !$todayPresence ? route('presence.check-in') : route('presence.check-out') }}"
              method="POST" enctype="multipart/form-data" class="max-w-md mx-auto space-y-5">
              @csrf
              <input type="hidden" name="latitude" class="geo-lat">
              <input type="hidden" name="longitude" class="geo-lng">
              <input type="hidden" name="location_token" class="geo-token">

              <div
                class="p-4 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 text-center">
                <span class="text-xs text-gray-500 block mb-1">Status Anda Saat Ini</span>
                <span class="text-lg font-bold text-gray-900 dark:text-gray-100">
                  {{ !$todayPresence ? 'Belum Hadir (Check In)' : 'Sudah Hadir (Check Out)' }}
                </span>
              </div>

              @if ($todayPresence && !$todayPresence->jam_pulang)
                <div id="jobDescription">
                  <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Catatan Pekerjaan
                    (Wajib)</label>
                  <textarea name="pekerjaan" rows="3" placeholder="Tulis aktivitas pekerjaan Anda hari ini..." required
                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl outline-none focus:ring-2 focus:ring-telkom-500 text-sm"></textarea>
                </div>

                <div id="evidence_manual" class="w-full">
                  <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Foto Bukti / Selfie
                    (Wajib)</label>
                  <input type="hidden" name="foto_base64" id="fotoBase64_manual" required>
                  <div id="selfieContainer_manual"
                    class="relative w-full aspect-video bg-gray-900 rounded-xl overflow-hidden hidden mb-3">
                    <video id="selfieVideo_manual" autoplay muted playsinline
                      class="w-full h-full object-cover"></video>
                    <button type="button" onclick="captureSelfie('manual')"
                      class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white text-gray-900 px-4 py-2 rounded-full text-sm font-bold shadow-lg flex items-center gap-2 hover:bg-gray-100 transition z-10">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      </svg>
                      Ambil Foto
                    </button>
                  </div>
                  <div id="selfiePreviewContainer_manual"
                    class="relative w-full aspect-video bg-gray-100 dark:bg-gray-800 rounded-xl overflow-hidden hidden mb-3 border-2 border-gray-200 dark:border-gray-700">
                    <img id="selfiePreview_manual" class="w-full h-full object-cover">
                    <button type="button" onclick="retakeSelfie('manual')"
                      class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-gray-900 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg flex items-center gap-2 hover:bg-gray-800 transition z-10">
                      Ulangi
                    </button>
                  </div>
                  <button type="button" id="btnStartSelfie_manual" onclick="openSelfieCamera('manual')"
                    class="w-full py-3 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                      </path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Buka Kamera Selfie
                  </button>
                </div>
              @endif

              <button type="submit"
                class="w-full mt-2 flex items-center justify-center gap-2 py-3.5 gradient-telkom text-white rounded-xl font-bold shadow-lg shadow-telkom-600/30 hover:opacity-90 transition disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                  stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                  <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                  <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                <span>{{ !$todayPresence ? 'Simpan Check In' : 'Simpan Check Out' }}</span>
              </button>
            </form>
          @endif
        </div>

      </div>

    </div>

    <!-- Shared Hidden Canvas for capturing selfies -->
    <canvas id="selfieSharedCanvas" class="hidden"></canvas>
  </section>

  @push('scripts')
    <!-- Load Turf.js for Location Distance Calculation -->
    <script defer src="https://cdn.jsdelivr.net/npm/@turf/turf@7.1.0/turf.min.js"></script>

    <!-- Load Leaflet for Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script defer src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
      integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
      @keyframes scan {
        0% {
          top: 0%;
          opacity: 0;
        }

        10% {
          opacity: 1;
        }

        90% {
          opacity: 1;
        }

        100% {
          top: 100%;
          opacity: 0;
        }
      }

      /* Clean Leaflet styles for Dark Mode if needed */
      .dark .leaflet-layer,
      .dark .leaflet-control-zoom-in,
      .dark .leaflet-control-zoom-out,
      .dark .leaflet-control-attribution {
        filter: invert(100%) hue-rotate(180deg) brightness(95%) contrast(90%);
      }
    </style>

    <script>
      let realLatitude = null;
      let realLongitude = null;
      let lastLat = null;
      let lastLng = null;
      let lastAccuracy = null;
      let gpsUpdateCount = 0;
      let coordinateVarianceDetected = false;
      // Track whether we have a HIGH-ACCURACY validated fix (required for attendance)
      let isHighAccuracyValidated = false;

      // Intercept form submissions to enforce server-issued token + actual browser coordinates
      document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.action && (form.action.includes('check-in') || form.action.includes('check-out'))) {
          // 1. Must have a server-issued location token
          if (!locationToken) {
            e.preventDefault();
            alert('Akses Ditolak: Token verifikasi lokasi tidak ada. Muat ulang halaman dan tunggu GPS terverifikasi.');
            return false;
          }

          // 2. Token must not be expired client-side (server will also check)
          if (locationTokenIssuedAt && (Date.now() - locationTokenIssuedAt) > TOKEN_TTL_MS) {
            e.preventDefault();
            invalidateToken('Token lokasi sudah kedaluwarsa. GPS sedang diverifikasi ulang...');
            alert('Token verifikasi lokasi sudah kedaluwarsa. Tunggu sebentar dan coba lagi.');
            return false;
          }

          // 3. Must have high-accuracy GPS validated
          if (!window.isLocationValid || !isHighAccuracyValidated) {
            e.preventDefault();
            alert('Akses Ditolak: Lokasi belum terverifikasi atau terdeteksi menggunakan GPS palsu. Tunggu hingga GPS akurat terverifikasi.');
            return false;
          }

          // 4. Inject real coordinates from memory into form fields
          // (these match what the server recorded in the token)
          if (realLatitude !== null && realLongitude !== null) {
            form.querySelectorAll('.geo-lat').forEach(el => el.value = realLatitude);
            form.querySelectorAll('.geo-lng').forEach(el => el.value = realLongitude);
          }
        }
      });

      /**
       * ────────────────────────────────────────────────────────
       * LOCATION TOKEN SYSTEM
       * ────────────────────────────────────────────────────────
       * The server issues a short-lived HMAC-signed token after
       * validating the coordinates. The token is stored in memory
       * and injected into the form on submit.
       *
       * This makes ALL client-side manipulation useless:
       *   - window.isLocationValid = true  → rejected (no valid token)
       *   - editing hidden lat/lng fields   → rejected (coord mismatch)
       *   - JS injection before submit      → rejected (no token)
       */
      let locationToken = null;        // The current server-issued token
      let locationTokenIssuedAt = null; // When the token was issued (ms)
      const TOKEN_TTL_MS = 110_000;    // 110 s — slightly under server's 120 s
      let tokenRefreshTimer = null;

      /**
       * Request a new location token from the server.
       * Called after GPS coordinates pass the client-side fake-GPS checks.
       */
      async function requestLocationToken(lat, lng, accuracy) {
        try {
          const res = await fetch('{{ route('presence.location-token') }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ latitude: lat, longitude: lng, accuracy: accuracy }),
          });

          if (!res.ok) {
            const data = await res.json().catch(() => ({}));
            const reason = data.reason || data.message || 'Koordinat ditolak oleh server.';
            return { ok: false, reason };
          }

          const data = await res.json();
          return { ok: true, token: data.token };
        } catch (err) {
          console.error('Token request failed:', err);
          return { ok: false, reason: 'Gagal menghubungi server untuk verifikasi lokasi.' };
        }
      }

      function setLocationToken(token) {
        locationToken = token;
        locationTokenIssuedAt = Date.now();
        document.querySelectorAll('.geo-token').forEach(el => el.value = token);

        // Schedule auto-refresh 90 s after issuance (before the 120 s server TTL)
        clearTimeout(tokenRefreshTimer);
        tokenRefreshTimer = setTimeout(() => {
          // If we still have valid coordinates, silently refresh the token
          if (realLatitude !== null && realLongitude !== null && lastAccuracy !== null) {
            requestLocationToken(realLatitude, realLongitude, lastAccuracy).then(result => {
              if (result.ok) {
                setLocationToken(result.token);
              } else {
                // Token refresh failed — invalidate so submit will be blocked
                invalidateToken('Verifikasi lokasi kedaluwarsa. Muat ulang halaman.');
              }
            });
          } else {
            invalidateToken('Data GPS tidak tersedia untuk refresh token.');
          }
        }, 90_000);
      }

      function invalidateToken(reason) {
        locationToken = null;
        locationTokenIssuedAt = null;
        document.querySelectorAll('.geo-token').forEach(el => el.value = '');
        clearTimeout(tokenRefreshTimer);

        // Update UI to show GPS needs re-verification
        const title = document.getElementById('locationTitle');
        const desc = document.getElementById('locationDesc');
        const tag = document.getElementById('locationTag');
        const banner = document.getElementById('locationBanner');
        const iconBg = document.getElementById('locationIconBg');
        const btnSubmitManual = document.querySelector('#tabContent-manual button[type="submit"]');

        window.isLocationValid = false;
        isHighAccuracyValidated = false;

        if (banner) banner.className = 'bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-900/50 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4';
        if (iconBg) iconBg.className = 'w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-600 flex items-center justify-center shrink-0';
        if (title) { title.className = 'font-bold text-amber-900 dark:text-amber-300 text-base'; title.textContent = 'Verifikasi Lokasi Kedaluwarsa'; }
        if (desc) { desc.className = 'text-xs sm:text-sm text-amber-700 dark:text-amber-400 mt-0.5'; desc.textContent = reason; }
        if (tag) { tag.className = 'flex items-center gap-2 px-3 py-1.5 bg-amber-100 dark:bg-amber-900/40 rounded-full text-xs font-bold text-amber-700 dark:text-amber-400 shrink-0'; tag.innerHTML = '<span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span> Token Kedaluwarsa'; }
        if (btnSubmitManual) btnSubmitManual.setAttribute('disabled', 'true');
        if (btnSubmitFace) btnSubmitFace.setAttribute('disabled', 'true');
      }

      /**
       * Deteksi GPS palsu berbasis analisis PERILAKU sinyal, bukan native code check.
       * Native code check mudah ditembus ekstensi Fake GPS modern.
       */
      function detectFakeLocation(position, isHighAccuracy) {
        const coords = position.coords;
        const now = Date.now();
        const isMobileDevice = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0);

        // 1. Timestamp staleness check — GPS palsu sering pakai timestamp lama / fixed
        if (isHighAccuracy) {
          const ageSec = (now - position.timestamp) / 1000;
          if (ageSec > 30) {
            return {
              spoofed: true,
              reason: 'Sinyal GPS kedaluwarsa (timestamp terlalu lama). GPS palsu mungkin aktif.'
            };
          }
        }

        // 2. Automation / headless browser check
        if (navigator.webdriver) {
          return {
            spoofed: true,
            reason: 'Akses menggunakan browser otomatis dilarang.'
          };
        }
        
        // 3. Chrome DevTools Protocol (CDP) & Emulator Checks
        // CDP default accuracy is exactly 150.
        if (coords.accuracy === 150) {
          return {
            spoofed: true,
            reason: 'Emulator / Chrome DevTools (CDP) terdeteksi (Akurasi mencurigakan).'
          };
        }
        
        // CDP and desktop emulators usually do not provide these hardware sensor values.
        // On real mobile devices, these are usually non-null (often 0 if stationary, but not null).
        if (isMobileDevice && coords.altitude === null && coords.altitudeAccuracy === null && coords.heading === null && coords.speed === null) {
          return {
            spoofed: true,
            reason: 'Sensor hardware tidak terdeteksi (Emulator/Fake GPS Chrome CDP).'
          };
        }

        // 4. Accuracy = 0 or 1 — impossible for real GPS
        if (coords.accuracy === 0 || coords.accuracy === 1) {
          return {
            spoofed: true,
            reason: 'Akurasi GPS tidak valid (GPS Palsu terdeteksi).'
          };
        }

        // 5. Chrome DevTools default sensor presets
        const devToolsPresets = [
          [51.507351, -0.127758],
          [35.676192, 139.650311],
          [-22.906847, -43.172897],
          [40.714272, -74.005966],
          [48.856613, 2.352222],
          [-33.868820, 151.209296],
        ];
        for (const [pLat, pLng] of devToolsPresets) {
          if (Math.abs(coords.latitude - pLat) < 0.01 && Math.abs(coords.longitude - pLng) < 0.01) {
            return {
              spoofed: true,
              reason: 'Developer Tools Sensors terdeteksi.'
            };
          }
        }

        // 6. Suspiciously low decimal precision — real GPS has >= 5 decimal places
        //    Fake GPS apps often set e.g. -6.2000000 or 107.0000
        const latStr = coords.latitude.toString();
        const lngStr = coords.longitude.toString();
        const latDec = (latStr.split('.')[1] || '').length;
        const lngDec = (lngStr.split('.')[1] || '').length;
        if (latDec < 4 || lngDec < 4) {
          return {
            spoofed: true,
            reason: 'Presisi koordinat GPS mencurigakan. Matikan aplikasi GPS palsu jika ada.'
          };
        }

        // 7. No variance after multiple high-accuracy updates (mobile only)
        //    Real GPS always has micro-fluctuations. A fixed coordinate = spoofed.
        if (isHighAccuracy && lastLat !== null && lastLng !== null) {
          const latDiff = Math.abs(coords.latitude - lastLat);
          const lngDiff = Math.abs(coords.longitude - lastLng);
          const accDiff = Math.abs(coords.accuracy - (lastAccuracy || 0));

          if (latDiff > 0.000001 || lngDiff > 0.000001 || accDiff > 0) {
            coordinateVarianceDetected = true;
          }

          gpsUpdateCount++;

          // After 4 high-accuracy updates, real mobile GPS MUST show some fluctuation
          if (isMobileDevice && gpsUpdateCount >= 4 && !coordinateVarianceDetected && coords.accuracy < 30) {
            return {
              spoofed: true,
              reason: 'GPS palsu terdeteksi (Koordinat tidak berfluktuasi — sinyal satelit palsu).'
            };
          }
        }

        lastLat = coords.latitude;
        lastLng = coords.longitude;
        lastAccuracy = coords.accuracy;

        return { spoofed: false };
      }

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

      // ==========================================
      // FACE RECOGNITION LOGIC
      // ==========================================
      const hasRegisteredFace = {{ $faceDescriptor ? 'true' : 'false' }};
      const registeredDescriptorData = {!! $faceDescriptor ? $faceDescriptor : 'null' !!};

      let isModelsLoaded = false;
      let cameraStream = null;
      let faceMatcher = null;
      let currentDetection = null;
      let isFaceVerified = false;
      let scanInterval = null;

      const video = document.getElementById('videoFeed');
      const canvas = document.getElementById('faceCanvas');
      const loadingText = document.getElementById('loadingText');
      const cameraLoading = document.getElementById('cameraLoading');
      const actionContainer = document.getElementById('actionContainer');
      const registerState = document.getElementById('registerState');
      const presenceState = document.getElementById('presenceState');
      const btnRegisterFace = document.getElementById('btnRegisterFace');
      const btnSubmitFace = document.getElementById('btnSubmitFace');
      const matchStatusText = document.getElementById('matchStatusText');

      window.retryCameraAccess = function() {
        if (!isModelsLoaded) {
          initFaceApi();
        } else {
          startCamera();
        }
      };

      async function initFaceApi() {
        if (!video) return; // if presence is done, video element doesn't exist

        try {
          const MODEL_URL = '/models/';

          const spinner = document.getElementById('cameraSpinner');
          const btnCamera = document.getElementById('btnRequestCamera');
          if (spinner) spinner.classList.remove('hidden');
          if (btnCamera) btnCamera.classList.add('hidden');

          // Load models sequentially to prevent overwhelming PHP built-in server (php artisan serve)
          await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
          await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
          await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);

          isModelsLoaded = true;
          loadingText.textContent = "Meminta akses kamera...";
          if (btnCamera) btnCamera.classList.remove('hidden');

          // setup face matcher if data exists
          if (hasRegisteredFace && registeredDescriptorData) {
            const descArray = new Float32Array(Object.values(registeredDescriptorData));
            const labeledDesc = new faceapi.LabeledFaceDescriptors('Current User', [descArray]);
            faceMatcher = new faceapi.FaceMatcher(labeledDesc, 0.55); // 0.55 threshold
          }

          startCamera();
        } catch (err) {
          console.error("Error loading face-api models:", err);
          loadingText.textContent = "Gagal memuat AI. Coba reload halaman.";
          const spinner = document.getElementById('cameraSpinner');
          if (spinner) spinner.classList.add('hidden');
        }
      }

      async function startCamera(deviceId = null) {
        const spinner = document.getElementById('cameraSpinner');
        const btnCamera = document.getElementById('btnRequestCamera');
        if (spinner) spinner.classList.remove('hidden');
        if (btnCamera) btnCamera.classList.remove('hidden');

        if (cameraStream) {
          cameraStream.getTracks().forEach(t => t.stop());
        }
        try {
          const constraints = {
            video: deviceId ? {
              deviceId: {
                exact: deviceId
              }
            } : {
              facingMode: 'user'
            }
          };
          cameraStream = await navigator.mediaDevices.getUserMedia(constraints);
          video.srcObject = cameraStream;
          try {
            await video.play();
          } catch (e) {}

          if (btnCamera) btnCamera.classList.add('hidden');
          if (spinner) spinner.classList.add('hidden');

          // Populate camera selection dropdown
          const select = document.getElementById('cameraSelect');
          const container = document.getElementById('cameraSelectContainer');
          if (select && select.options.length === 0) {
            const devices = await navigator.mediaDevices.enumerateDevices();
            const videoDevices = devices.filter(d => d.kind === 'videoinput');
            if (videoDevices.length > 1) {
              if (container) container.classList.remove('hidden');

              const activeTrack = cameraStream.getVideoTracks()[0];
              const activeDeviceId = activeTrack ? activeTrack.getSettings().deviceId : null;

              videoDevices.forEach((d, index) => {
                const opt = document.createElement('option');
                opt.value = d.deviceId;
                opt.text = d.label || `Kamera ${index + 1}`;
                if (deviceId === d.deviceId || activeDeviceId === d.deviceId) opt.selected = true;
                select.appendChild(opt);
              });
            }
          }
        } catch (err) {
          console.error("Camera access error:", err);
          loadingText.textContent = "Akses kamera ditolak/gagal.";
          if (spinner) spinner.classList.add('hidden');
          if (btnCamera) btnCamera.classList.remove('hidden');
        }
      }

      window.switchCamera = function(deviceId) {
        startCamera(deviceId);
      }

      if (video) {
        video.onplay = () => {
          cameraLoading.classList.add('hidden');
          actionContainer.classList.remove('hidden');

          if (!hasRegisteredFace) {
            registerState.classList.remove('hidden');
          } else {
            presenceState.classList.remove('hidden');
          }

          const displaySize = {
            width: video.videoWidth || video.clientWidth,
            height: video.videoHeight || video.clientHeight
          };
          faceapi.matchDimensions(canvas, displaySize);

          if (scanInterval) clearInterval(scanInterval);

          scanInterval = setInterval(async () => {
            if (isFaceVerified || !isModelsLoaded) return;

            const detection = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
              .withFaceLandmarks().withFaceDescriptor();

            if (isFaceVerified) return;

            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            if (detection) {
              const resizedDetections = faceapi.resizeResults(detection, displaySize);
              faceapi.draw.drawDetections(canvas, resizedDetections);
              currentDetection = detection;

              if (!hasRegisteredFace) {
                btnRegisterFace.removeAttribute('disabled');
                btnRegisterFace.classList.remove('opacity-50');

                const regStatus = document.getElementById('registerStatusText');
                if (regStatus) {
                  regStatus.textContent = "Wajah terdeteksi. Silakan klik tombol di bawah untuk mendaftar.";
                  regStatus.classList.remove('text-red-500', 'text-gray-600', 'dark:text-gray-400');
                  regStatus.classList.add('text-green-600');
                }
              } else {
                // Match with database descriptor
                const bestMatch = faceMatcher.findBestMatch(detection.descriptor);
                if (bestMatch.label === 'Current User' && bestMatch.distance < 0.55) {
                  isFaceVerified = true;
                  matchStatusText.innerHTML =
                    `Wajah dikenali sebagai <strong class="text-gray-900 dark:text-white">{{ addslashes(auth()->user()->name) }}</strong>!`;
                  matchStatusText.classList.remove('text-gray-500', 'text-red-500');
                  matchStatusText.classList.add('text-green-600');

                  // Check location validity
                  if (window.isLocationValid) {
                    btnSubmitFace.removeAttribute('disabled');
                  } else {
                    btnSubmitFace.setAttribute('disabled', 'true');
                    matchStatusText.innerHTML +=
                      `<br><span class="text-red-500 text-xs">Namun lokasi Anda di luar jangkauan radius.</span>`;
                  }

                  // Auto stop camera and hide video element
                  stopCamera();
                  video.classList.add('opacity-0');
                  canvas.classList.add('opacity-0');
                  const frame = document.getElementById('faceFrame');
                  if (frame) frame.classList.add('hidden');

                  const successEl = document.getElementById('faceSuccessCheck');
                  if (successEl) {
                    successEl.classList.remove('hidden');
                    successEl.classList.add('flex');
                  }

                  const nameEl = document.getElementById('verifiedNameDisplay');
                  if (nameEl) nameEl.textContent = `Halo, {{ addslashes(auth()->user()->name) }}!`;
                } else {
                  matchStatusText.textContent = "Wajah tidak cocok (Distance: " + bestMatch.distance.toFixed(2) +
                    ")";
                  matchStatusText.classList.remove('text-gray-500', 'text-green-600');
                  matchStatusText.classList.add('text-red-500');
                  if (btnSubmitFace) btnSubmitFace.setAttribute('disabled', 'true');
                }
              }
            } else {
              currentDetection = null;
              if (!hasRegisteredFace) {
                if (btnRegisterFace) btnRegisterFace.setAttribute('disabled', 'true');

                const regStatus = document.getElementById('registerStatusText');
                if (regStatus) {
                  regStatus.textContent =
                    "Wajah tidak terdeteksi. Silakan posisikan wajah Anda dengan benar di dalam bingkai.";
                  regStatus.classList.remove('text-green-600', 'text-gray-600', 'dark:text-gray-400');
                  regStatus.classList.add('text-red-500');
                }
              } else {
                matchStatusText.textContent =
                  "Wajah tidak terdeteksi. Silakan posisikan wajah Anda dengan benar di dalam bingkai.";
                matchStatusText.classList.remove('text-gray-500', 'text-green-600');
                matchStatusText.classList.add('text-red-500');
                if (btnSubmitFace) btnSubmitFace.setAttribute('disabled', 'true');
              }
            }
          }, 500); // scan every 500ms
        };
      }

      if (btnRegisterFace) {
        btnRegisterFace.addEventListener('click', async () => {
          if (!currentDetection) return;

          btnRegisterFace.textContent = "Menyimpan...";
          btnRegisterFace.setAttribute('disabled', 'true');

          // convert Float32Array to standard array so it can be JSON stringified properly
          const descriptorArray = Array.from(currentDetection.descriptor);

          try {
            const response = await fetch('{{ route('presence.register-face') }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              body: JSON.stringify({
                face_descriptor: JSON.stringify(descriptorArray)
              })
            });

            if (response.ok) {
              window.location.reload();
            } else {
              alert("Gagal mendaftarkan wajah.");
              btnRegisterFace.textContent = "Daftarkan Wajah Sekarang";
              btnRegisterFace.removeAttribute('disabled');
            }
          } catch (e) {
            console.error(e);
            alert("Error menyimpan wajah.");
          }
        });
      }

      // Stop camera when leaving tab
      function stopCamera() {
        if (cameraStream) {
          cameraStream.getTracks().forEach(track => track.stop());
        }
      }

      // Override switchTab to manage camera
      const originalSwitchTab = switchTab;
      switchTab = function(tabId) {
        originalSwitchTab(tabId);
        if (tabId === 'face') {
          checkAndInitFaceApi();
        } else {
          stopCamera();
        }
      };

      // Global location validity flag
      window.isLocationValid = false;
      let watchId = null;
      let highAccWatchId = null;
      let map = null;
      let userMarker = null;
      let officeCircle = null;

      /**
       * Update the map marker only — does NOT affect button state.
       * Called for both low-accuracy (fast) and high-accuracy positions.
       */
      function updateMapDisplay(lat, lng) {
        const officeLat = {{ $officeLat ?? 'null' }};
        const officeLng = {{ $officeLng ?? 'null' }};
        const officeRadius = {{ $officeRadius ?? 100 }};

        if (!officeLat || !officeLng) return;

        const mapContainer = document.getElementById('mapContainer');
        if (mapContainer) {
          mapContainer.classList.remove('hidden');

          if (!map) {
            map = L.map('map', { zoomControl: false }).setView([officeLat, officeLng], 17);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            officeCircle = L.circle([officeLat, officeLng], {
              color: '#ef4444',
              fillColor: '#ef4444',
              fillOpacity: 0.1,
              weight: 2,
              radius: officeRadius
            }).addTo(map);
          }

          if (userMarker) {
            userMarker.setLatLng([lat, lng]);
          } else {
            userMarker = L.marker([lat, lng]).addTo(map).bindPopup('Lokasi Anda').openPopup();
          }

          const group = new L.featureGroup([userMarker, officeCircle]);
          map.fitBounds(group.getBounds(), { padding: [30, 30] });
        }
      }

      /**
       * processPosition: validates a HIGH-ACCURACY GPS fix.
       * This controls whether attendance buttons are enabled.
       */
      function processPosition(position) {
        const tag = document.getElementById('locationTag');
        const btnGPS = document.getElementById('btnRequestGPS');
        const title = document.getElementById('locationTitle');
        const desc = document.getElementById('locationDesc');
        const banner = document.getElementById('locationBanner');
        const iconBg = document.getElementById('locationIconBg');
        const btnSubmitManual = document.querySelector('#tabContent-manual button[type="submit"]');

        const officeLat = {{ $officeLat ?? 'null' }};
        const officeLng = {{ $officeLng ?? 'null' }};
        const officeRadius = {{ $officeRadius ?? 100 }};

        // Run behavioral fake GPS detection (high-accuracy mode)
        const check = detectFakeLocation(position, true);
        if (check.spoofed) {
          invalidateToken(check.reason);
          // Update banner for spoofed state (handled partially by invalidateToken, but we enforce red styling)
          if (banner) banner.className =
            "bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-900/50 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4";
          if (iconBg) iconBg.className =
            "w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/40 text-red-600 flex items-center justify-center shrink-0";
          if (title) {
            title.className = "font-bold text-red-900 dark:text-red-300 text-base";
            title.textContent = "GPS Palsu Terdeteksi!";
          }
          if (desc) {
            desc.className = "text-xs sm:text-sm text-red-700 dark:text-red-400 mt-0.5";
            desc.textContent = check.reason;
          }
          if (tag) {
            tag.className =
              "flex items-center gap-2 px-3 py-1.5 bg-red-100 dark:bg-red-900/40 rounded-full text-xs font-bold text-red-700 dark:text-red-400 shrink-0";
            tag.innerHTML = '<span class="w-2 h-2 rounded-full bg-red-500"></span> GPS Tidak Aman';
          }

          // Stop watching after spoofing detected
          if (highAccWatchId !== null) {
            navigator.geolocation.clearWatch(highAccWatchId);
            highAccWatchId = null;
          }
          return true; // Spoofed
        }

        const lat = position.coords.latitude;
        const lng = position.coords.longitude;
        const accuracy = position.coords.accuracy;

        // Only high-accuracy validated coordinates are used
        isHighAccuracyValidated = true;
        realLatitude = lat;
        realLongitude = lng;

        if (btnGPS) btnGPS.classList.add('hidden');

        // Update map with validated position
        updateMapDisplay(lat, lng);

        if (officeLat && officeLng) {
          const from = turf.point([lng, lat]);
          const to = turf.point([officeLng, officeLat]);
          const distance = turf.distance(from, to, { units: 'meters' });

          if (distance <= officeRadius) {
            // Check if we already have a valid token
            if (locationToken && locationTokenIssuedAt && (Date.now() - locationTokenIssuedAt) < TOKEN_TTL_MS) {
              window.isLocationValid = true;
              showVerifiedUI(distance, banner, iconBg, title, desc, tag, btnSubmitManual);
            } else {
              // Request server token
              if (tag) {
                tag.innerHTML = '<span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span> Mengamankan Sesi...';
              }
              if (title) title.textContent = "Meminta Token Server...";

              requestLocationToken(lat, lng, accuracy).then(result => {
                if (result.ok) {
                  setLocationToken(result.token);
                  window.isLocationValid = true;
                  showVerifiedUI(distance, banner, iconBg, title, desc, tag, btnSubmitManual);
                } else {
                  invalidateToken(result.reason);
                  showErrorUI(result.reason, banner, iconBg, title, desc, tag, btnSubmitManual);
                }
              });
            }
          } else {
            invalidateToken('Lokasi di luar radius kantor.');
            window.isLocationValid = false;
            if (banner) banner.className =
              "bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-900/50 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4";
            if (iconBg) iconBg.className =
              "w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/40 text-red-600 flex items-center justify-center shrink-0";
            if (title) {
              title.className = "font-bold text-red-900 dark:text-red-300 text-base";
              title.textContent = `Lokasi Di Luar Radius (${Math.round(distance)}m)`;
            }
            if (desc) {
              desc.className = "text-xs sm:text-sm text-red-700 dark:text-red-400 mt-0.5";
              desc.textContent = `Maksimal radius yang diizinkan adalah ${officeRadius} meter`;
            }
            if (tag) {
              tag.className =
                "flex items-center gap-2 px-3 py-1.5 bg-red-100 dark:bg-red-900/40 rounded-full text-xs font-bold text-red-700 dark:text-red-400 shrink-0";
              tag.innerHTML = '<span class="w-2 h-2 rounded-full bg-red-500"></span> Di Luar Jangkauan';
            }
            if (officeCircle) officeCircle.setStyle({ color: '#ef4444', fillColor: '#ef4444' });
          }
        } else {
          if (title) title.textContent = "Pengaturan Lokasi Belum Diset";
          if (desc) desc.textContent = "Hubungi admin untuk mengatur lokasi kantor";
        }
        return false; // Not client spoofed
      }

      function showVerifiedUI(distance, banner, iconBg, title, desc, tag, btnSubmitManual) {
        if (banner) banner.className =
          "bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-900/50 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4";
        if (iconBg) iconBg.className =
          "w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 flex items-center justify-center shrink-0";
        if (title) {
          title.className = "font-bold text-emerald-900 dark:text-emerald-300 text-base";
          title.textContent = `Lokasi Terverifikasi (${Math.round(distance)}m)`;
        }
        if (desc) {
          desc.className = "text-xs sm:text-sm text-emerald-700 dark:text-emerald-400 mt-0.5";
          desc.textContent = "GPS akurat — Anda berada di dalam radius kantor";
        }
        if (tag) {
          tag.className =
            "flex items-center gap-2 px-3 py-1.5 bg-emerald-100 dark:bg-emerald-900/40 rounded-full text-xs font-bold text-emerald-700 dark:text-emerald-400 shrink-0";
          tag.innerHTML = '<span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> GPS Terverifikasi';
        }
        if (btnSubmitManual) btnSubmitManual.removeAttribute('disabled');
        if (btnSubmitFace && isFaceVerified) btnSubmitFace.removeAttribute('disabled');
        if (officeCircle) officeCircle.setStyle({ color: '#10b981', fillColor: '#10b981' });
      }

      function showErrorUI(reason, banner, iconBg, title, desc, tag, btnSubmitManual) {
        if (banner) banner.className =
          "bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-900/50 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4";
        if (iconBg) iconBg.className =
          "w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/40 text-red-600 flex items-center justify-center shrink-0";
        if (title) {
          title.className = "font-bold text-red-900 dark:text-red-300 text-base";
          title.textContent = "Verifikasi Server Gagal";
        }
        if (desc) {
          desc.className = "text-xs sm:text-sm text-red-700 dark:text-red-400 mt-0.5";
          desc.textContent = reason;
        }
        if (tag) {
          tag.className =
            "flex items-center gap-2 px-3 py-1.5 bg-red-100 dark:bg-red-900/40 rounded-full text-xs font-bold text-red-700 dark:text-red-400 shrink-0";
          tag.innerHTML = '<span class="w-2 h-2 rounded-full bg-red-500"></span> Ditolak Server';
        }
        if (officeCircle) officeCircle.setStyle({ color: '#ef4444', fillColor: '#ef4444' });
      }

      window.requestGPSPermission = function() {
        const tag = document.getElementById('locationTag');
        const btnGPS = document.getElementById('btnRequestGPS');
        const title = document.getElementById('locationTitle');
        const desc = document.getElementById('locationDesc');

        if (tag) {
          tag.className =
            "flex items-center gap-2 px-3 py-1.5 bg-gray-100 dark:bg-gray-800 rounded-full text-xs font-bold text-gray-500 shrink-0";
          tag.innerHTML = '<span class="w-2 h-2 rounded-full bg-gray-400 animate-pulse"></span> Menunggu GPS';
        }
        if (title) title.textContent = "Mengecek Lokasi...";
        if (desc) desc.textContent = "Mendapatkan koordinat GPS";
        if (btnGPS) btnGPS.classList.add('hidden');

        if (!navigator.geolocation) {
          if (title) title.textContent = "GPS Tidak Didukung";
          return;
        }

        // Clear any previous watches
        if (watchId !== null) { navigator.geolocation.clearWatch(watchId); watchId = null; }
        if (highAccWatchId !== null) { navigator.geolocation.clearWatch(highAccWatchId); highAccWatchId = null; }

        // ── PHASE 1: Fast display (low accuracy / cached) ──────────────────────
        // Show map position quickly without blocking attendance button.
        // We use maximumAge to accept a cached position immediately.
        navigator.geolocation.getCurrentPosition(
          position => {
            // Just update the map marker for fast feedback, no fake-GPS check yet
            updateMapDisplay(position.coords.latitude, position.coords.longitude);
            // Show loading indicator while high-accuracy kicks in
            if (tag) {
              tag.className =
                "flex items-center gap-2 px-3 py-1.5 bg-amber-100 dark:bg-amber-900/40 rounded-full text-xs font-bold text-amber-700 dark:text-amber-400 shrink-0";
              tag.innerHTML = '<span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span> Memverifikasi GPS...';
            }
            if (title) {
              title.className = "font-bold text-amber-900 dark:text-amber-300 text-base";
              title.textContent = "Memverifikasi GPS...";
            }
            if (desc) {
              desc.className = "text-xs sm:text-sm text-amber-700 dark:text-amber-400 mt-0.5";
              desc.textContent = "Peta ditampilkan. Menunggu GPS akurat untuk absensi.";
            }
          },
          () => { /* Phase 1 failure is non-critical, Phase 2 will handle it */ },
          { enableHighAccuracy: false, timeout: 3000, maximumAge: 60000 }
        );

        // ── PHASE 2: Strict validation (high accuracy, no cache) ───────────────
        // Only after this succeeds will attendance buttons become enabled.
        navigator.geolocation.getCurrentPosition(
          position => {
            const isSpoofed = processPosition(position);
            if (!isSpoofed) {
              startWatchingGPS();
            }
          },
          error => {
            console.warn('High accuracy GPS failed, trying low accuracy fallback...', error);
            // Phase 2 fallback: low accuracy — still run full fake GPS check
            navigator.geolocation.getCurrentPosition(
              position => {
                const isSpoofed = processPosition(position);
                if (!isSpoofed) startWatchingGPS();
              },
              errorFallback => {
                console.error('GPS Fallback failed too:', errorFallback);
                handleLocationError(errorFallback);
              },
              { enableHighAccuracy: false, timeout: 5000, maximumAge: 30000 }
            );
          },
          { enableHighAccuracy: true, timeout: 4000, maximumAge: 0 }
        );
      };

      function startWatchingGPS() {
        if (highAccWatchId !== null) return;
        highAccWatchId = navigator.geolocation.watchPosition(
          position => { processPosition(position); },
          error => { console.error('Background GPS watch error:', error); },
          { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
      }

      function handleLocationError(error) {
        const title = document.getElementById('locationTitle');
        const desc = document.getElementById('locationDesc');
        const tag = document.getElementById('locationTag');
        const btnGPS = document.getElementById('btnRequestGPS');

        console.error("Geolocation error:", error);
        if (title) title.textContent = "Gagal Mendapatkan Lokasi";
        if (desc) desc.textContent = "Izinkan akses GPS di pengaturan browser Anda.";
        if (tag) {
          tag.className =
            "flex items-center gap-2 px-3 py-1.5 bg-red-100 dark:bg-red-900/40 rounded-full text-xs font-bold text-red-700 dark:text-red-400 shrink-0";
          tag.innerHTML = '<span class="w-2 h-2 rounded-full bg-red-500"></span> Akses Ditolak';
        }
        if (btnGPS) btnGPS.classList.remove('hidden');
      }

      // Function to check and load Face API safely
      function checkAndInitFaceApi() {
        if (typeof faceapi !== 'undefined') {
          if (!isModelsLoaded) {
            initFaceApi();
          } else {
            startCamera();
          }
        } else {
          setTimeout(checkAndInitFaceApi, 500);
        }
      }

      // Auto-init on load
      document.addEventListener('DOMContentLoaded', () => {
        // Load Face API dynamically so it doesn't block DOMContentLoaded
        const script = document.createElement('script');
        script.src = '/face-api.min.js';
        script.async = true;
        document.body.appendChild(script);

        // Prioritize GPS check first so it doesn't get blocked
        window.requestGPSPermission();
        
        // Start checking for face API if the tab is active
        if (document.getElementById('tabContent-face')?.classList.contains('block')) {
          checkAndInitFaceApi();
        }
      });

      // Selfie Camera Logic
      let selfieStream = null;

      async function openSelfieCamera(type) {
        try {
          selfieStream = await navigator.mediaDevices.getUserMedia({
            video: {
              facingMode: 'user'
            }
          });
          const video = document.getElementById(`selfieVideo_${type}`);
          video.srcObject = selfieStream;
          try {
            await video.play();
          } catch (e) {}

          document.getElementById(`btnStartSelfie_${type}`).classList.add('hidden');
          document.getElementById(`selfieContainer_${type}`).classList.remove('hidden');
        } catch (err) {
          alert("Gagal mengakses kamera selfie: " + err.message);
        }
      }

      function captureSelfie(type) {
        const video = document.getElementById(`selfieVideo_${type}`);
        const canvas = document.getElementById('selfieSharedCanvas');
        canvas.width = video.videoWidth || 640;
        canvas.height = video.videoHeight || 480;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
        document.getElementById(`fotoBase64_${type}`).value = dataUrl;
        document.getElementById(`selfiePreview_${type}`).src = dataUrl;

        // Stop stream
        if (selfieStream) selfieStream.getTracks().forEach(t => t.stop());

        document.getElementById(`selfieContainer_${type}`).classList.add('hidden');
        document.getElementById(`selfiePreviewContainer_${type}`).classList.remove('hidden');
      }

      function retakeSelfie(type) {
        document.getElementById(`fotoBase64_${type}`).value = '';
        document.getElementById(`selfiePreviewContainer_${type}`).classList.add('hidden');
        openSelfieCamera(type);
      }
    </script>
  @endpush
@endsection
