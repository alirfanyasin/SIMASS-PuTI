<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portal PuTI</title>
  <link rel="icon" type="image/webp" href="{{ asset('logo-puti.webp') }}">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100" style="font-family:'Plus Jakarta Sans',sans-serif">
  <header class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 px-6 py-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
      <img src="{{ asset('logo-puti.webp') }}" class="w-8 h-8" alt="PuTI">
      <span class="font-bold text-lg">Portal PuTI</span>
    </div>
    <div class="flex items-center gap-4">
      <span class="text-sm text-gray-600 dark:text-gray-400">{{ auth()->user()->name }}</span>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium">Logout</button>
      </form>
    </div>
  </header>
  <main class="max-w-5xl mx-auto p-6 lg:p-10">
    @yield('content')
  </main>
  @include('helpers.darkmode')
  @stack('scripts')
</body>
</html>
