@extends('layouts.app')

@section('title', 'Top Up ' . $game->name . ' - GameTopup')

@push('styles')
<style>
  /* Premium Active Selection Styles for checkout cards */
  .nominal-card.neup-pressed-sm {
    background-color: #ffeef4 !important;
    border: 2.5px solid #ff007f !important;
    box-shadow: inset 0 4px 10px rgba(255, 0, 127, 0.08) !important;
  }

  .payment-card.neup-pressed-sm {
    background-color: #ffeef4 !important;
    border: 2.5px solid #ff007f !important;
    box-shadow: inset 0 4px 10px rgba(255, 0, 127, 0.08) !important;
  }

  @media (max-width: 1023px) {
    #game-detail-page {
      padding-bottom: 9.5rem !important;
    }

    #checkout-left-panel {
      position: relative !important;
      z-index: 9999 !important;
    }

    #payment-summary-card {
      position: fixed !important;
      bottom: 0 !important;
      left: 0 !important;
      right: 0 !important;
      z-index: 9999 !important;
      border-radius: 1.75rem 1.75rem 0 0 !important;
      margin: 0 !important;
      box-shadow: 0 -8px 30px rgba(0, 0, 0, 0.7) !important;
      border: none !important;
      border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
      padding: 1rem 1.25rem !important;
      background: #0f1322 !important;
      transition: all 0.3s ease-in-out !important;
    }

    /* Hide base h3 header and decorator icon on mobile */
    #payment-summary-card > h3,
    #payment-summary-card > .absolute {
      display: none !important;
    }

    /* Hide details rows by default on mobile */
    #payment-summary-card #summary-details-rows {
      display: none !important;
    }

    /* Show details when expanded */
    #payment-summary-card.mobile-expanded #summary-details-rows {
      display: block !important;
      margin-bottom: 0.85rem !important;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
      padding-bottom: 0.85rem !important;
    }

    /* Rotate toggle chevron */
    #payment-summary-card.mobile-expanded #mobile-summary-chevron {
      transform: rotate(180deg) !important;
    }

    /* Rearrange price & button side-by-side */
    #payment-summary-action-area {
      border-top: none !important;
      padding-top: 0 !important;
      margin-top: 0 !important;
      display: flex !important;
      flex-direction: row !important;
      align-items: center !important;
      justify-content: space-between !important;
      width: 100% !important;
      gap: 1.25rem !important;
    }

    /* Adjust total price text */
    #payment-summary-total-price-wrapper {
      display: flex !important;
      flex-direction: column !important;
      align-items: flex-start !important;
      flex-shrink: 0 !important;
    }
    
    #payment-summary-total-price-wrapper span.text-xs {
      font-size: 8px !important;
      color: #94a3b8 !important;
      line-height: 1 !important;
      margin-between: 0 !important;
      margin-bottom: 0.25rem !important;
    }

    #summary-total-price {
      font-size: 1.15rem !important;
      line-height: 1 !important;
    }

    /* Buy button width */
    #btn-buy-now {
      margin-top: 0 !important;
      flex: 1 0 auto !important;
      width: auto !important;
      min-width: 150px !important;
      padding: 0.85rem 1.5rem !important;
      border-radius: 1.25rem !important;
    }

    /* Make sure validation error banner displays nicely above the sticky bar if triggered */
    #validation-error-banner {
      position: absolute !important;
      bottom: 100% !important;
      left: 1.25rem !important;
      right: 1.25rem !important;
      margin-bottom: 0.5rem !important;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4) !important;
      z-index: 10000 !important;
    }
  }
</style>
@endpush

