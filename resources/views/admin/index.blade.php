@extends('layouts.admin')

@section('title', 'Admin Portal - Ringkasan Stats')

@section('content')
  <div class="text-left">
    
    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-slate-800 pb-5 mb-8">
      <div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-[10px] font-extrabold uppercase text-cyan-400 shadow-sm shadow-blue-500/5">
          <i data-lucide="shield-check" class="h-3 w-3"></i> SECURE PORTAL CS & ADMIN
        </span>
        <h1 class="text-2xl font-black mt-2 tracking-tight text-white">Overview Ringkasan Sistem</h1>
        <p class="text-xs text-slate-400 mt-1 font-medium">Status pendapatan, rekap transaksi masuk, dan rekap pendaftaran pengguna otomatis.</p>
      </div>
      <div class="mt-4 md:mt-0 flex gap-3">
        <a href="{{ route('admin.chat') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-500 text-xs font-bold text-white shadow-lg shadow-blue-500/10 hover:shadow-blue-500/20 transition-all border-none cursor-pointer">
          <i data-lucide="message-square" class="h-4 w-4"></i> Konsol Live Chat
        </a>
      </div>
    </div>

    <!-- STATS CARD GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
      
      <!-- Total Revenue -->
      <div class="rounded-3xl border border-slate-800 p-6 bg-[#111827]/75 backdrop-blur-xl relative overflow-hidden shadow-lg shadow-cyan-950/10 hover:border-cyan-500/30 transition-all group">
        <div class="absolute -right-3 -bottom-3 opacity-10 pointer-events-none group-hover:scale-110 transition-all">
          <i data-lucide="wallet" class="h-24 w-24 text-cyan-400"></i>
        </div>
        <p class="text-[9px] text-slate-400 font-extrabold uppercase tracking-widest leading-none">TOTAL REVENUE (PAID)</p>
        <p class="mt-4 text-3xl font-black text-white font-mono leading-none">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        <div class="flex items-center gap-1.5 mt-3 text-[10px] text-cyan-400 font-bold">
          <i data-lucide="trending-up" class="h-3.5 w-3.5"></i>
          <span>Pendapatan bersih terverifikasi otomatis</span>
        </div>
      </div>

      <!-- Total Transactions -->
      <div class="rounded-3xl border border-slate-800 p-6 bg-[#111827]/75 backdrop-blur-xl relative overflow-hidden shadow-lg shadow-indigo-950/10 hover:border-indigo-500/30 transition-all group">
        <div class="absolute -right-3 -bottom-3 opacity-10 pointer-events-none group-hover:scale-110 transition-all">
          <i data-lucide="clipboard-list" class="h-24 w-24 text-indigo-400"></i>
        </div>
        <p class="text-[9px] text-slate-400 font-extrabold uppercase tracking-widest leading-none">TOTAL TRANSAKSI</p>
        <p class="mt-4 text-3xl font-black text-white font-mono leading-none">{{ $totalTransactions }}</p>
        <div class="flex items-center gap-1.5 mt-3 text-[10px] text-indigo-400 font-bold">
          <i data-lucide="activity" class="h-3.5 w-3.5"></i>
          <span>Pending, sukses, & gagal terakumulasi</span>
        </div>
      </div>

      <!-- Active Users -->
      <div class="rounded-3xl border border-slate-800 p-6 bg-[#111827]/75 backdrop-blur-xl relative overflow-hidden shadow-lg shadow-pink-950/10 hover:border-pink-500/30 transition-all group">
        <div class="absolute -right-3 -bottom-3 opacity-10 pointer-events-none group-hover:scale-110 transition-all">
          <i data-lucide="users" class="h-24 w-24 text-pink-400"></i>
        </div>
        <p class="text-[9px] text-slate-400 font-extrabold uppercase tracking-widest leading-none">PENGGUNA TERDAFTAR</p>
        <p class="mt-4 text-3xl font-black text-white font-mono leading-none">{{ $totalUsers }}</p>
        <div class="flex items-center gap-1.5 mt-3 text-[10px] text-pink-400 font-bold">
          <i data-lucide="user-check" class="h-3.5 w-3.5"></i>
          <span>Total akun member di database</span>
        </div>
      </div>

    </div>

    <!-- DETAIL STATS CARD ROW -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
      
      <!-- Successful Transactions -->
      <div class="rounded-2xl border border-slate-850 p-5 bg-[#111827]/50 flex items-center justify-between">
        <div>
          <p class="text-[9px] font-black tracking-wider uppercase text-slate-400">Sukses</p>
          <h3 class="text-xl font-bold font-mono text-emerald-400 mt-1">{{ $successTransactions }} <span class="text-xs text-slate-400 font-bold font-sans">TX</span></h3>
        </div>
        <div class="h-10 w-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400">
          <i data-lucide="check-circle" class="h-5 w-5"></i>
        </div>
      </div>

      <!-- Pending Transactions -->
      <div class="rounded-2xl border border-slate-850 p-5 bg-[#111827]/50 flex items-center justify-between">
        <div>
          <p class="text-[9px] font-black tracking-wider uppercase text-slate-400">Pending</p>
          <h3 class="text-xl font-bold font-mono text-amber-400 mt-1">{{ $pendingTransactions }} <span class="text-xs text-slate-400 font-bold font-sans">TX</span></h3>
        </div>
        <div class="h-10 w-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-400 animate-pulse">
          <i data-lucide="clock" class="h-5 w-5"></i>
        </div>
      </div>

      <!-- Failed Transactions -->
      <div class="rounded-2xl border border-slate-850 p-5 bg-[#111827]/50 flex items-center justify-between">
        <div>
          <p class="text-[9px] font-black tracking-wider uppercase text-slate-400">Gagal</p>
          <h3 class="text-xl font-bold font-mono text-rose-400 mt-1">{{ $failedTransactions }} <span class="text-xs text-slate-400 font-bold font-sans">TX</span></h3>
        </div>
        <div class="h-10 w-10 rounded-xl bg-rose-500/10 flex items-center justify-center text-rose-400">
          <i data-lucide="x-circle" class="h-5 w-5"></i>
        </div>
      </div>

    </div>

    <!-- CHARTS SECTION -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      
      <!-- Revenue Chart Card -->
      <div class="rounded-3xl border border-slate-800 p-6 bg-[#111827]/75 backdrop-blur-xl">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-xs font-black text-white uppercase tracking-wider flex items-center gap-2">
            <i data-lucide="trending-up" class="h-4 w-4 text-cyan-400"></i> Tren Pendapatan Harian
          </h3>
          <span class="text-[9px] px-2 py-0.5 rounded bg-slate-800 text-slate-400 font-bold">7 Hari Terakhir</span>
        </div>
        <div class="h-[250px] relative">
          <canvas id="revenueOverviewChart"></canvas>
        </div>
      </div>

      <!-- Transaction Volume Chart Card -->
      <div class="rounded-3xl border border-slate-800 p-6 bg-[#111827]/75 backdrop-blur-xl">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-xs font-black text-white uppercase tracking-wider flex items-center gap-2">
            <i data-lucide="bar-chart" class="h-4 w-4 text-indigo-400"></i> Volume Transaksi Harian
          </h3>
          <span class="text-[9px] px-2 py-0.5 rounded bg-slate-800 text-slate-400 font-bold">7 Hari Terakhir</span>
        </div>
        <div class="h-[250px] relative">
          <canvas id="transactionsOverviewChart"></canvas>
        </div>
      </div>

    </div>

    <!-- LOWER SECTION: POPULAR GAMES & RECENT ACTIVITY -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
      
      <!-- POPULAR GAMES LIST -->
      <div class="lg:col-span-5 rounded-3xl border border-slate-800 p-6 bg-[#111827]/75 backdrop-blur-xl flex flex-col justify-between">
        <div>
          <div class="flex items-center gap-2.5 border-b border-slate-800 pb-4 mb-4">
            <i data-lucide="gamepad-2" class="h-5 w-5 text-cyan-400"></i>
            <h3 class="text-xs font-black text-white uppercase tracking-wider">Produk / Game Terpopuler</h3>
          </div>
          
          <div class="space-y-3.5">
            @forelse($popularGames as $idx => $pGame)
              <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-900/40 border border-slate-850">
                <div class="flex items-center gap-3">
                  <span class="text-xs font-black font-mono text-slate-500 w-4">#{{ $idx + 1 }}</span>
                  <img src="{{ $pGame->game->thumbnail_url }}" alt="{{ $pGame->game->name }}" class="h-9 w-9 rounded-lg object-cover">
                  <div class="text-left">
                    <p class="text-xs font-bold text-white">{{ $pGame->game->name }}</p>
                    <p class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $pGame->game->developer }}</p>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-xs font-black text-cyan-400 font-mono">{{ $pGame->sales_count }} sold</p>
                  <p class="text-[9px] text-slate-500 font-bold mt-0.5">Rp {{ number_format($pGame->revenue, 0, ',', '.') }}</p>
                </div>
              </div>
            @empty
              <p class="text-xs text-slate-500 py-10 font-bold text-center">Belum ada penjualan sukses.</p>
            @endforelse
          </div>
        </div>
        <div class="mt-4 pt-3.5 border-t border-slate-800">
          <a href="{{ route('admin.games') }}" class="text-[10px] font-black text-cyan-400 hover:text-cyan-300 hover:underline decoration-none flex items-center justify-center gap-1">Kelola Seluruh Game <i data-lucide="arrow-right" class="h-3 w-3"></i></a>
        </div>
      </div>

      <!-- RECENT TRANSACTIONS -->
      <div class="lg:col-span-7 rounded-3xl border border-slate-800 p-6 bg-[#111827]/75 backdrop-blur-xl">
        <div class="flex items-center justify-between border-b border-slate-800 pb-4 mb-4">
          <div class="flex items-center gap-2.5">
            <i data-lucide="clipboard-list" class="h-5 w-5 text-indigo-400"></i>
            <h3 class="text-xs font-black text-white uppercase tracking-wider">Histori 10 Transaksi Terakhir</h3>
          </div>
          <a href="{{ route('admin.transactions') }}" class="text-[10px] font-black text-indigo-400 hover:text-indigo-300 hover:underline decoration-none">Lihat Semua →</a>
        </div>

        <div class="space-y-3 max-h-[390px] overflow-y-auto pr-1 scrollbar-none">
          @forelse($transactions as $tx)
            <div class="border border-slate-850 rounded-2xl p-4 bg-slate-900/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <div class="text-left">
                <div class="flex items-center gap-2.5">
                  <span class="text-xs font-black text-white font-mono">{{ $tx->invoice }}</span>
                  @if($tx->status === 'success')
                    <span class="rounded bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 text-[8px] font-black text-emerald-400 uppercase tracking-wider">SUCCESS</span>
                  @elseif($tx->status === 'failed')
                    <span class="rounded bg-rose-500/10 border border-rose-500/20 px-2 py-0.5 text-[8px] font-black text-rose-400 uppercase tracking-wider">FAILED</span>
                  @else
                    <span class="rounded bg-amber-500/10 border border-amber-500/20 px-2 py-0.5 text-[8px] font-black text-amber-400 uppercase tracking-wider animate-pulse">PENDING</span>
                  @endif
                </div>
                <p class="text-[10px] text-slate-350 font-bold mt-1.5">
                  {{ $tx->game->name }} • {{ $tx->nominal_name }} • ID: {{ $tx->target_id }} {{ $tx->zone_id ? "({$tx->zone_id})" : "" }}
                </p>
                <p class="text-[9px] text-slate-450 font-semibold mt-1">
                  Total: <span class="text-white font-bold">Rp {{ number_format($tx->total_payment, 0, ',', '.') }}</span> via {{ $tx->paymentMethod->name }} • {{ $tx->created_at->format('d M, H:i') }} WIB
                </p>
              </div>

              <!-- Quick Status action buttons -->
              <div class="flex items-center gap-2 self-start sm:self-center">
                <form action="{{ route('admin.transactions.update-status', $tx->id) }}" method="POST" class="m-0 p-0 flex gap-2">
                  @csrf
                  <select name="status" class="rounded-xl border border-slate-700 bg-slate-800 text-slate-200 px-2.5 py-1.5 text-[10px] font-bold focus:outline-none cursor-pointer">
                    <option value="pending" {{ $tx->status === 'pending' ? 'selected' : '' }}>PENDING</option>
                    <option value="success" {{ $tx->status === 'success' ? 'selected' : '' }}>SUCCESS</option>
                    <option value="failed" {{ $tx->status === 'failed' ? 'selected' : '' }}>FAILED</option>
                  </select>
                  <button type="submit" class="border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-100 font-extrabold rounded-xl px-3 py-1.5 text-[10px] cursor-pointer transition-all active:scale-95">
                    Update
                  </button>
                </form>
              </div>
            </div>
          @empty
            <p class="text-xs text-slate-500 py-10 font-bold text-center">Belum ada transaksi di database.</p>
          @endforelse
        </div>
      </div>

    </div>

  </div>
