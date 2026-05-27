@extends('layouts.admin')

@section('title', 'Admin Panel - Kelola Metode Pembayaran')

@section('content')
  <div class="text-left animate-fade-in">
    <!-- Top Header -->
    <div class="border-b border-slate-800 pb-5 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-[10px] font-extrabold uppercase text-cyan-400 shadow-sm shadow-blue-500/5">
          <i data-lucide="wallet" class="h-3.5 w-3.5"></i> PAYMENT CHANNELS
        </span>
        <h1 class="text-2xl font-black mt-2 tracking-tight text-white">Manajemen Metode Pembayaran (CRUD)</h1>
        <p class="text-xs text-slate-400 mt-1 font-semibold">Konfigurasi fee admin gerbang pembayaran, nomor rekening Virtual Account, dan petunjuk bayar.</p>
      </div>

      <button onclick="toggleAddForm()" class="bg-gradient-to-r from-blue-600 to-cyan-500 border-none text-white font-extrabold text-xs py-3 px-5 rounded-2xl cursor-pointer hover:shadow-lg hover:shadow-blue-500/20 active:scale-95 transition-all shadow-md flex items-center gap-2">
        <i data-lucide="plus-circle" class="h-4 w-4"></i> Tambah Metode Baru
      </button>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
      <div class="mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-4 text-xs font-bold text-emerald-400 shadow-md">
        ✓ {{ session('success') }}
      </div>
    @endif

    <!-- ADD PAYMENT FORM (Collapsible) -->
    <div id="add-payment-form-container" class="hidden rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl mb-8 text-slate-300 text-left">
      <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-5">
        <h3 class="text-xs font-black text-white uppercase tracking-wider">Tambah Metode Pembayaran Baru</h3>
        <button type="button" onclick="toggleAddForm()" class="bg-transparent border-none text-slate-500 hover:text-slate-350 cursor-pointer"><i data-lucide="x" class="h-5 w-5"></i></button>
      </div>

      <form action="{{ route('admin.payment-methods.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @csrf
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Nama Metode Pembayaran</label>
          <input type="text" name="name" required placeholder="cth: Gopay / ShopeePay / BNI VA" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Grup Metode</label>
          <select name="group" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-bold focus:outline-none cursor-pointer">
            <option value="qris">QRIS (All E-Wallet)</option>
            <option value="e-wallet">E-Wallet Dompet Digital</option>
            <option value="bank">Virtual Account / Transfer Bank</option>
          </select>
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Fee Layanan Admin (IDR)</label>
          <input type="number" name="fee" required placeholder="1000" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Nomor Akun Rekening VA (Jika VA/Transfer)</label>
          <input type="text" name="account_number" placeholder="cth: 880012345678" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500">
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Logo/Icon Pembayaran</label>
          <input type="file" name="image_file" accept="image/*" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3 px-4 text-xs font-semibold focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-blue-600/10 file:text-cyan-400 hover:file:bg-blue-600/20 file:cursor-pointer cursor-pointer">
        </div>
        <div class="flex flex-col gap-1.5 col-span-full">
          <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Petunjuk Cara Bayar (Tulis satu instruksi per baris)</label>
          <textarea name="instructions" required placeholder="Langkah 1: Buka aplikasi...&#10;Langkah 2: Pilih bayar...&#10;Langkah 3: Masukkan PIN..." class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 h-28 text-xs font-semibold focus:outline-none resize-none placeholder:text-slate-500"></textarea>
        </div>
        <button type="submit" class="col-span-full bg-gradient-to-r from-blue-600 to-cyan-500 border-none text-white font-black tracking-wide uppercase py-4 rounded-2xl text-xs cursor-pointer hover:shadow-lg hover:shadow-blue-500/20 active:scale-95 transition-all mt-2">
          Simpan Metode Pembayaran Baru 🚀
        </button>
      </form>
    </div>

    <!-- PAYMENTS LIST TABLE -->
    <div class="rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl overflow-hidden">
      <div class="overflow-x-auto w-full">
        <table class="w-full text-slate-350 text-xs font-semibold border-collapse text-left">
          <thead>
            <tr class="border-b border-slate-800 text-[10px] uppercase tracking-wider text-slate-400">
              <th class="pb-3.5 pl-2">Saluran Pembayaran</th>
              <th class="pb-3.5">Grup Saluran</th>
              <th class="pb-3.5">Fee Layanan</th>
              <th class="pb-3.5">Rekening VA Tujuan</th>
              <th class="pb-3.5">Petunjuk Bayar</th>
              <th class="pb-3.5 text-right pr-4">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800">
            @forelse($paymentMethods as $pm)
              <tr class="hover:bg-slate-900/40 transition-colors">
                <td class="py-4 pl-2">
                  <div class="flex items-center gap-3">
                    @if($pm->image)
                      <img src="{{ $pm->image }}" alt="{{ $pm->name }}" class="h-8 w-12 rounded-lg object-contain bg-slate-900/60 p-1 border border-slate-800 shadow-md">
                    @else
                      <div class="h-8 w-12 rounded-lg bg-slate-800/80 border border-slate-800 flex items-center justify-center text-[10px] text-slate-550 font-black">NO IMG</div>
                    @endif
                    <div>
                      <p class="font-black text-white leading-tight">{{ $pm->name }}</p>
                      <p class="text-[9px] text-slate-500 font-mono mt-0.5 leading-none">{{ $pm->slug }}</p>
                    </div>
                  </div>
                </td>
                <td class="py-4 uppercase text-[9px] font-bold">
                  @if($pm->group === 'qris')
                    <span class="bg-fuchsia-500/10 border border-fuchsia-500/20 text-fuchsia-400 px-2.5 py-0.5 rounded-lg shadow-xs">QRIS</span>
                  @elseif($pm->group === 'e-wallet')
                    <span class="bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 px-2.5 py-0.5 rounded-lg shadow-xs">E-Wallet</span>
                  @else
                    <span class="bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 px-2.5 py-0.5 rounded-lg shadow-xs">VA BANK</span>
                  @endif
                </td>
                <td class="py-4 font-mono font-black text-cyan-400">
                  @if($pm->fee > 0)
                    Rp {{ number_format($pm->fee, 0, ',', '.') }}
                  @else
                    GRATIS
                  @endif
                </td>
                <td class="py-4 font-mono font-bold text-slate-300">{{ $pm->account_number ?? '-' }}</td>
                <td class="py-4 text-[10px] text-slate-400 leading-relaxed font-bold">
                  ✓ {{ count($pm->instructions) }} Langkah Instruksi
                </td>
                <td class="py-4 text-right pr-4">
                  <div class="inline-flex gap-2">
                    <button onclick="toggleEditForm({{ $pm->id }})" class="border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-100 font-extrabold rounded-xl px-3.5 py-2.5 text-[10px] cursor-pointer transition-all active:scale-95 shadow-sm">
                      Edit
                    </button>
                    <form action="{{ route('admin.payment-methods.delete', $pm->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus metode pembayaran {{ $pm->name }}?')" class="m-0 p-0">
                      @csrf
                      <button type="submit" class="border border-slate-700 bg-rose-950/20 hover:bg-rose-900/30 text-rose-400 font-extrabold rounded-xl px-3.5 py-2.5 text-[10px] cursor-pointer transition-all active:scale-95 shadow-sm">
                        Hapus
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              
              <!-- COLLAPSIBLE EDIT FORM ROW -->
              <tr id="edit-row-{{ $pm->id }}" class="hidden bg-slate-900/10">
                <td colspan="6" class="p-6 border-t border-b border-slate-800">
                  <div class="max-w-3xl text-left font-bold text-slate-300">
                    <h4 class="text-xs font-black text-white uppercase tracking-wider mb-4">Edit Data Metode: {{ $pm->name }}</h4>
                    
                    <form action="{{ route('admin.payment-methods.update', $pm->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4.5">
                      @csrf
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Nama Metode Pembayaran</label>
                        <input type="text" name="name" value="{{ $pm->name }}" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Grup Metode</label>
                        <select name="group" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-bold focus:outline-none cursor-pointer">
                          <option value="qris" {{ $pm->group === 'qris' ? 'selected' : '' }}>QRIS (All E-Wallet)</option>
                          <option value="e-wallet" {{ $pm->group === 'e-wallet' ? 'selected' : '' }}>E-Wallet Dompet Digital</option>
                          <option value="bank" {{ $pm->group === 'bank' ? 'selected' : '' }}>Virtual Account / Transfer Bank</option>
                        </select>
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Fee Layanan Admin (IDR)</label>
                        <input type="number" name="fee" value="{{ $pm->fee }}" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Nomor VA Rekening Tujuan</label>
                        <input type="text" name="account_number" value="{{ $pm->account_number }}" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 text-xs font-semibold focus:outline-none">
                      </div>
                      <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Logo/Icon Pembayaran</label>
                        <div class="flex items-center gap-3">
                          @if($pm->image)
                            <img src="{{ $pm->image }}" alt="Icon" class="h-8 w-12 rounded-lg object-contain bg-slate-900/60 p-1 border border-slate-800 shadow-md">
                          @endif
                          <input type="file" name="image_file" accept="image/*" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3 px-4 text-xs font-semibold focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-blue-600/10 file:text-cyan-400 hover:file:bg-blue-600/20 file:cursor-pointer cursor-pointer">
                        </div>
                      </div>
                      <div class="flex flex-col gap-1.5 col-span-full">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Petunjuk Cara Bayar (Tulis satu instruksi per baris)</label>
                        <textarea name="instructions" required class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 px-4 h-28 text-xs font-semibold focus:outline-none resize-none">{{ implode("\n", $pm->instructions) }}</textarea>
                      </div>
                      <div class="col-span-full flex gap-3.5 justify-end mt-2">
                        <button type="button" onclick="toggleEditForm({{ $pm->id }})" class="border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-300 font-extrabold rounded-2xl px-5 py-3 text-xs cursor-pointer transition-all active:scale-95">Batal</button>
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-cyan-500 border-none text-white font-black text-xs py-3 px-5 rounded-2xl cursor-pointer hover:shadow-lg hover:shadow-blue-500/20 active:scale-95 transition-all">Simpan Perubahan</button>
                      </div>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="py-8 text-center text-slate-500 font-bold">Belum ada metode pembayaran terdaftar.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    function toggleAddForm() {
      const container = document.getElementById('add-payment-form-container');
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
