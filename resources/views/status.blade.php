@extends('layouts.app')

@section('title', 'Lacak Status Transaksi - GameTopup')

@push('styles')
<style>
  /* Premium Snappy Transition for Neumorphic Cards (FAQ Style Alignment) */
  #transaction-status-page .neup-flat {
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
  }
</style>
@endpush

@section('content')
  <div class="flex-1 py-8" id="transaction-status-page">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
      
      <!-- Header Title -->
      <div class="text-center mb-8">
        <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl border border-white/50 neup-flat text-pink-650 mb-3.5 bg-white shadow-sm">
          <i data-lucide="clipboard-list" class="h-6 w-6 text-pink-600"></i>
        </div>
        <h1 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">Lacak Status Transaksi</h1>
        <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto font-bold leading-relaxed">Cukup masukkan nomor Invoice/Invois pesanan Anda untuk melacak pengisian diamond game.</p>
      </div>

      <!-- SEARCH FORM -->
      <div class="rounded-3xl border border-white/50 neup-flat p-5 md:p-6 mb-6 bg-white shadow-sm">
        <form action="{{ route('status.search') }}" method="GET" id="invoice-search-form" class="flex gap-2.5 m-0 p-0">
          <div class="relative flex-1">
            <i data-lucide="search" class="absolute left-3.5 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-slate-400"></i>
            <input
              type="text"
              name="invoice"
              id="search-invoice-input"
              value="{{ request('invoice') ?? (isset($transaction) ? $transaction->invoice : '') }}"
              placeholder="Masukkan No. Invoice (cth: INV-20260520-ML32)"
              class="w-full rounded-2xl border border-white/30 neup-pressed-xs py-3.5 pl-11 pr-4 text-xs font-bold text-slate-800 placeholder:text-slate-400 focus:outline-none bg-transparent"
              required
            />
          </div>
          <button
            type="submit"
            class="neup-orange-flat hover:neup-orange-pressed active:scale-95 text-white transition-all rounded-2xl px-6 py-3.5 flex items-center justify-center font-black text-xs cursor-pointer border-none shadow-sm"
          >
            Cari Invoice
          </button>
        </form>

        <!-- Search error alert box -->
        @error('invoice')
          <div id="search-invoice-error" class="mt-3 text-xs font-bold text-rose-500 flex items-center gap-1.5 pl-1 text-left">
            <i data-lucide="alert-triangle" class="h-4 w-4 text-rose-500"></i> 
            {{ $message }}
          </div>
        @enderror

        <!-- Quick click shortcuts lists -->
        <div id="search-history-shortcuts" class="mt-4 border-t border-slate-200/50 pt-3.5 text-left">
          <span class="text-[10px] text-slate-400 font-bold mr-2.5">Invois Terakhir Anda:</span>
          <a href="{{ route('status.search') }}?invoice=INV-20260520-FF91" class="text-[9px] font-black text-indigo-600 neup-flat-sm hover:neup-pressed-xs rounded-xl px-2.5 py-1.5 mr-2 font-mono decoration-none inline-block">INV-20260520-FF91</a>
          <a href="{{ route('status.search') }}?invoice=INV-20260523-ML32" class="text-[9px] font-black text-indigo-600 neup-flat-sm hover:neup-pressed-xs rounded-xl px-2.5 py-1.5 font-mono decoration-none inline-block">INV-20260523-ML32</a>
        </div>
      </div>

      <!-- RESULTS STATUS DETAILS DISPLAY CARD -->
      <div id="search-results-area">
        @if(isset($transaction))
          <div class="space-y-6">
            
            <!-- Result Overview Card -->
            <div class="rounded-3xl border border-white/50 neup-flat p-5 md:p-6 bg-white shadow-sm text-left">
              <div class="flex items-center justify-between border-b border-slate-300/40 pb-4.5 mb-5">
                <div>
                  <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest leading-none">ID Pemesanan</p>
                  <p class="text-sm font-black text-slate-800 font-mono mt-1.5 leading-none">{{ $transaction->invoice }}</p>
                </div>
                
                @if($transaction->status === 'success')
                  <span class="rounded-full bg-emerald-500/10 border border-emerald-500/15 px-3 py-1 text-[9px] font-black text-emerald-600 uppercase tracking-wider">
                    🎉 BERHASIL (PAID)
                  </span>
                @elseif($transaction->status === 'waiting_delivery')
                  <span class="rounded-full bg-amber-500/10 border border-amber-500/15 px-3 py-1 text-[9px] font-black text-amber-600 uppercase tracking-wider animate-pulse">
                    ⏳ MENUNGGU PENGIRIMAN
                  </span>
                @elseif($transaction->status === 'delivered')
                  <span class="rounded-full bg-emerald-500/10 border border-emerald-500/15 px-3 py-1 text-[9px] font-black text-emerald-600 uppercase tracking-wider">
                    ✅ AKUN TERKIRIM
                  </span>
                @elseif($transaction->status === 'failed')
                  <span class="rounded-full bg-rose-500/10 border border-rose-500/15 px-3 py-1 text-[9px] font-black text-rose-600 uppercase tracking-wider">
                    ✕ GAGAL
                  </span>
                @else
                  <span class="rounded-full bg-amber-500/10 border border-amber-500/15 px-3 py-1 text-[9px] font-black text-amber-600 uppercase tracking-wider animate-pulse">
                    ⏳ MENUNGGU BAYAR
                  </span>
                @endif
              </div>

              <!-- Details Split Grid -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-bold text-slate-500">
                <div class="neup-pressed-xs border border-white/30 rounded-2xl p-4 bg-transparent">
                  <p class="text-[9px] text-slate-400 font-bold leading-none mb-1">Game</p>
                  <p class="text-slate-800">{{ $transaction->game->name }}</p>
                </div>
                <div class="neup-pressed-xs border border-white/30 rounded-2xl p-4 bg-transparent">
                  @if($transaction->game_account_id)
                    <p class="text-[9px] text-slate-400 font-bold leading-none mb-1">Email Penerima Akun</p>
                    <p class="text-slate-800 font-mono">{{ $transaction->target_id }}</p>
                  @else
                    <p class="text-[9px] text-slate-400 font-bold leading-none mb-1">Player / Server ID</p>
                    <p class="text-slate-800 font-mono">{{ $transaction->target_id }}{{ $transaction->zone_id ? ' (' . $transaction->zone_id . ')' : '' }}</p>
                  @endif
                </div>
                <div class="neup-pressed-xs border border-white/30 rounded-2xl p-4 bg-transparent">
                  <p class="text-[9px] text-slate-400 font-bold leading-none mb-1">Item Diamond</p>
                  <p class="text-fuchsia-600 font-extrabold">{{ $transaction->nominal_name }}</p>
                </div>
                <div class="neup-pressed-xs border border-white/30 rounded-2xl p-4 bg-transparent">
                  <p class="text-[9px] text-slate-400 font-bold leading-none mb-1">Metode & Bayar</p>
                  <p class="text-slate-800">{{ $transaction->paymentMethod->name }} • Rp {{ number_format($transaction->total_payment, 0, ',', '.') }}</p>
                </div>
              </div>

              @if($transaction->status === 'pending')
                <div class="mt-6 pt-4 border-t border-slate-200/60">
                  <a href="{{ route('payment.waiting', $transaction->invoice) }}" class="block w-full text-center rounded-2xl py-3.5 text-xs font-black text-white neup-orange-flat hover:neup-orange-pressed active:scale-98 transition-all decoration-none">
                    Lanjutkan ke Halaman Pembayaran 🛡️
                  </a>
                </div>
              @endif
            </div>

            <!-- TIMELINE LOGS -->
            <div class="rounded-3xl border border-white/50 neup-flat p-5 md:p-6 bg-white shadow-sm text-left">
              <h3 class="text-xs font-black text-slate-800 border-b border-slate-300/40 pb-3 mb-5 uppercase tracking-widest">Riwayat Status Log Real-Time</h3>
              
              <div class="relative">
                <div class="absolute left-[13px] top-2 bottom-2 w-0.5 bg-slate-200"></div>

                <div class="space-y-6">
                  @foreach($transaction->status_logs as $log)
                    <div class="relative flex items-start gap-4">
                      <span class="z-10 flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-white font-bold text-[9px] shadow-sm flex-shrink-0">✓</span>
                      <div>
                        <p class="text-xs font-black text-slate-800 leading-snug">{{ $log['message'] }}</p>
                        <p class="text-[9px] text-slate-400 mt-1.5 leading-none font-extrabold flex items-center gap-1">
                          <i data-lucide="clock" class="h-3 w-3"></i> {{ $log['time'] }} WIB
                        </p>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>

          </div>
        @endif
      </div>

      <!-- CUSTOMER HELP SUPPORT FAQ -->
      <section class="mt-14 border-t border-slate-300/40 pt-10 text-center">
        <i data-lucide="help-circle" class="h-7 w-7 text-pink-600 mx-auto mb-2"></i>
        <p class="text-xs text-slate-500 font-extrabold">Mengalami Kendala Mengenai Invoice Anda?</p>
        <p class="text-[11px] text-slate-400 mt-1 max-w-sm mx-auto leading-relaxed font-semibold">
          Hubungi agen CS Live kami secara langsung untuk melakukan koreksi manual apabila ID Game Anda salah input atau saldo tertunda.
        </p>
        <a
          href="{{ route('support') }}"
          class="mt-4 inline-flex items-center gap-2 rounded-2xl px-5 py-3 text-xs font-black text-pink-600 neup-flat-sm hover:neup-pressed-xs active:scale-95 transition-all cursor-pointer bg-white decoration-none"
        >
          Buka Live Hub CS Chat
        </a>
      </section>

    </div>
  </div>
@endsection
