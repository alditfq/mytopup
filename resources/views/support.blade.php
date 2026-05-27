@extends('layouts.app')

@section('title', 'Hubungi Bantuan CS - GameTopup')

@section('content')

  <div class="flex-1 py-8" id="support-page">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
      
      <!-- Page title banner -->
      <div class="text-center mb-10">
        <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl border border-white/55 neup-flat text-pink-655 mb-3.5 bg-white shadow-sm">
          <i data-lucide="help-circle" class="h-6 w-6 text-pink-600"></i>
        </div>
        <h1 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">Pusat Bantuan Pelanggan</h1>
        <p class="text-xs text-slate-500 mt-1 max-w-lg mx-auto font-bold leading-relaxed">Kami siap melayani kebutuhan informasi kendala top-up game Anda. Hubungi kami melalui saluran bantuan resmi di bawah.</p>
      </div>

      <!-- FAQ EXPLORE & CONTACTS -->
      <div class="space-y-6 text-left">
        
        <div class="rounded-3xl border border-white/50 neup-flat p-5 md:p-6 shadow-none bg-white shadow-sm">
          <div class="flex items-center gap-2.5 border-b border-slate-300/40 pb-3.5 mb-5 text-left">
            <i data-lucide="help-circle" class="h-5 w-5 text-pink-600"></i>
            <h3 class="text-sm font-black text-slate-800">Temukan Jawaban Cepat (FAQ)</h3>
          </div>

          <!-- Tabs list shortcuts -->
          <div class="flex flex-wrap gap-1.5 overflow-x-auto pb-1 scrollbar-none justify-start">
            <button onclick="filterFaq('all', this)" class="faq-tab-btn active text-[10px] md:text-xs font-black rounded-xl px-4 py-2.5 cursor-pointer transition-all border-none bg-[#ff007f] text-white shadow-sm">
              Semua Tanya Jawab
            </button>
            <button onclick="filterFaq('general', this)" class="faq-tab-btn text-[10px] md:text-xs font-black rounded-xl px-4 py-2.5 cursor-pointer transition-all bg-transparent text-slate-600 border border-white/20 neup-flat-sm hover:neup-pressed-sm">
              Umum & Proses
            </button>
            <button onclick="filterFaq('payment', this)" class="faq-tab-btn text-[10px] md:text-xs font-black rounded-xl px-4 py-2.5 cursor-pointer transition-all bg-transparent text-slate-600 border border-white/20 neup-flat-sm hover:neup-pressed-sm">
              Metode Pembayaran
            </button>
            <button onclick="filterFaq('refund', this)" class="faq-tab-btn text-[10px] md:text-xs font-black rounded-xl px-4 py-2.5 cursor-pointer transition-all bg-transparent text-slate-600 border border-white/20 neup-flat-sm hover:neup-pressed-sm">
              Refund & Salah ID
            </button>
          </div>

          <!-- FAQ Lists accordion -->
          <div class="space-y-3 mt-6 text-left" id="support-faq-lists">
            
            <div class="faq-item rounded-2xl neup-flat border border-white/50 overflow-hidden text-left transition-all bg-white" data-category="general">
              <button onclick="toggleFaq(0, this)" class="w-full flex items-center justify-between p-5 text-left border-none bg-transparent cursor-pointer font-black text-xs text-slate-700">
                <span>Bagaimana cara melakukan top up di GameTopup?</span>
                <i data-lucide="chevron-down" class="h-4.5 w-4.5 text-slate-400 transition-all dropdown-chevron"></i>
              </button>
              <div id="faq-ans-0" class="faq-answer hidden px-5 pb-5 pt-1 text-xs text-slate-500 leading-relaxed font-semibold">
                Cukup pilih game yang ingin Anda top up, masukkan User ID & Zone ID (jika ada), pilih jumlah nominal item yang diinginkan, pilih metode pembayaran, masukkan kode voucher promo jika ada, dan klik Beli Sekarang. Lakukan pembayaran sesuai petunjuk pembayaran.
              </div>
            </div>

            <div class="faq-item rounded-2xl neup-flat border border-white/50 overflow-hidden text-left transition-all bg-white" data-category="general">
              <button onclick="toggleFaq(1, this)" class="w-full flex items-center justify-between p-5 text-left border-none bg-transparent cursor-pointer font-black text-xs text-slate-700">
                <span>Berapa lama proses pengisian diamond/item game?</span>
                <i data-lucide="chevron-down" class="h-4.5 w-4.5 text-slate-400 transition-all dropdown-chevron"></i>
              </button>
              <div id="faq-ans-1" class="faq-answer hidden px-5 pb-5 pt-1 text-xs text-slate-500 leading-relaxed font-semibold">
                Hampir seluruh transaksi kami diselesaikan secara otomatis dalam waktu 1-3 menit setelah pembayaran Anda berhasil didepositkan. Jika ada antrian server game, proses terkadang dapat membutuhkan waktu hingga 15 menit.
              </div>
            </div>

            <div class="faq-item rounded-2xl neup-flat border border-white/50 overflow-hidden text-left transition-all bg-white" data-category="payment">
              <button onclick="toggleFaq(2, this)" class="w-full flex items-center justify-between p-5 text-left border-none bg-transparent cursor-pointer font-black text-xs text-slate-700">
                <span>Metode pembayaran apa saja yang didukung?</span>
                <i data-lucide="chevron-down" class="h-4.5 w-4.5 text-slate-400 transition-all dropdown-chevron"></i>
              </button>
              <div id="faq-ans-2" class="faq-answer hidden px-5 pb-5 pt-1 text-xs text-slate-500 leading-relaxed font-semibold">
                Kami mendukung berbagai pilihan metode pembayaran instan populer di Indonesia, meliputi E-Wallet (DANA, OVO, ShopeePay), QRIS Kode Standar nasional Indonesia, dan Virtual Account Transfer bank utama (BCA, Mandiri, BNI, BRI).
              </div>
            </div>

            <div class="faq-item rounded-2xl neup-flat border border-white/50 overflow-hidden text-left transition-all bg-white" data-category="payment">
              <button onclick="toggleFaq(3, this)" class="w-full flex items-center justify-between p-5 text-left border-none bg-transparent cursor-pointer font-black text-xs text-slate-700">
                <span>Apakah ada tambahan biaya admin?</span>
                <i data-lucide="chevron-down" class="h-4.5 w-4.5 text-slate-400 transition-all dropdown-chevron"></i>
              </button>
              <div id="faq-ans-3" class="faq-answer hidden px-5 pb-5 pt-1 text-xs text-slate-500 leading-relaxed font-semibold">
                Kami menerapkan biaya admin yang transparan dan sangat minim. Untuk QRIS gratis biaya admin, OVO dikenakan Rp 200, dan Transfer Virtual Account bank dikenakan Rp 1.000 per transaksi.
              </div>
            </div>

            <div class="faq-item rounded-2xl neup-flat border border-white/50 overflow-hidden text-left transition-all bg-white" data-category="refund">
              <button onclick="toggleFaq(4, this)" class="w-full flex items-center justify-between p-5 text-left border-none bg-transparent cursor-pointer font-black text-xs text-slate-700">
                <span>Dapatkah saya membatalkan atau me-refund transaksi?</span>
                <i data-lucide="chevron-down" class="h-4.5 w-4.5 text-slate-400 transition-all dropdown-chevron"></i>
              </button>
              <div id="faq-ans-4" class="faq-answer hidden px-5 pb-5 pt-1 text-xs text-slate-500 leading-relaxed font-semibold">
                Transaksi game top-up bersifat final dan langsung diproses secara otomatis setelah pembayaran terdeteksi. Silakan periksa kembali kecocokan User ID dan server Anda sebelum membuat pesanan, karena transaksi yang salah kirim akibat kesalahan input User ID tidak dapat dikembalikan atau di-refund.
              </div>
            </div>

          </div>
        </div>

        <!-- Support contacts -->
        <div class="rounded-3xl border border-white/40 neup-flat p-5 text-left grid grid-cols-1 md:grid-cols-3 gap-5 bg-white shadow-sm">
          <div class="flex items-center gap-3">
            <span class="h-9 w-9 rounded-xl border border-white/40 bg-transparent neup-pressed-xs flex items-center justify-center text-pink-600 font-black flex-shrink-0"><i data-lucide="mail" class="h-4.5 w-4.5"></i></span>
            <div class="min-w-0">
              <p class="text-[9px] text-slate-400 font-black uppercase leading-none">Email Resmi</p>
              <p class="text-xs font-black text-slate-800 mt-1 truncate">support@gametopup.id</p>
            </div>
          </div>
          <div class="flex items-center gap-3">
            <span class="h-9 w-9 rounded-xl border border-white/40 bg-transparent neup-pressed-xs flex items-center justify-center text-emerald-600 font-bold flex-shrink-0"><i data-lucide="phone" class="h-4.5 w-4.5"></i></span>
            <div class="min-w-0">
              <p class="text-[9px] text-slate-400 font-black uppercase leading-none">Whatsapp Hotline</p>
              <p class="text-xs font-black text-slate-800 mt-1 truncate">+62 812-3456-7890</p>
            </div>
          </div>
          <div class="flex items-center gap-3">
            <span class="h-9 w-9 rounded-xl border border-white/40 bg-transparent neup-pressed-xs flex items-center justify-center text-indigo-600 font-bold flex-shrink-0"><i data-lucide="clock" class="h-4.5 w-4.5"></i></span>
            <div class="min-w-0">
              <p class="text-[9px] text-slate-400 font-black uppercase leading-none">Jam Kerja CS</p>
              <p class="text-xs font-black text-slate-800 mt-1 truncate">09:00 - 22:00 WIB</p>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>

  <script>
    // FAQ tab filter
    function filterFaq(category, btn) {
      document.querySelectorAll('.faq-tab-btn').forEach(b => {
        b.className = "faq-tab-btn text-[10px] md:text-xs font-black rounded-xl px-4 py-2.5 cursor-pointer transition-all bg-transparent text-slate-600 border border-white/20 neup-flat-sm hover:neup-pressed-sm";
        b.style.background = 'transparent';
        b.style.color = '#475569';
      });

      btn.className = "faq-tab-btn active text-[10px] md:text-xs font-black rounded-xl px-4 py-2.5 cursor-pointer transition-all border-none bg-[#ff007f] text-white shadow-sm";
      btn.style.background = '#ff007f';
      btn.style.color = '#ffffff';

      document.querySelectorAll('.faq-item').forEach(item => {
        const cat = item.getAttribute('data-category');
        if (category === 'all' || cat === category) {
          item.style.display = 'block';
        } else {
          item.style.display = 'none';
        }
      });
    }

    // Toggle FAQ answers
    function toggleFaq(idx, btn) {
      const ans = document.getElementById(`faq-ans-${idx}`);
      const chevron = btn.querySelector('.dropdown-chevron');
      if (ans) {
        ans.classList.toggle('hidden');
        const isHidden = ans.classList.contains('hidden');
        if (chevron) {
          chevron.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(180deg)';
        }
      }
    }
  </script>
@endsection