@section('content')
  <div class="flex-1 py-8" id="game-detail-page">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      
      <!-- Navigation Breadcrumb -->
      <a href="{{ route('home') }}" class="group inline-flex items-center gap-2 px-4 py-2.5 text-xs font-black text-slate-600 neup-flat-sm hover:neup-pressed-sm cursor-pointer transition-all mb-8 rounded-xl bg-white text-left decoration-none">
        <i data-lucide="arrow-left" class="h-4 w-4 transition-transform group-hover:-translate-x-1 text-slate-500"></i>
        Kembali ke Beranda
      </a>

      <!-- Flash Messages -->
      @if(session('error'))
        <div class="mb-6 rounded-2xl bg-rose-50 border border-rose-100 p-4 text-xs font-bold text-rose-600 text-left">
          {{ session('error') }}
        </div>
      @endif

      <!-- Outer Form Wrapper -->
      <form action="{{ route('checkout') }}" method="POST" id="checkout-form" class="m-0 p-0">
        @csrf
        <input type="hidden" name="game_id" value="{{ $game->id }}">

        <!-- Outer Layout Split Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          
          <!-- LEFT PANEL (Game Info + Ringkasan) -->
          <div id="checkout-left-panel" class="lg:col-span-1 space-y-6">
            
            <!-- Game Card Description Panel -->
            <div class="overflow-hidden rounded-3xl border border-white/50 p-5 neup-flat bg-white">
              <div class="relative aspect-video w-full overflow-hidden rounded-2xl bg-slate-200 shadow-inner">
                <img id="game-banner-img" src="{{ $game->banner_url }}" alt="{{ $game->name }} Banner" class="h-full w-full object-cover" />
              </div>

              <div class="mt-5 text-left">
                <span id="game-dev-badge" class="inline-flex items-center rounded-lg bg-pink-500/10 px-2.5 py-1 text-[10px] font-extrabold text-pink-600 tracking-wider uppercase border border-pink-500/20 mb-2">
                  {{ $game->developer }}
                </span>
                <h1 id="game-title" class="text-lg md:text-xl font-black text-slate-800 leading-tight">{{ $game->name }}</h1>
                
                <div class="mt-3 flex items-center gap-4 text-xs font-bold text-slate-600">
                  <div class="flex items-center gap-1 text-amber-500">
                    <i data-lucide="star" class="h-4 w-4 fill-amber-500 text-amber-500"></i>
                    <span id="game-rating">{{ $game->rating }}</span>
                  </div>
                  <span class="text-slate-300">|</span>
                  <span><span id="game-sold">{{ $game->total_sold }}</span> Top-up Berhasil</span>
                </div>

                <div class="mt-4 border-t border-slate-300/40 pt-4 flex items-center gap-2.5 text-left neup-pressed-xs p-3.5 rounded-2xl border border-white/45 bg-transparent">
                  <i data-lucide="shield" class="h-4.5 w-4.5 text-emerald-600 flex-shrink-0"></i>
                  <p id="game-support-text" class="text-[10px] font-bold text-slate-500 leading-normal">
                    Proses Top Up langsung & aman. Terkoneksi API resmi developer game.
                  </p>
                </div>
              </div>
            </div>

            <!-- RINGKASAN + ACCORDION — STICKY -->
            <div style="position: sticky; top: 5rem; display: flex; flex-direction: column; gap: 1.5rem;">              <!-- RINGKASAN PEMBAYARAN -->
              <div id="payment-summary-card" class="rounded-3xl p-6 neup-dark-flat border border-slate-800 text-slate-100 relative overflow-hidden text-left bg-slate-900 shadow-2xl">
                <div class="absolute top-0 right-0 p-8 opacity-5 pointer-events-none">
                  <i data-lucide="ticket" class="h-44 w-44 text-fuchsia-500"></i>
                </div>
                
                <h3 class="text-sm font-black text-slate-100 border-b border-slate-800 pb-3 mb-4.5">Ringkasan Pembayaran</h3>

                <!-- Mobile Toggle Header (Only visible on mobile) -->
                <div onclick="toggleMobileSummary()" class="lg:hidden flex items-center justify-between pb-2 mb-2 border-b border-slate-800/60 cursor-pointer select-none">
                  <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                    <i data-lucide="ticket" class="h-3.5 w-3.5 text-fuchsia-500 animate-pulse"></i> Detail Ringkasan
                  </span>
                  <i data-lucide="chevron-up" id="mobile-summary-chevron" class="h-4 w-4 text-slate-400 transition-transform duration-300"></i>
                </div>

                <!-- Mobile container wrapper -->
                <div id="payment-summary-mobile-flex" class="flex flex-col gap-0">
                  
                  <div id="summary-details-rows" class="space-y-3 text-xs font-bold text-slate-400">
                    <div class="flex justify-between">
                      <span>Nama Game</span>
                      <span id="summary-game-name" class="text-slate-200">{{ $game->name }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                      <span>Target Player / Account ID</span>
                      <span id="summary-player-id" class="text-slate-205 font-mono">-</span>
                    </div>

                    <div class="flex justify-between">
                      <span>Item Dipilih</span>
                      <span id="summary-nominal-item" class="text-slate-205">-</span>
                    </div>

                    <div class="flex justify-between">
                      <span>Metode Transaksi</span>
                      <span id="summary-payment-method" class="text-slate-205">-</span>
                    </div>

                    <div id="summary-discount-row" class="hidden flex justify-between text-pink-400">
                      <span>Potongan Promo</span>
                      <span id="summary-discount-val">-Rp 0</span>
                    </div>
                  </div>

                  <!-- Flex split wrapper for Mobile Bottom Bar -->
                  <div id="payment-summary-action-area" class="flex flex-col justify-between gap-4 mt-4 border-t border-slate-800/80 pt-4">
                    
                    <div class="flex justify-between items-baseline" id="payment-summary-total-price-wrapper">
                      <span class="text-xs font-black text-slate-400 uppercase tracking-wider">Total Pembayaran</span>
                      <span id="summary-total-price" class="text-lg md:text-xl font-black text-fuchsia-400 font-mono">
                        Rp 0
                      </span>
                    </div>

                    <button type="submit" id="btn-buy-now" class="w-full rounded-2xl py-4 text-center text-xs md:text-sm font-black tracking-wide uppercase transition-all shadow-xs neup-orange-flat text-white cursor-pointer hover:neup-orange-pressed active:scale-98 border-none m-0">
                      Beli Sekarang 🚀
                    </button>
                  </div>
                </div>

                <div id="validation-error-banner" class="hidden my-3 p-3 rounded-xl bg-rose-500/15 border border-rose-500/30 text-[10px] text-rose-400 font-bold"></div>
              </div>

              <!-- Accordion: Cara Top Up -->
              <div class="rounded-3xl border border-white/50 neup-flat bg-white overflow-hidden shadow-sm">
                <button type="button" onclick="toggleAccordion()" id="accordion-how-to" class="flex w-full items-center justify-between p-5 text-left hover:neup-pressed-xs transition-all active:scale-[0.99] cursor-pointer bg-white border-none">
                  <div class="flex items-center gap-2.5">
                    <i data-lucide="info" class="h-4.5 w-4.5 text-slate-500"></i>
                    <span class="text-xs font-black text-slate-800">Petunjuk Cara Top Up</span>
                  </div>
                  <i data-lucide="chevron-down" id="accordion-chevron" class="h-4.5 w-4.5 text-slate-400 transition-all"></i>
                </button>

                <div id="accordion-how-to-content" class="hidden border-t border-slate-300/40 p-5 text-xs text-slate-650 space-y-4 font-semibold text-left">
                  <ol class="list-decimal list-inside space-y-3 leading-relaxed">
                    <li>Masukkan target akun game Anda dengan benar.</li>
                    <li>Pilih server game Anda jika dibutuhkan.</li>
                    <li>Tentukan item dan nominal pengisian saldo yang diinginkan.</li>
                    <li>Pilih gerbang pembayaran e-wallet, QRIS, atau Bank VA terpercaya Anda.</li>
                    <li>Tambahkan voucher diskon (contoh: <code class="bg-pink-50 text-pink-600 px-1.5 py-0.5 rounded font-mono border border-pink-100">WEEKENDGAMER</code>).</li>
                    <li>Ketuk tombol Beli Sekarang dan selesaikan transfer tagihan Anda.</li>
                  </ol>
                </div>
              </div>

            </div><!-- end sticky wrapper -->

          </div><!-- end left panel -->

          <!-- RIGHT PANEL (Checkout Steps) -->
          <div class="lg:col-span-2 space-y-6">
            
            <!-- STEP 1: PLAYER ID INPUT -->
            <div class="rounded-3xl border border-white/50 neup-flat p-5 md:p-6 bg-white text-left">
              <div class="flex items-center gap-3 border-b border-slate-300/40 pb-3.5 mb-5">
                <span class="flex h-7 w-7 items-center justify-center rounded-xl text-xs font-black text-white bg-gradient-to-tr from-pink-500 to-fuchsia-600 shadow-xs">1</span>
                <span class="text-sm font-black text-slate-800">Lengkapi ID Game Anda</span>
              </div>
              <div id="step-1-inputs" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex flex-col gap-2">
                  <label for="target_id" class="text-xs font-black text-slate-700">{{ $game->id_label }} <span class="text-rose-500">*</span></label>
                  <input
                    type="text"
                    name="target_id"
                    id="target_id"
                    required
                    placeholder="Masukkan {{ $game->id_label }}"
                    class="rounded-2xl py-3.5 px-4 text-xs font-bold text-slate-700 neup-pressed-sm focus:outline-none border-t border-l border-white/40"
                  />
                </div>
                
                @if($game->zone_id_label)
                  <div class="flex flex-col gap-2">
                    <label for="zone_id" class="text-xs font-black text-slate-700">{{ $game->zone_id_label }} <span class="text-rose-500">*</span></label>
                    <input
                      type="text"
                      name="zone_id"
                      id="zone_id"
                      required
                      placeholder="Masukkan {{ $game->zone_id_label }}"
                      class="rounded-2xl py-3.5 px-4 text-xs font-bold text-slate-700 neup-pressed-sm focus:outline-none border-t border-l border-white/40"
                    />
                  </div>
                @endif

                <p class="col-span-full text-[10px] text-slate-500 leading-normal font-semibold mt-1">
                  <i data-lucide="info" class="h-3 w-3 inline text-slate-400 mr-1 align-middle"></i>{{ $game->id_helper_text }}
                </p>
              </div>
            </div>

            <!-- STEP 2: NOMINAL DIAMOND SELECTION -->
            <div class="rounded-3xl border border-white/50 neup-flat p-5 md:p-6 bg-white text-left">
              <div class="flex items-center gap-3 border-b border-slate-300/40 pb-3.5 mb-5">
                <span class="flex h-7 w-7 items-center justify-center rounded-xl text-xs font-black text-white bg-gradient-to-tr from-cyan-400 to-indigo-600 shadow-xs">2</span>
                <span class="text-sm font-black text-slate-800">Tentukan Item / Nominal Isi</span>
              </div>
              <div id="nominals-grid" class="grid grid-cols-2 md:grid-cols-3 gap-4.5">
                @foreach($game->nominals as $nominal)
                  <label for="nominal-{{ $nominal->id }}" class="nominal-card relative rounded-2xl p-4.5 border border-white/50 neup-flat flex flex-col justify-between cursor-pointer transition-all active:scale-98 select-none">
                    <input type="radio" name="nominal_id" id="nominal-{{ $nominal->id }}" value="{{ $nominal->id }}" class="hidden" required onclick="selectNominal(this, '{{ $nominal->name }}', {{ $nominal->discount_price ?? $nominal->price }})">
                    
                    @if($nominal->tag || $nominal->is_best_seller)
                      <div class="absolute top-2 right-2 flex flex-col items-end gap-1 select-none pointer-events-none scale-90">
                        @if($nominal->tag)
                          <span class="bg-cyan-500/10 border border-cyan-500/30 text-cyan-600 text-[8px] font-black px-1.5 py-0.5 rounded uppercase tracking-wider shadow-sm">{{ $nominal->tag }}</span>
                        @endif
                        @if($nominal->is_best_seller)
                          <span class="bg-pink-500 text-white text-[8px] font-black px-1.5 py-0.5 rounded uppercase tracking-wider shadow-sm">HOT</span>
                        @endif
                      </div>
                    @endif

                    <div class="text-left">
                      <p class="text-xs font-black text-slate-800 leading-tight">{{ $nominal->name }}</p>
                    </div>
                    <div class="mt-4 text-left border-t border-slate-100/80 pt-2 flex flex-col gap-0.5">
                      @if($nominal->discount_price)
                        <span class="text-[9px] text-slate-400 line-through font-bold">Rp {{ number_format($nominal->price, 0, ',', '.') }}</span>
                        <span class="text-xs font-black text-[#ff007f]">Rp {{ number_format($nominal->discount_price, 0, ',', '.') }}</span>
                      @else
                        <span class="text-xs font-black text-[#ff007f]">Rp {{ number_format($nominal->price, 0, ',', '.') }}</span>
                      @endif
                    </div>
                  </label>
                @endforeach
              </div>
            </div>

            <!-- STEP 3: PAYMENT METHOD -->
            <div class="rounded-3xl border border-white/50 neup-flat p-5 md:p-6 bg-white text-left">
              <div class="flex items-center gap-3 border-b border-slate-300/40 pb-3.5 mb-5">
                <span class="flex h-7 w-7 items-center justify-center rounded-xl text-xs font-black text-white bg-gradient-to-tr from-fuchsia-500 to-pink-500 shadow-xs">3</span>
                <span class="text-sm font-black text-slate-800">Pilih Metode Pembayaran</span>
              </div>
              
              <!-- Payment Group Listings -->
              <div id="payments-container" class="space-y-6">
                
                <!-- Group 1: QRIS -->
                <div>
                  <h4 class="text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2.5">QRIS Instan</h4>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    @foreach($paymentMethods->where('group', 'qris') as $pm)
                      <label for="payment-{{ $pm->id }}" class="payment-card rounded-2xl p-4.5 border border-white/50 neup-flat flex items-center justify-between cursor-pointer transition-all active:scale-98 select-none">
                        <input type="radio" name="payment_method_id" id="payment-{{ $pm->id }}" value="{{ $pm->id }}" class="hidden" required onclick="selectPaymentMethod(this, '{{ $pm->name }}', {{ $pm->fee }})">
                        <div class="flex items-center gap-3">
                          @if($pm->image)
                            <img src="{{ $pm->image }}" alt="{{ $pm->name }}" class="h-8 w-12 rounded-xl object-contain bg-white p-0.5 border border-slate-200 shadow-sm flex-shrink-0">
                          @else
                            <div class="h-8 w-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center font-black text-fuchsia-500 text-sm shadow-sm flex-shrink-0">QR</div>
                          @endif
                          <div>
                            <p class="text-xs font-black text-slate-800 leading-tight">{{ $pm->name }}</p>
                            <p class="text-[9px] text-slate-400 font-bold mt-0.5">Biaya admin: Rp {{ number_format($pm->fee, 0, ',', '.') }}</p>
                          </div>
                        </div>
                      </label>
                    @endforeach
                  </div>
                </div>

                <!-- Group 2: E-Wallet -->
                <div>
                  <h4 class="text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2.5">E-Wallet Dompet Digital</h4>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    @foreach($paymentMethods->where('group', 'e-wallet') as $pm)
                      <label for="payment-{{ $pm->id }}" class="payment-card rounded-2xl p-4.5 border border-white/50 neup-flat flex items-center justify-between cursor-pointer transition-all active:scale-98 select-none">
                        <input type="radio" name="payment_method_id" id="payment-{{ $pm->id }}" value="{{ $pm->id }}" class="hidden" required onclick="selectPaymentMethod(this, '{{ $pm->name }}', {{ $pm->fee }})">
                        <div class="flex items-center gap-3">
                          @if($pm->image)
                            <img src="{{ $pm->image }}" alt="{{ $pm->name }}" class="h-8 w-12 rounded-xl object-contain bg-white p-0.5 border border-slate-200 shadow-sm flex-shrink-0">
                          @else
                            <div class="h-8 w-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center font-black text-cyan-500 text-sm shadow-sm flex-shrink-0">EW</div>
                          @endif
                          <div>
                            <p class="text-xs font-black text-slate-800 leading-tight">{{ $pm->name }}</p>
                            <p class="text-[9px] text-slate-400 font-bold mt-0.5">Biaya admin: Rp {{ number_format($pm->fee, 0, ',', '.') }}</p>
                          </div>
                        </div>
                      </label>
                    @endforeach
                  </div>
                </div>

                <!-- Group 3: Virtual Account -->
                <div>
                  <h4 class="text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2.5">Transfer Bank Virtual Account</h4>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    @foreach($paymentMethods->where('group', 'bank') as $pm)
                      <label for="payment-{{ $pm->id }}" class="payment-card rounded-2xl p-4.5 border border-white/50 neup-flat flex items-center justify-between cursor-pointer transition-all active:scale-98 select-none">
                        <input type="radio" name="payment_method_id" id="payment-{{ $pm->id }}" value="{{ $pm->id }}" class="hidden" required onclick="selectPaymentMethod(this, '{{ $pm->name }}', {{ $pm->fee }})">
                        <div class="flex items-center gap-3">
                          @if($pm->image)
                            <img src="{{ $pm->image }}" alt="{{ $pm->name }}" class="h-8 w-12 rounded-xl object-contain bg-white p-0.5 border border-slate-200 shadow-sm flex-shrink-0">
                          @else
                            <div class="h-8 w-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center font-black text-indigo-500 text-sm shadow-sm flex-shrink-0">VA</div>
                          @endif
                          <div>
                            <p class="text-xs font-black text-slate-800 leading-tight">{{ $pm->name }}</p>
                            <p class="text-[9px] text-slate-400 font-bold mt-0.5">Biaya admin: Rp {{ number_format($pm->fee, 0, ',', '.') }}</p>
                          </div>
                        </div>
                      </label>
                    @endforeach
                  </div>
                </div>

              </div>
            </div>

            <!-- STEP 4: PROMO / VOUCHER SECTION -->
            <div class="rounded-3xl border border-white/50 neup-flat p-5 md:p-6 bg-white text-left">
              <div class="flex items-center gap-3 border-b border-slate-300/40 pb-3.5 mb-4">
                <i data-lucide="ticket" class="h-5 w-5 text-pink-500"></i>
                <span class="text-sm font-black text-slate-800">Pakai Voucher Promo</span>
              </div>

              <div id="promo-wrapper-area">
                <div class="space-y-3">
                  <div class="flex gap-3">
                    <input
                      type="text"
                      name="promo_code"
                      id="promo_input_code"
                      placeholder="Masukkan Kode Voucher (contoh: WEEKENDGAMER)"
                      class="w-full rounded-2xl py-3 px-4 text-xs font-bold text-slate-700 neup-pressed-sm focus:outline-none border-t border-l border-white/40"
                    />
                    <button type="button" onclick="applyPromoCode()" id="apply-promo-btn" class="text-white font-black text-xs rounded-2xl px-5 py-3 cursor-pointer max-w-[120px] w-full neup-orange-flat hover:neup-orange-pressed active:scale-95 transition-all border-none">
                      Gunakan
                    </button>
                  </div>
                  <div id="promo-status-box" class="hidden p-3 rounded-xl text-[10px] font-bold text-left"></div>
                  
                  <div class="flex flex-wrap gap-2.5 pt-1 text-left">
                    <span class="text-[10px] text-slate-400 self-center font-bold">Kupon Tersedia:</span>
                    @foreach($promos as $promo)
                      <button type="button" onclick="shortcutPromo('{{ $promo->code }}')" class="text-[10px] font-black text-fuchsia-600 bg-transparent border border-white/40 rounded-xl px-3 py-1.5 font-mono cursor-pointer neup-flat-sm hover:neup-pressed-xs active:scale-95">
                        {{ $promo->code }} (-Rp {{ number_format($promo->discount_amount, 0, ',', '.') }})
                      </button>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>

            <!-- RELATED RECOMMENDATIONS GAMES -->
            <div class="rounded-3xl border border-white/50 neup-flat p-5 md:p-6 bg-white text-left">
              <h3 class="text-xs font-black tracking-widest text-slate-400 uppercase mb-4.5">Rekomendasi Game Lainnya</h3>
              <div id="recs-games-container" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- We will seed some cool static/dynamic other games -->
                <div class="neup-pressed-sm p-3.5 rounded-2xl border border-white/40 flex items-center gap-3 cursor-pointer transition-all hover:neup-flat-sm" onclick="window.location.href='/game/free-fire'">
                  <div class="h-10 w-10 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0">
                    <img src="https://images.unsplash.com/photo-1552820728-8b83bb6b773f?w=100&q=85" class="h-full w-full object-cover">
                  </div>
                  <div>
                    <h5 class="text-xs font-black text-slate-700 m-0">Free Fire</h5>
                    <p class="text-[9px] text-slate-400 font-bold m-0 mt-0.5">Top Up Diamonds</p>
                  </div>
                </div>
                <div class="neup-pressed-sm p-3.5 rounded-2xl border border-white/40 flex items-center gap-3 cursor-pointer transition-all hover:neup-flat-sm" onclick="window.location.href='/game/pubg-mobile'">
                  <div class="h-10 w-10 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0">
                    <img src="https://images.unsplash.com/photo-1511512578047-dfb367046420?w=100&q=85" class="h-full w-full object-cover">
                  </div>
                  <div>
                    <h5 class="text-xs font-black text-slate-700 m-0">PUBG Mobile</h5>
                    <p class="text-[9px] text-slate-400 font-bold m-0 mt-0.5">Top Up UC Cash</p>
                  </div>
                </div>
                <div class="neup-pressed-sm p-3.5 rounded-2xl border border-white/40 flex items-center gap-3 cursor-pointer transition-all hover:neup-flat-sm" onclick="window.location.href='/game/valorant'">
                  <div class="h-10 w-10 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0">
                    <img src="https://images.unsplash.com/photo-1612287230202-1bf1d85d1bdf?w=100&q=85" class="h-full w-full object-cover">
                  </div>
                  <div>
                    <h5 class="text-xs font-black text-slate-700 m-0">Valorant</h5>
                    <p class="text-[9px] text-slate-400 font-bold m-0 mt-0.5">Valorant Points</p>
                  </div>
                </div>
              </div>
            </div>

          </div>

        </div>
      </form>

    </div>
  </div>

  <script>
    // State for live summary preview calculations
    let selectedNominal = null;
    let selectedPayment = null;
    let promoDiscount = 0;
    let promoCodeActive = '';

    // Step 1 Player ID input event handler
    const targetInput = document.getElementById('target_id');
    const summaryPlayer = document.getElementById('summary-player-id');
    if (targetInput && summaryPlayer) {
      targetInput.addEventListener('input', (e) => {
        const val = e.target.value.trim();
        const zoneVal = document.getElementById('zone_id')?.value.trim();
        summaryPlayer.textContent = val ? (val + (zoneVal ? ` (${zoneVal})` : '')) : '-';
      });
    }

    const zoneInput = document.getElementById('zone_id');
    if (zoneInput && summaryPlayer) {
      zoneInput.addEventListener('input', (e) => {
        const zoneVal = e.target.value.trim();
        const val = targetInput.value.trim();
        summaryPlayer.textContent = val ? (val + (zoneVal ? ` (${zoneVal})` : '')) : '-';
      });
    }

    // Toggle accordion functionality
    function toggleAccordion() {
      const content = document.getElementById('accordion-how-to-content');
      const chev = document.getElementById('accordion-chevron');
      if (content) {
        content.classList.toggle('hidden');
        const isHidden = content.classList.contains('hidden');
        if (chev) {
          chev.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(180deg)';
        }
      }
    }

    // Selection helper for nominal
    function selectNominal(radio, name, price) {
      // Dehighlight all nominal cards
      document.querySelectorAll('.nominal-card').forEach(card => {
        card.classList.remove('neup-pressed-sm', 'border-indigo-500');
        card.classList.add('neup-flat');
      });

      // Highlight target card
      const parentLabel = radio.closest('label');
      if (parentLabel) {
        parentLabel.classList.remove('neup-flat');
        parentLabel.classList.add('neup-pressed-sm', 'border-indigo-500');
      }

      selectedNominal = { name, price };
      
      // Update summary preview
      document.getElementById('summary-nominal-item').textContent = name;
      calculateTotal();
    }

    // Selection helper for payment
    function selectPaymentMethod(radio, name, fee) {
      // Dehighlight all payment cards
      document.querySelectorAll('.payment-card').forEach(card => {
        card.classList.remove('neup-pressed-sm', 'border-fuchsia-500');
        card.classList.add('neup-flat');
      });

      // Highlight target card
      const parentLabel = radio.closest('label');
      if (parentLabel) {
        parentLabel.classList.remove('neup-flat');
        parentLabel.classList.add('neup-pressed-sm', 'border-fuchsia-500');
      }

      selectedPayment = { name, fee };

      // Update summary preview
      document.getElementById('summary-payment-method').textContent = name;
      calculateTotal();
    }

    // Apply Promo Code
    function applyPromoCode() {
      const code = document.getElementById('promo_input_code').value.toUpperCase().trim();
      const statusBox = document.getElementById('promo-status-box');
      if (!selectedNominal) {
        alert('Silakan pilih nominal/item terlebih dahulu sebelum menggunakan voucher promo.');
        return;
      }

      if (!code) {
        promoDiscount = 0;
        promoCodeActive = '';
        statusBox.classList.add('hidden');
        calculateTotal();
        return;
      }

      statusBox.classList.remove('hidden');

      // Check client-side promos list
      const promosList = {
        'CSHBKNEW': { discount: 25000, min: 30000, desc: 'Kupon CSHBKNEW aktif! Potongan langsung Rp 25.000 berhasil dipasang.' },
        'WEEKENDGAMER': { discount: 15000, min: 50000, desc: 'Kupon WEEKENDGAMER aktif! Potongan langsung Rp 15.000 berhasil dipasang.' },
        'GARENASPEKTA': { discount: 10000, min: 20000, desc: 'Kupon GARENASPEKTA aktif! Potongan langsung Rp 10.000 berhasil dipasang.' }
      };

      const promo = promosList[code];
      if (promo) {
        if (selectedNominal.price >= promo.min) {
          promoDiscount = promo.discount;
          promoCodeActive = code;
          
          statusBox.className = 'p-3 rounded-xl text-[10px] font-bold text-left bg-emerald-50 border border-emerald-100 text-emerald-600';
          statusBox.textContent = promo.desc;
        } else {
          promoDiscount = 0;
          promoCodeActive = '';
          statusBox.className = 'p-3 rounded-xl text-[10px] font-bold text-left bg-rose-50 border border-rose-100 text-rose-500';
          statusBox.textContent = `Minimal transaksi untuk voucher ${code} adalah Rp ` + promo.min.toLocaleString('id-ID');
        }
      } else {
        promoDiscount = 0;
        promoCodeActive = '';
        statusBox.className = 'p-3 rounded-xl text-[10px] font-bold text-left bg-rose-50 border border-rose-100 text-rose-500';
        statusBox.textContent = 'Kode kupon promo tidak valid atau sudah kedaluwarsa.';
      }

      calculateTotal();
    }

    function shortcutPromo(code) {
      document.getElementById('promo_input_code').value = code;
      applyPromoCode();
    }

    // Rolling number animators for summary preview
    let currentPriceValue = 0;
    let currentDiscountValue = 0;

    function animatePriceCount(targetValue) {
      const duration = 400; // Snappy 400ms rolling time
      const startValue = currentPriceValue;
      const startTime = performance.now();
      const el = document.getElementById('summary-total-price');
      if (!el) return;

      function update(currentTime) {
        const elapsedTime = currentTime - startTime;
        if (elapsedTime >= duration) {
          el.textContent = 'Rp ' + targetValue.toLocaleString('id-ID');
          currentPriceValue = targetValue;
        } else {
          const progress = elapsedTime / duration;
          const easeProgress = 1 - Math.pow(1 - progress, 3); // easeOutCubic
          const val = Math.round(startValue + (targetValue - startValue) * easeProgress);
          el.textContent = 'Rp ' + val.toLocaleString('id-ID');
          requestAnimationFrame(update);
        }
      }
      requestAnimationFrame(update);
    }

    function animateDiscountCount(targetValue) {
      const duration = 400;
      const startValue = currentDiscountValue;
      const startTime = performance.now();
      const el = document.getElementById('summary-discount-val');
      if (!el) return;

      function update(currentTime) {
        const elapsedTime = currentTime - startTime;
        if (elapsedTime >= duration) {
          el.textContent = '-Rp ' + targetValue.toLocaleString('id-ID');
          currentDiscountValue = targetValue;
        } else {
          const progress = elapsedTime / duration;
          const easeProgress = 1 - Math.pow(1 - progress, 3); // easeOutCubic
          const val = Math.round(startValue + (targetValue - startValue) * easeProgress);
          el.textContent = '-Rp ' + val.toLocaleString('id-ID');
          requestAnimationFrame(update);
        }
      }
      requestAnimationFrame(update);
    }

    // Live price calculations
    function calculateTotal() {
      if (!selectedNominal) return;

      const basePrice = selectedNominal.price;
      const fee = selectedPayment ? selectedPayment.fee : 0;
      const finalPrice = Math.max(0, (basePrice - promoDiscount) + fee);

      // Animate total price display using rolling number counter
      animatePriceCount(finalPrice);

      // Animate/Update discount row display
      const discRow = document.getElementById('summary-discount-row');
      if (promoDiscount > 0) {
        discRow.classList.remove('hidden');
        animateDiscountCount(promoDiscount);
      } else {
        discRow.classList.add('hidden');
        currentDiscountValue = 0;
      }
    }

    // Form submission validation
    const form = document.getElementById('checkout-form');
    const errBanner = document.getElementById('validation-error-banner');
    if (form) {
      form.addEventListener('submit', (e) => {
        errBanner.classList.add('hidden');

        if (!selectedNominal) {
          e.preventDefault();
          errBanner.classList.remove('hidden');
          errBanner.textContent = 'MOHON PILIH NOMINAL/ITEM TERLEBIH DAHULU!';
          document.getElementById('nominals-grid').scrollIntoView({ behavior: 'smooth', block: 'center' });
          return;
        }

        if (!selectedPayment) {
          e.preventDefault();
          errBanner.classList.remove('hidden');
          errBanner.textContent = 'MOHON PILIH METODE PEMBAYARAN TERLEBIH DAHULU!';
          document.getElementById('payments-container').scrollIntoView({ behavior: 'smooth', block: 'center' });
          return;
        }
      });
    }

    function toggleMobileSummary() {
      if (window.innerWidth >= 1024) return;
      const card = document.getElementById('payment-summary-card');
      const chevron = document.getElementById('mobile-summary-chevron');
      if (card) {
        card.classList.toggle('mobile-expanded');
        const isExpanded = card.classList.contains('mobile-expanded');
        if (chevron) {
          chevron.style.transform = isExpanded ? 'rotate(180deg)' : 'rotate(0deg)';
        }
      }
    }
  </script>
@endsection
