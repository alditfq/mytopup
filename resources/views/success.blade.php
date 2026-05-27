@extends('layouts.app')

@section('title', 'Pembayaran Sukses - GameTopup')

@section('content')
  <div class="flex-1 py-12" id="success-page">
    <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8 text-center">
      
      <!-- Checkmark Badge -->
      <div class="relative inline-flex items-center justify-center">
        <div id="success-checkmark-box" class="flex h-24 w-24 items-center justify-center rounded-full border border-white/50 neup-flat text-emerald-600 ring-8 ring-white/10 shadow-sm bg-white">
          <i data-lucide="check-circle" class="h-10 w-10 text-emerald-600"></i>
        </div>
        <div class="absolute -top-3 -right-3">
          <span id="success-sparkles" class="text-2xl inline-block">✨</span>
        </div>
      </div>

      <!-- Success Headings -->
      <div class="mt-6">
        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Transaksi Selesai</p>
        <h1 class="mt-2 text-2xl md:text-3xl font-black text-slate-800 tracking-tight">Pembayaran Berhasil! 🎉</h1>
        <p class="mt-2.5 text-xs text-slate-500 max-w-sm mx-auto font-bold leading-relaxed">
          Hore! Pembayaran Anda sudah diterima oleh sistem. Item game sedang didepositkan otomatis dalam beberapa detik.
        </p>
      </div>

      <!-- Progress Delivery Checklist -->
      <div class="mt-8 max-w-md mx-auto rounded-3xl border border-white/40 neup-pressed-xs py-4 px-5 text-left bg-transparent mb-6">
        <div class="space-y-3.5 font-bold text-xs text-slate-700">
          <div class="flex items-center gap-2.5">
            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500 text-white font-black text-[9px] shadow-sm flex-shrink-0">✓</span>
            <span>Pembayaran terverifikasi aman & legal</span>
          </div>
          <div class="flex items-center gap-2.5 text-slate-750">
            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500 text-white font-black text-[9px] shadow-sm flex-shrink-0">✓</span>
            <span>Item game berhasil dikirim ke ID: <strong id="success-target-id-string" class="font-mono text-slate-900">{{ $transaction->target_id }}</strong></span>
          </div>
        </div>
      </div>

      <!-- RECEIPT CARD -->
      <div class="mt-6 rounded-3xl border border-white/50 neup-flat p-5 md:p-6 text-left max-w-md mx-auto mb-6 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-300/50 pb-3.5 mb-4">
          <span class="flex items-center gap-2 text-xs font-black text-slate-800">
            <i data-lucide="receipt" class="h-4.5 w-4.5 text-slate-400"></i>
            Resi / Bukti Top Up
          </span>
          <span class="rounded-full bg-emerald-500/10 border border-emerald-500/15 px-2.5 py-0.5 text-[8px] font-black text-emerald-600 uppercase tracking-wider">
            BERHASIL (PAID)
          </span>
        </div>

        <div class="space-y-4 text-xs font-bold text-slate-500">
          <div class="flex justify-between">
            <span>No. Invois</span>
            <span id="success-receipt-invoice" class="text-slate-800 font-mono text-xs">{{ $transaction->invoice }}</span>
          </div>

          <div class="flex justify-between">
            <span>Nama Game</span>
            <span id="success-receipt-game" class="text-slate-800">{{ $transaction->game->name }}</span>
          </div>

          <div class="flex justify-between">
            <span>Target Player ID</span>
            <span id="success-receipt-player-id" class="text-slate-800 font-mono">
              {{ $transaction->target_id }}{{ $transaction->zone_id ? ' (' . $transaction->zone_id . ')' : '' }}
            </span>
          </div>

          <div class="flex justify-between">
            <span>Nickname Terverifikasi</span>
            <span id="success-receipt-nickname" class="text-slate-800 font-extrabold">{{ $transaction->nickname }}</span>
          </div>

          <div class="flex justify-between">
            <span>Spesifikasi Item</span>
            <span id="success-receipt-item" class="text-fuchsia-600 font-extrabold">{{ $transaction->nominal_name }}</span>
          </div>

          <div class="flex justify-between">
            <span>Metode Pembayaran</span>
            <span id="success-receipt-payment" class="text-slate-800">{{ $transaction->paymentMethod->name }}</span>
          </div>

          <div class="border-t border-slate-300/50 pt-3.5 flex justify-between items-baseline font-black">
            <span class="text-slate-700">Total Nominal</span>
            <span id="success-receipt-total" class="text-base text-slate-900 font-mono">Rp {{ number_format($transaction->total_payment, 0, ',', '.') }}</span>
          </div>
        </div>
      </div>

      <!-- CTA REDIRECT BUTTONS -->
      <div class="mt-8 flex flex-col sm:flex-row gap-4 max-w-md mx-auto font-black">
        <a href="{{ route('status.search') }}?invoice={{ $transaction->invoice }}" id="btn-success-track" class="flex-1 rounded-2xl py-4 text-center text-xs font-black text-white neup-orange-flat hover:neup-orange-pressed active:scale-98 transition-all flex items-center justify-center gap-2 cursor-pointer decoration-none">
          <i data-lucide="clipboard-list" class="h-4 w-4"></i> Lacak Pesanan
        </a>
        
        <a href="{{ route('home') }}" id="btn-success-home" class="flex-1 rounded-2xl py-4 text-center text-xs font-black text-slate-600 flex items-center justify-center gap-2 cursor-pointer neup-flat-sm hover:neup-pressed-sm active:scale-98 bg-white decoration-none">
          <i data-lucide="gamepad-2" class="h-4 w-4"></i> Top Up Game Lain
        </a>
      </div>

      <!-- ⭐ RATING & REVIEW SECTION -->
      <div class="mt-10 max-w-md mx-auto">
        @if(session('review_success'))
          <!-- Already submitted state -->
          <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6 text-center shadow-sm">
            <div class="flex justify-center mb-3">
              <div class="h-12 w-12 rounded-full bg-emerald-100 flex items-center justify-center">
                <i data-lucide="star" class="h-6 w-6 text-emerald-500"></i>
              </div>
            </div>
            <p class="text-sm font-black text-emerald-700">{{ session('review_success') }}</p>
            <div class="flex justify-center gap-1 mt-2">
              @for($i = 1; $i <= 5; $i++)<span class="text-amber-400 text-lg">★</span>@endfor
            </div>
          </div>
        @elseif($alreadyReviewed)
          <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-5 text-center shadow-sm">
            <p class="text-xs font-black text-emerald-600 flex items-center justify-center gap-2">
              <i data-lucide="check-circle" class="h-4 w-4"></i> Ulasan Anda sudah dikirim. Terima kasih!
            </p>
          </div>
        @else
          <!-- Rating Form -->
          <div class="rounded-3xl border border-slate-200 bg-white neup-flat p-6 shadow-sm text-left">
            <div class="text-center mb-5">
              <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Bagaimana pengalaman top up kamu?</p>
              <h3 class="mt-1 text-base font-black text-slate-800">Beri Rating untuk <span class="text-fuchsia-600">{{ $transaction->game->name }}</span></h3>
            </div>

            @if(session('review_error'))
              <div class="mb-4 rounded-xl bg-rose-50 border border-rose-200 p-3 text-xs font-bold text-rose-600 text-center">
                {{ session('review_error') }}
              </div>
            @endif

            <form action="{{ route('review.store', $transaction->invoice) }}" method="POST" id="review-form" class="space-y-4">
              @csrf

              <!-- Star Rating -->
              <div class="flex flex-col items-center gap-2">
                <div class="flex gap-2" id="star-container">
                  @for($s = 1; $s <= 5; $s++)
                    <button type="button" class="star-btn text-3xl text-slate-300 hover:text-amber-400 transition-all leading-none"
                      data-value="{{ $s }}" id="star-{{ $s }}" onclick="setRating({{ $s }})">★</button>
                  @endfor
                </div>
                <span class="text-[10px] font-bold text-slate-400" id="rating-label">Pilih rating</span>
                <input type="hidden" name="rating" id="rating-input" value="">
              </div>

              <!-- Reviewer Name -->
              <div class="flex flex-col gap-1">
                <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Nama Kamu</label>
                <input type="text" name="reviewer_name"
                  value="{{ old('reviewer_name', auth()->user()?->name ?? '') }}"
                  placeholder="Masukkan nama kamu..."
                  class="w-full rounded-xl border border-slate-200 bg-slate-50 text-slate-800 text-xs px-3.5 py-2.5 focus:border-fuchsia-400 focus:ring-1 focus:ring-fuchsia-300 outline-none transition placeholder:text-slate-400 font-semibold"
                  required>
              </div>

              <!-- Message -->
              <div class="flex flex-col gap-1">
                <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Komentar <span class="font-normal normal-case">(opsional)</span></label>
                <textarea name="message" rows="3"
                  placeholder="Ceritakan pengalamanmu top up di sini... (opsional)"
                  class="w-full rounded-xl border border-slate-200 bg-slate-50 text-slate-800 text-xs px-3.5 py-2.5 resize-none focus:border-fuchsia-400 focus:ring-1 focus:ring-fuchsia-300 outline-none transition placeholder:text-slate-400 font-semibold">{{ old('message') }}</textarea>
              </div>

              <button type="submit" id="submit-review-btn"
                class="w-full rounded-2xl py-3.5 text-xs font-black text-white neup-orange-flat hover:neup-orange-pressed active:scale-98 transition-all flex items-center justify-center gap-2 border-none cursor-pointer"
                disabled>
                <i data-lucide="send" class="h-4 w-4"></i> Kirim Ulasan
              </button>
            </form>
          </div>
        @endif
      </div>

      <!-- RECOMMENDATION AREA -->
      <div class="mt-14 border-t border-slate-300/55 pt-10">
        <h3 class="text-xs font-black tracking-widest text-slate-400 uppercase mb-6 text-center">Top Up Game Populer Lainnya</h3>
        
        <div id="success-recs-container" class="grid grid-cols-2 sm:grid-cols-4 gap-4">
          <div class="neup-flat border border-white/50 p-3 rounded-2xl cursor-pointer hover:neup-pressed-xs" onclick="window.location.href='/game/mobile-legends'">
            <div class="h-20 rounded-xl bg-slate-100 overflow-hidden mb-2">
              <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?w=200&q=85" class="h-full w-full object-cover">
            </div>
            <p class="text-[10px] font-black text-slate-700 leading-tight m-0 text-left truncate">Mobile Legends</p>
          </div>
          <div class="neup-flat border border-white/50 p-3 rounded-2xl cursor-pointer hover:neup-pressed-xs" onclick="window.location.href='/game/free-fire'">
            <div class="h-20 rounded-xl bg-slate-100 overflow-hidden mb-2">
              <img src="https://images.unsplash.com/photo-1552820728-8b83bb6b773f?w=200&q=85" class="h-full w-full object-cover">
            </div>
            <p class="text-[10px] font-black text-slate-700 leading-tight m-0 text-left truncate">Free Fire</p>
          </div>
          <div class="neup-flat border border-white/50 p-3 rounded-2xl cursor-pointer hover:neup-pressed-xs" onclick="window.location.href='/game/pubg-mobile'">
            <div class="h-20 rounded-xl bg-slate-100 overflow-hidden mb-2">
              <img src="https://images.unsplash.com/photo-1511512578047-dfb367046420?w=200&q=85" class="h-full w-full object-cover">
            </div>
            <p class="text-[10px] font-black text-slate-700 leading-tight m-0 text-left truncate">PUBG Mobile</p>
          </div>
          <div class="neup-flat border border-white/50 p-3 rounded-2xl cursor-pointer hover:neup-pressed-xs" onclick="window.location.href='/game/valorant'">
            <div class="h-20 rounded-xl bg-slate-100 overflow-hidden mb-2">
              <img src="https://images.unsplash.com/photo-1612287230202-1bf1d85d1bdf?w=200&q=85" class="h-full w-full object-cover">
            </div>
            <p class="text-[10px] font-black text-slate-700 leading-tight m-0 text-left truncate">Valorant</p>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection

