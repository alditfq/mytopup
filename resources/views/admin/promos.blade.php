@extends('layouts.admin')

@section('title', 'Admin Panel - Kelola Voucher Promo')

@section('content')
  <div class="text-left animate-fade-in">
    <!-- Top Header -->
    <div class="border-b border-slate-800 pb-5 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-[10px] font-extrabold uppercase text-cyan-400 shadow-sm shadow-blue-500/5">
          <i data-lucide="tag" class="h-3.5 w-3.5"></i> VOUCHERS & PROMOS
        </span>
        <h1 class="text-2xl font-black mt-2 tracking-tight text-white">Manajemen Kode Voucher</h1>
        <p class="text-xs text-slate-400 mt-1 font-semibold">Buat kode promo baru, tentukan diskon nominal/persen, batasi minimum transaksi, dan atur kuota kupon.</p>
      </div>

      <button onclick="toggleAddForm()" class="bg-gradient-to-r from-blue-600 to-cyan-500 border-none text-white font-extrabold text-xs py-3 px-5 rounded-2xl cursor-pointer hover:shadow-lg hover:shadow-blue-500/20 active:scale-95 transition-all shadow-md flex items-center gap-2">
        <i data-lucide="plus-circle" class="h-4 w-4"></i> Terbitkan Voucher Baru
      </button>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
      <div class="mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-4 text-xs font-bold text-emerald-400 shadow-md">
        ✓ {{ session('success') }}
      </div>
    @endif

    <!-- ADD PROMO FORM (Collapsible) -->
    <div id="add-promo-form-container" class="hidden rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl mb-8 text-slate-300">
      <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-5">
        <h3 class="text-xs font-black text-white uppercase tracking-wider">Terbitkan Voucher Promo Baru</h3>
        <button type="button" onclick="toggleAddForm()" class="bg-transparent border-none text-slate-500 hover:text-slate-350 cursor-pointer"><i data-lucide="x" class="h-5 w-5"></i></button>
      </div>

      <form action="{{ route('admin.promos.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @csrf
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Nama Kampanye Promo</label>
          <input type="text" name="title" required placeholder="cth: Kejutan Berkah Cashback" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Kode Voucher (Kapital/Unique)</label>
          <input type="text" name="code" required placeholder="cth: BERKAHGAMER" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Tipe Diskon</label>
          <select name="discount_type" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-bold focus:outline-none cursor-pointer">
            <option value="nominal">Nominal Potongan Tetap (IDR)</option>
            <option value="percent">Persentase (%)</option>
          </select>
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Besar Diskon (Nominal Rupiah / Nilai Persen)</label>
          <input type="number" name="discount_amount" required placeholder="cth: 15000 / 10" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Minimum Transaksi (IDR)</label>
          <input type="number" name="min_transaction" required placeholder="cth: 30000" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Masa Berlaku (Hingga Tanggal - Opsional)</label>
          <input type="date" name="expiry_date" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none cursor-pointer">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Batas Kuota Pemakaian (Kuota Total)</label>
          <input type="number" name="max_uses" required value="100" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Banner Promo</label>
          <input type="file" name="image_file" required accept="image/*" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3 px-4 text-xs font-semibold focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-blue-600/10 file:text-cyan-400 hover:file:bg-blue-600/20 file:cursor-pointer cursor-pointer">
        </div>
        <div class="flex flex-col gap-1.5 col-span-full">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Link URL Klaim Promo (Opsional - Mengarahkan tombol banner ke halaman game/link khusus)</label>
          <input type="text" name="claim_url" placeholder="cth: /game/valorant atau https://external-link.com" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex flex-col gap-1.5 col-span-full">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Deskripsi Lengkap Syarat & Ketentuan</label>
          <textarea name="description" required placeholder="Detail syarat pemakaian kupon..." class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 h-24 text-xs font-semibold focus:outline-none resize-none placeholder:text-slate-500"></textarea>
        </div>
        <button type="submit" class="col-span-full bg-gradient-to-r from-blue-600 to-cyan-500 border-none text-white font-black tracking-wide uppercase py-4 rounded-2xl text-xs cursor-pointer hover:shadow-lg hover:shadow-blue-500/20 active:scale-95 transition-all mt-2">
          Terbitkan Voucher 🚀
        </button>
      </form>
    </div>

    <!-- PROMOS LIST TABLE -->
    <div class="rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl overflow-hidden">
      <div class="overflow-x-auto w-full">
        <table class="w-full text-slate-350 text-xs font-semibold border-collapse text-left">
          <thead>
            <tr class="border-b border-slate-800 text-[10px] uppercase tracking-wider text-slate-400">
              <th class="pb-3.5 pl-2">Voucher Promo</th>
              <th class="pb-3.5">Kode Unik</th>
              <th class="pb-3.5">Besaran Diskon</th>
              <th class="pb-3.5">Min. Belanja</th>
              <th class="pb-3.5">Kuota Terpakai</th>
              <th class="pb-3.5">Masa Berlaku</th>
              <th class="pb-3.5">Status</th>
              <th class="pb-3.5 text-right pr-4">Aksi Kontrol</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800">
            @forelse($promos as $promo)
              <tr class="hover:bg-slate-900/40 transition-colors">
                <!-- Title & Image Banner -->
                <td class="py-4 pl-2">
                  <div class="flex items-center gap-3">
                    <img src="{{ $promo->image }}" alt="{{ $promo->title }}" class="h-10 w-16 rounded-lg object-cover border border-slate-800 shadow-md">
                    <div class="max-w-[200px]">
                      <p class="font-black text-white leading-tight truncate">{{ $promo->title }}</p>
                      <p class="text-[9px] text-slate-400 leading-relaxed mt-0.5 truncate">{{ $promo->description }}</p>
                    </div>
                  </div>
                </td>
                
                <!-- Code -->
                <td class="py-4 font-mono font-black text-cyan-400">{{ $promo->code }}</td>
                
                <!-- Discount Value -->
                <td class="py-4 font-black">
                  @if($promo->discount_type === 'percent')
                    <span class="text-pink-400">{{ $promo->discount_amount }}% Potongan</span>
                  @else
                    <span class="text-emerald-400 font-mono">Rp {{ number_format($promo->discount_amount, 0, ',', '.') }}</span>
                  @endif
                </td>
                
                <!-- Min Transaction -->
                <td class="py-4 font-mono font-bold">Rp {{ number_format($promo->min_transaction, 0, ',', '.') }}</td>
                
                <!-- Usage stats -->
                <td class="py-4 font-mono font-bold">
                  {{ $promo->uses_count }} / {{ $promo->max_uses }}
                  <span class="block text-[8px] text-slate-500 font-sans mt-0.5">Sisa kuota: {{ $promo->max_uses - $promo->uses_count }}</span>
                </td>
                
                <!-- Expiry Date -->
                <td class="py-4 font-bold">
                  @if($promo->expiry_date)
                    @if(strtotime($promo->expiry_date) < time())
                      <span class="text-rose-400 text-[10px]">Kadaluarsa ({{ date('d M Y', strtotime($promo->expiry_date)) }})</span>
                    @else
                      <span class="text-slate-300 text-[10px]">{{ date('d M Y', strtotime($promo->expiry_date)) }}</span>
                    @endif
                  @else
                    <span class="text-slate-500">-</span>
                  @endif
                </td>

                <!-- Status Toggles -->
                <td class="py-4">
                  <form action="{{ route('admin.promos.toggle', $promo->id) }}" method="POST" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="border-none bg-transparent cursor-pointer">
                      @if($promo->is_active && ($promo->expiry_date ? strtotime($promo->expiry_date) >= time() : true))
                        <span class="rounded bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 text-[8px] font-black text-emerald-400 uppercase tracking-wider hover:bg-emerald-500/20 transition-all">AKTIF</span>
                      @else
                        <span class="rounded bg-rose-500/10 border border-rose-500/20 px-2.5 py-0.5 text-[8px] font-black text-rose-400 uppercase tracking-wider hover:bg-rose-500/20 transition-all">NON-AKTIF</span>
                      @endif
                    </button>
                  </form>
                </td>
                
                <!-- Table controls -->
                <td class="py-4 text-right pr-4">
                  <div class="inline-flex gap-2">
                    <button onclick="toggleEditForm({{ $promo->id }})" class="border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-100 font-extrabold rounded-xl px-3.5 py-2.5 text-[10px] cursor-pointer transition-all active:scale-95 shadow-sm">
                      Edit
                    </button>
                    <form action="{{ route('admin.promos.delete', $promo->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kode voucher {{ $promo->code }}?')" class="m-0 p-0">
                      @csrf
                      <button type="submit" class="border border-slate-700 bg-[#3f1922]/20 hover:bg-[#3f1922]/40 text-rose-400 font-extrabold rounded-xl px-3.5 py-2.5 text-[10px] cursor-pointer transition-all active:scale-95 shadow-sm">
                        Hapus
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              
              <!-- COLLAPSIBLE EDIT FORM ROW -->
              <tr id="edit-row-{{ $promo->id }}" class="hidden bg-slate-900/10">
                <td colspan="8" class="p-6 border-t border-b border-slate-800">
                  <div class="max-w-3xl text-left font-bold text-slate-300">
                    <h4 class="text-xs font-black text-white uppercase tracking-wider mb-4">Edit Data Voucher: {{ $promo->code }}</h4>
                    
                    <form action="{{ route('admin.promos.update', $promo->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4.5">
                      @csrf
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Nama Kampanye Promo</label>
                        <input type="text" name="title" value="{{ $promo->title }}" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Kode Voucher</label>
                        <input type="text" name="code" value="{{ $promo->code }}" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Tipe Diskon</label>
                        <select name="discount_type" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-bold focus:outline-none cursor-pointer">
                          <option value="nominal" {{ $promo->discount_type === 'nominal' ? 'selected' : '' }}>Nominal Potongan Tetap (IDR)</option>
                          <option value="percent" {{ $promo->discount_type === 'percent' ? 'selected' : '' }}>Persentase (%)</option>
                        </select>
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Besar Diskon</label>
                        <input type="number" name="discount_amount" value="{{ $promo->discount_amount }}" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Minimum Transaksi (IDR)</label>
                        <input type="number" name="min_transaction" value="{{ $promo->min_transaction }}" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Masa Berlaku (Hingga Tanggal)</label>
                        <input type="date" name="expiry_date" value="{{ $promo->expiry_date ? date('Y-m-d', strtotime($promo->expiry_date)) : '' }}" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none cursor-pointer">
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Batas Kuota Pemakaian</label>
                        <input type="number" name="max_uses" value="{{ $promo->max_uses }}" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Banner Promo</label>
                        <div class="flex items-center gap-3">
                          @if($promo->image)
                            <img src="{{ $promo->image }}" alt="Promo" class="h-10 w-16 rounded-xl object-cover border border-slate-800 shadow-md">
                          @endif
                          <input type="file" name="image_file" accept="image/*" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3 px-4 text-xs font-semibold focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-blue-600/10 file:text-cyan-400 hover:file:bg-blue-600/20 file:cursor-pointer cursor-pointer">
                        </div>
                      </div>
                      <div class="flex flex-col gap-1.5 col-span-full">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Link URL Klaim Promo (Opsional - Mengarahkan tombol banner ke halaman game/link khusus)</label>
                        <input type="text" name="claim_url" value="{{ $promo->claim_url }}" placeholder="cth: /game/valorant atau https://external-link.com" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
                      </div>
                      <div class="flex flex-col gap-1.5 col-span-full">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Deskripsi Lengkap Syarat & Ketentuan</label>
                        <textarea name="description" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 h-24 text-xs font-semibold focus:outline-none resize-none">{{ $promo->description }}</textarea>
                      </div>
                      <div class="col-span-full flex gap-3.5 justify-end mt-2">
                        <button type="button" onclick="toggleEditForm({{ $promo->id }})" class="border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-300 font-extrabold rounded-2xl px-5 py-3 text-xs cursor-pointer transition-all active:scale-95">Batal</button>
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-cyan-500 border-none text-white font-black text-xs py-3 px-5 rounded-2xl cursor-pointer hover:shadow-lg hover:shadow-blue-500/20 active:scale-95 transition-all">Simpan Perubahan</button>
                      </div>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="py-8 text-center text-slate-500 font-bold">Belum ada kode voucher terdaftar.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    function toggleAddForm() {
      const container = document.getElementById('add-promo-form-container');
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
