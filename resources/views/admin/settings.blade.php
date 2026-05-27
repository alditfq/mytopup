@extends('layouts.admin')

@section('title', 'Admin Panel - Pengaturan Sistem')

@section('content')
  <div class="text-left animate-fade-in">
    <!-- Top Header -->
    <div class="border-b border-slate-800 pb-5 mb-8">
      <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-[10px] font-extrabold uppercase text-cyan-400 shadow-sm shadow-blue-500/5">
        <i data-lucide="settings" class="h-3.5 w-3.5"></i> SYSTEM CONFIG
      </span>
      <h1 class="text-2xl font-black mt-2 tracking-tight text-white">Pengaturan Sistem Utama</h1>
      <p class="text-xs text-slate-400 mt-1 font-semibold">Sesuaikan penamaan toko, logo, pengumuman marquee, countdown flash sale, dan maintenance mode.</p>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
      <div class="mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-4 text-xs font-bold text-emerald-400 shadow-md">
        ✓ {{ session('success') }}
      </div>
    @endif

    @if($errors->any())
      <div class="mb-6 rounded-2xl bg-rose-500/10 border border-rose-500/30 p-4 text-xs font-bold text-rose-400 shadow-md">
        <ul class="list-disc list-inside m-0 p-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- SETTINGS EDIT FORM -->
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8 max-w-4xl font-bold text-slate-300">
      @csrf

      <!-- 1. GENERAL IDENTITY -->
      <div class="rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl">
        <div class="flex items-center gap-2.5 border-b border-slate-800 pb-3.5 mb-5">
          <i data-lucide="store" class="h-5 w-5 text-cyan-400"></i>
          <h3 class="text-xs font-black uppercase tracking-wider text-white">Identitas Toko & Tampilan</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="flex flex-col gap-1.5">
            <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Nama Toko Marketplace</label>
            <input
              type="text"
              name="shop_name"
              value="{{ old('shop_name', $shopName) }}"
              required
              class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500"
            />
          </div>
          <div class="flex flex-col gap-1.5">
            <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Unggah Logo Toko (Opsional)</label>
            <div class="flex items-center gap-3">
              @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="Current Logo" class="h-10 w-10 rounded-xl object-contain border border-slate-800 bg-slate-900/60 p-1 shadow-md">
              @endif
              <input
                type="file"
                name="logo"
                accept="image/*"
                class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3 px-4 text-xs font-semibold focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-blue-600/10 file:text-cyan-400 hover:file:bg-blue-600/20 file:cursor-pointer cursor-pointer"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- 2. HOME MARQUEE & FLASH COUNTDOWN -->
      <div class="rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl">
        <div class="flex items-center gap-2.5 border-b border-slate-800 pb-3.5 mb-5">
          <i data-lucide="alert-circle" class="h-5 w-5 text-cyan-400"></i>
          <h3 class="text-xs font-black uppercase tracking-wider text-white">Promosi & Alert Beranda</h3>
        </div>

        <div class="space-y-5">
          <div class="flex flex-col gap-1.5">
            <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Teks Pengumuman Berjalan (Marquee Alert)</label>
            <input
              type="text"
              name="marquee_text"
              value="{{ old('marquee_text', $marqueeText) }}"
              placeholder="Tulis pengumuman penting di sini..."
              class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500"
            />
          </div>
          
          <div class="flex flex-col gap-1.5">
            <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Batas Hitung Mundur Flash Sale (End Date & Time)</label>
            <input
              type="datetime-local"
              name="flash_sale_end"
              value="{{ old('flash_sale_end', $flashSaleEnd ? date('Y-m-d\TH:i', strtotime($flashSaleEnd)) : '') }}"
              class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none cursor-pointer"
            />
            <span class="text-[9px] text-slate-500 font-bold mt-1">✓ Tanggal hitung mundur pada banner diskon homepage akan otomatis menyesuaikan batas waktu ini.</span>
          </div>
        </div>
      </div>

      <!-- 3. MAINTENANCE MODE TOGGLE -->
      <div class="rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl">
        <div class="flex items-center gap-2.5 border-b border-slate-800 pb-3.5 mb-5">
          <i data-lucide="shield-alert" class="h-5 w-5 text-cyan-400"></i>
          <h3 class="text-xs font-black uppercase tracking-wider text-white">Mode Pemeliharaan (Maintenance Mode)</h3>
        </div>

        <div class="flex flex-col gap-3">
          <p class="text-xs text-slate-400 font-semibold leading-relaxed mb-2">
            Mengaktifkan Mode Perbaikan akan mengunci seluruh akses halaman member/toko dan menampilkan pesan khusus. Bilah Panel Admin tetap terbuka agar Anda dapat menonaktifkannya kembali kapan saja.
          </p>

          <div class="flex items-center gap-6 mt-1">
            <label class="flex items-center gap-2.5 cursor-pointer select-none">
              <input
                type="radio"
                name="is_maintenance"
                value="true"
                {{ $isMaintenance === 'true' ? 'checked' : '' }}
                class="h-4.5 w-4.5 text-rose-500 border border-slate-700 bg-slate-850 rounded-full cursor-pointer focus:ring-0"
              />
              <span class="text-xs font-extrabold uppercase tracking-wider text-rose-400 flex items-center gap-1">
                ⚠️ MAINTENANCE MODE AKTIF
              </span>
            </label>

            <label class="flex items-center gap-2.5 cursor-pointer select-none">
              <input
                type="radio"
                name="is_maintenance"
                value="false"
                {{ $isMaintenance !== 'true' ? 'checked' : '' }}
                class="h-4.5 w-4.5 text-emerald-500 border border-slate-700 bg-slate-850 rounded-full cursor-pointer focus:ring-0"
              />
              <span class="text-xs font-extrabold uppercase tracking-wider text-emerald-400 flex items-center gap-1">
                ✓ NORMAL (STORE ONLINE)
              </span>
            </label>
          </div>
        </div>
      </div>

      <!-- SUBMIT BUTTON -->
      <button
        type="submit"
        class="w-full bg-gradient-to-r from-blue-600 to-cyan-500 border-none text-white font-black tracking-wide uppercase py-4 rounded-2xl text-xs cursor-pointer hover:shadow-lg hover:shadow-blue-500/20 active:scale-95 transition-all flex items-center justify-center gap-2"
      >
        <i data-lucide="save" class="h-4.5 w-4.5"></i> Simpan Seluruh Pembaruan Konfigurasi
      </button>
    </form>
  </div>
@endsection