@push('scripts')
<script>
  const ratingLabels = ['', 'Sangat Buruk 😞', 'Kurang Memuaskan 😐', 'Cukup Baik 🙂', 'Bagus! 😊', 'Luar Biasa! 🌟'];
  let currentRating = 0;

  function setRating(val) {
    currentRating = val;
    document.getElementById('rating-input').value = val;
    document.getElementById('rating-label').textContent = ratingLabels[val] || '';

    document.querySelectorAll('.star-btn').forEach((btn, i) => {
      btn.style.color = (i < val) ? '#f59e0b' : '#cbd5e1';
      btn.style.transform = (i < val) ? 'scale(1.15)' : 'scale(1)';
    });

    document.getElementById('submit-review-btn').disabled = false;
    document.getElementById('submit-review-btn').style.opacity = '1';
  }

  // Hover effects
  document.querySelectorAll('.star-btn').forEach(btn => {
    btn.addEventListener('mouseenter', () => {
      const hoverVal = parseInt(btn.dataset.value);
      document.querySelectorAll('.star-btn').forEach((b, i) => {
        b.style.color = (i < hoverVal) ? '#fbbf24' : '#cbd5e1';
      });
    });
    btn.addEventListener('mouseleave', () => {
      document.querySelectorAll('.star-btn').forEach((b, i) => {
        b.style.color = (i < currentRating) ? '#f59e0b' : '#cbd5e1';
      });
    });
  });

  // Initially disable submit
  document.getElementById('submit-review-btn').style.opacity = '0.5';
</script>
@endpush
