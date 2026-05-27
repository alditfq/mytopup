@extends('layouts.admin')

@section('title', 'Admin Panel - Audit Transaksi #' . $tx->invoice)

@section('content')
  <div class="text-left">
    <!-- Top Header -->
    <div class="border-b border-slate-800 pb-5 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <a href="{{ route('admin.transactions') }}" class="group inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-800 border border-slate-700 text-[10px] font-bold text-slate-300 hover:text-white transition-all decoration-none">
          <i data-lucide="arrow-left" class="h-3.5 w-3.5 transition-transform group-hover:-translate-x-0.5"></i> KEMBALI
        </a>
        <h1 class="text-2xl font-black mt-3 tracking-tight text-white">Audit Detail Transaksi</h1>
        <p class="text-xs text-slate-400 mt-1 font-semibold">Audit rincian pemesanan saldo/diamonds, telusuri log status, dan lakukan tindakan manual.</p>
      </div>

      <!-- Quick Simulated WhatsApp trigger -->
      <button onclick="simulateNotification()" class="bg-gradient-to-r from-blue-600 to-cyan-500 border-none text-white font-extrabold text-xs py-3 px-5 rounded-2xl cursor-pointer hover:shadow-lg hover:shadow-blue-500/20 active:scale-95 transition-all shadow-md flex items-center gap-2">
        <i data-lucide="send" class="h-4 w-4"></i> Kirim Notifikasi Ulang
      </button>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
      <div id="status-toast" class="mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-4 text-xs font-bold text-emerald-400 shadow-md">
        ✓ {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="mb-6 rounded-2xl bg-rose-500/10 border border-rose-500/30 p-4 text-xs font-bold text-rose-400 shadow-md">
        ✕ {{ session('error') }}
      </div>
    @endif

    <!-- Dynamic Toast Notification Area -->
    <div id="ajax-toast" class="hidden mb-6 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 p-4 text-xs font-bold text-cyan-400 shadow-md animate-pulse">
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start mb-10">
      
      <!-- LEFT SIDE: TRANSACTION DETAIL CARD (Col 7) -->
      <div class="lg:col-span-7 space-y-6">
        <div class="rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl text-slate-300">
          <div class="flex items-center justify-between border-b border-slate-800 pb-3.5 mb-5">
            <h3 class="text-xs font-black uppercase tracking-wider text-white flex items-center gap-2">
              <i data-lucide="receipt" class="h-4.5 w-4.5 text-cyan-400"></i> Rincian Invoice Belanja
            </h3>
            <span class="font-mono text-xs font-black text-cyan-400">{{ $tx->invoice }}</span>
          </div>

          <div class="space-y-4 text-xs font-semibold">
            <div class="grid grid-cols-2 gap-4 border-b border-slate-800/60 pb-3">
              <span class="text-slate-400 uppercase text-[9px] tracking-wider">Nama Game</span>
              <span class="text-right text-white font-bold">{{ $tx->game->name }}</span>
            </div>
            <div class="grid grid-cols-2 gap-4 border-b border-slate-800/60 pb-3">
              <span class="text-slate-400 uppercase text-[9px] tracking-wider">Nominal Item</span>
              <span class="text-right text-cyan-400 font-bold font-mono">{{ $tx->nominal_name }}</span>
            </div>
            <div class="grid grid-cols-2 gap-4 border-b border-slate-800/60 pb-3">
              @if($tx->game_account_id)
                <span class="text-slate-400 uppercase text-[9px] tracking-wider">Email Penerima Akun</span>
                <span class="text-right text-white font-mono font-bold">{{ $tx->target_id }}</span>
              @else
                <span class="text-slate-400 uppercase text-[9px] tracking-wider">Target Player ID</span>
                <span class="text-right text-white font-mono font-bold">{{ $tx->target_id }}</span>
              @endif
            </div>
            @if(!$tx->game_account_id && $tx->zone_id)
              <div class="grid grid-cols-2 gap-4 border-b border-slate-800/60 pb-3">
                <span class="text-slate-400 uppercase text-[9px] tracking-wider">Zone / Server ID</span>
                <span class="text-right text-white font-mono font-bold">({{ $tx->zone_id }})</span>
              </div>
            @endif
            <div class="grid grid-cols-2 gap-4 border-b border-slate-800/60 pb-3">
              <span class="text-slate-400 uppercase text-[9px] tracking-wider">Total Pembayaran</span>
              <span class="text-right text-emerald-400 font-mono font-black">Rp {{ number_format($tx->total_payment, 0, ',', '.') }}</span>
            </div>
            <div class="grid grid-cols-2 gap-4 border-b border-slate-800/60 pb-3">
              <span class="text-slate-400 uppercase text-[9px] tracking-wider">Metode Pembayaran</span>
              <span class="text-right text-white font-bold">{{ $tx->paymentMethod->name }} (fee: Rp {{ number_format($tx->paymentMethod->fee, 0, ',', '.') }})</span>
            </div>
            <div class="grid grid-cols-2 gap-4 border-b border-slate-800/60 pb-3">
              <span class="text-slate-400 uppercase text-[9px] tracking-wider">Tanggal Dibuat</span>
              <span class="text-right text-slate-300">{{ $tx->created_at->format('d M Y, H:i') }} WIB</span>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <span class="text-slate-400 uppercase text-[9px] tracking-wider">Status Pembayaran</span>
              <span class="text-right">
                @if($tx->status === 'success')
                  <span class="rounded bg-emerald-500/10 border border-emerald-500/20 px-3 py-1 text-[8px] font-black text-emerald-400 uppercase tracking-widest">PAID SUCCESS</span>
                @elseif($tx->status === 'waiting_delivery')
                  <span class="rounded bg-amber-500/10 border border-amber-500/20 px-3 py-1 text-[8px] font-black text-amber-400 uppercase tracking-widest animate-pulse">⏳ MENUNGGU PENGIRIMAN</span>
                @elseif($tx->status === 'delivered')
                  <span class="rounded bg-emerald-500/10 border border-emerald-500/20 px-3 py-1 text-[8px] font-black text-emerald-400 uppercase tracking-widest">✅ AKUN TERKIRIM</span>
                @elseif($tx->status === 'failed')
                  <span class="rounded bg-rose-500/10 border border-rose-500/20 px-3 py-1 text-[8px] font-black text-rose-400 uppercase tracking-widest">FAILED / REFUNDED</span>
                @else
                  <span class="rounded bg-amber-500/10 border border-amber-500/20 px-3 py-1 text-[8px] font-black text-amber-400 uppercase tracking-widest animate-pulse">PENDING REVIEW</span>
                @endif
              </span>
            </div>
          </div>
        </div>

        <!-- BUYER INFO CARD -->
        <div class="rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl text-slate-300">
          <div class="flex items-center gap-2.5 border-b border-slate-800 pb-3.5 mb-5">
            <i data-lucide="user" class="h-4.5 w-4.5 text-cyan-400"></i>
            <h3 class="text-xs font-black uppercase tracking-wider text-white">Profil Data Pembeli</h3>
          </div>

          @if($tx->user)
            <div class="flex items-center gap-4 mb-4">
              <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100&h=100&fit=crop&q=80" alt="Avatar" class="h-11 w-11 rounded-xl object-cover border border-slate-800 shadow-md">
              <div class="text-left">
                <p class="font-black text-white leading-tight">{{ $tx->user->name }}</p>
                <p class="text-[9px] text-cyan-400 font-extrabold mt-0.5 tracking-wider uppercase">MEMBER REGISTERED</p>
              </div>
            </div>
            <div class="space-y-3.5 text-xs font-semibold">
              <div class="grid grid-cols-2 gap-4 border-b border-slate-800/60 pb-2.5">
                <span class="text-slate-400 uppercase text-[9px] tracking-wider">Email Akun</span>
                <span class="text-right text-slate-200">{{ $tx->user->email }}</span>
              </div>
              @if($tx->game_account_id)
                <div class="grid grid-cols-2 gap-4 border-b border-slate-800/60 pb-2.5">
                  <span class="text-slate-400 uppercase text-[9px] tracking-wider">Email Tujuan Pengiriman</span>
                  <span class="text-right text-cyan-400 font-mono font-bold">{{ $tx->target_id }}</span>
                </div>
              @endif
              <div class="grid grid-cols-2 gap-4">
                <span class="text-slate-400 uppercase text-[9px] tracking-wider">No. WhatsApp</span>
                <span class="text-right text-slate-200">{{ $tx->user->phone ?? '-' }}</span>
              </div>
            </div>
          @else
            <div class="space-y-3.5 text-xs font-semibold">
              <div class="grid grid-cols-2 gap-4 border-b border-slate-800/60 pb-2.5">
                <span class="text-slate-400 uppercase text-[9px] tracking-wider">Profil Akun</span>
                <span class="text-right text-white">Guest Customer (Tamu)</span>
              </div>
              <div class="grid grid-cols-2 gap-4 border-b border-slate-800/60 pb-2.5">
                <span class="text-slate-400 uppercase text-[9px] tracking-wider">Pemberian Nickname</span>
                <span class="text-right text-white font-mono font-bold">{{ $tx->nickname ?? '-' }}</span>
              </div>
              @if($tx->game_account_id)
                <div class="grid grid-cols-2 gap-4">
                  <span class="text-slate-400 uppercase text-[9px] tracking-wider">Email Tujuan Pengiriman</span>
                  <span class="text-right text-cyan-400 font-mono font-bold">{{ $tx->target_id }}</span>
                </div>
              @else
                <div class="grid grid-cols-2 gap-4">
                  <span class="text-slate-400 uppercase text-[9px] tracking-wider">Saluran Kontak</span>
                  <span class="text-right text-slate-300 font-mono">Diterima via Gerbang Pembayaran</span>
                </div>
              @endif
            </div>
          @endif
        </div>
      </div>

      <!-- RIGHT SIDE: STATUS TIMELINE & MANUAL ACTIONS (Col 5) -->
      <div class="lg:col-span-5 space-y-6">
        
        @if($tx->game_account_id)
          <!-- ACCOUNT GAME FULFILLMENT PANEL -->
          <div class="rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl text-slate-300 text-left">
            <div class="flex items-center gap-2.5 border-b border-slate-800 pb-3.5 mb-5">
              <i data-lucide="key-round" class="h-4.5 w-4.5 text-pink-500"></i>
              <h3 class="text-xs font-black uppercase tracking-wider text-white">Fulfillment Akun Game</h3>
            </div>

            @if($tx->status === 'waiting_delivery')
              <div class="mb-4 bg-slate-800/40 rounded-2xl p-4 border border-slate-700/45">
                <span class="text-[9px] uppercase tracking-wider text-pink-400 font-extrabold block mb-1.5">🔑 Kredensial Asli Database (Panduan Admin)</span>
                <pre class="text-[10px] font-mono text-slate-300 bg-slate-900/60 p-2.5 rounded-xl border border-slate-800 m-0 overflow-x-auto whitespace-pre-wrap">{{ $tx->gameAccount->account_data }}</pre>
              </div>

              <form action="{{ route('admin.transactions.deliver-account', $tx->id) }}" method="POST" class="space-y-4">
                @csrf
                <div class="flex flex-col gap-1.5">
                  <label for="account_email" class="text-[9px] uppercase tracking-wider text-slate-400 font-extrabold">Username / Email Akun</label>
                  <input
                    type="text"
                    name="account_email"
                    id="account_email"
                    required
                    placeholder="Masukkan email / username login akun..."
                    class="w-full rounded-xl border border-slate-700 bg-slate-800 text-slate-200 px-3.5 py-2.5 text-xs font-bold focus:outline-none focus:border-pink-500"
                  />
                </div>

                <div class="flex flex-col gap-1.5">
                  <label for="account_password" class="text-[9px] uppercase tracking-wider text-slate-400 font-extrabold">Password Akun</label>
                  <input
                    type="text"
                    name="account_password"
                    id="account_password"
                    required
                    placeholder="Masukkan password akun..."
                    class="w-full rounded-xl border border-slate-700 bg-slate-800 text-slate-200 px-3.5 py-2.5 text-xs font-bold focus:outline-none focus:border-pink-500"
                  />
                </div>

                <div class="flex flex-col gap-1.5">
                  <label for="notes" class="text-[9px] uppercase tracking-wider text-slate-400 font-extrabold">Catatan Tambahan (Opsional)</label>
                  <textarea
                    name="notes"
                    id="notes"
                    rows="3"
                    placeholder="Contoh: Akun bind login Gmail, harap siapkan kode verifikasi..."
                    class="w-full rounded-xl border border-slate-700 bg-slate-800 text-slate-200 px-3.5 py-2.5 text-xs font-bold focus:outline-none focus:border-pink-500 resize-none"
                  ></textarea>
                </div>

                <button type="submit" class="w-full font-black tracking-wide uppercase py-3.5 rounded-xl transition-all cursor-pointer bg-gradient-to-r from-pink-500 to-indigo-600 hover:shadow-lg hover:shadow-pink-500/25 border-none text-white text-[11px] active:scale-98 flex items-center justify-center gap-2">
                  <i data-lucide="mail" class="h-4 w-4"></i> KIRIM KREDENSIAL VIA EMAIL
                </button>
              </form>
            @elseif($tx->status === 'delivered')
              <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-2xl p-4.5 text-xs">
                <div class="flex items-center gap-2 text-emerald-400 font-black">
                  <i data-lucide="check-circle" class="h-4.5 w-4.5"></i>
                  <span>SUKSES TERKIRIM</span>
                </div>
                <p class="text-[11px] text-slate-350 mt-2 font-medium leading-relaxed">
                  Kredensial akun game telah dikirim ke email pembeli pada <strong class="text-white font-mono">{{ $tx->delivered_at->format('d M Y, H:i') }}</strong> oleh <strong class="text-white">{{ $tx->delivered_by }}</strong>.
                </p>
                <div class="mt-4 pt-3.5 border-t border-slate-800 flex items-center justify-between text-[10px] text-slate-400 font-bold">
                  <span>Metode Pengiriman:</span>
                  <span class="text-emerald-400 font-extrabold">Laravel Mail System</span>
                </div>
              </div>
            @else
              <div class="bg-slate-800/30 border border-slate-800 rounded-2xl p-4 text-center text-xs text-slate-400 font-bold py-6">
                <i data-lucide="clock" class="h-8 w-8 text-slate-500 mx-auto mb-2"></i>
                <span>Menunggu Pembayaran Pembeli Selesai</span>
              </div>
            @endif
          </div>
        @endif

        <!-- TIMELINE LOGS -->
        <div class="rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl text-slate-300">
          <div class="flex items-center gap-2.5 border-b border-slate-800 pb-3.5 mb-5">
            <i data-lucide="activity" class="h-4.5 w-4.5 text-cyan-400"></i>
            <h3 class="text-xs font-black uppercase tracking-wider text-white">Timeline Log Status</h3>
          </div>

          <div class="relative pl-6 space-y-5 border-l border-slate-800 ml-2 py-1 text-left">
            @forelse($tx->status_logs as $log)
              <div class="relative">
                <!-- Timeline Dot Indicator -->
                <div class="absolute -left-[32.5px] top-0.5 h-4 w-4 rounded-full border border-slate-800 bg-[#090e1a] flex items-center justify-center shadow-sm">
                  <div class="h-1.5 w-1.5 rounded-full bg-cyan-400"></div>
                </div>
                <p class="text-[10px] font-mono font-black text-cyan-400">{{ $log['time'] ?? date('H:i') }} WIB</p>
                <p class="text-xs text-slate-200 mt-1 font-semibold leading-relaxed">{{ $log['message'] ?? '' }}</p>
              </div>
            @empty
              <p class="text-xs text-slate-500 font-bold py-6 text-center">Belum ada catatan log aktivitas.</p>
            @endforelse
          </div>
        </div>

        <!-- MANUAL CONTROLS & TRIGGERS -->
        <div class="rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl text-slate-300 text-left">
          <div class="flex items-center gap-2.5 border-b border-slate-800 pb-3.5 mb-5">
            <i data-lucide="shield-check" class="h-4.5 w-4.5 text-cyan-400"></i>
            <h3 class="text-xs font-black uppercase tracking-wider text-white">Kontrol Manual Admin</h3>
          </div>

          <div class="space-y-4">
            <p class="text-[10px] text-slate-400 font-bold leading-normal">
              Gunakan tombol tindakan jika sistem API otomatis mengalami antrian atau hambatan server game.
            </p>

            <div class="grid grid-cols-2 gap-3.5 pt-2">
              <!-- Deliver -->
              <form action="{{ route('admin.transactions.deliver', $tx->id) }}" method="POST" class="m-0 p-0">
                @csrf
                <button
                  type="submit"
                  class="w-full font-black tracking-wide uppercase py-3 rounded-xl transition-all cursor-pointer bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-[10px] hover:bg-emerald-500/20 flex items-center justify-center gap-1.5"
                >
                  <i data-lucide="check-circle" class="h-3.5 w-3.5"></i> DELIVER MANUALLY
                </button>
              </form>

              <!-- Refund -->
              <form action="{{ route('admin.transactions.refund', $tx->id) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan & refund transaksi ini secara manual? Status diubah menjadi FAILED.')">
                @csrf
                <button
                  type="submit"
                  class="w-full font-black tracking-wide uppercase py-3 rounded-xl transition-all cursor-pointer bg-rose-500/10 border border-rose-500/30 text-rose-400 text-[10px] hover:bg-rose-500/20 flex items-center justify-center gap-1.5"
                >
                  <i data-lucide="x-circle" class="h-3.5 w-3.5"></i> MANUAL REFUND
                </button>
              </form>
            </div>
            
            <!-- Quick update dropdown as fallback -->
            <div class="border-t border-slate-800 pt-4 flex flex-col gap-2">
              <label class="text-[9px] uppercase tracking-wider text-slate-400 font-extrabold">Setel Ulang Status Pembayaran</label>
              <form action="{{ route('admin.transactions.update-status', $tx->id) }}" method="POST" class="flex gap-2 w-full">
                @csrf
                <select name="status" class="flex-1 rounded-xl border border-slate-700 bg-slate-800 text-slate-200 px-3 py-2.5 text-xs font-bold focus:outline-none cursor-pointer">
                  <option value="pending" {{ $tx->status === 'pending' ? 'selected' : '' }}>PENDING</option>
                  <option value="success" {{ $tx->status === 'success' ? 'selected' : '' }}>SUCCESS</option>
                  <option value="failed" {{ $tx->status === 'failed' ? 'selected' : '' }}>FAILED</option>
                </select>
                <button type="submit" class="border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-100 font-extrabold rounded-xl px-4 py-2.5 text-xs cursor-pointer transition-all active:scale-95 shadow-sm">
                  Set
                </button>
              </form>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>

  <script>
    function simulateNotification() {
      const toast = document.getElementById('ajax-toast');
      if (toast) {
        toast.innerHTML = '✓ WhatsApp Notifikasi Invoice berhasil dikirimkan kembali ke nomor WhatsApp Pelanggan! 📱';
        toast.classList.remove('hidden');
        
        const standardToast = document.getElementById('status-toast');
        if (standardToast) standardToast.classList.add('hidden');
        
        setTimeout(() => {
          toast.classList.add('hidden');
        }, 5000);
      }
    }
  </script>
@endsection
