@extends('layouts.app')

@section('title', 'Beli Akun Game Premium Terpercaya - GameTopup')

@section('content')
  <div class="flex-1 py-8" id="accounts-marketplace-page">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      
      <!-- Page Header Banner -->
      <div class="rounded-3xl border border-white/50 p-6 md:p-8 neup-flat bg-white mb-8 text-left relative overflow-hidden">
        <div class="absolute right-0 top-0 p-8 opacity-5 pointer-events-none scale-150 hidden md:block">
          <i data-lucide="key-round" class="h-44 w-44 text-indigo-500"></i>
        </div>
        <div class="max-w-2xl">
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/30 text-[10px] font-extrabold uppercase text-indigo-600 tracking-wider mb-3">
            💎 PREMIUM GAME ACCOUNTS
          </span>
          <h1 class="text-2xl md:text-3xl font-black text-slate-800 leading-tight">Marketplace Akun Game Terpercaya</h1>
          <p class="text-xs text-slate-500 font-bold leading-relaxed mt-2">
            Beli akun game favoritmu dengan spesifikasi GG, rank tinggi, puluhan skin langka, dan 100% garansi aman. Data kredensial dikirim instan secara otomatis dan aman (terenkripsi) begitu pembayaran berhasil!
          </p>
        </div>
      </div>

      <!-- Flash Messages -->
      @if(session('error'))
        <div class="mb-6 rounded-2xl bg-rose-50 border border-rose-100 p-4 text-xs font-bold text-rose-600 text-left">
          {{ session('error') }}
        </div>
      @endif

      <!-- SEARCH & FILTERS CONTROLS -->
      <div class="rounded-3xl border border-white/50 p-5 md:p-6 neup-flat bg-white mb-8 text-left">
        <form action="{{ route('accounts.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 font-semibold text-xs text-slate-650">
          
          <!-- Search input -->
          <div class="flex flex-col gap-1.5 md:col-span-2">
            <label for="search" class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Cari Kata Kunci</label>
            <div class="relative w-full">
              <input
                type="text"
                name="search"
                id="search"
                value="{{ request('search') }}"
                placeholder="Cari MLBB GG, skin collector, legend rank..."
                class="w-full rounded-2xl py-3 px-4 pl-10 text-xs font-bold text-slate-700 neup-pressed-sm focus:outline-none border-t border-l border-white/40"
              />
              <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                <i data-lucide="search" class="h-4 w-4"></i>
              </div>
            </div>
          </div>

          <!-- Filter Game -->
          <div class="flex flex-col gap-1.5">
            <label for="game_id" class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Filter Berdasarkan Game</label>
            <select name="game_id" id="game_id" class="w-full rounded-2xl py-3 px-4 text-xs font-bold text-slate-700 neup-pressed-sm focus:outline-none border-t border-l border-white/40 cursor-pointer bg-white">
              <option value="all">Semua Game</option>
              @foreach($games as $game)
                <option value="{{ $game->id }}" {{ request('game_id') == $game->id ? 'selected' : '' }}>{{ $game->name }}</option>
              @endforeach
            </select>
          </div>

          <!-- Sort & Filter Actions -->
          <div class="flex flex-col gap-1.5">
            <label for="sort" class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Urutkan Harga</label>
            <div class="flex gap-2">
              <select name="sort" id="sort" class="w-full rounded-2xl py-3 px-4 text-xs font-bold text-slate-700 neup-pressed-sm focus:outline-none border-t border-l border-white/40 cursor-pointer bg-white">
                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga: Terendah</option>
                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi</option>
              </select>
              
              <button type="submit" class="text-white font-black text-xs rounded-2xl px-5 py-3 cursor-pointer neup-orange-flat hover:neup-orange-pressed active:scale-95 transition-all border-none">
                Filter
              </button>
              
              @if(request()->filled('search') || request()->filled('game_id') || request()->filled('sort'))
                <a href="{{ route('accounts.index') }}" class="flex items-center justify-center rounded-2xl border border-white/40 text-slate-500 hover:neup-pressed-xs bg-white px-3.5 cursor-pointer decoration-none transition-all active:scale-95 shadow-sm" title="Reset Filters">
                  <i data-lucide="x" class="h-4.5 w-4.5"></i>
                </a>
              @endif
            </div>
          </div>

        </form>
      </div>

      <!-- ACCOUNTS CARDS GRID -->
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6 text-left">
        @forelse($accounts as $account)
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
        @empty
          <div class="col-span-full py-16 text-center rounded-3xl border border-white/50 neup-flat bg-white">
            <div class="flex justify-center mb-3 text-slate-350">
              <i data-lucide="inbox" class="h-12 w-12"></i>
            </div>
            <p class="text-sm font-black text-slate-550">Belum ada akun game yang cocok dengan pencarian Anda.</p>
            <p class="text-xs text-slate-400 mt-1 font-bold">Cobalah ubah filter pencarian atau cari game populer lainnya.</p>
          </div>
        @endforelse
      </div>

    </div>
  </div>
@endsection
