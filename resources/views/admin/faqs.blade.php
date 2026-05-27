@extends('layouts.admin')

@section('title', 'Admin Panel - Kelola FAQ')

@section('content')
  <div class="text-left animate-fade-in">
    <!-- Top Header -->
    <div class="border-b border-slate-800 pb-5 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-[10px] font-extrabold uppercase text-cyan-400 shadow-sm shadow-blue-500/5">
          <i data-lucide="help-circle" class="h-3.5 w-3.5"></i> FAQ MANAGEMENT
        </span>
        <h1 class="text-2xl font-black mt-2 tracking-tight text-white">Kelola Tanya Jawab (FAQ CRUD)</h1>
        <p class="text-xs text-slate-400 mt-1 font-semibold">Tulis tanya jawab baru, kategorikan bantuan, aktifkan status publikasi, dan urutkan urutan tampil.</p>
      </div>

      <button onclick="toggleAddForm()" class="bg-gradient-to-r from-blue-600 to-cyan-500 border-none text-white font-extrabold text-xs py-3 px-5 rounded-2xl cursor-pointer hover:shadow-lg hover:shadow-blue-500/20 active:scale-95 transition-all shadow-md flex items-center gap-2">
        <i data-lucide="plus-circle" class="h-4 w-4"></i> Tambah Tanya Jawab
      </button>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
      <div class="mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-4 text-xs font-bold text-emerald-400 shadow-md">
        ✓ {{ session('success') }}
      </div>
    @endif

    <!-- ADD FAQ FORM (Collapsible) -->
    <div id="add-faq-form-container" class="hidden rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl mb-8 text-slate-300 text-left">
      <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-5">
        <h3 class="text-xs font-black text-white uppercase tracking-wider">Tambah Tanya Jawab FAQ Baru</h3>
        <button type="button" onclick="toggleAddForm()" class="bg-transparent border-none text-slate-500 hover:text-slate-350 cursor-pointer"><i data-lucide="x" class="h-5 w-5"></i></button>
      </div>

      <form action="{{ route('admin.faqs.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @csrf
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Kategori FAQ</label>
          <select name="category" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-bold focus:outline-none cursor-pointer">
            <option value="General">General (Umum)</option>
            <option value="Payment">Payment (Pembayaran)</option>
            <option value="Refund">Refund (Pengembalian Dana)</option>
            <option value="Account">Account (Akun Member)</option>
            <option value="Promotion">Promotion (Diskon & Promosi)</option>
            <option value="Technical">Technical (Kendala API / Sistem)</option>
          </select>
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Urutan Tampil (Sort Order)</label>
          <input type="number" name="sort_order" required value="0" min="0" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
        </div>
        <div class="flex flex-col gap-1.5 col-span-full">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Pertanyaan FAQ</label>
          <input type="text" name="question" required placeholder="cth: Bagaimana cara melacak status pengisian order saya?" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex flex-col gap-1.5 col-span-full">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Jawaban FAQ</label>
          <textarea name="answer" required placeholder="cth: Masuk ke menu 'Lacak Pesanan', masukkan Nomor Invois Anda..." class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 h-24 text-xs font-semibold focus:outline-none resize-none placeholder:text-slate-500"></textarea>
        </div>
        <button type="submit" class="col-span-full bg-gradient-to-r from-blue-600 to-cyan-500 border-none text-white font-black tracking-wide uppercase py-4 rounded-2xl text-xs cursor-pointer hover:shadow-lg hover:shadow-blue-500/20 active:scale-95 transition-all mt-2">
          Simpan FAQ Baru 🚀
        </button>
      </form>
    </div>

    <!-- FILTER & SEARCH BAR -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 mb-6">
      <!-- Category filter tabs -->
      <div class="flex flex-wrap gap-1.5">
        <a href="{{ route('admin.faqs', ['category' => 'all', 'search' => request('search')]) }}" class="px-4 py-2.5 rounded-xl text-xs font-bold decoration-none border {{ !request('category') || request('category') === 'all' ? 'bg-blue-500/10 text-cyan-400 border-blue-500/30' : 'bg-slate-800/40 text-slate-400 hover:text-slate-200 border-transparent' }}">
          Semua Kategori
        </a>
        @foreach(['general' => 'Umum', 'payment' => 'Pembayaran', 'refund' => 'Refund', 'account' => 'Akun', 'promotion' => 'Promo', 'technical' => 'Teknis'] as $catSlug => $catLabel)
          <a href="{{ route('admin.faqs', ['category' => $catSlug, 'search' => request('search')]) }}" class="px-4 py-2.5 rounded-xl text-xs font-bold decoration-none border {{ request('category') === $catSlug ? 'bg-blue-500/10 text-cyan-400 border-blue-500/30' : 'bg-slate-800/40 text-slate-400 hover:text-slate-200 border-transparent' }}">
            {{ $catLabel }}
          </a>
        @endforeach
      </div>

      <!-- Search Box -->
      <form action="{{ route('admin.faqs') }}" method="GET" class="relative max-w-xs w-full m-0 p-0 flex gap-2">
        <input type="hidden" name="category" value="{{ request('category', 'all') }}">
        <input
          type="text"
          name="search"
          value="{{ request('search') }}"
          placeholder="Cari kata kunci FAQ..."
          class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-2.5 pl-4 pr-10 text-xs font-semibold focus:outline-none placeholder:text-slate-500"
        />
        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-transparent border-none text-slate-500 hover:text-slate-350 cursor-pointer flex items-center justify-center">
          <i data-lucide="search" class="h-4 w-4"></i>
        </button>
      </form>
    </div>

    <!-- FAQS LIST TABLE -->
    <div class="rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl overflow-hidden">
      <div class="overflow-x-auto w-full">
        <table class="w-full text-slate-350 text-xs font-semibold border-collapse text-left">
          <thead>
            <tr class="border-b border-slate-800 text-[10px] uppercase tracking-wider text-slate-400">
              <th class="pb-3.5 pl-2 w-16">Urutan</th>
              <th class="pb-3.5 w-32">Kategori</th>
              <th class="pb-3.5">Pertanyaan FAQ & Jawaban</th>
              <th class="pb-3.5 w-24">Status</th>
              <th class="pb-3.5 text-right pr-4 w-32">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800">
            @forelse($faqs as $faq)
              <tr class="hover:bg-slate-900/40 transition-colors">
                <td class="py-4 pl-2 font-mono font-black text-cyan-400">#{{ $faq->sort_order }}</td>
                <td class="py-4">
                  <span class="rounded bg-blue-500/10 border border-blue-500/20 text-cyan-400 px-2 py-0.5 text-[8px] font-black uppercase tracking-wider">
                    {{ $faq->category }}
                  </span>
                </td>
                <td class="py-4 pr-6">
                  <p class="font-bold text-white text-xs leading-normal">{{ $faq->question }}</p>
                  <p class="text-[10px] text-slate-400 leading-relaxed mt-1 font-semibold">{{ $faq->answer }}</p>
                </td>
                <td class="py-4">
                  <form action="{{ route('admin.faqs.toggle', $faq->id) }}" method="POST" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="border-none bg-transparent cursor-pointer">
                      @if($faq->is_active)
                        <span class="rounded bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 text-[8px] font-black text-emerald-400 uppercase tracking-wider hover:bg-emerald-500/20 transition-all">AKTIF</span>
                      @else
                        <span class="rounded bg-rose-500/10 border border-rose-500/20 px-2.5 py-0.5 text-[8px] font-black text-rose-400 uppercase tracking-wider hover:bg-rose-500/20 transition-all">OFFLINE</span>
                      @endif
                    </button>
                  </form>
                </td>
                <td class="py-4 text-right pr-4">
                  <div class="inline-flex gap-2">
                    <button onclick="toggleEditForm({{ $faq->id }})" class="border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-100 font-extrabold rounded-xl px-3.5 py-2 text-[10px] cursor-pointer transition-all active:scale-95 shadow-sm">
                      Edit
                    </button>
                    <form action="{{ route('admin.faqs.delete', $faq->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tanya jawab FAQ ini?')" class="m-0 p-0">
                      @csrf
                      <button type="submit" class="border border-slate-700 bg-rose-950/20 hover:bg-rose-900/30 text-rose-400 font-extrabold rounded-xl px-3.5 py-2 text-[10px] cursor-pointer transition-all active:scale-95 shadow-sm">
                        Hapus
                      </button>
                    </form>
                  </div>
                </td>
              </tr>

              <!-- COLLAPSIBLE EDIT FORM ROW -->
              <tr id="edit-row-{{ $faq->id }}" class="hidden bg-slate-900/10">
                <td colspan="5" class="p-6 border-t border-b border-slate-800">
                  <div class="max-w-3xl text-left font-bold text-slate-300">
                    <h4 class="text-xs font-black text-white uppercase tracking-wider mb-4">Edit Data Tanya Jawab FAQ</h4>
                    
                    <form action="{{ route('admin.faqs.update', $faq->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4.5">
                      @csrf
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Kategori FAQ</label>
                        <select name="category" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-bold focus:outline-none cursor-pointer">
                          <option value="General" {{ strtolower($faq->category) === 'general' ? 'selected' : '' }}>General (Umum)</option>
                          <option value="Payment" {{ strtolower($faq->category) === 'payment' ? 'selected' : '' }}>Payment (Pembayaran)</option>
                          <option value="Refund" {{ strtolower($faq->category) === 'refund' ? 'selected' : '' }}>Refund (Pengembalian Dana)</option>
                          <option value="Account" {{ strtolower($faq->category) === 'account' ? 'selected' : '' }}>Account (Akun Member)</option>
                          <option value="Promotion" {{ strtolower($faq->category) === 'promotion' ? 'selected' : '' }}>Promotion (Diskon & Promosi)</option>
                          <option value="Technical" {{ strtolower($faq->category) === 'technical' ? 'selected' : '' }}>Technical (Kendala API / Sistem)</option>
                        </select>
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Urutan Tampil (Sort Order)</label>
                        <input type="number" name="sort_order" value="{{ $faq->sort_order }}" required min="0" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5 col-span-full">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Pertanyaan FAQ</label>
                        <input type="text" name="question" value="{{ $faq->question }}" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5 col-span-full">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Jawaban FAQ</label>
                        <textarea name="answer" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 h-24 text-xs font-semibold focus:outline-none resize-none">{{ $faq->answer }}</textarea>
                      </div>
                      <div class="col-span-full flex gap-3.5 justify-end mt-2">
                        <button type="button" onclick="toggleEditForm({{ $faq->id }})" class="border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-300 font-extrabold rounded-2xl px-5 py-3 text-xs cursor-pointer transition-all active:scale-95">Batal</button>
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-cyan-500 border-none text-white font-black text-xs py-3 px-5 rounded-2xl cursor-pointer hover:shadow-lg hover:shadow-blue-500/20 active:scale-95 transition-all">Simpan Perubahan</button>
                      </div>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="py-12 text-center text-slate-500 font-bold">Belum ada tanya jawab FAQ dalam database.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    function toggleAddForm() {
      const container = document.getElementById('add-faq-form-container');
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
