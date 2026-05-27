<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Admin Panel - ' . \App\Models\Setting::getVal('shop_name', 'GameTopup'))</title>
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
<body class="min-h-screen flex flex-col bg-[#090e1a] text-slate-100 font-sans relative overflow-x-hidden" id="admin-app-shell">

  <!-- BACKGROUND GLOW EFFECTS -->
  <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
    <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-blue-500/10 rounded-full blur-[180px]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-cyan-500/10 rounded-full blur-[180px]"></div>
    <div class="absolute top-[40%] right-[10%] w-[30%] h-[30%] bg-indigo-500/5 rounded-full blur-[130px]"></div>
  </div>

  <!-- Mobile Admin Topbar -->
  <header class="flex md:hidden items-center justify-between px-6 py-4 bg-[#111827]/75 backdrop-blur-xl border-b border-slate-800 shadow-lg">
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 decoration-none">
      <div class="flex h-9 w-9 items-center justify-center rounded-xl text-white bg-gradient-to-r from-blue-500 to-cyan-500 shadow-lg shadow-blue-500/20">
        <i data-lucide="shield-check" class="h-4.5 w-4.5"></i>
      </div>
      <span class="text-base font-extrabold tracking-tight text-white">
        Game<span class="text-cyan-400">Admin</span>
      </span>
    </a>
    <button id="admin-mobile-sidebar-trigger" class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-300 bg-slate-800 hover:bg-slate-700 border-none cursor-pointer">
      <i data-lucide="menu" id="admin-mobile-sidebar-icon" class="h-4.5 w-4.5"></i>
    </button>
  </header>

  <!-- Sidebar Container Grid -->
  <div class="flex-1 flex flex-row overflow-x-hidden min-h-screen relative">
    
    <!-- LEFT SIDEBAR -->
    <aside id="admin-sidebar" class="hidden md:flex flex-col w-64 bg-[#0d1324]/85 backdrop-blur-2xl border-r border-slate-800 flex-shrink-0 z-40 md:fixed md:left-0 md:top-0 md:bottom-0 md:h-screen p-5 text-left transition-all duration-300">
      <div class="flex flex-col h-full justify-between gap-6 w-full">
        <div class="overflow-y-auto pr-1 scrollbar-none">
          <!-- Brand Logo -->
          <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 transition-all active:scale-95 decoration-none mb-8">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl text-white bg-gradient-to-r from-blue-500 to-cyan-500 shadow-lg shadow-blue-500/25">
              <i data-lucide="shield-check" class="h-5 w-5"></i>
            </div>
            <span class="text-lg font-extrabold tracking-tight text-white">
              Game<span class="bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">Admin</span>
            </span>
          </a>

          <!-- Navigation Links -->
          <nav class="space-y-1.5 w-full">
            <a href="{{ route('admin.dashboard') }}" class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-xs font-bold transition-all decoration-none {{ Route::is('admin.dashboard') ? 'bg-blue-500/10 text-cyan-400 border border-blue-500/30' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-100 border border-transparent' }}">
              <i data-lucide="layout-dashboard" class="h-4 w-4"></i>
              <span>Ringkasan Stats</span>
            </a>

            <div class="pt-4 pb-1">
              <p class="text-[9px] uppercase tracking-wider font-extrabold text-slate-500 px-4">Marketplace Management</p>
            </div>

            <a href="{{ route('admin.transactions') }}" class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-xs font-bold transition-all decoration-none {{ Route::is('admin.transactions*') ? 'bg-blue-500/10 text-cyan-400 border border-blue-500/30' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-100 border border-transparent' }}">
              <i data-lucide="clipboard-list" class="h-4 w-4"></i>
              <span>Kelola Transaksi</span>
            </a>
            <a href="{{ route('admin.games') }}" class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-xs font-bold transition-all decoration-none {{ Route::is('admin.games') ? 'bg-blue-500/10 text-cyan-400 border border-blue-500/30' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-100 border border-transparent' }}">
              <i data-lucide="gamepad-2" class="h-4 w-4"></i>
              <span>Kelola Game</span>
            </a>
            <a href="{{ route('admin.nominals') }}" class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-xs font-bold transition-all decoration-none {{ Route::is('admin.nominals') ? 'bg-blue-500/10 text-cyan-400 border border-blue-500/30' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-100 border border-transparent' }}">
              <i data-lucide="layers" class="h-4 w-4"></i>
              <span>Kelola Item Paket</span>
            </a>
            <a href="{{ route('admin.promos') }}" class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-xs font-bold transition-all decoration-none {{ Route::is('admin.promos') ? 'bg-blue-500/10 text-cyan-400 border border-blue-500/30' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-100 border border-transparent' }}">
              <i data-lucide="tag" class="h-4 w-4"></i>
              <span>Kelola Promo / Banner</span>
            </a>
            <a href="{{ route('admin.flash-sale') }}" class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-xs font-bold transition-all decoration-none {{ Route::is('admin.flash-sale') ? 'bg-blue-500/10 text-cyan-400 border border-blue-500/30' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-100 border border-transparent' }}">
              <i data-lucide="flame" class="h-4 w-4"></i>
              <span>Kelola Flash Sale</span>
            </a>
            <a href="{{ route('admin.payment-methods') }}" class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-xs font-bold transition-all decoration-none {{ Route::is('admin.payment-methods') ? 'bg-blue-500/10 text-cyan-400 border border-blue-500/30' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-100 border border-transparent' }}">
              <i data-lucide="wallet" class="h-4 w-4"></i>
              <span>Metode Pembayaran</span>
            </a>
            <a href="{{ route('admin.accounts') }}" class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-xs font-bold transition-all decoration-none {{ Route::is('admin.accounts*') ? 'bg-blue-500/10 text-cyan-400 border border-blue-500/30' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-100 border border-transparent' }}">
              <i data-lucide="key-round" class="h-4 w-4"></i>
              <span>Kelola Akun Game</span>
            </a>

            <div class="pt-4 pb-1">
              <p class="text-[9px] uppercase tracking-wider font-extrabold text-slate-500 px-4">Administration & Users</p>
            </div>

            <a href="{{ route('admin.users') }}" class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-xs font-bold transition-all decoration-none {{ Route::is('admin.users') ? 'bg-blue-500/10 text-cyan-400 border border-blue-500/30' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-100 border border-transparent' }}">
              <i data-lucide="users" class="h-4 w-4"></i>
              <span>Kelola Pengguna</span>
            </a>

            <div class="pt-4 pb-1">
              <p class="text-[9px] uppercase tracking-wider font-extrabold text-slate-500 px-4">Analytics</p>
            </div>

            <a href="{{ route('admin.reports') }}" class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-xs font-bold transition-all decoration-none {{ Route::is('admin.reports') ? 'bg-blue-500/10 text-cyan-400 border border-blue-500/30' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-100 border border-transparent' }}">
              <i data-lucide="bar-chart-3" class="h-4 w-4"></i>
              <span>Laporan & Analitik</span>
            </a>

          </nav>
        </div>

        <!-- Sidebar Footer Actions -->
        <div class="space-y-1.5 border-t border-slate-800 pt-4 flex-shrink-0">
          <a href="{{ route('home') }}" target="_blank" class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-300 hover:bg-slate-800/40 hover:text-white transition-all decoration-none border border-transparent">
            <i data-lucide="globe" class="h-4 w-4"></i>
            <span>Buka Toko Utama</span>
          </a>
          <form action="{{ route('logout') }}" method="POST" class="m-0 p-0 w-full">
            @csrf
            <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-xs font-bold text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition-all border border-transparent bg-transparent cursor-pointer">
              <i data-lucide="log-out" class="h-4 w-4"></i>
              <span>Logout Admin</span>
            </button>
          </form>
        </div>
      </div>
    </aside>

    <!-- RIGHT PRIMARY WORKSPACE -->
    <main class="flex-1 flex flex-col min-w-0 bg-transparent overflow-y-auto md:pl-64 transition-all duration-300">
      <div class="p-6 md:p-8 lg:p-10 flex-1">
        @yield('content')
      </div>
    </main>

  </div>

  <!-- Scripts -->
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      if (window.lucide) {
        window.lucide.createIcons();
      }

      // Mobile Admin Sidebar Drawer Trigger
      const mobileBtn = document.getElementById('admin-mobile-sidebar-trigger');
      const sidebar = document.getElementById('admin-sidebar');
      const mobileIcon = document.getElementById('admin-mobile-sidebar-icon');

      if (mobileBtn && sidebar) {
        mobileBtn.addEventListener('click', (e) => {
          e.stopPropagation();
          
          const isHidden = sidebar.classList.contains('hidden');
          
          if (isHidden) {
            sidebar.classList.remove('hidden');
            sidebar.classList.add('flex', 'fixed', 'top-0', 'left-0', 'bg-[#0d1324]/98', 'z-50', 'h-screen', 'shadow-2xl');
            if (mobileIcon) {
              mobileIcon.setAttribute('data-lucide', 'x');
              if (window.lucide) window.lucide.createIcons();
            }
          } else {
            sidebar.classList.add('hidden');
            sidebar.classList.remove('flex', 'fixed', 'top-0', 'left-0', 'bg-[#0d1324]/98', 'z-50', 'h-screen', 'shadow-2xl');
            if (mobileIcon) {
              mobileIcon.setAttribute('data-lucide', 'menu');
              if (window.lucide) window.lucide.createIcons();
            }
          }
        });
        
        // Click main workspace to close mobile sidebar
        document.querySelector('main').addEventListener('click', () => {
          if (!sidebar.classList.contains('hidden') && window.innerWidth < 768) {
            mobileBtn.click();
          }
        });
      }
    });
  </script>
  
  @stack('scripts')
</body>
</html>
