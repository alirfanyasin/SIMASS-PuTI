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
</body>

</html>
