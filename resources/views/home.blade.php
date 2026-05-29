@extends('layouts.app')

@section('title', 'GameTopup - Portal Top Up Game Tercepat & Terpercaya')

@push('styles')
<style>
  /* Premium Neumorphic / Neo-Brutalist Hover Animations (FAQ Style) */
  #banner-promo-card {
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
  }
  #banner-promo-card:hover {
    transform: translateY(-8px) scale(1.015) !important;
    border-color: #ff007f !important;
    background: #fffffff2 !important;
    box-shadow: 0 18px #ff007f, 0 32px 48px -10px rgba(255, 0, 127, 0.35), inset 0 2px #fff !important;
  }
  #banner-promo-card .promo-slide {
    transition: background-size 0.5s ease !important;
  }
  #banner-promo-card:hover .promo-slide.active {
    background-size: 104% !important;
  }

  #homepage-flash-sale-card {
    background: #121528f2 !important;
    backdrop-filter: blur(18px) !important;
    -webkit-backdrop-filter: blur(18px) !important;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
  }
  #homepage-flash-sale-card:hover {
    transform: translateY(-8px) scale(1.015) !important;
    border-color: #ff007f !important;
    background: #121528f2 !important;
    box-shadow: 0 18px #ff007f, 0 32px 48px -10px rgba(255, 0, 127, 0.55) !important;
  }
  #homepage-flash-sale-card .flash-sale-flame-container i {
    transition: all 0.5s ease !important;
  }
  #homepage-flash-sale-card:hover .flash-sale-flame-container i {
    transform: scale(1.2) rotate(6deg) !important;
    color: #ff007f !important;
    opacity: 0.25 !important;
  }

  .payment-stat-card {
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
  }
  .payment-stat-card:hover {
    transform: translateY(-8px) scale(1.015) !important;
    background: #fffffff2 !important;
  }
  .payment-stat-card-cyan:hover {
    border-color: #06b6d4 !important;
    box-shadow: 0 18px #06b6d4, 0 32px 48px -10px rgba(6, 182, 212, 0.35), inset 0 2px #fff !important;
  }
  .payment-stat-card-cyan:hover .h-9 {
    background: #06b6d4 !important;
    color: #ffffff !important;
    transform: scale(1.12) !important;
  }
  .payment-stat-card-fuchsia:hover {
    border-color: #d946ef !important;
    box-shadow: 0 18px #d946ef, 0 32px 48px -10px rgba(217, 70, 239, 0.35), inset 0 2px #fff !important;
  }
  .payment-stat-card-fuchsia:hover .h-9 {
    background: #d946ef !important;
    color: #ffffff !important;
    transform: scale(1.12) !important;
  }
  .payment-stat-card-indigo:hover {
    border-color: #6366f1 !important;
    box-shadow: 0 18px #6366f1, 0 32px 48px -10px rgba(99, 102, 241, 0.35), inset 0 2px #fff !important;
  }
  .payment-stat-card-indigo:hover .h-9 {
    background: #6366f1 !important;
    color: #ffffff !important;
    transform: scale(1.12) !important;
  }
  .payment-stat-card .h-9 {
    transition: all 0.3s ease !important;
  }

  .homepage-testimonial-card {
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
  }
  .homepage-testimonial-card:hover {
    transform: translateY(-8px) scale(1.015) !important;
    border-color: #6366f1 !important;
    background: #fffffff2 !important;
    box-shadow: 0 18px #6366f1, 0 32px 48px -10px rgba(99, 102, 241, 0.35), inset 0 2px #fff !important;
  }
  .homepage-testimonial-card:hover p {
    color: #1e293b !important;
  }
  .homepage-testimonial-card .testimonial-avatar {
    transition: all 0.3s ease !important;
  }
  .homepage-testimonial-card:hover .testimonial-avatar {
    transform: scale(1.12) !important;
    border-color: #6366f1 !important;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25) !important;
  }
  .homepage-testimonial-card:hover span.text-indigo-600 {
    color: #ff007f !important;
  }

  #payment-stats-section {
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
  }

  /* Game Card Staggered Entrance Keyframes */
  @keyframes cardFadeInUp {
    from {
      opacity: 0;
      transform: translateY(24px) scale(0.96);
    }
    to {
      opacity: 1;
      transform: translateY(0) scale(1);
    }
  }

  .game-card-animate-in {
    opacity: 0; /* Base state before animation starts */
    animation: cardFadeInUp 0.4s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
  }

  /* Smooth Slide Track for Banner Carousel */
  #promo-slide-track {
    display: flex;
    height: 100%;
    width: 100%;
    transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1) !important;
  }

  /* Interactive Hover Arrows Slide-In */
  .banner-arrow-btn {
    opacity: 0 !important;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
  }
  #promo-prev-btn {
    left: -2.5rem !important;
    transform: translateY(-50%) scale(0.8) !important;
  }
  #promo-next-btn {
    right: -2.5rem !important;
    transform: translateY(-50%) scale(0.8) !important;
  }
  #banner-promo-card:hover #promo-prev-btn {
    opacity: 1 !important;
    left: 1.25rem !important;
    transform: translateY(-50%) scale(1) !important;
  }
  #banner-promo-card:hover #promo-next-btn {
    opacity: 1 !important;
    right: 1.25rem !important;
    transform: translateY(-50%) scale(1) !important;
  }

  /* Staggered Content Sliding inside active slide */
  .promo-slide > div > * {
    opacity: 0;
    transform: translate3d(50px, 0, 0);
    transition: opacity 0.6s cubic-bezier(0.25, 1, 0.5, 1), transform 0.6s cubic-bezier(0.25, 1, 0.5, 1) !important;
  }
  
  .promo-slide.active > div > * {
    opacity: 1;
    transform: translate3d(0, 0, 0);
  }
  
  /* Apply staggered transition delays */
  .promo-slide.active > div > span {
    transition-delay: 0.05s !important;
  }
  .promo-slide.active > div > h3 {
    transition-delay: 0.15s !important;
  }
  .promo-slide.active > div > p {
    transition-delay: 0.25s !important;
  }
  .promo-slide.active > div > button {
    transition-delay: 0.35s !important;
  }

  /* Premium Testimonial Infinite Marquee Animation */
  @keyframes testimonialMarquee {
    0% {
      transform: translate3d(0, 0, 0);
    }
    100% {
      transform: translate3d(-50%, 0, 0);
    }
  }

  .animate-testimonial-marquee {
    display: flex;
    width: max-content;
    animation: testimonialMarquee 35s linear infinite;
  }

  #testimonials-marquee-container:hover .animate-testimonial-marquee {
    animation-play-state: paused;
  }

  /* Fixed size normal boxes for Testimonial Cards to prevent stretching */
  .homepage-testimonial-card {
    width: 320px !important;
    min-width: 320px !important;
    max-width: 320px !important;
    box-sizing: border-box !important;
    white-space: normal !important;
    display: flex !important;
    flex-direction: column !important;
    justify-content: space-between !important;
    background: rgba(255, 255, 255, 0.8) !important;
    backdrop-filter: blur(8px) !important;
  }

  #testimonials-marquee-container {
    padding-top: 1.5rem !important;
    padding-bottom: 2.5rem !important;
    overflow: hidden !important;
    position: relative !important;
    width: 100% !important;
  }

  /* Announcement Bar Marquee */
  @keyframes marquee {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-50%); }
  }

  .animate-marquee {
    display: flex;
    width: max-content;
    animation: marquee 25s linear infinite;
    will-change: transform;
  }
