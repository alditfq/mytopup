<footer class="bg-slate-900 text-slate-400 border-t border-slate-800 mt-auto">
  <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
    <div class="xl:grid xl:grid-cols-3 xl:gap-8">
      
      <!-- Logo & Brand Description -->
      <div class="space-y-6 xl:col-span-1 text-left">
        <div class="flex items-center gap-2.5">
          <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-tr from-pink-500 to-fuchsia-600">
            <i data-lucide="gamepad-2" class="h-6 w-6 text-white"></i>
          </div>
          <span class="text-xl font-bold tracking-tight text-white">
            Game<span class="bg-gradient-to-r from-cyan-400 to-fuchsia-400 bg-clip-text text-transparent">Topup</span>
          </span>
        </div>
        <p class="text-sm max-w-md leading-relaxed text-slate-400" id="footer-desc-text">
          Portal top-up game instan Indonesia terlengkap, termurah, dan terpercaya. Proses pengisian otomatis 24 jam nonstop untuk Diamond, UC, Voucher, dan Points Anda.
        </p>
        <div class="flex space-x-4 justify-start">
          <a href="#" class="p-2 bg-slate-800 rounded-lg text-slate-300 hover:text-pink-500 hover:bg-slate-750 transition-colors">
            <i data-lucide="instagram" class="h-4 w-4"></i>
          </a>
          <a href="#" class="p-2 bg-slate-800 rounded-lg text-slate-300 hover:text-pink-500 hover:bg-slate-750 transition-colors">
            <i data-lucide="youtube" class="h-4 w-4"></i>
          </a>
          <a href="#" class="p-2 bg-slate-800 rounded-lg text-slate-300 hover:text-pink-500 hover:bg-slate-750 transition-colors">
            <i data-lucide="twitter" class="h-4 w-4"></i>
          </a>
        </div>
      </div>

      <!-- Quick Navigation -->
      <div class="mt-12 grid grid-cols-1 sm:grid-cols-3 gap-8 xl:col-span-2 xl:mt-0 text-left">
        <div>
          <h3 class="text-sm font-bold tracking-wider text-slate-200 uppercase mb-4" id="footer-nav-title">Navigasi</h3>
          <ul role="list" class="space-y-3 text-xs font-semibold">
            <li><a href="{{ route('home') }}" class="hover:text-white transition-colors" id="footer-nav-catalog">Katalog Game</a></li>
            <li><a href="{{ route('status') }}" class="hover:text-white transition-colors" id="footer-nav-track">Cek Histori Pesanan</a></li>
            <li><a href="{{ route('support') }}" class="hover:text-white transition-colors" id="footer-nav-support">Hubungi Bantuan CS</a></li>
          </ul>
        </div>
        
        <div>
          <h3 class="text-sm font-bold tracking-wider text-slate-200 uppercase mb-4" id="footer-legal-title">Hukum</h3>
          <ul role="list" class="space-y-3 text-xs font-semibold">
            <li><a href="#" class="hover:text-white transition-colors" id="footer-legal-terms">Syarat & Ketentuan</a></li>
            <li><a href="#" class="hover:text-white transition-colors" id="footer-legal-privacy">Kebijakan Privasi</a></li>
            <li><a href="#" class="hover:text-white transition-colors" id="footer-legal-refund">Kebijakan Pengembalian</a></li>
          </ul>
        </div>

        <div>
          <h3 class="text-sm font-bold tracking-wider text-slate-200 uppercase mb-4" id="footer-contact-title">Kontak Layanan</h3>
          <ul class="space-y-3.5 text-xs font-medium text-slate-400">
            <li class="flex items-center gap-2.5">
              <i data-lucide="mail" class="h-4 w-4 text-cyan-400"></i>
              <span>support@gametopup.id</span>
            </li>
            <li class="flex items-center gap-2.5">
              <i data-lucide="help-circle" class="h-4 w-4 text-pink-400"></i>
              <span id="footer-contact-chat">Layanan CS Online (24/7)</span>
            </li>
            <li class="flex items-start gap-2.5">
              <i data-lucide="clock" class="h-4 w-4 text-fuchsia-400 mt-0.5"></i>
              <div>
                <p class="text-slate-300 font-semibold">09:00 - 22:00 WIB</p>
                <p class="text-[10px] text-slate-500 mt-0.5" id="footer-hours-sub">Jam Kerja Support Operasional</p>
              </div>
            </li>
          </ul>
        </div>
      </div>

    </div>

    <!-- Separator -->
    <div class="mt-12 border-t border-slate-800 pt-8 flex flex-col md:flex-row items-center justify-between gap-6">
      <p class="text-xs">&copy; 2026 GameTopup Marketplace. All rights reserved.</p>
      
      <div class="flex flex-wrap items-center gap-4 text-xs font-semibold justify-start">
        <span class="text-slate-500 font-bold">Supported Payments:</span>
        <span class="px-2 py-1 bg-slate-800 text-slate-300 rounded font-bold tracking-wider text-[10px]">DANA</span>
        <span class="px-2 py-1 bg-slate-800 text-slate-300 rounded font-bold tracking-wider text-[10px]">OVO</span>
        <span class="px-2 py-1 bg-slate-800 text-slate-300 rounded font-bold tracking-wider text-[10px]">QRIS</span>
        <span class="px-2 py-1 bg-slate-800 text-slate-300 rounded font-bold tracking-wider text-[10px]">BCA</span>
        <span class="px-2 py-1 bg-slate-800 text-slate-300 rounded font-bold tracking-wider text-[10px]">MANDIRI</span>
      </div>
    </div>

  </div>
</footer>
