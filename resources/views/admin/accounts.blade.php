@extends('layouts.admin')

@section('title', 'Admin Panel - Kelola Akun Game')

@section('content')
  <div class="text-left animate-fade-in">
    <!-- Top Header -->
    <div class="border-b border-slate-800 pb-5 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-[10px] font-extrabold uppercase text-emerald-400 shadow-sm shadow-emerald-500/5">
          <i data-lucide="key-round" class="h-3.5 w-3.5"></i> GAME ACCOUNTS MARKETPLACE
        </span>
        <h1 class="text-2xl font-black mt-2 tracking-tight text-white">Manajemen Akun Game</h1>
        <p class="text-xs text-slate-400 mt-1 font-semibold">Publikasikan akun game, kelola harga, spesifikasi detail, serta unggah screenshot galeri.</p>
      </div>

      <button onclick="toggleAddForm()" class="bg-gradient-to-r from-emerald-600 to-teal-500 border-none text-white font-extrabold text-xs py-3 px-5 rounded-2xl cursor-pointer hover:shadow-lg hover:shadow-emerald-500/20 active:scale-95 transition-all shadow-md flex items-center gap-2">
        <i data-lucide="plus-circle" class="h-4 w-4"></i> Publikasikan Akun Baru
      </button>
    </div>

    <!-- Flash Messages / Success notification -->
    @if(session('success'))
      <div class="mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-4 text-xs font-bold text-emerald-400 shadow-md">
        ✓ {{ session('success') }}
      </div>
    @endif
    
    @if($errors->any())
      <div class="mb-6 rounded-2xl bg-rose-500/10 border border-rose-500/30 p-4 text-xs font-bold text-rose-450 shadow-md">
        <p class="mb-1 text-rose-400">Oops, terdapat kesalahan validasi:</p>
        <ul class="list-disc list-inside m-0 p-0 text-slate-350">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- ADD ACCOUNT FORM (Collapsible) -->
    <div id="add-account-form-container" class="hidden rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl mb-8 text-slate-300">
      <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-5">
        <h3 class="text-xs font-black text-white uppercase tracking-wider">Publikasikan Akun Game Baru</h3>
        <button type="button" onclick="toggleAddForm()" class="bg-transparent border-none text-slate-500 hover:text-slate-350 cursor-pointer"><i data-lucide="x" class="h-5 w-5"></i></button>
      </div>

      <form action="{{ route('admin.accounts.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-5 font-semibold text-xs">
        @csrf
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Game Terkait</label>
          <select name="game_id" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-bold focus:outline-none cursor-pointer">
            <option value="" disabled selected>Pilih Game...</option>
            @foreach($games as $game)
              <option value="{{ $game->id }}">{{ $game->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="flex flex-col gap-1.5 md:col-span-2">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Judul Posting Akun</label>
          <input type="text" name="title" required placeholder="cth: MLBB Akun GG, 50+ Skins, Legend Rank" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Spesifikasi Rank</label>
          <input type="text" name="rank" required placeholder="cth: Mythical Glory / Legend II" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Tingkat Level</label>
          <input type="number" name="level" required min="1" value="30" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Jumlah Skin</label>
          <input type="number" name="skin_count" required min="0" value="45" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Metode Login</label>
          <input type="text" name="login_method" required placeholder="Google Play / Moonton ID / Facebook" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Status Bind Akun</label>
          <input type="text" name="bind_status" required placeholder="All Unbind / Bind Moonton Saja" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Harga Jual (Rp)</label>
          <input type="number" name="price" required min="0" placeholder="cth: 350000" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex flex-col gap-1.5 col-span-full">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Screenshot Akun (Multiple Images)</label>
          <input type="file" name="images[]" required multiple accept="image/*" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3 px-4 text-xs font-semibold focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-emerald-600/10 file:text-emerald-400 hover:file:bg-emerald-600/20 file:cursor-pointer cursor-pointer">
          <p class="text-[9px] text-slate-500 m-0">Anda dapat memilih lebih dari satu gambar sekaligus untuk galeri slide di detail produk.</p>
        </div>
        <div class="flex flex-col gap-1.5 col-span-full md:col-span-2">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Detail Kredensial Akun (Terenkripsi Database)</label>
          <textarea name="account_data" required placeholder="Masukkan Username, Password, Akun Gmail Pemulihan, Kode Backup, dll. Informasi ini hanya akan dikirimkan kepada pembeli sah setelah status transaksi Paid/Success." class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 h-28 text-xs font-semibold focus:outline-none resize-none placeholder:text-slate-500"></textarea>
        </div>
        <div class="flex flex-col gap-1.5 col-span-full md:col-span-1">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Deskripsi / Informasi Tambahan</label>
          <textarea name="description" placeholder="Informasi hero GG, skin langka, status top up pertama, dll..." class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 h-28 text-xs font-semibold focus:outline-none resize-none placeholder:text-slate-500"></textarea>
        </div>
        <div class="flex items-center gap-2 col-span-full">
          <input type="checkbox" name="featured" id="featured_checkbox" value="1" class="rounded border-slate-750 bg-slate-800 h-4 w-4 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
          <label for="featured_checkbox" class="text-xs font-bold text-slate-350 cursor-pointer">Jadikan sebagai Akun Pilihan (Featured Account) di Halaman Beranda</label>
        </div>
        <button type="submit" class="col-span-full bg-gradient-to-r from-emerald-600 to-teal-500 border-none text-white font-black tracking-wide uppercase py-4 rounded-2xl text-xs cursor-pointer hover:shadow-lg hover:shadow-emerald-500/20 active:scale-98 transition-all mt-2">
          Publikasikan Akun Game 🚀
        </button>
      </form>
    </div>

    <!-- FILTER & SEARCH PANEL -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <form action="{{ route('admin.accounts') }}" method="GET" class="w-full grid grid-cols-1 sm:grid-cols-3 gap-3 font-semibold">
        <!-- Search field -->
        <div class="relative w-full sm:col-span-2">
          <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari judul posting akun game..."
            class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-2.5 pl-4 pr-10 text-xs font-semibold focus:outline-none placeholder:text-slate-500"
          />
          <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-transparent border-none text-slate-500 hover:text-slate-350 cursor-pointer flex items-center justify-center">
            <i data-lucide="search" class="h-4 w-4"></i>
          </button>
        </div>

        <!-- Game dropdown -->
        <div class="flex gap-2">
          <select name="game_id" onchange="this.form.submit()" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-2.5 px-4 text-xs font-bold focus:outline-none cursor-pointer">
            <option value="all" {{ request('game_id') === 'all' || !request('game_id') ? 'selected' : '' }}>Semua Game</option>
            @foreach($games as $game)
              <option value="{{ $game->id }}" {{ request('game_id') == $game->id ? 'selected' : '' }}>{{ $game->name }}</option>
            @endforeach
          </select>

          @if(request()->filled('search') || request()->filled('game_id'))
            <a href="{{ route('admin.accounts') }}" class="flex items-center justify-center rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 hover:bg-rose-500/25 px-3 cursor-pointer decoration-none transition-all active:scale-95 shadow-sm" title="Reset Filter">
              <i data-lucide="x" class="h-4 w-4"></i>
            </a>
          @endif
        </div>
      </form>
    </div>

    <!-- ACCOUNTS LIST TABLE -->
    <div class="rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl overflow-hidden">
      <div class="overflow-x-auto w-full">
        <table class="w-full text-slate-350 text-xs font-semibold border-collapse text-left">
          <thead>
            <tr class="border-b border-slate-800 text-[10px] uppercase tracking-wider text-slate-400">
              <th class="pb-3.5 pl-2">Informasi Akun</th>
              <th class="pb-3.5">Game</th>
              <th class="pb-3.5">Spesifikasi</th>
              <th class="pb-3.5">Harga</th>
              <th class="pb-3.5">Status</th>
              <th class="pb-3.5 text-right pr-4">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800">
            @forelse($accounts as $acc)
              <tr class="hover:bg-slate-900/40 transition-colors">
                <td class="py-4 pl-2">
                  <div class="flex items-start gap-3.5">
                    <div class="h-12 w-20 rounded-xl overflow-hidden border border-slate-800 shadow-md flex-shrink-0 bg-slate-900">
                      @if($acc->images && count($acc->images) > 0)
                        <img src="{{ $acc->images[0] }}" class="h-full w-full object-cover">
                      @else
                        <div class="h-full w-full flex items-center justify-center font-black text-slate-650 uppercase">No Img</div>
                      @endif
                    </div>
                    <div>
                      <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="font-black text-white text-sm leading-tight">{{ $acc->title }}</span>
                        @if($acc->featured)
                          <span class="bg-amber-500/10 border border-amber-500/35 text-amber-500 text-[8px] font-black px-1.5 py-0.2 rounded uppercase">Featured</span>
                        @endif
                      </div>
                      <p class="text-[9px] text-slate-500 font-mono mt-1 leading-none">Slug: {{ $acc->slug }}</p>
                    </div>
                  </div>
                </td>
                <td class="py-4 text-slate-250 font-black">
                  <span class="bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 px-2.5 py-0.5 rounded-lg uppercase text-[9px]">{{ $acc->game->name }}</span>
                </td>
                <td class="py-4 font-bold text-slate-300">
                  <div class="space-y-0.5">
                    <p class="text-xs">Rank: <strong class="text-white">{{ $acc->rank }}</strong></p>
                    <p class="text-[10px] text-slate-450">Lvl: {{ $acc->level }} | Skin: {{ $acc->skin_count }}</p>
                  </div>
                </td>
                <td class="py-4 font-mono font-black text-[#ff007f] text-sm">Rp {{ number_format($acc->price, 0, ',', '.') }}</td>
                <td class="py-4 font-extrabold uppercase text-[9px]">
                  @if($acc->status === 'available')
                    <span class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-2.5 py-0.5 rounded-lg">Tersedia</span>
                  @else
                    <span class="bg-slate-500/15 border border-slate-800 text-slate-500 px-2.5 py-0.5 rounded-lg">Terjual</span>
                  @endif
                </td>
                <td class="py-4 text-right pr-4">
                  <div class="inline-flex gap-2 items-center">
                    <!-- Toggle Featured -->
                    <form action="{{ route('admin.accounts.toggle', $acc->id) }}?type=featured" method="POST" class="m-0 p-0" title="Toggle Unggulan Beranda">
                      @csrf
                      <button type="submit" class="border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-100 font-extrabold rounded-xl px-2.5 py-2 text-[10px] cursor-pointer transition-all active:scale-95 shadow-sm">
                        <i data-lucide="star" class="h-3.5 w-3.5 {{ $acc->featured ? 'fill-amber-400 text-amber-400' : 'text-slate-450' }}"></i>
                      </button>
                    </form>
                    
                    <!-- Toggle Sold/Available -->
                    <form action="{{ route('admin.accounts.toggle', $acc->id) }}?type=status" method="POST" class="m-0 p-0" title="Ubah Status Sold/Available">
                      @csrf
                      <button type="submit" class="border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-100 font-extrabold rounded-xl px-2.5 py-2 text-[10px] cursor-pointer transition-all active:scale-95 shadow-sm">
                        <i data-lucide="shopping-cart" class="h-3.5 w-3.5 {{ $acc->status === 'sold' ? 'text-[#ff007f]' : 'text-emerald-400' }}"></i>
                      </button>
                    </form>

                    <button onclick="toggleEditForm({{ $acc->id }})" class="border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-100 font-extrabold rounded-xl px-3.5 py-2.5 text-[10px] cursor-pointer transition-all active:scale-95 shadow-sm">
                      Edit
                    </button>

                    <form action="{{ route('admin.accounts.delete', $acc->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun game {{ $acc->title }} secara permanen dari marketplace?')" class="m-0 p-0">
                      @csrf
                      <button type="submit" class="border border-slate-700 bg-rose-950/20 hover:bg-rose-900/30 text-rose-400 font-extrabold rounded-xl px-3.5 py-2.5 text-[10px] cursor-pointer transition-all active:scale-95 shadow-sm">
                        Hapus
                      </button>
                    </form>
                  </div>
                </td>
              </tr>

              <!-- COLLAPSIBLE EDIT FORM ROW -->
              <tr id="edit-row-{{ $acc->id }}" class="hidden bg-slate-900/10">
                <td colspan="6" class="p-6 border-t border-b border-slate-800">
                  <div class="max-w-4xl text-left font-bold text-slate-300 text-xs">
                    <h4 class="text-xs font-black text-white uppercase tracking-wider mb-4">Edit Data Akun: {{ $acc->title }}</h4>
                    
                    <form action="{{ route('admin.accounts.update', $acc->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-4.5">
                      @csrf
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Game Terkait</label>
                        <select name="game_id" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-bold focus:outline-none cursor-pointer">
                          @foreach($games as $game)
                            <option value="{{ $game->id }}" {{ $acc->game_id == $game->id ? 'selected' : '' }}>{{ $game->name }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="flex flex-col gap-1.5 md:col-span-2">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Judul Posting Akun</label>
                        <input type="text" name="title" value="{{ $acc->title }}" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Spesifikasi Rank</label>
                        <input type="text" name="rank" value="{{ $acc->rank }}" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Tingkat Level</label>
                        <input type="number" name="level" value="{{ $acc->level }}" required min="1" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Jumlah Skin</label>
                        <input type="number" name="skin_count" value="{{ $acc->skin_count }}" required min="0" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Metode Login</label>
                        <input type="text" name="login_method" value="{{ $acc->login_method }}" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Status Bind Akun</label>
                        <input type="text" name="bind_status" value="{{ $acc->bind_status }}" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Harga Jual (Rp)</label>
                        <input type="number" name="price" value="{{ $acc->price }}" required min="0" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5 col-span-full">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Screenshot Akun (Biarkan Kosong Jika Tidak Diganti)</label>
                        @if($acc->images && count($acc->images) > 0)
                          <div class="flex gap-2.5 mb-2.5 overflow-x-auto py-1">
                            @foreach($acc->images as $imgUrl)
                              <img src="{{ $imgUrl }}" class="h-14 w-24 rounded-lg object-cover border border-slate-850 shadow-inner">
                            @endforeach
                          </div>
                        @endif
                        <input type="file" name="images[]" multiple accept="image/*" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3 px-4 text-xs font-semibold focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-emerald-600/10 file:text-emerald-400 hover:file:bg-emerald-600/20 file:cursor-pointer cursor-pointer">
                      </div>
                      <div class="flex flex-col gap-1.5 col-span-full md:col-span-2">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Detail Kredensial Akun (Terenkripsi Database)</label>
                        <textarea name="account_data" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 h-28 text-xs font-semibold focus:outline-none resize-none">{{ $acc->account_data }}</textarea>
                      </div>
                      <div class="flex flex-col gap-1.5 col-span-full md:col-span-1">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Deskripsi / Informasi Tambahan</label>
                        <textarea name="description" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 h-28 text-xs font-semibold focus:outline-none resize-none">{{ $acc->description }}</textarea>
                      </div>
                      <div class="flex items-center gap-2 col-span-full">
                        <input type="checkbox" name="featured" id="edit_featured_{{ $acc->id }}" value="1" {{ $acc->featured ? 'checked' : '' }} class="rounded border-slate-750 bg-slate-800 h-4 w-4 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                        <label for="edit_featured_{{ $acc->id }}" class="text-xs font-bold text-slate-350 cursor-pointer">Tandai sebagai Akun Pilihan (Featured Account) di Halaman Beranda</label>
                      </div>
                      <div class="col-span-full flex gap-3.5 justify-end mt-2">
                        <button type="button" onclick="toggleEditForm({{ $acc->id }})" class="border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-300 font-extrabold rounded-2xl px-5 py-3 text-xs cursor-pointer transition-all active:scale-95">Batal</button>
                        <button type="submit" class="bg-gradient-to-r from-emerald-600 to-teal-500 border-none text-white font-black text-xs py-3 px-5 rounded-2xl cursor-pointer hover:shadow-lg hover:shadow-emerald-500/20 active:scale-95 transition-all">Simpan Perubahan</button>
                      </div>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="py-8 text-center text-slate-500 font-bold">Belum ada akun game terdaftar.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    function toggleAddForm() {
      const container = document.getElementById('add-account-form-container');
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
