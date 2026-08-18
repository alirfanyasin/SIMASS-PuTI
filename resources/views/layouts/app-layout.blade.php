<!DOCTYPE html>
<html lang="id" class="">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <title>@yield('title', 'Presensi') — Portal PuTI</title>
  <link rel="icon" type="image/webp" href="{{ asset('logo-puti.webp') }}">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
    rel="stylesheet">

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 text-gray-900 transition-colors duration-300 dark:bg-gray-950 dark:text-gray-100">

  @include('partials.sidebar')

  <div class="lg:pl-64">
    @include('partials.navbar')
    <main class="p-4 lg:p-8 pb-24 lg:pb-8 mx-auto">
      @yield('content')
    </main>
  </div>

  @include('partials.mobile-navbar')

  @include('helpers.darkmode')

  <x-confirm-delete />
  <x-toast />

  @stack('scripts')

  {{-- Anti-Inspect Protection (Aggressive) --}}
  <script>
    (function () {
      // 1. Disable Right Click & Common Keys
      document.addEventListener('contextmenu', e => e.preventDefault());
      document.addEventListener('keydown', e => {
        const block = ['F12', 'I', 'J', 'C', 'U', 'S', 'P'];
        if (e.key === 'F12' || ((e.ctrlKey || e.metaKey) && (e.shiftKey && block.includes(e.key.toUpperCase()) || block.includes(e.key.toUpperCase())))) {
          e.preventDefault();
          return false;
        }
      });

      // 2. DevTools Detection via Performance / Debugger Trap
      const checkDevTools = () => {
        const start = performance.now();
        // eslint-disable-next-line no-debugger
        debugger;
        if (performance.now() - start > 100) {
          return true;
        }
        return false;
      };

      // 3. DevTools Detection via Dimension Difference (if docked)
      const checkDimensions = () => {
        const threshold = 160;
        const widthDiff = window.outerWidth - window.innerWidth > threshold;
        const heightDiff = window.outerHeight - window.innerHeight > threshold;
        return widthDiff || heightDiff;
      };

      // 4. Aggressive Loop & Penalty
      let isDevToolsOpen = false;
      const enforceSecurity = () => {
        if (checkDevTools() || checkDimensions()) {
          if (!isDevToolsOpen) {
            isDevToolsOpen = true;
            // Clear console constantly
            console.clear();
            // Nuke the DOM
            document.body.innerHTML = `
              <div style="display:flex;align-items:center;justify-content:center;min-height:100vh;flex-direction:column;font-family:system-ui,sans-serif;background:#000;color:#ef4444;">
                <svg width="80" height="80" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin-bottom:1rem">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <h1 style="font-size:2rem;font-weight:900;margin:0">SECURITY VIOLATION</h1>
                <p style="color:#9ca3af;margin-top:0.5rem">Developer Tools usage is strictly prohibited.</p>
                <button onclick="window.location.reload()" style="margin-top:2rem;padding:12px 24px;background:#ef4444;color:#fff;border:none;border-radius:8px;font-weight:bold;cursor:pointer">RELOAD SYSTEM</button>
              </div>`;
          }
        } else {
          if (isDevToolsOpen) {
            window.location.reload();
          }
        }
      };

      // Run aggressively
      setInterval(enforceSecurity, 200);
      setInterval(() => { console.clear(); }, 500);

      // Detect console opening via element toString (Chrome/Edge trick)
      const element = new Image();
      Object.defineProperty(element, 'id', {
        get: function () {
          enforceSecurity();
          throw new Error("DevTools detected");
        }
      });
      requestAnimationFrame(function check() {
        console.dir(element);
        console.clear();
        requestAnimationFrame(check);
      });
    })();
  </script>
</body>

</html>
