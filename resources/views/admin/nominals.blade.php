@extends('layouts.admin')

@section('title', 'Admin Panel - Kelola Nominal Item')

@section('content')
  <div class="text-left animate-fade-in">
    <!-- Top Header -->
    <div class="border-b border-slate-800 pb-5 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-[10px] font-extrabold uppercase text-cyan-400 shadow-sm shadow-blue-500/5">
          <i data-lucide="layers" class="h-3.5 w-3.5"></i> NOMINAL PACKAGES
        </span>
        <h1 class="text-2xl font-black mt-2 tracking-tight text-white">Manajemen Nominal Item (CRUD)</h1>
        <p class="text-xs text-slate-400 mt-1 font-semibold">Buat paket item top up baru, edit harga dasar/diskon, dan kelola hot items.</p>
      </div>

      <button onclick="toggleAddForm()" class="bg-gradient-to-r from-blue-600 to-cyan-500 border-none text-white font-extrabold text-xs py-3 px-5 rounded-2xl cursor-pointer hover:shadow-lg hover:shadow-blue-500/20 active:scale-95 transition-all shadow-md flex items-center gap-2">
        <i data-lucide="plus-circle" class="h-4 w-4"></i> Tambah Item Baru
      </button>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
      <div class="mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-4 text-xs font-bold text-emerald-400 shadow-md">
        ✓ {{ session('success') }}
      </div>
    @endif

    <!-- ADD NOMINAL FORM (Collapsible) -->
    <div id="add-nominal-form-container" class="hidden rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl mb-8 text-slate-300 text-left">
      <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-5">
        <h3 class="text-xs font-black text-white uppercase tracking-wider">Tambah Item Nominal Baru</h3>
        <button type="button" onclick="toggleAddForm()" class="bg-transparent border-none text-slate-500 hover:text-slate-350 cursor-pointer"><i data-lucide="x" class="h-5 w-5"></i></button>
      </div>

      <form action="{{ route('admin.nominals.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @csrf
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Pilih Game / Katalog</label>
          <select name="game_id" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-bold focus:outline-none cursor-pointer">
            <option value="" disabled selected>-- Pilih Game --</option>
            @foreach($games as $game)
              <option value="{{ $game->id }}">{{ $game->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Nama Paket Item (Nominal)</label>
          <input type="text" name="name" required placeholder="cth: 86 Diamonds / Weekly Diamond Pass" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Harga Dasar (IDR)</label>
          <input type="number" name="price" required placeholder="25000" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Harga Diskon (IDR - Opsional)</label>
          <input type="number" name="discount_price" placeholder="22000" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Label / Tag Item (cth: DISKON, PROMO, POPULER - Opsional)</label>
          <input type="text" name="tag" placeholder="cth: DISKON atau 15% OFF" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex items-center gap-2 select-none col-span-full pt-1 text-left">
          <input type="checkbox" name="is_best_seller" id="is_best_seller" value="1" class="h-4.5 w-4.5 text-cyan-400 border border-slate-750 bg-slate-800 rounded cursor-pointer">
          <label for="is_best_seller" class="text-[9px] font-black text-slate-400 uppercase tracking-wider cursor-pointer select-none">Tandai Sebagai BEST SELLER (Rekomendasi Utama)</label>
        </div>
        <button type="submit" class="col-span-full bg-gradient-to-r from-blue-600 to-cyan-500 border-none text-white font-black tracking-wide uppercase py-4 rounded-2xl text-xs cursor-pointer hover:shadow-lg hover:shadow-blue-500/20 active:scale-95 transition-all mt-2">
          Simpan Item Baru 🚀
        </button>
      </form>
    </div>

    <!-- FILTER & SEARCH PANEL -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <form action="{{ route('admin.nominals') }}" method="GET" class="w-full grid grid-cols-1 sm:grid-cols-3 gap-3 font-semibold">
        <!-- Search field -->
        <div class="relative w-full sm:col-span-2">
          <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari nama nominal item..."
            class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-2.5 pl-4 pr-10 text-xs font-semibold focus:outline-none placeholder:text-slate-500"
          />
          <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-transparent border-none text-slate-500 hover:text-slate-350 cursor-pointer flex items-center justify-center">
            <i data-lucide="search" class="h-4 w-4"></i>
          </button>
        </div>

        <!-- Game selector dropdown -->
        <div class="flex gap-2">
          <select name="game_id" onchange="this.form.submit()" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-2.5 px-4 text-xs font-bold focus:outline-none cursor-pointer">
            <option value="all" {{ request('game_id') === 'all' || !request('game_id') ? 'selected' : '' }}>Semua Game</option>
            @foreach($games as $g)
              <option value="{{ $g->id }}" {{ request('game_id') == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
            @endforeach
          </select>

          @if(request()->filled('search') || request()->filled('game_id'))
            <a href="{{ route('admin.nominals') }}" class="flex items-center justify-center rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 hover:bg-rose-500/25 px-3 cursor-pointer decoration-none transition-all active:scale-95 shadow-sm" title="Reset Filter">
              <i data-lucide="x" class="h-4 w-4"></i>
            </a>
          @endif
        </div>
      </form>
    </div>

    <!-- NOMINALS LIST TABLE -->
    <div class="rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl overflow-hidden">
      <div class="overflow-x-auto w-full">
        <table class="w-full text-slate-350 text-xs font-semibold border-collapse text-left">
          <thead>
            <tr class="border-b border-slate-800 text-[10px] uppercase tracking-wider text-slate-400">
              <th class="pb-3.5 pl-2">Game</th>
              <th class="pb-3.5">Nama Item / Nominal</th>
              <th class="pb-3.5">Harga Dasar</th>
              <th class="pb-3.5">Harga Final (Diskon)</th>
              <th class="pb-3.5">Status</th>
              <th class="pb-3.5 text-right pr-4">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800">
            @forelse($nominals as $nominal)
              <tr class="hover:bg-slate-900/40 transition-colors">
                <td class="py-4 pl-2 font-black text-white">{{ $nominal->game->name }}</td>
                <td class="py-4 font-black text-white">
                  {{ $nominal->name }}
                  <span class="block text-[8px] text-slate-500 font-mono mt-0.5">{{ $nominal->item_id }}</span>
                </td>
                <td class="py-4 font-mono font-bold">Rp {{ number_format($nominal->price, 0, ',', '.') }}</td>
                <td class="py-4 font-mono font-black text-cyan-400">
                  @if($nominal->discount_price)
                    Rp {{ number_format($nominal->discount_price, 0, ',', '.') }}
                  @else
                    <span class="text-slate-500 font-bold">-</span>
                  @endif
                </td>
                <td class="py-4">
                  <div class="flex flex-wrap gap-1.5 justify-start items-center">
                    @if($nominal->is_best_seller)
                      <span class="rounded bg-pink-500/10 border border-pink-500/20 px-2.5 py-0.5 text-[8px] font-black text-pink-400 uppercase tracking-wider shadow-sm">HOT ITEM</span>
                    @endif
                    @if($nominal->tag)
                      <span class="rounded bg-cyan-500/10 border border-cyan-500/20 px-2.5 py-0.5 text-[8px] font-black text-cyan-400 uppercase tracking-wider shadow-sm">{{ strtoupper($nominal->tag) }}</span>
                    @endif
                    @if(!$nominal->is_best_seller && !$nominal->tag)
                      <span class="text-slate-500">-</span>
                    @endif
                  </div>
                </td>
                <td class="py-4 text-right pr-4">
                  <div class="inline-flex gap-2">
                    <button onclick="toggleEditForm({{ $nominal->id }})" class="border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-100 font-extrabold rounded-xl px-3.5 py-2.5 text-[10px] cursor-pointer transition-all active:scale-95 shadow-sm">
                      Edit
                    </button>
                    <form action="{{ route('admin.nominals.delete', $nominal->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus nominal item {{ $nominal->name }}?')" class="m-0 p-0">
                      @csrf
                      <button type="submit" class="border border-slate-700 bg-rose-950/20 hover:bg-rose-900/30 text-rose-400 font-extrabold rounded-xl px-3.5 py-2.5 text-[10px] cursor-pointer transition-all active:scale-95 shadow-sm">
                        Hapus
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              
              <!-- COLLAPSIBLE EDIT FORM ROW -->
              <tr id="edit-row-{{ $nominal->id }}" class="hidden bg-slate-900/10">
                <td colspan="6" class="p-6 border-t border-b border-slate-800">
                  <div class="max-w-3xl text-left font-bold text-slate-300">
                    <h4 class="text-xs font-black text-white uppercase tracking-wider mb-4">Edit Data Nominal: {{ $nominal->name }} ({{ $nominal->game->name }})</h4>
                    
                    <form action="{{ route('admin.nominals.update', $nominal->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4.5">
                      @csrf
                      <div class="flex flex-col gap-1.5 col-span-full">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Nama Paket Item (Nominal)</label>
                        <input type="text" name="name" value="{{ $nominal->name }}" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Harga Dasar (IDR)</label>
                        <input type="number" name="price" value="{{ $nominal->price }}" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Harga Diskon (IDR - Opsional)</label>
                        <input type="number" name="discount_price" value="{{ $nominal->discount_price }}" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5 col-span-full">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Label / Tag Item (cth: DISKON, PROMO, POPULER - Opsional)</label>
                        <input type="text" name="tag" value="{{ $nominal->tag }}" placeholder="cth: DISKON atau 15% OFF" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex items-center gap-2 select-none col-span-full pt-1">
                        <input type="checkbox" name="is_best_seller" id="edit-best-{{ $nominal->id }}" value="1" {{ $nominal->is_best_seller ? 'checked' : '' }} class="h-4.5 w-4.5 text-cyan-400 border border-slate-700 bg-slate-800 rounded cursor-pointer">
                        <label for="edit-best-{{ $nominal->id }}" class="text-[9px] font-black text-slate-400 uppercase tracking-wider cursor-pointer select-none">Tandai Sebagai BEST SELLER (Rekomendasi Utama)</label>
                      </div>
                      <div class="col-span-full flex gap-3.5 justify-end mt-2">
                        <button type="button" onclick="toggleEditForm({{ $nominal->id }})" class="border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-300 font-extrabold rounded-2xl px-5 py-3 text-xs cursor-pointer transition-all active:scale-95">Batal</button>
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-cyan-500 border-none text-white font-black text-xs py-3 px-5 rounded-2xl cursor-pointer hover:shadow-lg hover:shadow-blue-500/20 active:scale-95 transition-all">Simpan Perubahan</button>
                      </div>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="py-8 text-center text-slate-500 font-bold">Belum ada paket nominal terdaftar.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    function toggleAddForm() {
      const container = document.getElementById('add-nominal-form-container');
      if (container) {
        container.classList.toggle('hidden');
        if (!container.classList.contains('hidden')) {
          container.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      }
    }

    function toggleEditForm(id) {
      const row = document.getElementById(`edit-row-${id}`);
      if (row) {
        row.classList.toggle('hidden');
      }
    }
  </script>
@endsection
