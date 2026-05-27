@extends('layouts.admin')

@section('title', 'Admin Panel - Kelola Flash Sale')

@section('content')
  <div class="text-left animate-fade-in">
    <!-- Top Header -->
    <div class="border-b border-slate-800 pb-5 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-[10px] font-extrabold uppercase text-cyan-400 shadow-sm shadow-blue-500/5">
          <i data-lucide="flame" class="h-3.5 w-3.5 text-amber-500 animate-pulse"></i> FLASH SALE KILAT
        </span>
        <h1 class="text-2xl font-black mt-2 tracking-tight text-white">Kelola Flash Sale Beranda</h1>
        <p class="text-xs text-slate-400 mt-1 font-semibold">Atur banner promosi flash sale yang ditampilkan di halaman beranda, ubah judul, sesuaikan countdown timer, dan arahkan link game.</p>
      </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
      <div class="mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-4 text-xs font-bold text-emerald-400 shadow-md">
        ✓ {{ session('success') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="mb-6 rounded-2xl bg-rose-500/10 border border-rose-500/30 p-4 text-xs font-bold text-rose-400 shadow-md">
        <ul class="list-disc pl-4 space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- FLASH SALE CONFIGURATION CARD -->
    <div class="rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl text-slate-300">
      <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-6">
        <h3 class="text-xs font-black text-white uppercase tracking-wider flex items-center gap-2">
          <i data-lucide="settings" class="h-4 w-4 text-cyan-400"></i> Konfigurasi Tampilan & Countdown
        </h3>
      </div>

      <form action="{{ route('admin.flash-sale.update') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5.5">
        @csrf
        
        <!-- Status Tampilan -->
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Status Flash Sale di Homepage</label>
          <select name="flash_sale_show" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-bold focus:outline-none cursor-pointer">
            <option value="true" {{ $flashSaleShow === 'true' ? 'selected' : '' }}>Tampilkan Banner Flash Sale</option>
            <option value="false" {{ $flashSaleShow === 'false' ? 'selected' : '' }}>Sembunyikan Banner Flash Sale</option>
          </select>
          <p class="text-[10px] text-slate-500 font-medium">Jika disembunyikan, seluruh card/banner flash sale tidak akan muncul di halaman depan.</p>
        </div>

        <!-- Waktu Berakhir (Countdown) -->
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Waktu Berakhir (Countdown)</label>
          <input type="datetime-local" name="flash_sale_end" value="{{ $flashSaleEnd }}" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none cursor-pointer">
          <p class="text-[10px] text-slate-500 font-medium">Waktu selesainya flash sale. Penghitung waktu mundur akan otomatis berjalan di beranda.</p>
        </div>

        <!-- Judul Flash Sale -->
        <div class="flex flex-col gap-1.5 col-span-full">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Judul Banner Flash Sale</label>
          <input type="text" name="flash_sale_title" value="{{ $flashSaleTitle }}" required placeholder="cth: Sabet Diskon Game Terpopuler Akhir Pekan" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>

        <!-- Deskripsi Flash Sale -->
        <div class="flex flex-col gap-1.5 col-span-full">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Deskripsi Flash Sale</label>
          <textarea name="flash_sale_description" required placeholder="cth: Diamond, token, dan Welkin Moon ready diskon kilat, instan terkirim secara otomatis." class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 h-20 text-xs font-semibold focus:outline-none resize-none placeholder:text-slate-500">{{ $flashSaleDescription }}</textarea>
        </div>

        <!-- Game Tujuan Tautan -->
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Game Tujuan Tautan Tombol</label>
          <select name="flash_sale_slug" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-bold focus:outline-none cursor-pointer">
            @foreach($games as $game)
              <option value="{{ $game->slug }}" {{ $flashSaleSlug === $game->slug ? 'selected' : '' }}>
                {{ $game->name }} ({{ $game->slug }})
              </option>
            @endforeach
          </select>
          <p class="text-[10px] text-slate-500 font-medium">Memilih game yang akan dikunjungi saat tombol flash sale di-klik.</p>
        </div>

        <!-- Teks Tombol Tautan -->
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Teks Tombol Tautan</label>
          <input type="text" name="flash_sale_button_text" value="{{ $flashSaleButtonText }}" required placeholder="cth: Cek Flash Sale MLBB" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
          <p class="text-[10px] text-slate-500 font-medium">Tulisan pada tombol aksi flash sale (misal: "Buru Diskon MLBB").</p>
        </div>

        <!-- Simpan Button -->
        <button type="submit" class="col-span-full bg-gradient-to-r from-blue-600 to-cyan-500 border-none text-white font-black tracking-wide uppercase py-4 rounded-2xl text-xs cursor-pointer hover:shadow-lg hover:shadow-blue-500/20 active:scale-95 transition-all mt-4 flex items-center justify-center gap-2">
          <i data-lucide="save" class="h-4 w-4"></i> Simpan Konfigurasi Flash Sale 🚀
        </button>
      </form>
    </div>
  </div>
@endsection
