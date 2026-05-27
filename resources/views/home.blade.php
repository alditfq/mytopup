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
</style>
@endpush

@section('content')
  <div class="flex-1 pb-16" id="landing-page">
    
    <!-- 1. ANNOUNCEMENT & PROMO MARQUEE -->
    @if($marqueeActive !== 'false')
    <section class="bg-gradient-to-r from-cyan-500 via-indigo-600 to-fuchsia-600 py-2.5 text-white overflow-hidden text-xs font-semibold shadow-sm">
      <div class="flex animate-marquee items-center whitespace-nowrap" id="announcement-marquee">
        @if($marqueeItems->isNotEmpty())
          @foreach($marqueeItems as $mItem)
            <span class="px-8">{{ $mItem->text }}</span>
            <span class="opacity-50">•</span>
          @endforeach
          {{-- Duplicate for seamless loop --}}
          @foreach($marqueeItems as $mItem)
            <span class="px-8">{{ $mItem->text }}</span>
            <span class="opacity-50">•</span>
          @endforeach
        @else
          <span class="px-8">🚀 Selamat Datang di {{ $shopName }}! Top up diamonds & game voucher tercepat, termurah, dan terpercaya otomatis 24 jam! 🚀</span>
          <span class="px-8 opacity-50">•</span>
          <span class="px-8">🚀 Selamat Datang di {{ $shopName }}! Top up diamonds & game voucher tercepat, termurah, dan terpercaya otomatis 24 jam! 🚀</span>
        @endif
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
            <h3 class="mt-2.5 text-xl md:text-2xl font-black text-slate-100 tracking-tight">Sabet Diskon Game Terpopuler Akhir Pekan</h3>
            <p class="mt-1 text-xs text-slate-400">Diamond, token, dan Welkin Moon ready diskon kilat, instan terkirim secara otomatis.</p>
            
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

          <a href="{{ route('game.detail', 'mobile-legends') }}" class="text-white font-extrabold text-xs tracking-wide px-6 py-3.5 rounded-xl neup-orange-flat hover:neup-orange-pressed active:scale-98 transition-all flex items-center gap-1.5 cursor-pointer decoration-none">
            Cek Flash Sale MLBB <i data-lucide="arrow-right" class="h-4 w-4"></i>
          </a>
        </div>
      </div>

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

      <!-- 6. TESTIMONIALS SECTION -->
      <section class="mt-16">
        @php
          $getAvatarClass = function($username) {
              $firstChar = strtoupper(substr($username, 0, 1));
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
              return $bgColors[$firstChar] ?? 'bg-slate-500 text-slate-50 border-slate-600';
          };
        @endphp

        <div class="text-center max-w-xl mx-auto">
          <h3 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">Lebih dari 100K Gamer Puas</h3>
          <p class="text-xs text-slate-500 mt-1 font-bold">Ulasan jujur dari komunitas gamer Indonesia mengenai kecepatan layanan kami.</p>
        </div>

        <div class="mt-8 overflow-hidden relative w-full py-4" id="testimonials-marquee-container">
          <!-- Gradient shadows overlay on edges for premium fading effect -->
          <div class="absolute inset-y-0 left-0 w-16 bg-gradient-to-r from-slate-50 to-transparent z-10 pointer-events-none"></div>
          <div class="absolute inset-y-0 right-0 w-16 bg-gradient-to-l from-slate-50 to-transparent z-10 pointer-events-none"></div>

          <div class="flex gap-6 animate-testimonial-marquee" id="testimonials-track">
            @foreach($testimonials as $testi)
              <div class="homepage-testimonial-card inline-flex flex-col justify-between text-left p-5 rounded-2xl neup-flat border border-white/50 w-[300px] min-w-[300px] shrink-0 whitespace-normal bg-white/80 backdrop-blur-sm shadow-sm transition-all duration-300">
                <p class="text-xs text-slate-600 font-bold leading-relaxed transition-colors duration-300">"{{ $testi->message }}"</p>
                <div class="mt-4 flex items-center gap-3">
                  <div class="testimonial-avatar h-9 w-9 rounded-xl flex items-center justify-center font-black text-sm border shadow-sm flex-shrink-0 {{ $getAvatarClass($testi->username) }}">
                    {{ strtoupper(substr($testi->username, 0, 1)) }}
                  </div>
                  <div>
                    <p class="text-xs font-black text-slate-800">{{ $testi->username }}</p>
                    <div class="flex items-center gap-1 mt-0.5">
                      <span class="text-[10px] text-indigo-600 font-extrabold transition-colors duration-300">{{ $testi->game_name }}</span>
                      <span class="text-[10px] text-slate-400 font-bold">•</span>
                      <span class="text-[10px] text-amber-500 font-extrabold flex items-center gap-0.5">★ {{ $testi->rating }}.0</span>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
            
            {{-- Duplicate for seamless loop --}}
            @foreach($testimonials as $testi)
              <div class="homepage-testimonial-card inline-flex flex-col justify-between text-left p-5 rounded-2xl neup-flat border border-white/50 w-[300px] min-w-[300px] shrink-0 whitespace-normal bg-white/80 backdrop-blur-sm shadow-sm transition-all duration-300">
                <p class="text-xs text-slate-600 font-bold leading-relaxed transition-colors duration-300">"{{ $testi->message }}"</p>
                <div class="mt-4 flex items-center gap-3">
                  <div class="testimonial-avatar h-9 w-9 rounded-xl flex items-center justify-center font-black text-sm border shadow-sm flex-shrink-0 {{ $getAvatarClass($testi->username) }}">
                    {{ strtoupper(substr($testi->username, 0, 1)) }}
                  </div>
                  <div>
                    <p class="text-xs font-black text-slate-800">{{ $testi->username }}</p>
                    <div class="flex items-center gap-1 mt-0.5">
                      <span class="text-[10px] text-indigo-600 font-extrabold transition-colors duration-300">{{ $testi->game_name }}</span>
                      <span class="text-[10px] text-slate-400 font-bold">•</span>
                      <span class="text-[10px] text-amber-500 font-extrabold flex items-center gap-0.5">★ {{ $testi->rating }}.0</span>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
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
            @foreach($faqs as $idx => $faq)
              <div class="faq-item rounded-2xl neup-flat border border-white/50 overflow-hidden text-left transition-all">
                <button onclick="toggleFaq({{ $idx }}, this)" class="w-full flex items-center justify-between p-5 text-left border-none bg-transparent cursor-pointer font-black text-xs text-slate-700">
                  <span>{{ $faq->question }}</span>
                  <i data-lucide="chevron-down" class="h-4 w-4 text-slate-400 transition-all dropdown-chevron"></i>
                </button>
                <div id="faq-ans-{{ $idx }}" class="faq-answer hidden px-5 pb-5 pt-1 text-xs text-slate-500 leading-relaxed font-semibold">
                  {{ $faq->answer }}
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </section>

    </div>
  </div>

  <script>
    // Slider functionality
    let currentSlide = 0;
    const slides = document.querySelectorAll('.promo-slide');
    const dots = document.querySelectorAll('.promo-dot');

    function goToSlide(idx) {
      if (slides.length === 0) return;
      
      // Shift slide track horizontally
      const track = document.getElementById('promo-slide-track');
      if (track) {
        track.style.transform = `translate3d(-${idx * 100}%, 0, 0)`;
      }

      // Update active slide classes
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

    // Drag/Swipe Gesture & Navigation Functionality
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

    // Touch events
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

    // Mouse events
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

    // FAQ Accordion functionality
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

    // Category filter functionality
    function filterCategory(category, btn) {
      // Toggle active class on buttons
      document.querySelectorAll('.category-btn').forEach(b => {
        b.classList.remove('active', 'neup-pressed-sm');
        b.classList.add('neup-flat-sm', 'text-slate-600');
        b.style.background = 'transparent';
        b.style.color = '#475569';
      });

      btn.classList.add('active');
      btn.style.background = '#ff007f';
      btn.style.color = '#ffffff';

      // Filter grid
      const cards = document.querySelectorAll('.game-card');
      let visibleIdx = 0;

      cards.forEach(card => {
        const cat = card.getAttribute('data-category');
        
        // Remove animation class & reset style first
        card.classList.remove('game-card-animate-in');
        card.style.opacity = '0';
        card.style.transform = 'translateY(24px) scale(0.96)';
        
        if (category === 'all' || cat === category) {
          card.style.display = 'flex';
          
          // Apply staggered animation delay (e.g. 35ms per card, up to max 280ms so it stays snappy!)
          const delay = Math.min(visibleIdx * 35, 280);
          card.style.animationDelay = `${delay}ms`;
          
          // Trigger reflow to restart animation
          void card.offsetWidth;
          
          card.classList.add('game-card-animate-in');
          visibleIdx++;
        } else {
          card.style.display = 'none';
        }
      });
    }

    // Search filter functionality
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

        // Show empty message if no matches
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

    // Animate game cards on initial load
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