</style>
@endpush

@section('content')
  <div class="flex-1 pb-16" id="landing-page">
    
    <!-- 1. ANNOUNCEMENT & PROMO MARQUEE -->
    @if($shopName && !empty(trim($shopName)))
    <section class="bg-gradient-to-r from-cyan-500 via-indigo-600 to-fuchsia-600 py-2.5 text-white overflow-hidden text-xs font-semibold shadow-sm">
      <div class="flex animate-marquee items-center whitespace-nowrap" id="announcement-marquee">
        <span class="px-8">🎮 Selamat datang di {{ $shopName }} - Platform top up game terpercaya 🎮</span>
        <span class="px-8 opacity-50">•</span>
        <span class="px-8">🎮 Selamat datang di {{ $shopName }} - Platform top up game terpercaya 🎮</span>
      </div>
    </section>
    @endif


    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-6">
      
      <!-- Carousel / Banner Promo Berjalan Card -->
      <div id="banner-promo-card" class="relative rounded-3xl neup-flat border border-white/50 p-2">
        <div id="promo-banner-container" class="relative h-[220px] md:h-[320px] w-full overflow-hidden rounded-2xl text-white">
          <div id="promo-slide-track" class="flex h-full w-full">
            
            @foreach($promos as $idx => $promo)
              <div class="promo-slide {{ $idx === 0 ? 'active' : '' }}" id="slide-{{ $idx }}" 
                   style="background-image:linear-gradient(135deg,rgba(0,0,0,0.55),rgba(0,0,0,0.3)),url('{{ $promo->image }}');
                          background-size:cover;background-position:center;
                          padding:2rem;display:flex;flex-direction:column;justify-content:center;height:100%;width:100%;min-width:100%;flex-shrink:0;box-sizing:border-box;">
                <div class="text-left flex flex-col gap-2.5">
                  <span class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-pink-500/25 border border-pink-500/40 text-[10px] font-black uppercase tracking-wider text-pink-500 width-fit-content w-fit">★ PROMO AKTIF</span>
                  <h3 class="text-lg md:text-2xl font-black text-white m-0 leading-tight">{{ $promo->title }}</h3>
                  <p class="text-xs text-white/80 font-bold m-0">{{ $promo->description }}</p>
                  @if($promo->claim_url)
                    <button onclick="window.location.href='{{ $promo->claim_url }}'" class="neup-orange-flat border-none text-white text-xs font-black py-2.5 px-5 rounded-full cursor-pointer w-fit mt-3 active:scale-95 transition-all">Klaim Promo →</button>
                  @else
                    <button onclick="window.location.href='{{ route('game.detail', 'mobile-legends') }}'" class="neup-orange-flat border-none text-white text-xs font-black py-2.5 px-5 rounded-full cursor-pointer w-fit mt-3 active:scale-95 transition-all">Klaim Promo →</button>
                  @endif
                </div>
              </div>
            @endforeach

          </div>
        </div>

        <!-- Navigation Arrow Buttons -->
        <button id="promo-prev-btn" aria-label="Sebelumnya" class="banner-arrow-btn">
          <i data-lucide="chevron-left" class="h-5 w-5"></i>
        </button>
        <button id="promo-next-btn" aria-label="Selanjutnya" class="banner-arrow-btn">
          <i data-lucide="chevron-right" class="h-5 w-5"></i>
        </button>

        <!-- Slider Dots Indicator -->
        <div class="absolute right-6 bottom-6 flex gap-2" id="promo-dots-container">
          @foreach($promos as $idx => $promo)
            <button class="promo-dot {{ $idx === 0 ? 'active' : '' }}" id="dot-{{ $idx }}" 
                    onclick="goToSlide({{ $idx }})" aria-label="Slide {{ $idx + 1 }}"
                    style="width:0.625rem;height:0.625rem;border-radius:50%;border:none;cursor:pointer;background:{{ $idx === 0 ? '#ff007f' : '#cbd5e1' }};transition:all 0.2s"></button>
          @endforeach
        </div>
      </div>

      <!-- 2. FLASH SALE BANNER & LIVE COUNTDOWN -->
      @if($flashSaleShow === 'true')
      <div id="homepage-flash-sale-card" class="mt-8 overflow-hidden rounded-3xl p-6 md:p-8 text-white relative neup-dark-flat border border-slate-800 shadow-xl">
        <div class="absolute top-0 right-0 p-8 opacity-10 pointer-events-none flash-sale-flame-container">
          <i data-lucide="flame" class="h-44 w-44 text-[#ff007f] animate-pulse"></i>
        </div>
        
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative z-10">
          <div class="text-left">
            <div class="flex items-center gap-2.5">
              <span class="flex h-7 w-7 items-center justify-center rounded-xl bg-[#ff007f] animate-bounce shadow-md">
                <i data-lucide="flame" class="h-4.5 w-4.5 text-white"></i>
              </span>
              <span class="text-xs font-black text-[#ff007f] uppercase tracking-widest text-neon-pink">Flash Sale Kilat!</span>
            </div>
            <h3 class="mt-2.5 text-xl md:text-2xl font-black text-slate-100 tracking-tight">{{ $flashSaleTitle }}</h3>
            <p class="mt-1 text-xs text-slate-400">{{ $flashSaleDescription }}</p>
            
            <div class="mt-5 flex items-center gap-3">
              <span class="text-xs text-slate-400 font-bold uppercase tracking-wider flex items-center gap-1.5 animate-pulse">
                <i data-lucide="clock" class="h-3.5 w-3.5 text-rose-400"></i> Waktu Berakhir:
              </span>
              <div class="flex gap-2 font-mono text-xs font-black" id="countdown-timer-container" data-end="{{ $flashSaleEnd }}">
                <span id="cd-hours" class="neup-dark-pressed-sm rounded-xl px-3.5 py-2.5 text-amber-400 border border-slate-900/40">00</span>
                <span class="text-slate-400 self-center font-bold">:</span>
                <span id="cd-minutes" class="neup-dark-pressed-sm rounded-xl px-3.5 py-2.5 text-amber-400 border border-slate-900/40">00</span>
                <span class="text-slate-400 self-center font-bold">:</span>
                <span id="cd-seconds" class="neup-dark-pressed-sm rounded-xl px-3.5 py-2.5 text-rose-400 border border-slate-900/40 animate-pulse">00</span>
              </div>
            </div>
          </div>

          <a href="{{ route('game.detail', $flashSaleSlug) }}" id="flash-sale-action-btn" class="text-white font-extrabold text-xs tracking-wide px-6 py-3.5 rounded-xl neup-orange-flat hover:neup-orange-pressed active:scale-98 transition-all flex items-center gap-1.5 cursor-pointer decoration-none">
            {{ $flashSaleButtonText }} <i data-lucide="arrow-right" class="h-4 w-4"></i>
          </a>
        </div>
      </div>
      @endif


      <!-- 3. SEARCH & CATEGORIES -->
      <div class="mt-12">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
          <div class="text-left">
            <h2 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">Jelajahi Dunia Game Lebih Hemat</h2>
            <p class="text-xs text-slate-500 mt-1 font-medium">Beli diamond, voucher legal, dan instant delivery.</p>
          </div>
          
          <!-- Search Input -->
          <form onsubmit="event.preventDefault();" class="relative max-w-sm w-full m-0 p-0">
            <i data-lucide="search" class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
            <input
              type="text"
              id="game-search-input"
              placeholder="Cari game populer atau voucher..."
              class="w-full rounded-2xl py-3.5 pl-11 pr-4 text-xs font-bold placeholder:text-slate-400 text-slate-700 neup-pressed-sm focus:outline-none border-t border-l border-white/40"
            />
          </form>
        </div>

        <!-- Category Filter Shortcuts -->
        <div class="mt-6 flex flex-wrap gap-3 overflow-x-auto pb-3.5 scrollbar-none" id="categories-container">
          <button onclick="filterCategory('all', this)" class="category-btn active rounded-xl px-4 py-2.5 text-xs font-black transition-all cursor-pointer bg-[#ff007f] text-white border-none shadow-sm">
            Semua
          </button>
          <button onclick="filterCategory('mobile', this)" class="category-btn rounded-xl px-4 py-2.5 text-xs font-black text-slate-600 neup-flat-sm hover:neup-pressed-sm transition-all cursor-pointer border-none bg-transparent">
            Mobile
          </button>
          <button onclick="filterCategory('pc', this)" class="category-btn rounded-xl px-4 py-2.5 text-xs font-black text-slate-600 neup-flat-sm hover:neup-pressed-sm transition-all cursor-pointer border-none bg-transparent">
            PC
          </button>
          <button onclick="filterCategory('popular', this)" class="category-btn rounded-xl px-4 py-2.5 text-xs font-black text-slate-600 neup-flat-sm hover:neup-pressed-sm transition-all cursor-pointer border-none bg-transparent">
            Populer
          </button>
          <button onclick="filterCategory('voucher', this)" class="category-btn rounded-xl px-4 py-2.5 text-xs font-black text-slate-600 neup-flat-sm hover:neup-pressed-sm transition-all cursor-pointer border-none bg-transparent">
            Voucher
          </button>
        </div>

        <!-- 4. GAME GRID CARD SECTION -->
        <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6" id="games-grid">
          
          @foreach($games as $game)
            <div class="game-card neup-flat border border-white/50 p-3 rounded-3xl flex flex-col justify-between cursor-pointer transition-all hover:scale-102 hover:shadow-md" 
                 data-category="{{ $game->category }}" 
                 data-name="{{ strtolower($game->name) }}"
                 onclick="window.location.href='{{ route('game.detail', $game->slug) }}'">
              <div>
                <div class="relative rounded-2xl overflow-hidden h-36 mb-3.5 bg-slate-100">
                  <img src="{{ $game->thumbnail_url }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
                  @if($game->has_discount)
                    <span class="absolute top-3 left-3 bg-[#ff007f] text-white text-[9px] font-black px-2 py-1 rounded-lg uppercase tracking-wider shadow-sm">DISKON</span>
                  @endif
                </div>
                <h4 class="text-xs font-black text-slate-800 text-left leading-snug truncate">{{ $game->name }}</h4>
                <div class="flex items-center gap-1.5 mt-1.5">
                  <i data-lucide="star" class="h-3 w-3 text-amber-500 fill-amber-500"></i>
                  <span class="text-[10px] text-slate-500 font-bold">{{ $game->rating }} • {{ $game->total_sold }} Terjual</span>
                </div>
              </div>
              <div class="mt-4 pt-3.5 border-t border-slate-100/80 flex items-center justify-between">
                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Mulai</span>
                <span class="text-xs font-black text-[#ff007f]">Rp {{ number_format($game->nominals->min('price'), 0, ',', '.') }}</span>
              </div>
            </div>
          @endforeach

        </div>
      </div>

      <!-- 4.5 FEATURED GAME ACCOUNTS SECTION -->
      @if(isset($featuredAccounts) && count($featuredAccounts) > 0)
        <section class="mt-16 text-left">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div class="flex items-center gap-2">
              <span class="h-7 w-7 rounded-lg text-white font-black text-xs flex items-center justify-center neup-orange-flat shadow-sm flex-shrink-0"><i data-lucide="key-round" class="h-4 w-4"></i></span>
              <div>
                <h3 class="text-sm md:text-base font-black text-slate-800">Koleksi Akun Game Pilihan</h3>
                <p class="text-[10px] text-slate-400 font-bold mt-0.5">Beli akun game premium dengan jaminan aman & garansi instan.</p>
              </div>
            </div>
            <a href="{{ route('accounts.index') }}" class="text-[10px] font-black text-indigo-600 bg-white border border-white/40 rounded-xl px-4 py-2.5 cursor-pointer neup-flat-sm hover:neup-pressed-xs decoration-none w-fit">
              Lihat Semua Akun
            </a>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 text-left">
            @foreach($featuredAccounts as $account)
              <div onclick="window.location.href='{{ route('accounts.detail', $account->slug) }}'" class="group relative rounded-3xl border border-white/50 neup-flat p-4.5 bg-white cursor-pointer select-none flex flex-col justify-between hover:neup-pressed-xs active:scale-[0.98]">
                
                <!-- Account Badge Featured/Tag -->
                @if($account->featured)
                  <div class="absolute top-3 right-3 z-10 scale-90 select-none pointer-events-none">
                    <span class="bg-amber-500 text-white text-[8px] font-black px-2 py-0.8 rounded-lg uppercase tracking-wider shadow-sm flex items-center gap-0.5"><i data-lucide="star" class="h-3 w-3 fill-white"></i> HOT</span>
                  </div>
                @endif

                <div>
                  <!-- Account Image Banner Thumbnail -->
                  <div class="relative aspect-video w-full overflow-hidden rounded-2xl bg-slate-100 shadow-inner">
                    @if($account->images && count($account->images) > 0)
                      <img src="{{ $account->images[0] }}" alt="{{ $account->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                    @else
                      <div class="h-full w-full flex items-center justify-center font-black text-slate-300">Gambar Galeri</div>
                    @endif
                  </div>

                  <!-- Specs details -->
                  <div class="mt-3.5">
                    <!-- Game Name Badge Above Title -->
                    <span class="inline-block text-[8px] font-black uppercase text-indigo-600 bg-indigo-50 border border-indigo-100/35 px-2 py-0.5 rounded mb-1.5">
                      {{ $account->game->name }}
                    </span>
                    
                    <!-- Headline Account Title -->
                    <h3 class="text-xs md:text-sm font-black text-slate-800 leading-snug line-clamp-2 h-9 text-left group-hover:text-indigo-600 transition-colors" title="{{ $account->title }}">
                      {{ $account->title }}
                    </h3>
                    
                    <!-- Spec Flow Mini-Badges -->
                    <div class="mt-3.5 flex flex-wrap gap-1.5 text-[9px] font-extrabold text-slate-500">
                      <span class="px-2 py-0.8 rounded-lg bg-slate-50 border border-slate-150 text-slate-600 flex items-center gap-1" title="Rank Account">
                        <i data-lucide="award" class="h-3 w-3 text-indigo-500"></i>
                        <span>{{ $account->rank }}</span>
                      </span>
                      <span class="px-2 py-0.8 rounded-lg bg-slate-50 border border-slate-150 text-slate-600 flex items-center gap-1" title="Skin Count">
                        <i data-lucide="palette" class="h-3 w-3 text-pink-500"></i>
                        <span>{{ $account->skin_count }} Skins</span>
                      </span>
                      <span class="px-2 py-0.8 rounded-lg bg-slate-50 border border-slate-150 text-slate-600 flex items-center gap-1" title="Bind Status">
                        <i data-lucide="shield-alert" class="h-3 w-3 text-emerald-500"></i>
                        <span class="truncate max-w-[70px]">{{ $account->bind_status }}</span>
                      </span>
                    </div>
                  </div>
                </div>

                <!-- Price and CTA -->
                <div class="mt-4.5 border-t border-slate-200/50 pt-3 flex items-center justify-between">
                  <div class="flex flex-col">
                    <span class="text-[8px] text-slate-400 uppercase font-black tracking-wider leading-none">Harga Jual</span>
                    <span class="text-xs md:text-sm font-black text-[#ff007f] font-mono mt-1">Rp {{ number_format($account->price, 0, ',', '.') }}</span>
                  </div>
                  <span class="flex h-8 w-8 items-center justify-center rounded-2xl text-white neup-orange-flat group-hover:scale-105 active:scale-95 transition-all shadow-sm">
                    <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i>
                  </span>
                </div>

              </div>
            @endforeach
          </div>
        </section>
      @endif

      <!-- 5. PROMO / PAYMENT DISCOUNTS STATS -->
      <section id="payment-stats-section" class="mt-16 rounded-3xl p-6 md:p-8 neup-flat border border-white/50">
        <div class="flex items-center gap-2 mb-6 text-left">
          <span class="h-7 w-7 rounded-lg text-white font-black text-xs flex items-center justify-center neup-orange-flat shadow-sm">%</span>
          <h3 class="text-sm md:text-base font-black text-slate-800">Hemat Lebih Banyak dengan Diskon Metode Pembayaran</h3>
        </div>
        
        <div class="grid md:grid-cols-3 gap-5">
          <div class="payment-stat-card payment-stat-card-cyan rounded-2xl p-4.5 neup-flat-sm border border-white/40 flex items-start gap-4 text-left">
            <span class="h-9 w-9 rounded-xl neup-flat-sm flex items-center justify-center font-black text-cyan-500 bg-white shadow-sm flex-shrink-0"><i data-lucide="wallet" class="h-4.5 w-4.5"></i></span>
            <div>
              <p class="text-xs font-black text-slate-800 font-bold">Bebas Biaya Admin DANA</p>
              <p class="text-[10px] text-slate-500 mt-1 leading-relaxed font-semibold">Nikmati penghematan instan Rp 0 biaya layanan di setiap top up.</p>
            </div>
          </div>
          <div class="payment-stat-card payment-stat-card-fuchsia rounded-2xl p-4.5 neup-flat-sm border border-white/40 flex items-start gap-4 text-left">
            <span class="h-9 w-9 rounded-xl neup-flat-sm flex items-center justify-center font-black text-fuchsia-500 bg-white shadow-sm flex-shrink-0"><i data-lucide="qr-code" class="h-4.5 w-4.5"></i></span>
            <div>
              <p class="text-xs font-black text-slate-800 font-bold">QRIS Scan Hemat & Cepat</p>
              <p class="text-[10px] text-slate-500 mt-1 leading-relaxed font-semibold">Dompet digital Gopay, OVO, Dana, LinkAja, ShopeePay gratis biaya admin.</p>
            </div>
          </div>
          <div class="payment-stat-card payment-stat-card-indigo rounded-2xl p-4.5 neup-flat-sm border border-white/40 flex items-start gap-4 text-left">
            <span class="h-9 w-9 rounded-xl neup-flat-sm flex items-center justify-center font-black text-indigo-500 bg-white shadow-sm flex-shrink-0"><i data-lucide="landmark" class="h-4.5 w-4.5"></i></span>
            <div>
              <p class="text-xs font-black text-slate-800 font-bold">Transfer Virtual Account Resmi</p>
              <p class="text-[10px] text-slate-500 mt-1 leading-relaxed font-semibold">BCA & Mandiri VA terdaftar resmi, hanya Rp 1.000 flat admin fee.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- 7. FAQ ACCORDION SECTION -->
      <section class="mt-16 border-t border-slate-300/40 pt-16">
        <div class="max-w-3xl mx-auto">
          <div class="text-center mb-10">
            <h3 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">Paling Sering Ditanyakan</h3>
            <p class="text-xs text-slate-500 mt-1 font-bold">Temukan jawaban cepat seputar kendala order, refund, dan cara bayar.</p>
          </div>

          <div class="space-y-4" id="faqs-accordion">
            
            <div class="faq-item rounded-2xl neup-flat border border-white/50 overflow-hidden text-left transition-all">
              <button onclick="toggleFaq(0, this)" class="w-full flex items-center justify-between p-5 text-left border-none bg-transparent cursor-pointer font-black text-xs text-slate-700">
                <span>Bagaimana cara melakukan top up di GameTopup?</span>
                <i data-lucide="chevron-down" class="h-4 w-4 text-slate-400 transition-all dropdown-chevron"></i>
              </button>
              <div id="faq-ans-0" class="faq-answer hidden px-5 pb-5 pt-1 text-xs text-slate-500 leading-relaxed font-semibold">
                Cukup pilih game yang ingin Anda top up, masukkan User ID & Zone ID (jika ada), pilih jumlah nominal item yang diinginkan, pilih metode pembayaran, masukkan kode voucher promo jika ada, dan klik Beli Sekarang. Lakukan pembayaran sesuai petunjuk pembayaran.
              </div>
            </div>

            <div class="faq-item rounded-2xl neup-flat border border-white/50 overflow-hidden text-left transition-all">
              <button onclick="toggleFaq(1, this)" class="w-full flex items-center justify-between p-5 text-left border-none bg-transparent cursor-pointer font-black text-xs text-slate-700">
                <span>Berapa lama proses pengisian diamond/item game?</span>
                <i data-lucide="chevron-down" class="h-4 w-4 text-slate-400 transition-all dropdown-chevron"></i>
              </button>
              <div id="faq-ans-1" class="faq-answer hidden px-5 pb-5 pt-1 text-xs text-slate-500 leading-relaxed font-semibold">
                Hampir seluruh transaksi kami diselesaikan secara otomatis dalam waktu 1-3 menit setelah pembayaran Anda berhasil didepositkan. Jika ada antrian server game, proses terkadang dapat membutuhkan waktu hingga 15 menit.
              </div>
            </div>

            <div class="faq-item rounded-2xl neup-flat border border-white/50 overflow-hidden text-left transition-all">
              <button onclick="toggleFaq(2, this)" class="w-full flex items-center justify-between p-5 text-left border-none bg-transparent cursor-pointer font-black text-xs text-slate-700">
                <span>Metode pembayaran apa saja yang didukung?</span>
                <i data-lucide="chevron-down" class="h-4 w-4 text-slate-400 transition-all dropdown-chevron"></i>
              </button>
              <div id="faq-ans-2" class="faq-answer hidden px-5 pb-5 pt-1 text-xs text-slate-500 leading-relaxed font-semibold">
                Kami mendukung berbagai pilihan metode pembayaran instan populer di Indonesia, meliputi E-Wallet (DANA, OVO, ShopeePay), QRIS Kode Standar nasional Indonesia, dan Virtual Account Transfer bank utama (BCA, Mandiri, BNI, BRI).
              </div>
            </div>

            <div class="faq-item rounded-2xl neup-flat border border-white/50 overflow-hidden text-left transition-all">
              <button onclick="toggleFaq(3, this)" class="w-full flex items-center justify-between p-5 text-left border-none bg-transparent cursor-pointer font-black text-xs text-slate-700">
                <span>Apakah ada tambahan biaya admin?</span>
                <i data-lucide="chevron-down" class="h-4 w-4 text-slate-400 transition-all dropdown-chevron"></i>
              </button>
              <div id="faq-ans-3" class="faq-answer hidden px-5 pb-5 pt-1 text-xs text-slate-500 leading-relaxed font-semibold">
                Kami menerapkan biaya admin yang transparan dan sangat minim. Untuk QRIS gratis biaya admin, OVO dikenakan Rp 200, dan Transfer Virtual Account bank dikenakan Rp 1.000 per transaksi.
              </div>
            </div>

            <div class="faq-item rounded-2xl neup-flat border border-white/50 overflow-hidden text-left transition-all">
              <button onclick="toggleFaq(4, this)" class="w-full flex items-center justify-between p-5 text-left border-none bg-transparent cursor-pointer font-black text-xs text-slate-700">
                <span>Dapatkah saya membatalkan atau me-refund transaksi?</span>
                <i data-lucide="chevron-down" class="h-4 w-4 text-slate-400 transition-all dropdown-chevron"></i>
              </button>
              <div id="faq-ans-4" class="faq-answer hidden px-5 pb-5 pt-1 text-xs text-slate-500 leading-relaxed font-semibold">
                Transaksi game top-up bersifat final dan langsung diproses secara otomatis setelah pembayaran terdeteksi. Silakan periksa kembali kecocokan User ID dan server Anda sebelum membuat pesanan, karena transaksi yang salah kirim akibat kesalahan input User ID tidak dapat dikembalikan atau di-refund.
              </div>
            </div>
          </div>
        </div>
      </section>

    </div>
  </div>

  <script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.promo-slide');
    const dots = document.querySelectorAll('.promo-dot');

    function goToSlide(idx) {
      if (slides.length === 0) return;
      
      const track = document.getElementById('promo-slide-track');
      if (track) {
        track.style.transform = `translate3d(-${idx * 100}%, 0, 0)`;
      }

      slides.forEach((s, i) => {
        if (i === idx) {
          s.classList.add('active');
        } else {
          s.classList.remove('active');
        }
      });

      dots.forEach((d, i) => {
        if (i === idx) {
          d.classList.add('active');
          d.style.background = '#ff007f';
        } else {
          d.classList.remove('active');
          d.style.background = '#cbd5e1';
        }
      });
      currentSlide = idx;
    }

    const container = document.getElementById('promo-banner-container');
    const track = document.getElementById('promo-slide-track');
    
    let isDragging = false;
    let startX = 0;
    let currentX = 0;
    let walk = 0;
    let autoPlayTimer = null;

    function startAutoPlay() {
      stopAutoPlay();
      autoPlayTimer = setInterval(() => {
        let next = currentSlide + 1;
        if (next >= slides.length) next = 0;
        goToSlide(next);
      }, 6000);
    }

    function stopAutoPlay() {
      if (autoPlayTimer) {
        clearInterval(autoPlayTimer);
      }
    }

    document.getElementById('promo-prev-btn')?.addEventListener('click', () => {
      let prev = currentSlide - 1;
      if (prev < 0) prev = slides.length - 1;
      goToSlide(prev);
      startAutoPlay();
    });

    document.getElementById('promo-next-btn')?.addEventListener('click', () => {
      let next = currentSlide + 1;
      if (next >= slides.length) next = 0;
      goToSlide(next);
      startAutoPlay();
    });

    container?.addEventListener('touchstart', (e) => {
      isDragging = true;
      startX = e.touches[0].clientX;
      stopAutoPlay();
      if (track) track.style.transition = 'none';
    }, { passive: true });

    container?.addEventListener('touchmove', (e) => {
      if (!isDragging || !track) return;
      currentX = e.touches[0].clientX;
      walk = currentX - startX;
      
      const slideWidth = container.offsetWidth;
      const baseOffset = -currentSlide * slideWidth;
      const finalOffset = baseOffset + walk;
      
      track.style.transform = `translate3d(${finalOffset}px, 0, 0)`;
    }, { passive: true });

    container?.addEventListener('touchend', (e) => {
      if (!isDragging || !track) return;
      isDragging = false;
      track.style.transition = 'transform 0.6s cubic-bezier(0.25, 1, 0.5, 1)';
      
      const slideWidth = container.offsetWidth;
      const threshold = slideWidth * 0.2;
      
      if (Math.abs(walk) > threshold) {
        if (walk > 0) {
          let prev = currentSlide - 1;
          if (prev < 0) prev = slides.length - 1;
          goToSlide(prev);
        } else {
          let next = currentSlide + 1;
          if (next >= slides.length) next = 0;
          goToSlide(next);
        }
      } else {
        goToSlide(currentSlide);
      }
      walk = 0;
      startAutoPlay();
    });

    container?.addEventListener('mousedown', (e) => {
      isDragging = true;
      startX = e.clientX;
      stopAutoPlay();
      if (track) track.style.transition = 'none';
      container.style.cursor = 'grabbing';
      e.preventDefault();
    });

    window.addEventListener('mousemove', (e) => {
      if (!isDragging || !track || !container) return;
      currentX = e.clientX;
      walk = currentX - startX;
      
      const slideWidth = container.offsetWidth;
      const baseOffset = -currentSlide * slideWidth;
      const finalOffset = baseOffset + walk;
      
      track.style.transform = `translate3d(${finalOffset}px, 0, 0)`;
    });

    window.addEventListener('mouseup', (e) => {
      if (!isDragging || !track || !container) return;
      isDragging = false;
      track.style.transition = 'transform 0.6s cubic-bezier(0.25, 1, 0.5, 1)';
      container.style.cursor = 'grab';
      
      const slideWidth = container.offsetWidth;
      const threshold = slideWidth * 0.2;
      
      if (Math.abs(walk) > threshold) {
        if (walk > 0) {
          let prev = currentSlide - 1;
          if (prev < 0) prev = slides.length - 1;
          goToSlide(prev);
        } else {
          let next = currentSlide + 1;
          if (next >= slides.length) next = 0;
          goToSlide(next);
        }
      } else {
        goToSlide(currentSlide);
      }
      walk = 0;
      startAutoPlay();
    });

    if (container) container.style.cursor = 'grab';
    startAutoPlay();

    function toggleFaq(idx, btn) {
      const answer = document.getElementById(`faq-ans-${idx}`);
      const chevron = btn.querySelector('.dropdown-chevron');
      if (answer) {
        answer.classList.toggle('hidden');
        if (chevron) {
          const isHidden = answer.classList.contains('hidden');
          chevron.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(180deg)';
        }
      }
    }

    function filterCategory(category, btn) {
      document.querySelectorAll('.category-btn').forEach(b => {
        b.classList.remove('active', 'neup-pressed-sm');
        b.classList.add('neup-flat-sm', 'text-slate-600');
        b.style.background = 'transparent';
        b.style.color = '#475569';
      });

      btn.classList.add('active');
      btn.style.background = '#ff007f';
      btn.style.color = '#ffffff';

      const cards = document.querySelectorAll('.game-card');
      let visibleIdx = 0;

      cards.forEach(card => {
        const cat = card.getAttribute('data-category');
        
        card.classList.remove('game-card-animate-in');
        card.style.opacity = '0';
        card.style.transform = 'translateY(24px) scale(0.96)';
        
        if (category === 'all' || cat === category) {
          card.style.display = 'flex';
          
          const delay = Math.min(visibleIdx * 35, 280);
          card.style.animationDelay = `${delay}ms`;
          
          void card.offsetWidth;
          
          card.classList.add('game-card-animate-in');
          visibleIdx++;
        } else {
          card.style.display = 'none';
        }
      });
    }

    const searchInput = document.getElementById('game-search-input');
    if (searchInput) {
      searchInput.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase().trim();
        const cards = document.querySelectorAll('.game-card');
        let matches = 0;

        cards.forEach(card => {
          const name = card.getAttribute('data-name');
          if (name.includes(query)) {
            card.style.display = 'flex';
            matches++;
          } else {
            card.style.display = 'none';
          }
        });

        let emptyMsg = document.getElementById('search-empty-msg');
        if (matches === 0) {
          if (!emptyMsg) {
            emptyMsg = document.createElement('div');
            emptyMsg.id = 'search-empty-msg';
            emptyMsg.className = 'col-span-full py-12 text-center text-slate-400 font-bold text-xs';
            emptyMsg.innerHTML = '<i data-lucide="alert-circle" class="h-8 w-8 mx-auto mb-2 text-slate-300"></i>Game atau voucher tidak ditemukan';
            document.getElementById('games-grid').appendChild(emptyMsg);
            if (window.lucide) window.lucide.createIcons();
          }
        } else if (emptyMsg) {
          emptyMsg.remove();
        }
      });
    }


    document.addEventListener('DOMContentLoaded', () => {
      const cards = document.querySelectorAll('.game-card');
      cards.forEach((card, idx) => {
        card.classList.remove('game-card-animate-in');
        card.style.opacity = '0';
        card.style.transform = 'translateY(24px) scale(0.96)';
        const delay = Math.min(idx * 35, 280);
        card.style.animationDelay = `${delay}ms`;
        void card.offsetWidth;
        card.classList.add('game-card-animate-in');
      });
    });
  </script>
@endsection
