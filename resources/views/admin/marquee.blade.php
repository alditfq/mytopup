@extends('layouts.admin')

@section('title', 'Admin Panel - Kelola Marquee')

@section('content')
  <div class="text-left animate-fade-in">
    <!-- Top Header -->
    <div class="border-b border-slate-800 pb-5 mb-8">
      <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-[10px] font-extrabold uppercase text-cyan-400 shadow-sm shadow-blue-500/5">
        <i data-lucide="megaphone" class="h-3.5 w-3.5"></i> MARQUEE
      </span>
      <h1 class="text-2xl font-black mt-2 tracking-tight text-white">Kelola Teks Marquee</h1>
      <p class="text-xs text-slate-400 mt-1 font-semibold">Tambah, hapus, aktifkan, atau ubah urutan teks pengumuman berjalan di halaman utama toko.</p>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
      <div class="mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-4 text-xs font-bold text-emerald-400 shadow-md flex items-center gap-2">
        <i data-lucide="check-circle" class="h-4 w-4 flex-shrink-0"></i>
        {{ session('success') }}
      </div>
    @endif
    @if($errors->any())
      <div class="mb-6 rounded-2xl bg-rose-500/10 border border-rose-500/30 p-4 text-xs font-bold text-rose-400 shadow-md">
        <ul class="list-disc list-inside m-0 p-0">
          @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
      </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

      <!-- LEFT: LIST & CRUD -->
      <div class="xl:col-span-2 space-y-5">

        <!-- Global Toggle Card -->
        <div class="rounded-3xl border border-slate-800 p-5 bg-[#111827]/75 backdrop-blur-xl shadow-xl">
          <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3">
              <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-cyan-500/10 border border-cyan-500/20">
                <i data-lucide="power" class="h-4 w-4 text-cyan-400"></i>
              </div>
              <div>
                <p class="text-xs font-black text-white">Status Marquee Bar</p>
                <p class="text-[10px] text-slate-500 font-semibold mt-0.5">Aktifkan atau sembunyikan seluruh baris marquee di homepage.</p>
              </div>
            </div>
            <form action="{{ route('admin.marquee.update') }}" method="POST" class="m-0">
              @csrf
              <input type="hidden" name="marquee_active" id="global-active-field" value="{{ $marqueeActive }}">
              <label class="flex items-center gap-3 cursor-pointer" for="global-toggle">
                <div class="relative">
                  <input type="checkbox" id="global-toggle" class="sr-only peer"
                    {{ $marqueeActive === 'true' ? 'checked' : '' }}
                    onchange="
                      document.getElementById('global-active-field').value = this.checked ? 'true' : 'false';
                      this.closest('form').submit();
                    ">
                  <div class="w-12 h-6 bg-slate-700 rounded-full peer peer-checked:bg-cyan-500 transition-all"></div>
                  <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full transition-all peer-checked:translate-x-6 shadow"></div>
                </div>
                <span class="text-xs font-bold {{ $marqueeActive === 'true' ? 'text-cyan-400' : 'text-slate-500' }}">
                  {{ $marqueeActive === 'true' ? 'Aktif' : 'Nonaktif' }}
                </span>
              </label>
            </form>
          </div>
        </div>

        <!-- Add New Item Form -->
        <div class="rounded-3xl border border-slate-800 p-5 bg-[#111827]/75 backdrop-blur-xl shadow-xl">
          <div class="flex items-center gap-2.5 border-b border-slate-800 pb-3.5 mb-4">
            <i data-lucide="plus-circle" class="h-4 w-4 text-cyan-400"></i>
            <h3 class="text-xs font-black uppercase tracking-wider text-white">Tambah Item Marquee Baru</h3>
          </div>
          <form action="{{ route('admin.marquee.items.store') }}" method="POST" class="flex gap-3 items-end flex-wrap">
            @csrf
            <div class="flex-1 min-w-[220px] flex flex-col gap-1.5">
              <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Teks Pengumuman</label>
              <input
                type="text"
                name="text"
                placeholder="Contoh: 🔥 Flash Sale Akhir Pekan! Diskon hingga 15 Ribu! 🔥"
                value="{{ old('text') }}"
                class="w-full rounded-xl bg-slate-900/60 border border-slate-700 text-slate-100 text-xs px-3.5 py-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500/40 outline-none transition placeholder:text-slate-600"
              >
            </div>
            <button type="submit" id="add-marquee-item-btn" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-400 hover:to-cyan-400 text-white text-xs font-extrabold px-4 py-2.5 rounded-xl transition-all shadow-lg shadow-blue-500/20 active:scale-95 border-none cursor-pointer flex-shrink-0">
              <i data-lucide="plus" class="h-4 w-4"></i>
              Tambahkan
            </button>
          </form>
        </div>

        <!-- Item List -->
        <div class="rounded-3xl border border-slate-800 bg-[#111827]/75 backdrop-blur-xl shadow-xl overflow-hidden">
          <div class="flex items-center justify-between gap-3 px-5 py-4 border-b border-slate-800">
            <div class="flex items-center gap-2.5">
              <i data-lucide="list" class="h-4 w-4 text-cyan-400"></i>
              <h3 class="text-xs font-black uppercase tracking-wider text-white">Daftar Item Marquee</h3>
            </div>
            <span class="text-[10px] font-bold text-slate-500 bg-slate-800/60 px-2.5 py-1 rounded-full">{{ $items->count() }} item</span>
          </div>

          @if($items->isEmpty())
            <div class="flex flex-col items-center justify-center py-14 text-center px-6">
              <div class="w-14 h-14 rounded-2xl bg-slate-800/60 flex items-center justify-center mb-4">
                <i data-lucide="megaphone" class="h-6 w-6 text-slate-600"></i>
              </div>
              <p class="text-sm font-black text-slate-400">Belum ada item marquee</p>
              <p class="text-xs text-slate-600 font-semibold mt-1">Tambahkan teks pengumuman menggunakan form di atas.</p>
            </div>
          @else
            <div class="divide-y divide-slate-800/60" id="marquee-items-list">
              @foreach($items as $item)
                <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-slate-800/20 transition group" id="marquee-row-{{ $item->id }}">
                  <!-- Drag Handle / Order Arrows -->
                  <div class="flex flex-col gap-0.5 flex-shrink-0">
                    <form action="{{ route('admin.marquee.items.sort', $item->id) }}" method="POST" class="m-0 p-0">
                      @csrf
                      <input type="hidden" name="direction" value="up">
                      <button type="submit" class="flex h-5 w-5 items-center justify-center rounded text-slate-600 hover:text-slate-300 hover:bg-slate-700/50 transition border-none bg-transparent cursor-pointer" title="Geser naik">
                        <i data-lucide="chevron-up" class="h-3.5 w-3.5"></i>
                      </button>
                    </form>
                    <form action="{{ route('admin.marquee.items.sort', $item->id) }}" method="POST" class="m-0 p-0">
                      @csrf
                      <input type="hidden" name="direction" value="down">
                      <button type="submit" class="flex h-5 w-5 items-center justify-center rounded text-slate-600 hover:text-slate-300 hover:bg-slate-700/50 transition border-none bg-transparent cursor-pointer" title="Geser turun">
                        <i data-lucide="chevron-down" class="h-3.5 w-3.5"></i>
                      </button>
                    </form>
                  </div>

                  <!-- Order Badge -->
                  <span class="flex-shrink-0 w-6 h-6 rounded-lg bg-slate-800 text-[10px] font-black text-slate-400 flex items-center justify-center">{{ $loop->iteration }}</span>

                  <!-- Text -->
                  <p class="flex-1 text-xs font-semibold {{ $item->is_active ? 'text-slate-200' : 'text-slate-600 line-through' }} truncate min-w-0">
                    {{ $item->text }}
                  </p>

                  <!-- Active badge -->
                  <span class="flex-shrink-0 px-2 py-0.5 rounded-full text-[9px] font-black {{ $item->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-800 text-slate-500 border border-slate-700' }}">
                    {{ $item->is_active ? 'AKTIF' : 'NONAKTIF' }}
                  </span>

                  <!-- Actions -->
                  <div class="flex items-center gap-1.5 flex-shrink-0">
                    <!-- Toggle -->
                    <form action="{{ route('admin.marquee.items.toggle', $item->id) }}" method="POST" class="m-0 p-0">
                      @csrf
                      <button type="submit" title="{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                        class="flex h-7 w-7 items-center justify-center rounded-lg {{ $item->is_active ? 'text-amber-400 hover:bg-amber-500/10' : 'text-emerald-400 hover:bg-emerald-500/10' }} transition border-none bg-transparent cursor-pointer">
                        <i data-lucide="{{ $item->is_active ? 'eye-off' : 'eye' }}" class="h-3.5 w-3.5"></i>
                      </button>
                    </form>
                    <!-- Delete -->
                    <form action="{{ route('admin.marquee.items.delete', $item->id) }}" method="POST" class="m-0 p-0"
                          onsubmit="return confirm('Hapus item marquee ini?')">
                      @csrf
                      <button type="submit" title="Hapus"
                        class="flex h-7 w-7 items-center justify-center rounded-lg text-rose-400 hover:bg-rose-500/10 transition border-none bg-transparent cursor-pointer">
                        <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                      </button>
                    </form>
                  </div>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>

      <!-- RIGHT: LIVE PREVIEW & INFO -->
      <div class="flex flex-col gap-5">

        <!-- Live Preview -->
        <div class="rounded-3xl border border-slate-800 p-5 bg-[#111827]/75 backdrop-blur-xl shadow-xl">
          <div class="flex items-center gap-2.5 border-b border-slate-800 pb-3.5 mb-4">
            <i data-lucide="eye" class="h-4 w-4 text-cyan-400"></i>
            <h3 class="text-xs font-black uppercase tracking-wider text-white">Preview Marquee</h3>
          </div>
          <p class="text-[10px] text-slate-500 font-semibold mb-3">Tampilan perkiraan marquee di toko ({{ $items->where('is_active', true)->count() }} item aktif):</p>

          @if($marqueeActive === 'true')
            <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-blue-600/10 to-cyan-500/10 border border-blue-500/20 py-2.5">
              @php $activeItems = $items->where('is_active', true); @endphp
              @if($activeItems->isNotEmpty())
                <div class="flex animate-marquee-preview items-center whitespace-nowrap">
                  @foreach($activeItems as $mItem)
                    <span class="text-xs font-bold text-cyan-300 px-6">{{ $mItem->text }}</span>
                    <span class="text-cyan-500/50">•</span>
                  @endforeach
                  @foreach($activeItems as $mItem)
                    <span class="text-xs font-bold text-cyan-300 px-6">{{ $mItem->text }}</span>
                    <span class="text-cyan-500/50">•</span>
                  @endforeach
                </div>
              @else
                <p class="text-center text-[10px] text-slate-600 font-semibold py-1">— Tidak ada item aktif —</p>
              @endif
            </div>
          @else
            <div class="rounded-xl border border-slate-800 bg-slate-900/40 py-3 text-center">
              <p class="text-[10px] text-slate-600 font-semibold">Marquee sedang dinonaktifkan</p>
            </div>
          @endif
        </div>

        <!-- Info Card -->
        <div class="rounded-3xl border border-amber-500/20 p-5 bg-amber-500/5 shadow-xl">
          <div class="flex items-center gap-2 mb-3">
            <i data-lucide="info" class="h-4 w-4 text-amber-400"></i>
            <span class="text-[10px] font-black uppercase tracking-wider text-amber-400">Petunjuk</span>
          </div>
          <ul class="text-[11px] text-slate-400 font-semibold space-y-2 list-none m-0 p-0">
            <li class="flex items-start gap-1.5"><span class="text-amber-400 mt-0.5">•</span> Tambahkan beberapa item untuk ditampilkan bergantian.</li>
            <li class="flex items-start gap-1.5"><span class="text-amber-400 mt-0.5">•</span> Gunakan tombol ▲▼ untuk mengubah urutan tampilan.</li>
            <li class="flex items-start gap-1.5"><span class="text-amber-400 mt-0.5">•</span> Toggle mata untuk aktif/nonaktifkan tiap item.</li>
            <li class="flex items-start gap-1.5"><span class="text-amber-400 mt-0.5">•</span> Teks mendukung emoji Unicode (🔥 ⚡ 🎉).</li>
            <li class="flex items-start gap-1.5"><span class="text-amber-400 mt-0.5">•</span> Toggle global di atas akan menyembunyikan <em>seluruh</em> baris marquee.</li>
          </ul>
        </div>

      </div>
    </div>
  </div>
@endsection

@push('styles')
<style>
  @keyframes marquee-preview {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-50%); }
  }
  .animate-marquee-preview {
    animation: marquee-preview 18s linear infinite;
  }
</style>
@endpush
