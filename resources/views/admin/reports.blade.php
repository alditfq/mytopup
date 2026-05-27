@extends('layouts.admin')

@section('title', 'Admin Panel - Laporan & Analitik')

@section('content')
  <div class="text-left animate-fade-in">
    <!-- Top Header -->
    <div class="border-b border-slate-800 pb-5 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-[10px] font-extrabold uppercase text-cyan-400 shadow-sm shadow-blue-500/5">
          <i data-lucide="bar-chart-3" class="h-3.5 w-3.5"></i> REPORTS & ANALYTICS
        </span>
        <h1 class="text-2xl font-black mt-2 tracking-tight text-white">Laporan & Analitik Penjualan</h1>
        <p class="text-xs text-slate-400 mt-1 font-semibold">Data real-time 30 hari terakhir — pendapatan, volume transaksi, dan metode bayar terpopuler.</p>
      </div>
      <div></div>
    </div>

    <!-- Summary Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
      <div class="rounded-2xl border border-slate-800 p-5 bg-[#111827]/75 backdrop-blur-xl flex items-center justify-between">
        <div>
          <p class="text-[9px] font-black tracking-wider uppercase text-slate-500">Pendapatan 30 Hari</p>
          <h3 class="text-xl font-black font-mono text-cyan-400 mt-1.5">Rp {{ number_format($totalRevenue30d, 0, ',', '.') }}</h3>
        </div>
        <div class="h-10 w-10 rounded-xl bg-cyan-500/10 flex items-center justify-center text-cyan-400">
          <i data-lucide="trending-up" class="h-5 w-5"></i>
        </div>
      </div>
      <div class="rounded-2xl border border-slate-800 p-5 bg-[#111827]/75 backdrop-blur-xl flex items-center justify-between">
        <div>
          <p class="text-[9px] font-black tracking-wider uppercase text-slate-500">Total Transaksi 30 Hari</p>
          <h3 class="text-xl font-black font-mono text-indigo-400 mt-1.5">{{ $totalTx30d }} <span class="text-xs text-slate-400 font-bold font-sans">TX</span></h3>
        </div>
        <div class="h-10 w-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400">
          <i data-lucide="activity" class="h-5 w-5"></i>
        </div>
      </div>
      <div class="rounded-2xl border border-slate-800 p-5 bg-[#111827]/75 backdrop-blur-xl flex items-center justify-between">
        <div>
          <p class="text-[9px] font-black tracking-wider uppercase text-slate-500">Rata-rata Nilai Order</p>
          <h3 class="text-xl font-black font-mono text-emerald-400 mt-1.5">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</h3>
        </div>
        <div class="h-10 w-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400">
          <i data-lucide="wallet" class="h-5 w-5"></i>
        </div>
      </div>
    </div>

    <!-- CHARTS GRID ROW 1 -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">

      <!-- Revenue Line Chart (8 cols) -->
      <div class="lg:col-span-8 rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl">
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center gap-2.5">
            <i data-lucide="trending-up" class="h-5 w-5 text-cyan-400"></i>
            <h3 class="text-xs font-black uppercase tracking-wider text-white">Tren Pendapatan Harian</h3>
          </div>
          <span class="text-[9px] px-2 py-0.5 rounded-full bg-slate-800 text-slate-400 font-bold border border-slate-700">30 Hari Terakhir</span>
        </div>
        <div class="relative w-full" style="height: 240px;">
          <canvas id="dailyRevenueChart"></canvas>
        </div>
      </div>

      <!-- Payment Donut Chart (4 cols) -->
      <div class="lg:col-span-4 rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl">
        <div class="flex items-center gap-2.5 mb-4">
          <i data-lucide="credit-card" class="h-5 w-5 text-indigo-400"></i>
          <h3 class="text-xs font-black uppercase tracking-wider text-white">Metode Bayar</h3>
        </div>
        @if(count($paymentLabels) > 0)
          <div class="relative" style="height: 180px;">
            <canvas id="paymentDonutChart"></canvas>
          </div>
          <div class="mt-4 space-y-2">
            @foreach($popularPayments as $idx => $pm)
              <div class="flex items-center justify-between text-[10px] font-bold">
                <div class="flex items-center gap-2">
                  <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" id="donut-legend-{{ $idx }}"></span>
                  <span class="text-slate-300 truncate max-w-[120px]">{{ $pm->paymentMethod->name }}</span>
                </div>
                <span class="text-slate-500 font-mono">{{ $pm->count }} order</span>
              </div>
            @endforeach
          </div>
        @else
          <div class="flex items-center justify-center h-[200px] text-center">
            <p class="text-xs text-slate-600 font-bold">Belum ada data pembayaran.</p>
          </div>
        @endif
      </div>
    </div>

    <!-- CHARTS GRID ROW 2: Transaction Volume Stacked Bar -->
    <div class="rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl mb-6">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2.5">
          <i data-lucide="bar-chart" class="h-5 w-5 text-indigo-400"></i>
          <h3 class="text-xs font-black uppercase tracking-wider text-white">Volume Transaksi Harian (per Status)</h3>
        </div>
        <div class="flex items-center gap-3">
          <span class="flex items-center gap-1.5 text-[9px] font-bold text-emerald-400"><span class="w-2 h-2 rounded-full bg-emerald-400"></span>Sukses</span>
          <span class="flex items-center gap-1.5 text-[9px] font-bold text-amber-400"><span class="w-2 h-2 rounded-full bg-amber-400"></span>Pending</span>
          <span class="flex items-center gap-1.5 text-[9px] font-bold text-rose-400"><span class="w-2 h-2 rounded-full bg-rose-400"></span>Gagal</span>
        </div>
      </div>
      <div class="relative w-full" style="height: 220px;">
        <canvas id="txVolumeChart"></canvas>
      </div>
    </div>

    <!-- BEST SELLING GAMES TABLE -->
    <div class="rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl overflow-hidden">
      <div class="flex items-center gap-2.5 border-b border-slate-800 pb-4 mb-5">
        <i data-lucide="award" class="h-5 w-5 text-cyan-400"></i>
        <h3 class="text-xs font-black uppercase tracking-wider text-white">5 Game Terlaris (Berdasarkan Pendapatan)</h3>
      </div>
      <div class="overflow-x-auto w-full">
        <table class="w-full text-slate-350 text-xs font-semibold border-collapse text-left">
          <thead>
            <tr class="border-b border-slate-800 text-[10px] uppercase tracking-wider text-slate-400">
              <th class="pb-3.5 pl-2">Game</th>
              <th class="pb-3.5">Developer</th>
              <th class="pb-3.5 pr-4 text-right">Jumlah Transaksi</th>
              <th class="pb-3.5 pr-4 text-right">Total Pendapatan</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-850">
            @forelse($bestGames as $bg)
              <tr class="hover:bg-slate-900/40 transition-colors">
                <td class="py-4 pl-2">
                  <div class="flex items-center gap-3">
                    <img src="{{ $bg->game->thumbnail_url }}" alt="{{ $bg->game->name }}" class="h-9 w-9 rounded-lg object-cover border border-slate-800 shadow-md">
                    <p class="font-black text-white leading-tight">{{ $bg->game->name }}</p>
                  </div>
                </td>
                <td class="py-4 text-slate-400 font-bold">{{ $bg->game->developer }}</td>
                <td class="py-4 pr-4 text-right font-mono font-black text-white">{{ $bg->sales_count }} Kali</td>
                <td class="py-4 pr-4 text-right font-mono font-black text-emerald-400">Rp {{ number_format($bg->revenue, 0, ',', '.') }}</td>
              </tr>
            @empty
              <tr><td colspan="4" class="py-8 text-center text-slate-500 font-bold">Belum ada transaksi terverifikasi.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const labels       = {!! json_encode($chartLabels) !!};
    const revenue      = {!! json_encode($chartRevenue) !!};
    const txSuccess    = {!! json_encode($chartSuccess) !!};
    const txPending    = {!! json_encode($chartPending) !!};
    const txFailed     = {!! json_encode($chartFailed) !!};
    const pmLabels     = {!! json_encode($paymentLabels) !!};
    const pmCounts     = {!! json_encode($paymentCounts) !!};

    // ── 1. Revenue Line Chart ──
    const ctxRev = document.getElementById('dailyRevenueChart');
    if (ctxRev) {
      const gradRev = ctxRev.getContext('2d').createLinearGradient(0, 0, 0, 220);
      gradRev.addColorStop(0, 'rgba(6,182,212,0.35)');
      gradRev.addColorStop(1, 'rgba(6,182,212,0.0)');
      new Chart(ctxRev, {
        type: 'line',
        data: {
          labels,
          datasets: [{
            label: 'Pendapatan (IDR)',
            data: revenue,
            borderColor: '#06b6d4',
            backgroundColor: gradRev,
            borderWidth: 2.5,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#06b6d4',
            pointBorderColor: '#090e1a',
            pointBorderWidth: 2,
            pointRadius: 3.5,
            pointHoverRadius: 6
          }]
        },
        options: {
          responsive: true, maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: c => ' Rp ' + c.parsed.y.toLocaleString('id-ID') } }
          },
          scales: {
            x: { grid: { color: 'rgba(255,255,255,0.03)' }, ticks: { color: '#64748b', font: { size: 8, weight: 'bold' }, maxTicksLimit: 10 } },
            y: {
              beginAtZero: true,
              grid: { color: 'rgba(255,255,255,0.04)' },
              ticks: {
                color: '#64748b', font: { size: 9, weight: 'bold' },
                callback: v => v >= 1000000 ? 'Rp'+(v/1000000).toFixed(1)+'M' : v >= 1000 ? 'Rp'+(v/1000)+'k' : 'Rp'+v
              }
            }
          }
        }
      });
    }

    // ── 2. Transaction Volume Stacked Bar ──
    const ctxTx = document.getElementById('txVolumeChart');
    if (ctxTx) {
      new Chart(ctxTx, {
        type: 'bar',
        data: {
          labels,
          datasets: [
            { label: 'Sukses',  data: txSuccess, backgroundColor: 'rgba(52,211,153,0.75)', borderRadius: 0, stack: 'tx' },
            { label: 'Pending', data: txPending, backgroundColor: 'rgba(251,191,36,0.75)',  borderRadius: 0, stack: 'tx' },
            { label: 'Gagal',   data: txFailed,  backgroundColor: 'rgba(248,113,113,0.75)', borderRadius: 4, stack: 'tx' }
          ]
        },
        options: {
          responsive: true, maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: { mode: 'index', intersect: false }
          },
          scales: {
            x: { stacked: true, grid: { display: false }, ticks: { color: '#64748b', font: { size: 8, weight: 'bold' }, maxTicksLimit: 10 } },
            y: { stacked: true, beginAtZero: true, grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#64748b', font: { size: 9, weight: 'bold' }, stepSize: 1 } }
          }
        }
      });
    }

    // ── 3. Payment Method Donut ──
    const ctxDoughnut = document.getElementById('paymentDonutChart');
    if (ctxDoughnut && pmLabels.length > 0) {
      const donutColors = ['#06b6d4','#6366f1','#f59e0b','#34d399','#f87171','#a78bfa'];
      new Chart(ctxDoughnut, {
        type: 'doughnut',
        data: {
          labels: pmLabels,
          datasets: [{
            data: pmCounts,
            backgroundColor: donutColors.slice(0, pmLabels.length),
            borderColor: '#0d1324',
            borderWidth: 3,
            hoverOffset: 6
          }]
        },
        options: {
          responsive: true, maintainAspectRatio: false,
          cutout: '68%',
          plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: c => ' ' + c.label + ': ' + c.parsed + ' order' } }
          }
        }
      });
      // Color the legend dots
      document.querySelectorAll('[id^="donut-legend-"]').forEach((el, i) => {
        el.style.backgroundColor = donutColors[i] || '#64748b';
      });
    }
  });
</script>
@endpush
