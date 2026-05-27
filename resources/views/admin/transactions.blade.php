@extends('layouts.admin')

@section('title', 'Admin Panel - Daftar Transaksi')

@section('content')
  <div class="text-left">
    <!-- Top Header -->
    <div class="border-b border-slate-800 pb-5 mb-8">
      <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-[10px] font-extrabold uppercase text-cyan-400 shadow-sm shadow-blue-500/5">
        <i data-lucide="clipboard-list" class="h-3.5 w-3.5"></i> TRANSACTIONS AUDIT
      </span>
      <h1 class="text-2xl font-black mt-2 tracking-tight text-white">Daftar Transaksi Masuk</h1>
      <p class="text-xs text-slate-400 mt-1 font-semibold">Pantau seluruh order top up pelanggan, verifikasi pembayaran pending, dan kelola refund.</p>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
      <div class="mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-4 text-xs font-bold text-emerald-400 shadow-lg shadow-emerald-950/10">
        ✓ {{ session('success') }}
      </div>
    @endif

    <!-- FILTER & SEARCH PANEL -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <form action="{{ route('admin.transactions') }}" method="GET" class="w-full grid grid-cols-1 sm:grid-cols-4 gap-3 font-semibold">
        <!-- Search field -->
        <div class="relative w-full sm:col-span-2">
          <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari invoice / player ID / nickname..."
            class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-2.5 pl-4 pr-10 text-xs font-semibold focus:outline-none placeholder:text-slate-500"
          />
          <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-transparent border-none text-slate-500 hover:text-slate-350 cursor-pointer flex items-center justify-center">
            <i data-lucide="search" class="h-4 w-4"></i>
          </button>
        </div>

        <!-- Game selector -->
        <div>
          <select name="game_id" onchange="this.form.submit()" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-2.5 px-4 text-xs font-bold focus:outline-none cursor-pointer">
            <option value="all" {{ request('game_id') === 'all' || !request('game_id') ? 'selected' : '' }}>Semua Game</option>
            @foreach($games as $g)
              <option value="{{ $g->id }}" {{ request('game_id') == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
            @endforeach
          </select>
        </div>

        <!-- Status selector -->
        <div class="flex gap-2">
          <select name="status" onchange="this.form.submit()" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-2.5 px-4 text-xs font-bold focus:outline-none cursor-pointer">
            <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>Semua Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>PENDING</option>
            <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>SUCCESS (PAID)</option>
            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>FAILED</option>
          </select>

          @if(request()->filled('search') || request()->filled('game_id') || request()->filled('status'))
            <a href="{{ route('admin.transactions') }}" class="flex items-center justify-center rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 hover:bg-rose-500/25 px-3 cursor-pointer decoration-none transition-all active:scale-95 shadow-sm" title="Reset Filter">
              <i data-lucide="x" class="h-4 w-4"></i>
            </a>
          @endif
        </div>
      </form>
    </div>

    <!-- TRANSACTIONS LIST TABLE -->
    <div class="rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl overflow-hidden mb-8">
      <div class="overflow-x-auto w-full">
        <table class="w-full text-slate-350 text-xs font-semibold border-collapse text-left">
          <thead>
            <tr class="border-b border-slate-800 text-[10px] uppercase tracking-wider text-slate-400">
              <th class="pb-3.5 pl-2">No. Invoice</th>
              <th class="pb-3.5">Detail Pesanan</th>
              <th class="pb-3.5">Target Player ID</th>
              <th class="pb-3.5">Total Bayar</th>
              <th class="pb-3.5">Status</th>
              <th class="pb-3.5">Tanggal</th>
              <th class="pb-3.5 text-right pr-4">Tindakan</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800">
            @forelse($transactions as $tx)
              <tr class="hover:bg-slate-900/40 transition-colors">
                <td class="py-4 pl-2 font-mono font-black text-white">
                  <a href="{{ route('admin.transactions.detail', $tx->id) }}" class="hover:text-cyan-400 transition-colors decoration-none">
                    {{ $tx->invoice }}
                  </a>
                </td>
                <td class="py-4">
                  <div class="flex items-center gap-3">
                    <img src="{{ $tx->game->thumbnail_url }}" alt="{{ $tx->game->name }}" class="h-9 w-9 rounded-lg object-cover border border-slate-800 shadow-md">
                    <div>
                      <p class="font-black text-white leading-tight">{{ $tx->game->name }}</p>
                      <p class="text-[9px] text-slate-400 font-mono mt-0.5 leading-none">{{ $tx->nominal_name }}</p>
                    </div>
                  </div>
                </td>
                <td class="py-4 font-mono font-bold text-slate-300">
                  {{ $tx->target_id }}{{ $tx->zone_id ? ' (' . $tx->zone_id . ')' : '' }}
                </td>
                <td class="py-4 font-mono font-black text-cyan-400">
                  Rp {{ number_format($tx->total_payment, 0, ',', '.') }}
                  <span class="block text-[8px] text-slate-500 font-sans mt-0.5 font-bold">{{ $tx->paymentMethod->name }}</span>
                </td>
                <td class="py-4">
                  @if($tx->status === 'success')
                    <span class="rounded bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 text-[8px] font-black text-emerald-400 uppercase tracking-wider shadow-sm">PAID</span>
                  @elseif($tx->status === 'failed')
                    <span class="rounded bg-rose-500/10 border border-rose-500/20 px-2.5 py-0.5 text-[8px] font-black text-rose-400 uppercase tracking-wider shadow-sm">FAILED</span>
                  @else
                    <span class="rounded bg-amber-500/10 border border-amber-500/20 px-2.5 py-0.5 text-[8px] font-black text-amber-400 uppercase tracking-wider animate-pulse shadow-sm">PENDING</span>
                  @endif
                </td>
                <td class="py-4 text-[10px] text-slate-400 font-bold leading-normal">
                  {{ $tx->created_at->format('d M Y, H:i') }} WIB
                </td>
                <td class="py-4 text-right pr-4">
                  <div class="flex items-center justify-end gap-2.5">
                    <form action="{{ route('admin.transactions.update-status', $tx->id) }}" method="POST" class="m-0 p-0 flex gap-2 justify-end">
                      @csrf
                      <select name="status" class="rounded-xl border border-slate-700 bg-slate-800 text-slate-200 px-2 py-1.5 text-[10px] font-bold focus:outline-none cursor-pointer">
                        <option value="pending" {{ $tx->status === 'pending' ? 'selected' : '' }}>PENDING</option>
                        <option value="success" {{ $tx->status === 'success' ? 'selected' : '' }}>SUCCESS</option>
                        <option value="failed" {{ $tx->status === 'failed' ? 'selected' : '' }}>FAILED</option>
                      </select>
                      <button type="submit" class="border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-100 font-extrabold rounded-xl px-2.5 py-1.5 text-[10px] cursor-pointer transition-all active:scale-95 shadow-sm">
                        Set
                      </button>
                    </form>
                    <a href="{{ route('admin.transactions.detail', $tx->id) }}" class="flex h-7 w-7 items-center justify-center rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 hover:text-white cursor-pointer decoration-none transition-all shadow-sm active:scale-95">
                      <i data-lucide="eye" class="h-3.5 w-3.5"></i>
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="py-12 text-center text-slate-500 font-bold">Belum ada transaksi di dalam database.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- PAGINATION LINKS -->
    @if($transactions->hasPages())
      <div class="mt-4 p-4 rounded-3xl bg-[#111827]/75 backdrop-blur-xl border border-slate-800 flex justify-center text-slate-350 shadow-xl">
        {{ $transactions->appends(request()->query())->links() }}
      </div>
    @endif

  </div>
@endsection
