@extends('layouts.admin')

@section('title', 'Admin Panel - Ulasan Pelanggan')

@section('content')
  <div class="text-left animate-fade-in">
    <!-- Top Header -->
    <div class="border-b border-slate-800 pb-5 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-[10px] font-extrabold uppercase text-cyan-400 shadow-sm shadow-blue-500/5">
          <i data-lucide="smile" class="h-3.5 w-3.5"></i> TESTIMONIAL MODERATION
        </span>
        <h1 class="text-2xl font-black mt-2 tracking-tight text-white">Manajemen Ulasan Pelanggan</h1>
        <p class="text-xs text-slate-400 mt-1 font-semibold">Tinjau ulasan masuk dari member, publikasikan testimonial ke homepage, and jadikan ulasan unggulan.</p>
      </div>

      <button onclick="toggleAddForm()" class="bg-gradient-to-r from-blue-600 to-cyan-500 border-none text-white font-extrabold text-xs py-3 px-5 rounded-2xl cursor-pointer hover:shadow-lg hover:shadow-blue-500/20 active:scale-95 transition-all shadow-md flex items-center gap-2">
        <i data-lucide="plus-circle" class="h-4 w-4"></i> Tambah Ulasan Manual
      </button>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
      <div class="mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-4 text-xs font-bold text-emerald-400 shadow-md">
        ✓ {{ session('success') }}
      </div>
    @endif

    <!-- ADD TESTIMONIAL FORM (Collapsible) -->
    <div id="add-testimonial-form-container" class="hidden rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl mb-8 text-slate-300 text-left">
      <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-5">
        <h3 class="text-xs font-black text-white uppercase tracking-wider">Tambah Ulasan Testimonial Baru</h3>
        <button type="button" onclick="toggleAddForm()" class="bg-transparent border-none text-slate-500 hover:text-slate-350 cursor-pointer"><i data-lucide="x" class="h-5 w-5"></i></button>
      </div>

      <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @csrf
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Nama Pelanggan (Username)</label>
          <input type="text" name="username" required placeholder="cth: Rian Hidayat" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Rating Penilaian (1 - 5 Stars)</label>
          <select name="rating" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-bold focus:outline-none cursor-pointer">
            <option value="5">★★★★★ (5 Stars / Sempurna)</option>
            <option value="4">★★★★☆ (4 Stars / Sangat Puas)</option>
            <option value="3">★★★☆☆ (3 Stars / Cukup)</option>
            <option value="2">★★☆☆☆ (2 Stars / Kurang)</option>
            <option value="1">★☆☆☆☆ (1 Star / Buruk)</option>
          </select>
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Game Yang Di-Top Up</label>
          <input type="text" name="game_name" required placeholder="cth: Mobile Legends / Free Fire" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Avatar Pelanggan (Opsional)</label>
          <input type="file" name="avatar_file" accept="image/*" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3 px-4 text-xs font-semibold focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-blue-600/10 file:text-cyan-400 hover:file:bg-blue-600/20 file:cursor-pointer cursor-pointer">
        </div>
        <div class="flex flex-col gap-1.5 col-span-full">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Isi Ulasan Testimonial</label>
          <textarea name="message" required placeholder="Tulis ulasan kepuasan pelanggan..." class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 h-24 text-xs font-semibold focus:outline-none resize-none placeholder:text-slate-500"></textarea>
        </div>
        <button type="submit" class="col-span-full bg-gradient-to-r from-blue-600 to-cyan-500 border-none text-white font-black tracking-wide uppercase py-4 rounded-2xl text-xs cursor-pointer hover:shadow-lg hover:shadow-blue-500/20 active:scale-95 transition-all mt-2">
          Terbitkan Ulasan Testimonial 🚀
        </button>
      </form>
    </div>

    <!-- FILTER & SEARCH PANEL -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 mb-6">
      <!-- Filter links -->
      <div class="flex flex-wrap gap-1.5">
        <a href="{{ route('admin.testimonials', ['filter' => 'all', 'search' => request('search')]) }}" class="px-4 py-2.5 rounded-xl text-xs font-bold decoration-none border {{ !request('filter') || request('filter') === 'all' ? 'bg-blue-500/10 text-cyan-400 border-blue-500/30' : 'bg-slate-800/40 text-slate-400 hover:text-slate-200 border-transparent' }}">
          Semua Ulasan
        </a>
        <a href="{{ route('admin.testimonials', ['filter' => 'pending', 'search' => request('search')]) }}" class="px-4 py-2.5 rounded-xl text-xs font-bold decoration-none border {{ request('filter') === 'pending' ? 'bg-blue-500/10 text-cyan-400 border-blue-500/30' : 'bg-slate-800/40 text-slate-400 hover:text-slate-200 border-transparent' }}">
          Butuh Review (Pending)
        </a>
        <a href="{{ route('admin.testimonials', ['filter' => 'approved', 'search' => request('search')]) }}" class="px-4 py-2.5 rounded-xl text-xs font-bold decoration-none border {{ request('filter') === 'approved' ? 'bg-blue-500/10 text-cyan-400 border-blue-500/30' : 'bg-slate-800/40 text-slate-400 hover:text-slate-200 border-transparent' }}">
          Terbit (Approved)
        </a>
        <a href="{{ route('admin.testimonials', ['filter' => 'featured', 'search' => request('search')]) }}" class="px-4 py-2.5 rounded-xl text-xs font-bold decoration-none border {{ request('filter') === 'featured' ? 'bg-blue-500/10 text-cyan-400 border-blue-500/30' : 'bg-slate-800/40 text-slate-400 hover:text-slate-200 border-transparent' }}">
          Unggulan (Featured)
        </a>
      </div>

      <!-- Search Box -->
      <form action="{{ route('admin.testimonials') }}" method="GET" class="relative max-w-xs w-full m-0 p-0 flex gap-2">
        <input type="hidden" name="filter" value="{{ request('filter', 'all') }}">
        <input
          type="text"
          name="search"
          value="{{ request('search') }}"
          placeholder="Cari ulasan / nama..."
          class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-2.5 pl-4 pr-10 text-xs font-semibold focus:outline-none placeholder:text-slate-500"
        />
        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-transparent border-none text-slate-500 hover:text-slate-350 cursor-pointer flex items-center justify-center">
          <i data-lucide="search" class="h-4 w-4"></i>
        </button>
      </form>
    </div>

    <!-- TESTIMONIAL CARDS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
      @forelse($testimonials as $testi)
        <div class="rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl flex flex-col justify-between text-slate-300 text-left">
          
          <!-- Message and rating -->
          <div>
            <div class="flex items-center gap-1.5 mb-3.5">
              @for($i = 1; $i <= 5; $i++)
                <i data-lucide="star" class="h-4 w-4 {{ $i <= $testi->rating ? 'text-amber-400 fill-amber-400' : 'text-slate-700' }}"></i>
              @endfor
            </div>
            
            <p class="text-xs font-semibold leading-relaxed text-slate-200">"{{ $testi->message }}"</p>
          </div>

          <!-- Profile details & actions -->
          <div class="mt-6 border-t border-slate-800 pt-4">
            <div class="flex items-center gap-3 mb-4">
              @php
                $firstChar = strtoupper(substr($testi->username, 0, 1));
                $bgColors = [
                    'A' => 'from-rose-600/30 to-rose-500/10 text-rose-400 border-rose-800/40',
                    'B' => 'from-pink-600/30 to-pink-500/10 text-pink-400 border-pink-800/40',
                    'C' => 'from-fuchsia-600/30 to-fuchsia-500/10 text-fuchsia-400 border-fuchsia-800/40',
                    'D' => 'from-purple-600/30 to-purple-500/10 text-purple-400 border-purple-800/40',
                    'E' => 'from-violet-600/30 to-violet-500/10 text-violet-400 border-violet-800/40',
                    'F' => 'from-indigo-600/30 to-indigo-500/10 text-indigo-400 border-indigo-800/40',
                    'G' => 'from-blue-600/30 to-blue-500/10 text-blue-400 border-blue-800/40',
                    'H' => 'from-sky-600/30 to-sky-500/10 text-sky-400 border-sky-800/40',
                    'I' => 'from-cyan-600/30 to-cyan-500/10 text-cyan-400 border-cyan-800/40',
                    'J' => 'from-teal-600/30 to-teal-500/10 text-teal-450 border-teal-800/40',
                    'K' => 'from-emerald-600/30 to-emerald-500/10 text-emerald-400 border-emerald-800/40',
                    'L' => 'from-green-600/30 to-green-500/10 text-green-450 border-green-800/40',
                    'M' => 'from-lime-600/30 to-lime-500/10 text-lime-455 border-lime-800/40',
                    'N' => 'from-yellow-600/30 to-yellow-500/10 text-yellow-300 border-yellow-800/40',
                    'O' => 'from-amber-600/30 to-amber-500/10 text-amber-300 border-amber-800/40',
                    'P' => 'from-orange-600/30 to-orange-500/10 text-orange-400 border-orange-800/40',
                    'Q' => 'from-red-600/30 to-red-500/10 text-red-400 border-red-800/40',
                    'R' => 'from-rose-600/35 to-rose-700/15 text-rose-350 border-rose-750/40',
                    'S' => 'from-pink-600/35 to-pink-700/15 text-pink-350 border-pink-750/40',
                    'T' => 'from-fuchsia-600/35 to-fuchsia-700/15 text-fuchsia-350 border-fuchsia-750/40',
                    'U' => 'from-purple-600/35 to-purple-700/15 text-purple-350 border-purple-750/40',
                    'V' => 'from-violet-600/35 to-violet-700/15 text-violet-350 border-violet-750/40',
                    'W' => 'from-indigo-600/35 to-indigo-700/15 text-indigo-350 border-indigo-750/40',
                    'X' => 'from-blue-600/35 to-blue-700/15 text-blue-350 border-blue-750/40',
                    'Y' => 'from-sky-600/35 to-sky-700/15 text-sky-350 border-sky-700/40',
                    'Z' => 'from-cyan-600/35 to-cyan-700/15 text-cyan-350 border-cyan-700/40',
                ];
                $gradClass = $bgColors[$firstChar] ?? 'from-slate-600/30 to-slate-500/10 text-slate-350 border-slate-750/40';
              @endphp
              <div class="h-9 w-9 rounded-xl bg-gradient-to-br border flex items-center justify-center font-black text-xs flex-shrink-0 {{ $gradClass }}">
                {{ $firstChar }}
              </div>
              <div>
                <p class="text-xs font-black text-white leading-none">{{ $testi->username }}</p>
                <div class="flex items-center gap-1.5 mt-1.5">
                  <span class="text-[10px] font-black text-cyan-400">{{ $testi->game_name }}</span>
                </div>
              </div>
            </div>

            <!-- Action buttons -->
            <div class="flex flex-wrap items-center justify-between gap-2 border-t border-slate-850 pt-3">
              <!-- Approve/Reject trigger -->
              <form action="{{ route('admin.testimonials.toggle-approve', $testi->id) }}" method="POST" class="m-0 p-0">
                @csrf
                <button type="submit" class="border border-slate-700 bg-slate-800 hover:bg-slate-700 rounded-xl px-2.5 py-1.5 text-[9px] font-bold cursor-pointer text-slate-300 hover:text-white transition-all flex items-center gap-1">
                  @if($testi->is_approved)
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Disetujui
                  @else
                    <span class="h-1.5 w-1.5 rounded-full bg-rose-500 animate-pulse"></span> Approve?
                  @endif
                </button>
              </form>

              <div class="flex items-center gap-2">
                <!-- Featured trigger -->
                <form action="{{ route('admin.testimonials.toggle-featured', $testi->id) }}" method="POST" class="m-0 p-0">
                  @csrf
                  <button type="submit" class="border border-slate-700 bg-slate-800 hover:bg-slate-700 rounded-xl p-1.5 text-[9px] font-bold cursor-pointer transition-all {{ $testi->is_featured ? 'text-amber-400' : 'text-slate-400 hover:text-slate-200' }}">
                    <i data-lucide="award" class="h-4.5 w-4.5"></i>
                  </button>
                </form>

                <!-- Delete trigger -->
                <form action="{{ route('admin.testimonials.delete', $testi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus testimonial ini?')" class="m-0 p-0">
                  @csrf
                  <button type="submit" class="border border-slate-700 bg-rose-950/20 hover:bg-rose-900/30 rounded-xl p-1.5 text-rose-455 cursor-pointer transition-all active:scale-95">
                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                  </button>
                </form>
              </div>
            </div>

          </div>

        </div>
      @empty
        <p class="col-span-full py-12 text-center text-slate-500 font-bold">Belum ada ulasan testimonial untuk disajikan.</p>
      @endforelse
    </div>
  </div>

  <script>
    function toggleAddForm() {
      const container = document.getElementById('add-testimonial-form-container');
      if (container) {
        container.classList.toggle('hidden');
        if (!container.classList.contains('hidden')) {
          container.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      }
    }
  </script>
@endsection
