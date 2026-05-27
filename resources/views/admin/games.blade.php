@extends('layouts.admin')

@section('title', 'Admin Panel - Kelola Game')

@section('content')
  <div class="text-left animate-fade-in">
    <!-- Top Header -->
    <div class="border-b border-slate-800 pb-5 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-[10px] font-extrabold uppercase text-cyan-400 shadow-sm shadow-blue-500/5">
          <i data-lucide="gamepad-2" class="h-3.5 w-3.5"></i> CATALOG GAMES
        </span>
        <h1 class="text-2xl font-black mt-2 tracking-tight text-white">Manajemen Kelola Game (CRUD)</h1>
        <p class="text-xs text-slate-400 mt-1 font-semibold">Daftarkan game baru, kelola developer, instruksi top up, dan hapus katalog.</p>
      </div>

      <button onclick="toggleAddForm()" class="bg-gradient-to-r from-blue-600 to-cyan-500 border-none text-white font-extrabold text-xs py-3 px-5 rounded-2xl cursor-pointer hover:shadow-lg hover:shadow-blue-500/20 active:scale-95 transition-all shadow-md flex items-center gap-2">
        <i data-lucide="plus-circle" class="h-4 w-4"></i> Tambah Game Baru
      </button>
    </div>

    <!-- Flash Messages / Success notification -->
    @if(session('success'))
      <div class="mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-4 text-xs font-bold text-emerald-400 shadow-md">
        ✓ {{ session('success') }}
      </div>
    @endif

    <!-- ADD GAME FORM (Collapsible) -->
    <div id="add-game-form-container" class="hidden rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl mb-8 text-slate-300">
      <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-5">
        <h3 class="text-xs font-black text-white uppercase tracking-wider">Tambah Game Baru</h3>
        <button type="button" onclick="toggleAddForm()" class="bg-transparent border-none text-slate-500 hover:text-slate-350 cursor-pointer"><i data-lucide="x" class="h-5 w-5"></i></button>
      </div>

      <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @csrf
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Nama Game</label>
          <input type="text" name="name" required placeholder="cth: Mobile Legends" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Kategori</label>
          <select name="category" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-bold focus:outline-none cursor-pointer">
            <option value="mobile">Mobile</option>
            <option value="pc">PC</option>
            <option value="console">Console</option>
            <option value="popular">Popular</option>
            <option value="voucher">Voucher</option>
          </select>
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Developer</label>
          <input type="text" name="developer" required placeholder="Moonton / Riot Games" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Cashback Persen (%)</label>
          <input type="number" name="cashback_percent" required min="0" max="100" value="5" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Thumbnail Game</label>
          <input type="file" name="thumbnail" required accept="image/*" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3 px-4 text-xs font-semibold focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-blue-600/10 file:text-cyan-400 hover:file:bg-blue-600/20 file:cursor-pointer cursor-pointer">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Banner Game</label>
          <input type="file" name="banner" required accept="image/*" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3 px-4 text-xs font-semibold focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-blue-600/10 file:text-cyan-400 hover:file:bg-blue-600/20 file:cursor-pointer cursor-pointer">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Label Input ID</label>
          <input type="text" name="id_label" required placeholder="User ID / Player ID" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Label Zone/Server ID (Opsional)</label>
          <input type="text" name="zone_id_label" placeholder="Zone ID / Server" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex flex-col gap-1.5 col-span-full">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Petunjuk Helper Text ID</label>
          <textarea name="id_helper_text" required placeholder="Petunjuk letak ID..." class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 h-24 text-xs font-semibold focus:outline-none resize-none placeholder:text-slate-500"></textarea>
        </div>
        <button type="submit" class="col-span-full bg-gradient-to-r from-blue-600 to-cyan-500 border-none text-white font-black tracking-wide uppercase py-4 rounded-2xl text-xs cursor-pointer hover:shadow-lg hover:shadow-blue-500/20 active:scale-95 transition-all mt-2">
          Simpan Game Baru 🚀
        </button>
      </form>
    </div>

    <!-- FILTER & SEARCH PANEL -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <form action="{{ route('admin.games') }}" method="GET" class="w-full grid grid-cols-1 sm:grid-cols-3 gap-3 font-semibold">
        <!-- Search field -->
        <div class="relative w-full sm:col-span-2">
          <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari nama game / developer..."
            class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-2.5 pl-4 pr-10 text-xs font-semibold focus:outline-none placeholder:text-slate-500"
          />
          <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-transparent border-none text-slate-500 hover:text-slate-350 cursor-pointer flex items-center justify-center">
            <i data-lucide="search" class="h-4 w-4"></i>
          </button>
        </div>

        <!-- Category dropdown -->
        <div class="flex gap-2">
          <select name="category" onchange="this.form.submit()" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-2.5 px-4 text-xs font-bold focus:outline-none cursor-pointer">
            <option value="all" {{ request('category') === 'all' || !request('category') ? 'selected' : '' }}>Semua Kategori</option>
            <option value="mobile" {{ request('category') === 'mobile' ? 'selected' : '' }}>Mobile</option>
            <option value="pc" {{ request('category') === 'pc' ? 'selected' : '' }}>PC</option>
            <option value="console" {{ request('category') === 'console' ? 'selected' : '' }}>Console</option>
            <option value="popular" {{ request('category') === 'popular' ? 'selected' : '' }}>Popular</option>
            <option value="voucher" {{ request('category') === 'voucher' ? 'selected' : '' }}>Voucher</option>
          </select>

          @if(request()->filled('search') || request()->filled('category'))
            <a href="{{ route('admin.games') }}" class="flex items-center justify-center rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 hover:bg-rose-500/25 px-3 cursor-pointer decoration-none transition-all active:scale-95 shadow-sm" title="Reset Filter">
              <i data-lucide="x" class="h-4 w-4"></i>
            </a>
          @endif
        </div>
      </form>
    </div>

    <!-- GAMES LIST TABLE -->
    <div class="rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl overflow-hidden">
      <div class="overflow-x-auto w-full">
        <table class="w-full text-slate-350 text-xs font-semibold border-collapse text-left">
          <thead>
            <tr class="border-b border-slate-800 text-[10px] uppercase tracking-wider text-slate-400">
              <th class="pb-3.5 pl-2">Game</th>
              <th class="pb-3.5">Developer</th>
              <th class="pb-3.5">Kategori</th>
              <th class="pb-3.5">Cashback (%)</th>
              <th class="pb-3.5 text-right pr-4">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800">
            @forelse($games as $game)
              <tr class="hover:bg-slate-900/40 transition-colors">
                <td class="py-4 pl-2">
                  <div class="flex items-center gap-3">
                    <img src="{{ $game->thumbnail_url }}" alt="{{ $game->name }}" class="h-10 w-10 rounded-xl object-cover border border-slate-800 shadow-md">
                    <div>
                      <p class="font-black text-white leading-tight">{{ $game->name }}</p>
                      <p class="text-[9px] text-slate-500 font-mono mt-0.5 leading-none">{{ $game->slug }}</p>
                    </div>
                  </div>
                </td>
                <td class="py-4 text-slate-300 font-bold">{{ $game->developer }}</td>
                <td class="py-4 uppercase text-[9px] font-bold">
                  <span class="bg-blue-500/10 border border-blue-500/20 text-cyan-400 px-2.5 py-0.5 rounded-lg">{{ $game->category }}</span>
                </td>
                <td class="py-4 font-mono font-black text-cyan-400">{{ $game->cashback_percent }}%</td>
                <td class="py-4 text-right pr-4">
                  <div class="inline-flex gap-2">
                    <button onclick="toggleEditForm({{ $game->id }})" class="border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-100 font-extrabold rounded-xl px-3.5 py-2.5 text-[10px] cursor-pointer transition-all active:scale-95 shadow-sm">
                      Edit
                    </button>
                    <form action="{{ route('admin.games.delete', $game->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus game {{ $game->name }}? Seluruh nominal item yang terhubung juga akan dihapus permanen.')" class="m-0 p-0">
                      @csrf
                      <button type="submit" class="border border-slate-700 bg-rose-950/20 hover:bg-rose-900/30 text-rose-400 font-extrabold rounded-xl px-3.5 py-2.5 text-[10px] cursor-pointer transition-all active:scale-95 shadow-sm">
                        Hapus
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              
              <!-- COLLAPSIBLE EDIT FORM ROW -->
              <tr id="edit-row-{{ $game->id }}" class="hidden bg-slate-900/10">
                <td colspan="5" class="p-6 border-t border-b border-slate-800">
                  <div class="max-w-3xl text-left font-bold text-slate-300">
                    <h4 class="text-xs font-black text-white uppercase tracking-wider mb-4">Edit Data Game: {{ $game->name }}</h4>
                    
                    <form action="{{ route('admin.games.update', $game->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4.5">
                      @csrf
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Nama Game</label>
                        <input type="text" name="name" value="{{ $game->name }}" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Kategori</label>
                        <select name="category" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-bold focus:outline-none cursor-pointer">
                          <option value="mobile" {{ $game->category === 'mobile' ? 'selected' : '' }}>Mobile</option>
                          <option value="pc" {{ $game->category === 'pc' ? 'selected' : '' }}>PC</option>
                          <option value="console" {{ $game->category === 'console' ? 'selected' : '' }}>Console</option>
                          <option value="popular" {{ $game->category === 'popular' ? 'selected' : '' }}>Popular</option>
                          <option value="voucher" {{ $game->category === 'voucher' ? 'selected' : '' }}>Voucher</option>
                        </select>
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Developer</label>
                        <input type="text" name="developer" value="{{ $game->developer }}" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Cashback Persen (%)</label>
                        <input type="number" name="cashback_percent" value="{{ $game->cashback_percent }}" required min="0" max="100" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Thumbnail Game</label>
                        <div class="flex items-center gap-3">
                          @if($game->thumbnail_url)
                            <img src="{{ $game->thumbnail_url }}" alt="Thumb" class="h-10 w-10 rounded-xl object-cover border border-slate-800 shadow-md">
                          @endif
                          <input type="file" name="thumbnail" accept="image/*" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3 px-4 text-xs font-semibold focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-blue-600/10 file:text-cyan-400 hover:file:bg-blue-600/20 file:cursor-pointer cursor-pointer">
                        </div>
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Banner Game</label>
                        <div class="flex items-center gap-3">
                          @if($game->banner_url)
                            <img src="{{ $game->banner_url }}" alt="Banner" class="h-10 w-16 rounded-xl object-cover border border-slate-800 shadow-md">
                          @endif
                          <input type="file" name="banner" accept="image/*" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3 px-4 text-xs font-semibold focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-blue-600/10 file:text-cyan-400 hover:file:bg-blue-600/20 file:cursor-pointer cursor-pointer">
                        </div>
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Label Input ID</label>
                        <input type="text" name="id_label" value="{{ $game->id_label }}" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Label Zone ID</label>
                        <input type="text" name="zone_id_label" value="{{ $game->zone_id_label }}" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5 col-span-full">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Petunjuk Helper Text ID</label>
                        <textarea name="id_helper_text" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 h-24 text-xs font-semibold focus:outline-none resize-none">{{ $game->id_helper_text }}</textarea>
                      </div>
                      <div class="col-span-full flex gap-3.5 justify-end mt-2">
                        <button type="button" onclick="toggleEditForm({{ $game->id }})" class="border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-300 font-extrabold rounded-2xl px-5 py-3 text-xs cursor-pointer transition-all active:scale-95">Batal</button>
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-cyan-500 border-none text-white font-black text-xs py-3 px-5 rounded-2xl cursor-pointer hover:shadow-lg hover:shadow-blue-500/20 active:scale-95 transition-all">Simpan Perubahan</button>
                      </div>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="py-8 text-center text-slate-500 font-bold">Belum ada game terdaftar.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    function toggleAddForm() {
      const container = document.getElementById('add-game-form-container');
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