@endsection

@push('scripts')
  <!-- Load Chart.js from CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Real data from database (last 7 days)
      const labels        = {!! json_encode($chartLabels) !!};
      const revenueData   = {!! json_encode($chartRevenue) !!};
      const txVolumeData  = {!! json_encode($chartTxVolume) !!};

      // 1. Glowing Gradient Revenue Chart
      const ctxRev = document.getElementById('revenueOverviewChart').getContext('2d');
      const gradRev = ctxRev.createLinearGradient(0, 0, 0, 220);
      gradRev.addColorStop(0, 'rgba(6, 182, 212, 0.35)');
      gradRev.addColorStop(1, 'rgba(6, 182, 212, 0.0)');

      new Chart(ctxRev, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: 'Pendapatan (IDR)',
            data: revenueData,
            borderColor: '#06b6d4',
            borderWidth: 2.5,
            backgroundColor: gradRev,
            fill: true,
            tension: 0.45,
            pointBackgroundColor: '#06b6d4',
            pointBorderColor: '#090e1a',
            pointBorderWidth: 2,
            pointRadius: 4.5,
            pointHoverRadius: 7
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: ctx => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID')
              }
            }
          },
          scales: {
            x: {
              grid: { color: 'rgba(255,255,255,0.04)' },
              ticks: { color: '#94a3b8', font: { size: 9, weight: 'bold' } }
            },
            y: {
              beginAtZero: true,
              grid: { color: 'rgba(255,255,255,0.04)' },
              ticks: {
                color: '#94a3b8',
                font: { size: 9, weight: 'bold' },
                callback: val => val >= 1000000 ? 'Rp ' + (val/1000000).toFixed(1) + 'M' : val >= 1000 ? 'Rp ' + (val/1000) + 'k' : 'Rp ' + val
              }
            }
          }
        }
      });

      // 2. Transaction Volume Bar Chart
      const ctxTx = document.getElementById('transactionsOverviewChart').getContext('2d');
      const gradTx = ctxTx.createLinearGradient(0, 0, 0, 220);
      gradTx.addColorStop(0, 'rgba(99, 102, 241, 0.5)');
      gradTx.addColorStop(1, 'rgba(99, 102, 241, 0.05)');

      new Chart(ctxTx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Total Transaksi',
            data: txVolumeData,
            backgroundColor: gradTx,
            borderColor: '#6366f1',
            borderWidth: 2,
            borderRadius: 8,
            barThickness: 18
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: ctx => ' ' + ctx.parsed.y + ' transaksi'
              }
            }
          },
          scales: {
            x: {
              grid: { color: 'rgba(255,255,255,0.04)' },
              ticks: { color: '#94a3b8', font: { size: 9, weight: 'bold' } }
            },
            y: {
              beginAtZero: true,
              grid: { color: 'rgba(255,255,255,0.04)' },
              ticks: { color: '#94a3b8', font: { size: 9, weight: 'bold' }, stepSize: 1 }
            }
          }
        }
      });
    });
  </script>
@endpush
