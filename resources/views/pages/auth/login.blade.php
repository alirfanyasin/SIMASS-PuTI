@extends('layouts.auth-layout')

@section('content')
  <!-- Form Content -->
  <div class="w-full max-w-sm fade-in-up mt-16 lg:mt-0">
    <!-- Icon Header -->
    <div class="text-center mb-10">
      <h2 class="text-2xl lg:text-3xl font-extrabold">Selamat Datang</h2>
      <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">
        Masuk ke akun portal presensi Anda
      </p>
    </div>

    <!-- Session / Error Messages -->
    @if (session('status'))
      <div
        class="mb-4 p-3 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 text-sm">
        {{ session('status') }}
      </div>
    @endif

    @if ($errors->any())
      @php
        $errorMsg = $errors->first();
        $isLocked = str_contains($errorMsg, 'dikunci') || str_contains($errorMsg, 'Terlalu banyak');

        // Extract seconds from error message for countdown (e.g. "dalam 3 menit (180 detik)")
        preg_match('/\((\d+) detik\)/', $errorMsg, $matches);
        $lockSeconds = $matches[1] ?? null;
      @endphp

      @if ($isLocked)
        <div
          class="mb-4 p-4 rounded-xl bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 text-sm">
          <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-orange-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
              <p class="font-bold text-orange-800 dark:text-orange-300">Akun Sementara Dikunci</p>
              <p class="text-orange-700 dark:text-orange-400 mt-0.5">Terlalu banyak percobaan login yang gagal.</p>
              @if ($lockSeconds)
                <p class="text-orange-700 dark:text-orange-400 mt-1 font-medium">
                  Coba lagi dalam: <span id="lockCountdown" class="font-bold tabular-nums">{{ $lockSeconds }}s</span>
                </p>
              @endif
            </div>
          </div>
        </div>
      @else
        <div
          class="mb-4 p-3 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 text-sm">
          {{ $errorMsg }}
        </div>
      @endif
    @endif

    <!-- Login Form -->
    <form action="{{ route('login') }}" method="POST" class="space-y-4 mb-6">
      @csrf
      <div>
        <label for="username" class="block text-xs font-semibold text-gray-500 mb-1">Username SSO</label>
        <input type="text" id="username" name="username" value="{{ old('username') }}"
          placeholder="Masukkan username SSO" required autocomplete="username"
          class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('username') ? 'border-red-400 dark:border-red-600' : 'border-gray-200 dark:border-gray-700' }} bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
      </div>
      <div>
        <label for="password" class="block text-xs font-semibold text-gray-500 mb-1">Password</label>
        <div class="relative">
          <input type="password" id="password" name="password" placeholder="Masukkan password Anda" required
            autocomplete="current-password"
            class="w-full pl-4 pr-10 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-telkom-500">
          <button type="button" onclick="togglePassword()"
            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
            <!-- Eye icon when hidden -->
            <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
              <circle cx="12" cy="12" r="3" />
            </svg>
            <!-- Eye-off icon when shown -->
            <svg id="eye-off-icon" class="w-5 h-5" style="display: none;" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path
                d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
              <line x1="1" y1="1" x2="23" y2="23" />
            </svg>
          </button>
        </div>
      </div>
      <div class="flex items-center justify-between text-xs">
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" name="remember" class="rounded border-gray-300 text-telkom-600 focus:ring-telkom-500">
          <span class="text-gray-500">Ingat Saya</span>
        </label>
        <a href="#" class="text-telkom-600 dark:text-telkom-400 font-semibold hover:underline">Lupa Password?</a>
      </div>
      <button type="submit" id="btnSubmit"
        class="w-full py-3.5 gradient-telkom text-white rounded-xl font-semibold hover:opacity-90 transition shadow-lg shadow-telkom-600/20 disabled:opacity-50 disabled:cursor-not-allowed">
        Masuk
      </button>
    </form>

    <!-- Help -->
    <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
      Mengalami masalah saat masuk?
      <a href="#" class="text-telkom-600 dark:text-telkom-400 font-semibold hover:underline">Hubungi IT
        Support</a>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const eyeIcon = document.getElementById('eye-icon');
      const eyeOffIcon = document.getElementById('eye-off-icon');

      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.style.display = 'none';
        eyeOffIcon.style.display = 'block';
      } else {
        passwordInput.type = 'password';
        eyeOffIcon.style.display = 'none';
        eyeIcon.style.display = 'block';
      }
    }

    // Countdown timer for lockout
    const countdownEl = document.getElementById('lockCountdown');
    const submitBtn = document.getElementById('btnSubmit');
    if (countdownEl) {
      let seconds = parseInt(countdownEl.textContent);
      submitBtn.disabled = true;

      const timer = setInterval(() => {
        seconds--;
        if (seconds <= 0) {
          clearInterval(timer);
          countdownEl.textContent = '0s';
          submitBtn.disabled = false;
          submitBtn.textContent = 'Coba Lagi';
        } else {
          const m = Math.floor(seconds / 60);
          const s = seconds % 60;
          countdownEl.textContent = m > 0 ? `${m}m ${s}s` : `${s}s`;
        }
      }, 1000);
    }
  </script>
@endpush
