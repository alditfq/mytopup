<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', \App\Models\Setting::getVal('shop_name', 'GameTopup') . ' - Portal Top-Up Game Tercepat & Terpercaya')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <!-- Stylesheets -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ time() }}" />
  
  <style>
    /* Global smooth transitions for all neup-flat cards */
    .neup-flat {
      transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
    }
  </style>
  
  @stack('styles')
</head>
<body class="min-h-screen flex flex-col neup-bg text-slate-800" id="main-app-shell">

  <!-- Universal Top Navigation -->
  @include('partials.navbar')

  <!-- Primary Screen Area -->
  <main class="flex-1 flex flex-col">
    @yield('content')
  </main>

  <!-- Universal Footer -->
  @include('partials.footer')

  <!-- Scripts -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="{{ asset('assets/js/script.js') }}"></script>
  
  @stack('scripts')
</body>
</html>
