<header class="sticky top-0 z-50 w-full bg-white/60 backdrop-blur-xl border-b border-slate-200/40 shadow-sm flex-shrink-0">
  <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
    
    <!-- Logo -->
    <a href="{{ route('home') }}" class="flex items-center gap-2.5 transition-all active:scale-95 decoration-none">
      @if(\App\Models\Setting::getVal('logo_url'))
        <img src="{{ \App\Models\Setting::getVal('logo_url') }}" alt="Logo" class="h-10 rounded-xl">
      @else
        <div class="flex h-10 w-10 items-center justify-center rounded-xl text-white neup-orange-flat shadow-sm">
          <i data-lucide="gamepad-2" class="h-5 w-5"></i>
        </div>
      @endif
      <span class="text-xl font-extrabold tracking-tight text-slate-800">
        @php
          $rawName = \App\Models\Setting::getVal('shop_name', 'GameTopup');
          $mid = ceil(strlen($rawName) / 2);
          $firstPart = substr($rawName, 0, $mid);
          $secondPart = substr($rawName, $mid);
        @endphp
        {{ $firstPart }}<span class="bg-gradient-to-r from-cyan-500 via-indigo-500 to-fuchsia-500 bg-clip-text text-transparent">{{ $secondPart }}</span>
      </span>
    </a>

    <!-- Desktop Navigation -->
    <nav class="hidden md:flex items-center gap-4">
      <a href="{{ route('home') }}" class="relative flex items-center gap-2 px-4 py-2 text-xs font-black rounded-xl transition-all {{ Route::is('home') ? 'neup-pressed-sm text-indigo-600 font-extrabold shadow-sm border border-indigo-200/20' : 'neup-flat-sm text-slate-600 hover:neup-pressed-xs hover:text-slate-900' }}">
        <i data-lucide="gamepad-2" class="h-4 w-4"></i>
        <span>Home</span>
      </a>
      <a href="{{ route('status') }}" class="relative flex items-center gap-2 px-4 py-2 text-xs font-black rounded-xl transition-all {{ Route::is('status') ? 'neup-pressed-sm text-indigo-600 font-extrabold shadow-sm border border-indigo-200/20' : 'neup-flat-sm text-slate-600 hover:neup-pressed-xs hover:text-slate-900' }}">
        <i data-lucide="clipboard-list" class="h-4 w-4"></i>
        <span id="nav-track-btn">Cek Transaksi</span>
      </a>
      <a href="{{ route('support') }}" class="relative flex items-center gap-2 px-4 py-2 text-xs font-black rounded-xl transition-all {{ Route::is('support') ? 'neup-pressed-sm text-indigo-600 font-extrabold shadow-sm border border-indigo-200/20' : 'neup-flat-sm text-slate-600 hover:neup-pressed-xs hover:text-slate-900' }}">
        <i data-lucide="message-circle" class="h-4 w-4"></i>
        <span id="nav-support-btn">Hubungi CS</span>
      </a>
    </nav>

    <!-- Right Menu Desk -->
    <div class="hidden md:flex items-center gap-4">
      <!-- Language Toggle Dropdown -->
      <div class="relative">
        <button id="lang-dropdown-trigger" class="flex items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-black text-slate-600 neup-flat-sm hover:neup-pressed-sm transition-all cursor-pointer">
          <i data-lucide="globe" class="h-3.5 w-3.5 text-slate-500"></i>
          <span id="nav-active-lang-text">ID</span>
          <i data-lucide="chevron-down" class="h-3 w-3 text-slate-500"></i>
        </button>
        <div id="lang-dropdown-menu" class="hidden absolute right-0 mt-2.5 w-36 overflow-hidden rounded-2xl border border-white/50 p-1.5 neup-flat z-50 bg-white">
          <button id="lang-btn-id" class="flex w-full items-center px-4 py-2.5 rounded-xl text-left text-xs font-bold hover:neup-pressed-xs">
            Bahasa (ID)
          </button>
          <button id="lang-btn-en" class="flex w-full items-center px-4 py-2.5 rounded-xl text-left text-xs font-bold hover:neup-pressed-xs">
            English (EN)
          </button>
        </div>
      </div>

      <!-- Server-side Dynamic User Profile -->
      @auth
        @php
          $firstChar = strtoupper(substr(Auth::user()->name, 0, 1));
          $bgColors = [
              'A' => 'bg-rose-500 text-rose-50 border-rose-600',
              'B' => 'bg-pink-500 text-pink-50 border-pink-600',
              'C' => 'bg-fuchsia-500 text-fuchsia-50 border-fuchsia-600',
              'D' => 'bg-purple-500 text-purple-50 border-purple-600',
              'E' => 'bg-violet-500 text-violet-50 border-violet-600',
              'F' => 'bg-indigo-500 text-indigo-50 border-indigo-600',
              'G' => 'bg-blue-500 text-blue-50 border-blue-600',
              'H' => 'bg-sky-500 text-sky-50 border-sky-600',
              'I' => 'bg-cyan-500 text-cyan-50 border-cyan-600',
              'J' => 'bg-teal-500 text-teal-50 border-teal-600',
              'K' => 'bg-emerald-500 text-emerald-50 border-emerald-600',
              'L' => 'bg-green-500 text-green-50 border-green-600',
              'M' => 'bg-lime-500 text-lime-50 border-lime-600',
              'N' => 'bg-yellow-500 text-yellow-950 border-yellow-600',
              'O' => 'bg-amber-500 text-amber-950 border-amber-600',
              'P' => 'bg-orange-500 text-orange-50 border-orange-600',
              'Q' => 'bg-red-500 text-red-50 border-red-600',
              'R' => 'bg-rose-600 text-rose-50 border-rose-700',
              'S' => 'bg-pink-600 text-pink-50 border-pink-700',
              'T' => 'bg-fuchsia-600 text-fuchsia-50 border-fuchsia-700',
              'U' => 'bg-purple-600 text-purple-50 border-purple-700',
              'V' => 'bg-violet-600 text-violet-50 border-violet-700',
              'W' => 'bg-indigo-600 text-indigo-50 border-indigo-700',
              'X' => 'bg-blue-600 text-blue-50 border-blue-700',
              'Y' => 'bg-sky-600 text-sky-50 border-sky-700',
              'Z' => 'bg-cyan-600 text-cyan-50 border-cyan-700',
          ];
          $avatarClass = $bgColors[$firstChar] ?? 'bg-slate-500 text-slate-50 border-slate-600';
        @endphp
        <div class="relative flex items-center gap-3" id="user-dropdown">
          
          <button id="user-menu-trigger" class="flex items-center gap-2 rounded-xl p-1.5 neup-flat-sm hover:neup-pressed-sm transition-all cursor-pointer border-none bg-transparent">
            <div class="h-7 w-7 rounded-lg flex items-center justify-center font-black text-xs border shadow-sm flex-shrink-0 {{ $avatarClass }}">
              {{ $firstChar }}
            </div>
            <span class="text-xs font-bold text-slate-700 max-w-[85px] truncate">{{ Auth::user()->name }}</span>
            <i data-lucide="chevron-down" class="h-3 w-3 text-slate-500"></i>
          </button>
          
          <!-- User Dropdown Menu -->
          <div id="user-dropdown-menu" class="hidden absolute right-0 top-full mt-2 w-48 overflow-hidden rounded-2xl border border-white/50 p-1.5 neup-flat z-50 bg-white shadow-xl">
            <div class="px-4 py-2 border-b border-slate-100/80 mb-1">
              <p class="text-xs font-bold text-slate-800">{{ Auth::user()->name }}</p>
              <p class="text-[10px] text-slate-400 font-medium truncate">{{ Auth::user()->email }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="flex w-full items-center gap-2 px-3 py-2 rounded-xl text-left text-xs font-bold text-slate-700 hover:neup-pressed-xs">
              <i data-lucide="layout-dashboard" class="h-3.5 w-3.5 text-slate-500"></i>
              Dashboard
            </a>
            <a href="{{ route('profile') }}" class="flex w-full items-center gap-2 px-3 py-2 rounded-xl text-left text-xs font-bold text-slate-700 hover:neup-pressed-xs">
              <i data-lucide="user" class="h-3.5 w-3.5 text-slate-500"></i>
              Edit Profil
            </a>
            @if(Auth::user()->role === 'admin')
              <a href="{{ route('admin.dashboard') }}" class="flex w-full items-center gap-2 px-3 py-2 rounded-xl text-left text-xs font-bold text-indigo-600 hover:neup-pressed-xs">
                <i data-lucide="shield-check" class="h-3.5 w-3.5 text-indigo-500"></i>
                Panel Admin
              </a>
            @endif
            <div class="my-1 border-t border-slate-100/80"></div>
            <form action="{{ route('logout') }}" method="POST" class="w-full m-0 p-0">
              @csrf
              <button type="submit" class="flex w-full items-center gap-2 px-3 py-2 rounded-xl text-left text-xs font-bold text-rose-600 hover:neup-pressed-xs border-none bg-transparent cursor-pointer">
                <i data-lucide="log-out" class="h-3.5 w-3.5 text-rose-500"></i>
                Keluar
              </button>
            </form>
          </div>
        </div>
      @else
        <div class="flex items-center gap-3">
          <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-900 transition-all">Masuk</a>
          <a href="{{ route('register') }}" class="px-4 py-2 text-xs font-bold text-white rounded-xl neup-orange-flat hover:scale-105 active:scale-95 transition-all shadow-sm">Daftar</a>
        </div>
      @endauth
    </div>

    <!-- Mobile Drawer Toggles -->
    <div class="flex items-center gap-2.5 md:hidden">
      <button id="mobile-lang-btn" class="flex items-center justify-center rounded-xl px-3 py-2 text-slate-600 neup-flat-xs hover:neup-pressed-xs">
        <i data-lucide="globe" class="h-3.5 w-3.5"></i>
        <span class="ml-1 text-[10px] font-black" id="mobile-lang-text">ID</span>
      </button>
      <button id="mobile-menu-trigger" class="flex h-10 w-10 items-center justify-center rounded-xl text-slate-600 neup-flat-xs hover:neup-pressed-xs">
        <i data-lucide="menu" id="mobile-menu-icon" class="h-5 w-5"></i>
      </button>
    </div>
  </div>

  <!-- Mobile Dropdown Drawer -->
  <div id="mobile-drawer" class="hidden border-t border-slate-200/40 bg-white/95 backdrop-blur-xl md:hidden overflow-hidden shadow-lg">
    <div class="space-y-1.5 p-4 pb-6">
      <a href="{{ route('home') }}" class="flex w-full items-center gap-3 rounded-xl px-4 py-3.5 text-xs font-black transition-all {{ Route::is('home') ? 'neup-pressed-sm text-indigo-600 font-extrabold border border-indigo-200/20' : 'neup-flat-sm text-slate-600 hover:neup-pressed-xs' }}">
        <i data-lucide="gamepad-2" class="h-4.5 w-4.5"></i>
        <span>Home</span>
      </a>
      <a href="{{ route('status') }}" class="flex w-full items-center gap-3 rounded-xl px-4 py-3.5 text-xs font-black transition-all {{ Route::is('status') ? 'neup-pressed-sm text-indigo-600 font-extrabold border border-indigo-200/20' : 'neup-flat-sm text-slate-600 hover:neup-pressed-xs' }}">
        <i data-lucide="clipboard-list" class="h-4.5 w-4.5"></i>
        <span id="mob-track-btn">Cek Transaksi</span>
      </a>
      <a href="{{ route('support') }}" class="flex w-full items-center gap-3 rounded-xl px-4 py-3.5 text-xs font-black transition-all {{ Route::is('support') ? 'neup-pressed-sm text-indigo-600 font-extrabold border border-indigo-200/20' : 'neup-flat-sm text-slate-600 hover:neup-pressed-xs' }}">
        <i data-lucide="message-circle" class="h-4.5 w-4.5"></i>
        <span id="mob-support-btn">Hubungi CS</span>
      </a>

      <div class="my-4 border-t border-slate-300/40"></div>

      <!-- Server-side Dynamic User Profile Mobile -->
      @auth
        <div class="p-3.5 rounded-2xl neup-flat bg-white/40 space-y-3">
          <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-xl flex items-center justify-center font-black text-xs border shadow-sm flex-shrink-0 {{ $avatarClass }}">
              {{ $firstChar }}
            </div>
            <div>
              <p class="text-xs font-bold text-slate-800">{{ Auth::user()->name }}</p>
              <p class="text-[9px] text-slate-400 font-medium truncate">{{ Auth::user()->email }}</p>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-2 pt-1">
            <a href="{{ route('dashboard') }}" class="flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-center text-xs font-black text-slate-700 neup-flat-sm hover:neup-pressed-sm transition-all">
              <i data-lucide="layout-dashboard" class="h-4 w-4"></i>
              <span>Dashboard</span>
            </a>
            <a href="{{ route('profile') }}" class="flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-center text-xs font-black text-slate-700 neup-flat-sm hover:neup-pressed-sm transition-all">
              <i data-lucide="user" class="h-4 w-4"></i>
              <span>Profil</span>
            </a>
            @if(Auth::user()->role === 'admin')
              <a href="{{ route('admin.dashboard') }}" class="col-span-2 flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-center text-xs font-black text-indigo-600 neup-flat-sm hover:neup-pressed-sm transition-all">
                <i data-lucide="shield-check" class="h-4 w-4"></i>
                <span>Panel Admin</span>
              </a>
            @endif
          </div>
          <div class="border-t border-slate-300/40 my-2"></div>
          <form action="{{ route('logout') }}" method="POST" class="w-full m-0 p-0">
            @csrf
            <button type="submit" class="flex w-full items-center justify-center gap-2 px-4 py-3 rounded-xl text-xs font-black text-rose-600 neup-flat-xs hover:neup-pressed-xs border-none bg-transparent cursor-pointer">
              <i data-lucide="log-out" class="h-4 w-4"></i>
              <span>Keluar</span>
            </button>
          </form>
        </div>
      @else
        <div class="grid grid-cols-2 gap-3 pt-2">
          <a href="{{ route('login') }}" class="flex items-center justify-center px-4 py-3 rounded-xl text-xs font-black text-slate-600 neup-flat-sm hover:neup-pressed-sm transition-all">Masuk</a>
          <a href="{{ route('register') }}" class="flex items-center justify-center px-4 py-3 rounded-xl text-xs font-black text-white neup-orange-flat hover:scale-105 transition-all shadow-sm">Daftar</a>
        </div>
      @endauth
    </div>
  </div>
  
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // User Menu Dropdown Toggle
      const userTrigger = document.getElementById('user-menu-trigger');
      const userMenu = document.getElementById('user-dropdown-menu');
      if (userTrigger && userMenu) {
        userTrigger.addEventListener('click', (e) => {
          e.stopPropagation();
          userMenu.classList.toggle('hidden');
          // Close lang menu if open
          const langMenu = document.getElementById('lang-dropdown-menu');
          if (langMenu) langMenu.classList.add('hidden');
        });
      }

      // Lang Menu Dropdown Toggle
      const langTrigger = document.getElementById('lang-dropdown-trigger');
      const langMenu = document.getElementById('lang-dropdown-menu');
      if (langTrigger && langMenu) {
        langTrigger.addEventListener('click', (e) => {
          e.stopPropagation();
          langMenu.classList.toggle('hidden');
          // Close user menu if open
          const userMenu = document.getElementById('user-dropdown-menu');
          if (userMenu) userMenu.classList.add('hidden');
        });
      }

      // Global click to close dropdowns
      document.addEventListener('click', () => {
        if (userMenu) userMenu.classList.add('hidden');
        if (langMenu) langMenu.classList.add('hidden');
      });

      // Mobile Menu Drawer Toggle (Fallback)
      const mobileBtn = document.getElementById('mobile-menu-trigger');
      const drawer = document.getElementById('mobile-drawer');
      const mobileMenuIcon = document.getElementById('mobile-menu-icon');
      if (mobileBtn && drawer) {
        mobileBtn.addEventListener('click', (e) => {
          e.stopPropagation();
          drawer.classList.toggle('hidden');
          const isOpen = !drawer.classList.contains('hidden');
          if (mobileMenuIcon) {
            mobileMenuIcon.setAttribute('data-lucide', isOpen ? 'x' : 'menu');
            // Re-render lucide icons if defined
            if (window.lucide) {
              window.lucide.createIcons();
            }
          }
        });
      }
    });
  </script>
</header>
