@extends('layouts.app')

@section('title', 'Dashboard Saya - GameTopup')

@section('content')
  <div class="flex-1 flex flex-col" id="dashboard-page">
    <div class="flex-1 py-8">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        <!-- Flash Messages -->
        @if(session('success'))
          <div class="mb-6 rounded-2xl bg-emerald-55 border border-emerald-100 p-4 text-xs font-bold text-emerald-600 text-left bg-white">
            {{ session('success') }}
          </div>
        @endif

        <!-- Header welcome banner -->
        <div class="rounded-3xl border border-white/50 p-6 md:p-8 neup-flat mb-8 bg-white flex flex-col md:flex-row items-center md:justify-between gap-6 text-left shadow-sm">
          <div class="flex items-center gap-4.5">
            @php
              $firstChar = strtoupper(substr($user->name, 0, 1));
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
            <div id="dash-avatar" class="h-16 w-16 rounded-2xl flex items-center justify-center font-black text-2xl border ring-4 ring-pink-500/25 shadow-md flex-shrink-0 {{ $avatarClass }}">
              {{ $firstChar }}
            </div>
            <div>
              <p class="text-[10px] text-slate-400 font-black uppercase tracking-wider leading-none">Selamat Datang</p>
              <h1 id="dash-username" class="mt-2 text-xl md:text-2xl font-black text-slate-800 leading-tight">{{ $user->name }}</h1>
              <p id="dash-email" class="text-xs text-slate-500 font-semibold mt-1">{{ $user->email }}</p>
            </div>
          </div>

          <a href="{{ route('profile') }}" class="rounded-xl px-5 py-3 text-xs font-black text-slate-600 border border-white/20 neup-flat-sm hover:neup-pressed-sm active:scale-95 transition-all bg-white decoration-none">
            Pengaturan Akun
          </a>
        </div>

        <!-- STATS PANEL GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 text-left">
          
          <!-- Total Transactions -->
          <div class="rounded-3xl border border-white/40 p-5 neup-flat bg-white relative overflow-hidden shadow-sm">
            <div class="absolute -right-3 -bottom-3 opacity-5 pointer-events-none">
              <i data-lucide="clipboard-list" class="h-28 w-28 text-slate-800"></i>
            </div>
            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest leading-none">Total Transaksi</p>
            <p id="dash-total-tx" class="mt-3.5 text-2xl font-black text-slate-900 font-mono">{{ $transactions->count() }}</p>
            <p class="text-[10px] text-slate-500 font-extrabold mt-2 flex items-center gap-1">
              ★ Dari awal pendaftaran akun
            </p>
          </div>

          <!-- Cashback saved -->
          <div class="rounded-3xl border border-white/40 p-5 neup-flat bg-white relative overflow-hidden shadow-sm">
            <div class="absolute -right-3 -bottom-3 opacity-5 pointer-events-none">
              <i data-lucide="tag" class="h-28 w-28 text-slate-800"></i>
            </div>
            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest leading-none">Akumulasi Cashback</p>
            <p id="dash-cashback" class="mt-3.5 text-2xl font-black text-slate-900 font-mono">Rp {{ number_format($user->cashback_saved, 0, ',', '.') }}</p>
            <p class="text-[10px] text-indigo-600 font-extrabold mt-2 flex items-center gap-1">
              💸 Hemat saldo telah terkumpul
            </p>
          </div>

        </div>

        <!-- CORE GRID Split layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
          
          <!-- LEFT PANEL: RIWAYAT TRANSAKSI (lg:col-span-8) -->
          <div class="lg:col-span-8 space-y-6 text-left">
            <div class="rounded-3xl border border-white/50 p-5 md:p-6 neup-flat bg-white shadow-sm">
              <div class="flex items-center gap-2.5 border-b border-slate-300/40 pb-3.5 mb-5">
                <i data-lucide="clipboard-list" class="h-5 w-5 text-pink-600"></i>
                <h3 class="text-sm font-black text-slate-800">Histori Transaksi Terakhir</h3>
              </div>

              <!-- History list -->
              <div class="space-y-4" id="dash-tx-history-list">
                @forelse($transactions as $tx)
                  <div class="neup-pressed-xs border border-white/30 rounded-2xl p-4.5 bg-transparent flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                      <div class="h-10 w-10 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0">
                        <img src="{{ $tx->game->thumbnail_url }}" class="h-full w-full object-cover">
                      </div>
                      <div>
                        <h4 class="text-xs font-black text-slate-800 leading-tight">{{ $tx->game->name }}</h4>
                        <p class="text-[10px] text-slate-400 font-bold mt-1">
                          {{ $tx->nominal_name }} • {{ $tx->created_at->format('d M Y, H:i') }}
                        </p>
                      </div>
                    </div>
                    <div class="flex items-center justify-between sm:justify-end gap-4.5 border-t sm:border-t-0 border-slate-100 pt-3.5 sm:pt-0">
                      <div class="text-left sm:text-right">
                        <p class="text-xs font-black text-slate-800 font-mono">Rp {{ number_format($tx->total_payment, 0, ',', '.') }}</p>
                        
                        @if($tx->status === 'success')
                          <span class="text-[9px] font-black text-emerald-600 uppercase tracking-wider block mt-1">✓ PAID</span>
                        @elseif($tx->status === 'waiting_delivery')
                          <span class="text-[9px] font-black text-amber-600 uppercase tracking-wider block mt-1 animate-pulse">⏳ PROCESS DELIVERY</span>
                        @elseif($tx->status === 'delivered')
                          <span class="text-[9px] font-black text-emerald-600 uppercase tracking-wider block mt-1">✓ DELIVERED</span>
                        @elseif($tx->status === 'failed')
                          <span class="text-[9px] font-black text-rose-600 uppercase tracking-wider block mt-1">✕ FAILED</span>
                        @else
                          <span class="text-[9px] font-black text-amber-600 uppercase tracking-wider block mt-1 animate-pulse">⏳ PENDING</span>
                        @endif
                      </div>
                      <a href="{{ route('status.search') }}?invoice={{ $tx->invoice }}" class="rounded-xl px-3.5 py-2.5 text-[10px] font-black text-indigo-600 neup-flat-sm hover:neup-pressed-xs transition-all decoration-none">
                        Rincian
                      </a>
                    </div>
                  </div>
                @empty
                  <div class="py-12 text-center text-slate-400 font-bold text-xs">
                    <i data-lucide="info" class="h-8 w-8 mx-auto mb-2 text-slate-350"></i>
                    Belum ada riwayat transaksi top-up.
                  </div>
                @endforelse
              </div>
            </div>
          </div>

          <!-- RIGHT PANEL: GAME FAVORIT (lg:col-span-4) -->
          <div class="lg:col-span-4 space-y-6 text-left">
            <div class="rounded-3xl border border-white/50 p-5 md:p-6 neup-flat bg-white shadow-sm">
              <div class="flex items-center gap-2.5 border-b border-slate-300/40 pb-3.5 mb-5">
                <i data-lucide="star" class="h-5 w-5 text-amber-500 fill-amber-500"></i>
                <h3 class="text-sm font-black text-slate-800">Game Terpopuler</h3>
              </div>

              <!-- Grid catalog list -->
              <div class="grid grid-cols-1 gap-3.5" id="dash-fav-games-grid">
                
                @forelse($popularGames as $game)
                  <div class="neup-pressed-sm p-3.5 rounded-2xl border border-white/40 flex items-center gap-3 cursor-pointer transition-all hover:neup-flat-sm" onclick="window.location.href='{{ route('game.detail', $game->slug) }}'">
                    <div class="h-10 w-10 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0">
                      <img src="{{ $game->thumbnail_url }}" alt="{{ $game->name }}" class="h-full w-full object-cover">
                    </div>
                    <div>
                      <h5 class="text-xs font-black text-slate-700 m-0">{{ $game->name }}</h5>
                      <p class="text-[9px] text-slate-400 font-bold m-0 mt-0.5">Top Up {{ $game->category === 'voucher' ? 'Voucher' : 'Diamonds / Item' }}</p>
                    </div>
                  </div>
                @empty
                  <div class="py-6 text-center text-slate-400 font-bold text-xs">
                    Belum ada game terdaftar.
                  </div>
                @endforelse

              </div>
            </div>
          </div>

        </div>

      </div>
    </div>
  </div>
@endsection
