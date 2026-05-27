@extends('layouts.app')

@section('title', 'Menunggu Pembayaran - GameTopup')

@section('content')
  <div class="flex-1 py-8" id="payment-waiting-page">
    <div id="waiting-core-container" class="flex-1 py-8">
      <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        
        <!-- Navigation Breadcrumb -->
        <a href="{{ route('home') }}" class="group inline-flex items-center gap-2 px-4 py-2.5 text-xs font-black text-slate-600 neup-flat-sm hover:neup-pressed-sm cursor-pointer transition-all mb-8 rounded-xl bg-white text-left decoration-none">
          <i data-lucide="arrow-left" class="h-4 w-4 transition-transform group-hover:-translate-x-1 text-slate-500"></i>
          Batalkan & Kembali
        </a>

        <!-- 1. STATE INDICATOR -->
        <div class="rounded-3xl border border-white/50 neup-flat p-6 text-center mb-6 bg-white shadow-sm">
          <span class="inline-flex items-center gap-2 rounded-full py-1 px-3 text-[10px] font-black uppercase text-[#ff007f] bg-[#ff007f]/10 border border-[#ff007f]/15 animate-pulse">
            <i data-lucide="clock" class="h-3.5 w-3.5 animate-spin"></i>
            MENUNGGU PEMBAYARAN
          </span>
          <h2 class="mt-4 text-base md:text-lg font-black text-slate-800 tracking-tight leading-tight">Selesaikan Pembayaran Anda Sebelum Expired</h2>
          
          <div class="mt-4 flex justify-center gap-2 font-mono text-sm font-black">
            <span id="wait-hour-box" class="neup-dark-flat rounded-xl px-3 py-1.5 text-pink-400 bg-slate-950">23</span>
            <span class="text-slate-400 self-center font-bold">:</span>
            <span id="wait-min-box" class="neup-dark-flat rounded-xl px-3 py-1.5 text-pink-400 bg-slate-950">59</span>
            <span class="text-slate-400 self-center font-bold">:</span>
            <span id="wait-sec-box" class="neup-dark-flat rounded-xl px-3 py-1.5 text-pink-400 animate-pulse bg-slate-950">59</span>
          </div>
        </div>

        <!-- 2. PAYMENT CHANNEL DETAILS -->
        <div class="mt-6 rounded-3xl border border-white/50 neup-flat p-5 md:p-6 mb-6 bg-white shadow-sm text-left">
          <div class="border-b border-slate-300/40 pb-4 text-center">
            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest leading-none">Metode Penyelesaian</p>
            <h3 id="waiting-channel-name" class="mt-2 text-sm font-black text-pink-600 uppercase">{{ $transaction->paymentMethod->name }}</h3>
          </div>

          <div class="mt-6 flex flex-col items-center">
            
            <!-- QRIS QR CODE -->
            @if($transaction->paymentMethod->slug === 'qris' || $transaction->qr_code_url)
              <div class="flex flex-col items-center p-4 rounded-2xl bg-white border border-slate-200 shadow-inner mb-6">
                <img src="{{ $transaction->qr_code_url ?? 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . $transaction->invoice }}" alt="QRIS Code" class="h-44 w-44 object-contain">
                <span class="text-[9px] font-black text-slate-400 mt-2 uppercase tracking-widest">SCAN KODE QRIS</span>
              </div>
            @endif

            <!-- Virtual Account / E-Wallet Info -->
            @if($transaction->paymentMethod->group === 'bank' || $transaction->paymentMethod->group === 'e-wallet')
              @if($transaction->paymentMethod->slug !== 'qris')
                <div class="w-full rounded-2xl p-4.5 neup-pressed-sm border border-white/40 flex items-center justify-between mb-6 bg-slate-50">
                  <div>
                    <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest leading-none">
                      {{ $transaction->paymentMethod->group === 'bank' ? 'Nomor Virtual Account' : 'Nomor HP Tujuan Transfer' }}
                    </p>
                    <p id="target-account-number" class="text-sm font-black text-slate-800 font-mono mt-2 tracking-wide">
                      {{ $transaction->va_number ?? $transaction->paymentMethod->account_number ?? '0812-3456-7890' }}
                    </p>
                  </div>
                  <button onclick="copyToClipboardText('{{ $transaction->va_number ?? $transaction->paymentMethod->account_number ?? '0812-3456-7890' }}', this)" class="rounded-xl px-3 py-2 text-[10px] font-black text-indigo-600 border border-indigo-200 neup-flat-sm hover:neup-pressed-xs active:scale-95 transition-all cursor-pointer">
                    Salin
                  </button>
                </div>
              @endif
            @endif

            <!-- Price Total Display -->
            <div class="border-t border-slate-300/40 pt-6 w-full max-w-sm text-center">
              <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Jumlah Tagihan Pembayaran</p>
              <div class="mt-3.5 flex items-baseline justify-center gap-1.5">
                <span id="waiting-total-bill" class="text-xl md:text-2xl font-black text-slate-800 font-mono">
                  Rp {{ number_format($transaction->total_payment, 0, ',', '.') }}
                </span>
                <span class="text-[9px] font-black text-slate-400 uppercase">JUMLAH PAS</span>
              </div>
            </div>

          </div>
        </div>

        <!-- 3. TRANSACTION DETAIL BILLING SUMMARY -->
        <div class="mt-6 rounded-3xl border border-white/50 neup-flat p-5 md:p-6 mb-6 bg-white shadow-sm">
          <h3 class="text-xs font-black text-slate-800 border-b border-slate-300/40 pb-3 mb-5 uppercase tracking-widest text-left">Detail Transaksi Pesanan</h3>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-bold text-slate-500 text-left">
            <div class="neup-pressed-xs border border-white/30 rounded-2xl p-4.5 bg-transparent">
              <p class="text-[10px] text-slate-400 font-bold leading-none mb-1.5">ID Invois Pemesanan</p>
              <p id="bill-summary-invoice" class="text-slate-800 font-mono text-xs">{{ $transaction->invoice }}</p>
            </div>
            <div class="neup-pressed-xs border border-white/30 rounded-2xl p-4.5 bg-transparent">
              <p class="text-[10px] text-slate-400 font-bold leading-none mb-1.5">Game / Katalog</p>
              <p id="bill-summary-game" class="text-slate-800">{{ $transaction->game->name }}</p>
            </div>
            <div class="neup-pressed-xs border border-white/30 rounded-2xl p-4.5 bg-transparent">
              @if($transaction->game_account_id)
                <p class="text-[10px] text-slate-400 font-bold leading-none mb-1.5">Email Penerima Akun</p>
                <p id="bill-summary-player-id" class="text-slate-800 font-mono">{{ $transaction->target_id }}</p>
              @else
                <p class="text-[10px] text-slate-400 font-bold leading-none mb-1.5">Player ID & Server</p>
                <p id="bill-summary-player-id" class="text-slate-800 font-mono">{{ $transaction->target_id }}{{ $transaction->zone_id ? ' (' . $transaction->zone_id . ')' : '' }}</p>
              @endif
            </div>
            <div class="neup-pressed-xs border border-white/30 rounded-2xl p-4.5 bg-transparent">
              <p class="text-[10px] text-slate-400 font-bold leading-none mb-1.5">Item Produk Top Up</p>
              <p id="bill-summary-nominal" class="text-slate-800">{{ $transaction->nominal_name }}</p>
            </div>
          </div>
        </div>

        <!-- 4. ACCORDION: PETUNJUK PEMBAYARAN -->
        <div class="mt-6 rounded-3xl border border-white/50 neup-flat shadow-xs overflow-hidden mb-6 bg-white">
          <button type="button" onclick="togglePayAccordion()" id="accordion-pay-trigger" class="flex w-full items-center justify-between p-5 text-left active:scale-[0.99] cursor-pointer hover:neup-pressed-xs transition-colors bg-white border-none">
            <span class="text-xs font-black text-slate-800">Petunjuk Cara Bayar</span>
            <i data-lucide="chevron-down" id="pay-accordion-chevron" class="h-4.5 w-4.5 text-slate-400 transition-all"></i>
          </button>

          <div id="accordion-pay-content" class="hidden border-t border-slate-300/40 p-5 text-xs text-slate-650 leading-relaxed font-semibold text-left space-y-3">
            <ol class="list-decimal list-inside space-y-2">
              @foreach($transaction->paymentMethod->instructions as $step)
                <li>{{ $step }}</li>
              @endforeach
            </ol>
          </div>
        </div>

        <!-- 5. ROADMAP STATUS TIMELINE -->
        <div class="mt-6 rounded-3xl border border-white/50 neup-flat p-5 md:p-6 mb-6 bg-white shadow-sm">
          <h3 class="text-xs font-black text-slate-800 border-b border-slate-300/40 pb-3 mb-5 uppercase tracking-widest text-left">Alur Progress Status Pesanan</h3>
          
          <div class="relative text-left">
            <div class="absolute left-[15px] top-2 bottom-2 w-0.5 bg-slate-200"></div>

            <div class="space-y-6">
              
              <!-- Step 1 -->
              <div class="relative flex items-start gap-4">
                <span class="z-10 flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500 text-white shadow ring-4 ring-emerald-100 flex-shrink-0">
                  <i data-lucide="check" class="h-4 w-4"></i>
                </span>
                <div>
                  <p class="text-xs font-black text-emerald-600">Pesanan Dibuat</p>
                  <p class="text-[10px] text-slate-400 mt-1 leading-none font-bold">Invoice checkout diterbitkan dengan sukses.</p>
                </div>
              </div>

              <!-- Step 2 -->
              <div class="relative flex items-start gap-4">
                <span class="z-10 flex h-8 w-8 items-center justify-center rounded-full bg-amber-500 text-white shadow ring-4 ring-amber-100 flex-shrink-0">
                  <span class="h-2 w-2 rounded-full bg-white animate-ping"></span>
                </span>
                <div>
                  <p class="text-xs font-black text-amber-705">Menunggu Pembayaran</p>
                  <p class="text-[10px] text-slate-400 mt-1 leading-none font-bold">Menunggu dana masuk dari operator billing payment.</p>
                </div>
              </div>

              <!-- Step 3 -->
              <div class="relative flex items-start gap-4 opacity-50 font-bold">
                <span class="z-10 flex h-8 w-8 items-center justify-center rounded-full text-slate-400 border border-white/20 bg-white neup-pressed-xs flex-shrink-0 font-extrabold text-[10px]">
                  3
                </span>
                <div>
                  <p class="text-xs font-black text-slate-650">Proses Pengisian</p>
                  <p class="text-[10px] text-slate-400 mt-1 leading-none font-bold">Antrean server pengisian item sedang mendelivery barang.</p>
                </div>
              </div>

              <!-- Step 4 -->
              <div class="relative flex items-start gap-4 opacity-50 font-bold">
                <span class="z-10 flex h-8 w-8 items-center justify-center rounded-full text-slate-400 border border-white/20 bg-white neup-pressed-xs flex-shrink-0 font-extrabold text-[10px]">
                  4
                </span>
                <div>
                  <p class="text-xs font-black text-slate-650">Transaksi Selesai</p>
                  <p class="text-[10px] text-slate-400 mt-1 leading-none font-bold">Diamond / Voucher sukses masuk di inbox aplikasi game.</p>
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- 6. DYNAMIC SIMULATED ACTION BUTTONS -->
        <div class="mt-8 flex flex-col md:flex-row gap-4 font-black">
          <form action="{{ route('payment.confirm', $transaction->invoice) }}" method="POST" class="flex-1 m-0 p-0">
            @csrf
            <button type="submit" id="btn-confirm-paid" class="w-full rounded-2xl py-4 text-center text-xs md:text-sm font-black text-white neup-orange-flat hover:neup-orange-pressed active:scale-98 transition-all cursor-pointer border-none shadow-sm">
              Saya Sudah Bayar 🛡️ (Simulasi Sukses)
            </button>
          </form>

          <button onclick="window.location.reload();" id="btn-refresh-status" class="rounded-2xl py-4 px-6 text-center text-xs font-black text-slate-600 flex items-center justify-center gap-2 cursor-pointer neup-flat-sm hover:neup-pressed-sm active:scale-98 bg-white border-none">
            <i data-lucide="refresh-cw" class="h-4 w-4"></i>
            <span>Refresh Status</span>
          </button>
        </div>

        <!-- 7. CUSTOMER SUPPORT -->
        <div class="mt-10 border-t border-slate-300/40 pt-8 text-center space-y-4">
          <p class="text-xs text-slate-500 font-extrabold max-w-md mx-auto leading-relaxed">
            Menghadapi kendala pembayaran? Jangan khawatir! Customer service kami siap membantu kendala top-up Anda dalam hitungan menit.
          </p>
          
          <div class="flex justify-center gap-3">
            <a href="{{ route('support') }}" class="inline-flex items-center gap-2 rounded-2xl px-5 py-3.5 text-xs font-black text-pink-600 neup-flat-sm hover:neup-pressed-xs active:scale-95 transition-all cursor-pointer bg-white decoration-none">
              <i data-lucide="help-circle" class="h-4.5 w-4.5"></i> Hubungi CS (Live Chat)
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script>
    // Live countdown timer ticking simulation
    let totalSeconds = 24 * 3600 - 15; // Simulated 24 hour limit
    const hrBox = document.getElementById('wait-hour-box');
    const minBox = document.getElementById('wait-min-box');
    const secBox = document.getElementById('wait-sec-box');

    if (hrBox && minBox && secBox) {
      const interval = setInterval(() => {
        if (totalSeconds > 0) {
          totalSeconds--;
          const hrs = Math.floor(totalSeconds / 3600);
          const mins = Math.floor((totalSeconds % 3600) / 60);
          const secs = totalSeconds % 60;

          hrBox.textContent = String(hrs).padStart(2, '0');
          minBox.textContent = String(mins).padStart(2, '0');
          secBox.textContent = String(secs).padStart(2, '0');
        } else {
          clearInterval(interval);
        }
      }, 1000);
    }

    // Toggle cara bayar accordion
    function togglePayAccordion() {
      const content = document.getElementById('accordion-pay-content');
      const chev = document.getElementById('pay-accordion-chevron');
      if (content) {
        content.classList.toggle('hidden');
        const isHidden = content.classList.contains('hidden');
        if (chev) {
          chev.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(180deg)';
        }
      }
    }

    // Copy to clipboard helper
    function copyToClipboardText(text, btn) {
      navigator.clipboard.writeText(text).then(() => {
        const oldText = btn.textContent;
        btn.textContent = 'Tersalin';
        btn.style.color = '#059669';
        btn.classList.add('neup-pressed-xs');
        setTimeout(() => {
          btn.textContent = oldText;
          btn.style.color = '';
          btn.classList.remove('neup-pressed-xs');
        }, 2000);
      });
    }
  </script>
@endsection
