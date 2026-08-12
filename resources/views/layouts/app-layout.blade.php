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

  {{-- Anti-Inspect Protection --}}
  <script>
    (function () {
      // Disable right-click context menu
      document.addEventListener('contextmenu', function (e) {
        e.preventDefault();
        return false;
      });

      // Block common DevTools keyboard shortcuts
      document.addEventListener('keydown', function (e) {
        // F12
        if (e.key === 'F12') { e.preventDefault(); return false; }
        // Ctrl+Shift+I / Cmd+Opt+I  (inspect element)
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && (e.key === 'I' || e.key === 'i')) { e.preventDefault(); return false; }
        // Ctrl+Shift+J / Cmd+Opt+J  (console)
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && (e.key === 'J' || e.key === 'j')) { e.preventDefault(); return false; }
        // Ctrl+Shift+C / Cmd+Opt+C  (element picker)
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && (e.key === 'C' || e.key === 'c')) { e.preventDefault(); return false; }
        // Ctrl+U  (view source)
        if ((e.ctrlKey || e.metaKey) && (e.key === 'U' || e.key === 'u')) { e.preventDefault(); return false; }
        // Ctrl+S  (save page)
        if ((e.ctrlKey || e.metaKey) && (e.key === 'S' || e.key === 's')) { e.preventDefault(); return false; }
      });

      // DevTools open detection via console timing trick
      var devToolsOpen = false;
      var threshold = 160;
      setInterval(function () {
        var start = new Date();
        // debugger triggers a pause when DevTools is open
        // eslint-disable-next-line no-debugger
        debugger;
        var elapsed = new Date() - start;
        if (elapsed > threshold && !devToolsOpen) {
          devToolsOpen = true;
          document.body.innerHTML =
            '<div style="display:flex;align-items:center;justify-content:center;min-height:100vh;flex-direction:column;font-family:sans-serif;background:#111;color:#fff;">' +
            '<svg width="64" height="64" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:#ef4444;margin-bottom:16px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>' +
            '<h1 style="font-size:1.5rem;font-weight:700;margin-bottom:8px">Akses Ditolak</h1>' +
            '<p style="color:#9ca3af;text-align:center;max-width:320px">Developer Tools terdeteksi. Tutup DevTools dan muat ulang halaman.</p>' +
            '<button onclick="location.reload()" style="margin-top:24px;padding:10px 24px;background:#ef4444;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer">Muat Ulang</button>' +
            '</div>';
        }
        if (elapsed <= threshold && devToolsOpen) {
          devToolsOpen = false;
          location.reload();
        }
      }, 1000);
    })();
  </script>
</body>

</html>
