@extends('layouts.app')

@section('title', $account->title . ' - GameTopup')

@section('content')
  <div class="flex-1 py-8" id="account-detail-page">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      
      <!-- Back to Marketplace Breadcrumb -->
      <a href="{{ route('accounts.index') }}" class="group inline-flex items-center gap-2 px-4 py-2.5 text-xs font-black text-slate-600 neup-flat-sm hover:neup-pressed-sm cursor-pointer transition-all mb-8 rounded-xl bg-white text-left decoration-none">
        <i data-lucide="arrow-left" class="h-4 w-4 transition-transform group-hover:-translate-x-1 text-slate-500"></i>
        Kembali ke Katalog Akun
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
        <input type="hidden" name="game_id" value="{{ $account->game_id }}">
        <input type="hidden" name="game_account_id" value="{{ $account->id }}">

        <!-- Split Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          
          <!-- LEFT COLUMN (Screenshots Gallery + Specs + Description) -->
          <div class="lg:col-span-2 space-y-6">
            
            <!-- Screenshot Gallery -->
            <div class="rounded-3xl border border-white/50 p-5 neup-flat bg-white text-left">
              <!-- Large Active View -->
              <div class="relative aspect-video w-full overflow-hidden rounded-2xl bg-slate-100 shadow-inner">
                @if($account->images && count($account->images) > 0)
                  <img id="active-gallery-img" src="{{ $account->images[0] }}" alt="{{ $account->title }}" class="h-full w-full object-cover transition-all duration-300" />
                @else
                  <div class="h-full w-full flex items-center justify-center font-black text-slate-350">Belum Ada Screenshot</div>
                @endif
                
                <div class="absolute top-3 left-3">
                  <span class="bg-[#111827]/75 border border-slate-700/30 text-white text-[8px] font-black px-2.5 py-0.8 rounded-lg uppercase tracking-widest backdrop-blur-md shadow-sm">
                    {{ $account->game->name }}
                  </span>
                </div>
              </div>

              <!-- Gallery Thumbnails -->
              @if($account->images && count($account->images) > 1)
                <div class="mt-4 flex gap-3 overflow-x-auto pb-1.5 scrollbar-none">
                  @foreach($account->images as $index => $imgUrl)
                    <button type="button" onclick="changeActiveImage('{{ $imgUrl }}', this)" class="gallery-thumb-btn h-12 w-20 rounded-xl overflow-hidden border {{ $index === 0 ? 'border-indigo-500 scale-95 shadow-inner' : 'border-slate-200' }} flex-shrink-0 cursor-pointer transition-all hover:border-indigo-400">
                      <img src="{{ $imgUrl }}" class="h-full w-full object-cover">
                    </button>
                  @endforeach
                </div>
              @endif
            </div>

            <!-- Specs Grid -->
            <div class="rounded-3xl border border-white/50 p-5 md:p-6 neup-flat bg-white text-left">
              <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider mb-4 border-b border-slate-100 pb-2">Spesifikasi Akun Lengkap</h3>
              
              <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-xs font-bold text-slate-500">
                <div class="p-3 rounded-2xl neup-pressed-xs border border-white/45 bg-transparent">
                  <span class="text-[9px] uppercase text-slate-400 font-black">Rank Game</span>
                  <p class="text-slate-800 text-sm font-black mt-1">{{ $account->rank }}</p>
                </div>
                <div class="p-3 rounded-2xl neup-pressed-xs border border-white/45 bg-transparent">
                  <span class="text-[9px] uppercase text-slate-400 font-black">Jumlah Skin</span>
                  <p class="text-slate-800 text-sm font-black mt-1">{{ $account->skin_count }} Skins</p>
                </div>
                <div class="p-3 rounded-2xl neup-pressed-xs border border-white/45 bg-transparent">
                  <span class="text-[9px] uppercase text-slate-400 font-black">Tingkat Level</span>
                  <p class="text-slate-800 text-sm font-black mt-1">Lvl {{ $account->level }}</p>
                </div>
                <div class="p-3 rounded-2xl neup-pressed-xs border border-white/45 bg-transparent">
                  <span class="text-[9px] uppercase text-slate-400 font-black">Metode Login</span>
                  <p class="text-slate-800 text-sm font-black mt-1">{{ $account->login_method }}</p>
                </div>
                <div class="p-3 rounded-2xl neup-pressed-xs border border-white/45 bg-transparent">
                  <span class="text-[9px] uppercase text-slate-400 font-black">Status Bind</span>
                  <p class="text-slate-800 text-sm font-black mt-1">{{ $account->bind_status }}</p>
                </div>
                <div class="p-3 rounded-2xl neup-pressed-xs border border-white/45 bg-transparent">
                  <span class="text-[9px] uppercase text-slate-400 font-black">Status Pembelian</span>
                  @if($account->status === 'available')
                    <p class="text-emerald-600 text-sm font-black mt-1 flex items-center gap-1">
                      <span class="h-2 w-2 rounded-full bg-emerald-500 inline-block animate-pulse"></span> Ready
                    </p>
                  @else
                    <p class="text-rose-500 text-sm font-black mt-1">Sold Out</p>
                  @endif
                </div>
              </div>
            </div>

            <!-- Description -->
            @if($account->description)
              <div class="rounded-3xl border border-white/50 p-5 md:p-6 neup-flat bg-white text-left">
                <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider mb-3 border-b border-slate-100 pb-2">Informasi Tambahan Akun</h3>
                <p class="text-xs text-slate-600 leading-relaxed font-semibold whitespace-pre-wrap">{{ $account->description }}</p>
              </div>
            @endif

          </div>

          <!-- RIGHT COLUMN (Checkout step form + Sticky Invoice Summary) -->
          <div class="lg:col-span-1 space-y-6">
            
            <div style="position: sticky; top: 5rem; display: flex; flex-direction: column; gap: 1.5rem;">
              
              <!-- STEP 1: CONTACT DATA -->
              <div class="rounded-3xl border border-white/50 p-5 neup-flat bg-white text-left">
                <div class="flex items-center gap-3 border-b border-slate-300/40 pb-3 mb-4">
                  <span class="flex h-6 w-6 items-center justify-center rounded-lg text-[10px] font-black text-white bg-gradient-to-tr from-pink-500 to-fuchsia-600 shadow-xs">1</span>
                  <span class="text-xs font-black text-slate-800">Informasi Kontak Pembeli</span>
                </div>
                
                <div class="flex flex-col gap-2 font-semibold">
                  <label for="target_id" class="text-[10px] font-black text-slate-500 uppercase tracking-wider">Email Kontak Penerima Akun <span class="text-rose-500">*</span></label>
                  <input
                    type="email"
                    name="target_id"
                    id="target_id"
                    required
                    value="{{ Auth::check() ? Auth::user()->email : '' }}"
                    placeholder="Masukkan Email Aktif Anda"
                    class="rounded-2xl py-3 px-4 text-xs font-bold text-slate-700 neup-pressed-sm focus:outline-none border-t border-l border-white/40"
                  />
                  <p class="text-[9px] text-slate-400 leading-normal">Kredensial login akun akan dikirimkan ke alamat email tujuan ini secara manual oleh Admin setelah transaksi berhasil.</p>
                </div>
              </div>

              <!-- STEP 2: CHOOSE PAYMENT -->
              <div class="rounded-3xl border border-white/50 p-5 neup-flat bg-white text-left">
                <div class="flex items-center gap-3 border-b border-slate-300/40 pb-3 mb-4">
                  <span class="flex h-6 w-6 items-center justify-center rounded-lg text-[10px] font-black text-white bg-gradient-to-tr from-cyan-400 to-indigo-650 shadow-xs">2</span>
                  <span class="text-xs font-black text-slate-800">Pilih Metode Pembayaran</span>
                </div>

                <!-- Payment groups accordion styling -->
                <div class="space-y-4 max-h-[220px] overflow-y-auto pr-1 scrollbar-none">
                  <!-- QRIS -->
                  @foreach($paymentMethods as $pm)
                    <label for="payment-{{ $pm->id }}" class="payment-card rounded-2xl p-3 border border-white/50 neup-flat flex items-center justify-between cursor-pointer transition-all active:scale-98 select-none">
                      <input type="radio" name="payment_method_id" id="payment-{{ $pm->id }}" value="{{ $pm->id }}" class="hidden" required onclick="selectPaymentMethod(this, '{{ $pm->name }}', {{ $pm->fee }})">
                      <div class="flex items-center gap-2.5">
                        @if($pm->image)
                          <img src="{{ $pm->image }}" alt="{{ $pm->name }}" class="h-6 w-9 rounded-lg object-contain bg-white border border-slate-200 shadow-sm flex-shrink-0">
                        @endif
                        <div>
                          <p class="text-[11px] font-black text-slate-800 leading-tight">{{ $pm->name }}</p>
                          <p class="text-[8px] text-slate-400 font-bold">Admin: Rp {{ number_format($pm->fee, 0, ',', '.') }}</p>
                        </div>
                      </div>
                    </label>
                  @endforeach
                </div>
              </div>

              <!-- STEP 3: VOUCHER PROMO -->
              <div class="rounded-3xl border border-white/50 p-5 neup-flat bg-white text-left">
                <div class="flex items-center gap-3 border-b border-slate-300/40 pb-3 mb-4">
                  <i data-lucide="ticket" class="h-4.5 w-4.5 text-pink-500"></i>
                  <span class="text-xs font-black text-slate-800">Pakai Voucher Promo</span>
                </div>

                <div class="flex gap-2">
                  <input
                    type="text"
                    name="promo_code"
                    id="promo_input_code"
                    placeholder="Masukkan Voucher..."
                    class="w-full rounded-2xl py-2.5 px-3 text-xs font-bold text-slate-700 neup-pressed-sm focus:outline-none border-t border-l border-white/40"
                  />
                  <button type="button" onclick="applyPromoCode()" class="text-white font-black text-xs rounded-2xl px-4 py-2.5 cursor-pointer neup-orange-flat hover:neup-orange-pressed active:scale-95 transition-all border-none">
                    Gunakan
                  </button>
                </div>
                <div id="promo-status-box" class="hidden mt-2 p-2.5 rounded-xl text-[9px] font-bold text-left"></div>
              </div>

              <!-- STICKY BILLING SUMMARY -->
              <div class="rounded-3xl p-5.5 neup-dark-flat border border-slate-800 text-slate-100 relative overflow-hidden text-left bg-slate-900 shadow-2xl">
                
                <h3 class="text-xs font-black text-slate-100 border-b border-slate-800 pb-2.5 mb-4">Ringkasan Invoice</h3>

                <div class="space-y-3.5 text-[11px] font-bold text-slate-400">
                  <div class="flex justify-between">
                    <span>Produk</span>
                    <span class="text-slate-200">Akun {{ $account->game->name }}</span>
                  </div>
                  
                  <div class="flex justify-between">
                    <span>Judul Akun</span>
                    <span class="text-slate-250 font-black truncate max-w-[140px]">{{ $account->title }}</span>
                  </div>

                  <div class="flex justify-between">
                    <span>Metode Bayar</span>
                    <span id="summary-payment-method" class="text-slate-200">-</span>
                  </div>

                  <div id="summary-discount-row" class="hidden flex justify-between text-pink-400">
                    <span>Potongan Diskon</span>
                    <span id="summary-discount-val">-Rp 0</span>
                  </div>

                  <div class="border-t border-slate-800/80 my-1 pt-3.5 flex justify-between items-baseline">
                    <span class="text-xs font-black text-slate-100">Total Tagihan</span>
                    <span id="summary-total-price" class="text-base font-black text-fuchsia-400 font-mono">
                      Rp {{ number_format($account->price, 0, ',', '.') }}
                    </span>
                  </div>
                </div>

                <div id="validation-error-banner" class="hidden my-3 p-2.5 rounded-xl bg-rose-500/15 border border-rose-500/30 text-[9px] text-rose-400 font-bold"></div>

                @if($account->status === 'available')
                  <button type="submit" id="btn-buy-now" class="mt-5 w-full rounded-2xl py-3.5 text-center text-xs font-black tracking-wide uppercase transition-all shadow-xs neup-orange-flat text-white cursor-pointer hover:neup-orange-pressed active:scale-98 border-none">
                    Beli Akun Sekarang 🚀
                  </button>
                @else
                  <button type="button" disabled class="mt-5 w-full rounded-2xl py-3.5 text-center text-xs font-black tracking-wide uppercase bg-slate-800 text-slate-550 border-none cursor-not-allowed">
                    SUDAH TERJUAL OUT ❌
                  </button>
                @endif
              </div>

            </div><!-- end sticky area -->

          </div><!-- end right column -->

        </div><!-- end split grid -->
      </form>

    </div>
  </div>

  <script>
    const baseAccountPrice = {{ $account->price }};
    let selectedPayment = null;
    let promoDiscount = 0;

    // Javascript Gallery Slide Switcher
    function changeActiveImage(imgUrl, btn) {
      // Set active image source
      const mainImg = document.getElementById('active-gallery-img');
      if (mainImg) {
        mainImg.style.opacity = '0.3';
        setTimeout(() => {
          mainImg.src = imgUrl;
          mainImg.style.opacity = '1';
        }, 150);
      }

      // Dehighlight all thumbs
      document.querySelectorAll('.gallery-thumb-btn').forEach(b => {
        b.classList.remove('border-indigo-500', 'scale-95', 'shadow-inner');
        b.classList.add('border-slate-200');
      });

      // Highlight current thumb
      if (btn) {
        btn.classList.remove('border-slate-200');
        btn.classList.add('border-indigo-500', 'scale-95', 'shadow-inner');
      }
    }

    // Payment Selection
    function selectPaymentMethod(radio, name, fee) {
      document.querySelectorAll('.payment-card').forEach(card => {
        card.classList.remove('neup-pressed-sm', 'border-indigo-500', 'bg-[#ffeef4]', 'border-[#ff007f]');
        card.classList.add('neup-flat');
      });

      const parentLabel = radio.closest('label');
      if (parentLabel) {
        parentLabel.classList.remove('neup-flat');
        parentLabel.classList.add('neup-pressed-sm', 'bg-[#ffeef4]', 'border-[#ff007f]');
      }

      selectedPayment = { name, fee };
      document.getElementById('summary-payment-method').textContent = name;
      calculateTotal();
    }

    // Promo Coupon Calculation
    function applyPromoCode() {
      const code = document.getElementById('promo_input_code').value.toUpperCase().trim();
      const statusBox = document.getElementById('promo-status-box');

      if (!code) {
        promoDiscount = 0;
        statusBox.classList.add('hidden');
        calculateTotal();
        return;
      }

      statusBox.classList.remove('hidden');

      // Client-side coupons list matching seeders
      const promosList = {
        'CSHBKNEW': { discount: 25000, min: 30000, desc: 'Kupon CSHBKNEW aktif! Diskon Rp 25.000 terpasang.' },
        'WEEKENDGAMER': { discount: 15000, min: 50000, desc: 'Kupon WEEKENDGAMER aktif! Diskon Rp 15.000 terpasang.' },
        'GARENASPEKTA': { discount: 10000, min: 20000, desc: 'Kupon GARENASPEKTA aktif! Diskon Rp 10.000 terpasang.' }
      };

      const promo = promosList[code];
      if (promo) {
        if (baseAccountPrice >= promo.min) {
          promoDiscount = promo.discount;
          statusBox.className = 'mt-2 p-2.5 rounded-xl text-[9px] font-bold text-left bg-emerald-50 border border-emerald-100 text-emerald-600';
          statusBox.textContent = promo.desc;
        } else {
          promoDiscount = 0;
          statusBox.className = 'mt-2 p-2.5 rounded-xl text-[9px] font-bold text-left bg-rose-50 border border-rose-100 text-rose-500';
          statusBox.textContent = `Minimal transaksi untuk kupon ${code} adalah Rp ` + promo.min.toLocaleString('id-ID');
        }
      } else {
        promoDiscount = 0;
        statusBox.className = 'mt-2 p-2.5 rounded-xl text-[9px] font-bold text-left bg-rose-50 border border-rose-100 text-rose-500';
        statusBox.textContent = 'Kode promo tidak valid atau sudah kedaluwarsa.';
      }

      calculateTotal();
    }

    // Number count roller effects
    let currentTotalVal = baseAccountPrice;
    let currentDiscountVal = 0;

    function animateTotal(targetVal) {
      const duration = 300;
      const startTime = performance.now();
      const startVal = currentTotalVal;
      const el = document.getElementById('summary-total-price');
      if (!el) return;

      function step(currentTime) {
        const elapsed = currentTime - startTime;
        if (elapsed >= duration) {
          el.textContent = 'Rp ' + targetVal.toLocaleString('id-ID');
          currentTotalVal = targetVal;
        } else {
          const progress = elapsed / duration;
          const val = Math.round(startVal + (targetVal - startVal) * progress);
          el.textContent = 'Rp ' + val.toLocaleString('id-ID');
          requestAnimationFrame(step);
        }
      }
      requestAnimationFrame(step);
    }

    function calculateTotal() {
      const fee = selectedPayment ? selectedPayment.fee : 0;
      const total = Math.max(0, (baseAccountPrice - promoDiscount) + fee);

      animateTotal(total);

      const discRow = document.getElementById('summary-discount-row');
      const discVal = document.getElementById('summary-discount-val');
      if (promoDiscount > 0) {
        discRow.classList.remove('hidden');
        discVal.textContent = '-Rp ' + promoDiscount.toLocaleString('id-ID');
      } else {
        discRow.classList.add('hidden');
      }
    }

    // Form Submission check
    const form = document.getElementById('checkout-form');
    const errBanner = document.getElementById('validation-error-banner');
    if (form) {
      form.addEventListener('submit', (e) => {
        errBanner.classList.add('hidden');

        if (!selectedPayment) {
          e.preventDefault();
          errBanner.classList.remove('hidden');
          errBanner.textContent = 'MOHON PILIH METODE PEMBAYARAN TERLEBIH DAHULU!';
          return;
        }
      });
    }
  </script>
@endsection
