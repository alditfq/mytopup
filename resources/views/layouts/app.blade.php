<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', \App\Models\Setting::getVal('shop_name', 'MyTopup') . ' - Portal Top-Up Game Tercepat & Terpercaya')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- SEO Meta --}}
  <meta name="description" content="@yield('meta_description', 'MyTopup adalah marketplace top-up game terpercaya. Top-up Diamond ML, UC PUBG, Robux, dan ratusan game lainnya dengan harga termurah dan proses cepat.')">
  <meta name="robots" content="index, follow">
  <meta name="theme-color" content="#6366f1">

  {{-- Open Graph --}}
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="{{ \App\Models\Setting::getVal('shop_name', 'MyTopup') }}">
  <meta property="og:title" content="@yield('title', \App\Models\Setting::getVal('shop_name', 'MyTopup') . ' - Portal Top-Up Game')">
  <meta property="og:description" content="@yield('meta_description', 'MyTopup adalah marketplace top-up game terpercaya. Top-up Diamond ML, UC PUBG, Robux, dan ratusan game lainnya dengan harga termurah dan proses cepat.')">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:image" content="@yield('og_image', asset('assets/img/og-default.png'))">

  {{-- Twitter Card --}}
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="@yield('title', \App\Models\Setting::getVal('shop_name', 'MyTopup'))">
  <meta name="twitter:description" content="@yield('meta_description', 'Top-up game murah, cepat, dan terpercaya.')">

  {{-- Favicon --}}
  <link rel="icon" type="image/png" href="{{ \App\Models\Setting::getVal('logo_url') ?: asset('assets/img/favicon.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('assets/img/favicon.png') }}">

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
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
  <script src="{{ asset('assets/js/script.js') }}"></script>

  @stack('scripts')
</body>
</html>
