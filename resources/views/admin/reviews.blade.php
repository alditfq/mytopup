@extends('layouts.admin')

@section('title', 'Admin Panel - Kelola Review Pengguna')

@section('content')
  <div class="text-left animate-fade-in">

    <!-- Header -->
    <div class="border-b border-slate-800 pb-5 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 text-[10px] font-extrabold uppercase text-amber-400">
          <i data-lucide="star" class="h-3.5 w-3.5"></i> REVIEW PENGGUNA
        </span>
        <h1 class="text-2xl font-black mt-2 tracking-tight text-white">Kelola Review & Rating</h1>
        <p class="text-xs text-slate-400 mt-1 font-semibold">Review yang dikirim pengguna setelah selesai top up. Promosikan ulasan terbaik menjadi testimonial homepage.</p>
      </div>

      <!-- Filter -->
      <div class="flex gap-2">
        <a href="{{ route('admin.reviews') }}" class="text-[10px] font-black px-3 py-1.5 rounded-xl {{ !request('filter') ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : 'bg-slate-800 text-slate-400 border border-slate-700' }} transition">Semua</a>
        <a href="{{ route('admin.reviews', ['filter' => 'pending']) }}" class="text-[10px] font-black px-3 py-1.5 rounded-xl {{ request('filter') === 'pending' ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : 'bg-slate-800 text-slate-400 border border-slate-700' }} transition">Belum Dipromosikan</a>
        <a href="{{ route('admin.reviews', ['filter' => 'promoted']) }}" class="text-[10px] font-black px-3 py-1.5 rounded-xl {{ request('filter') === 'promoted' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-slate-800 text-slate-400 border border-slate-700' }} transition">Sudah Jadi Testimonial</a>
      </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
      <div class="mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-4 text-xs font-bold text-emerald-400 flex items-center gap-2">
        <i data-lucide="check-circle" class="h-4 w-4 flex-shrink-0"></i> {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div class="mb-6 rounded-2xl bg-rose-500/10 border border-rose-500/30 p-4 text-xs font-bold text-rose-400 flex items-center gap-2">
        <i data-lucide="alert-circle" class="h-4 w-4 flex-shrink-0"></i> {{ session('error') }}
      </div>
    @endif

    <!-- Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
      @php
        $total     = $reviews->count();
        $promoted  = $reviews->where('is_promoted', true)->count();
        $pending   = $total - $promoted;
        $avgRating = $total > 0 ? round($reviews->avg('rating'), 1) : 0;
      @endphp
      <div class="rounded-2xl border border-slate-800 p-4 bg-[#111827]/75 flex items-center justify-between">
        <div><p class="text-[9px] font-black uppercase text-slate-500">Total Review</p><h3 class="text-xl font-black text-white font-mono mt-1">{{ $total }}</h3></div>
        <div class="h-9 w-9 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-400"><i data-lucide="star" class="h-4 w-4"></i></div>
      </div>
      <div class="rounded-2xl border border-slate-800 p-4 bg-[#111827]/75 flex items-center justify-between">
        <div><p class="text-[9px] font-black uppercase text-slate-500">Dipromosikan</p><h3 class="text-xl font-black text-emerald-400 font-mono mt-1">{{ $promoted }}</h3></div>
        <div class="h-9 w-9 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400"><i data-lucide="check-circle" class="h-4 w-4"></i></div>
      </div>
      <div class="rounded-2xl border border-slate-800 p-4 bg-[#111827]/75 flex items-center justify-between">
        <div><p class="text-[9px] font-black uppercase text-slate-500">Rata-rata Rating</p><h3 class="text-xl font-black text-amber-400 font-mono mt-1">{{ $avgRating }} <span class="text-sm">⭐</span></h3></div>
        <div class="h-9 w-9 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400"><i data-lucide="bar-chart-2" class="h-4 w-4"></i></div>
      </div>
    </div>

    <!-- Reviews Table -->
    <div class="rounded-3xl border border-slate-800 bg-[#111827]/75 backdrop-blur-xl shadow-xl overflow-hidden">
      <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800">
        <div class="flex items-center gap-2.5">
          <i data-lucide="message-square" class="h-4 w-4 text-amber-400"></i>
          <h3 class="text-xs font-black uppercase tracking-wider text-white">Daftar Review</h3>
        </div>
        <span class="text-[10px] font-bold text-slate-500 bg-slate-800/60 px-2.5 py-1 rounded-full">{{ $total }} review</span>
      </div>

      @if($reviews->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center">
          <div class="w-16 h-16 rounded-2xl bg-slate-800/60 flex items-center justify-center mb-4">
            <i data-lucide="star" class="h-7 w-7 text-slate-600"></i>
          </div>
          <p class="text-sm font-black text-slate-500">Belum ada review dari pengguna</p>
          <p class="text-xs text-slate-600 mt-1 font-semibold">Review akan muncul di sini setelah pengguna menyelesaikan top up.</p>
        </div>
      @else
        <div class="divide-y divide-slate-800/60">
          @foreach($reviews as $review)
            <div class="p-5 hover:bg-slate-800/20 transition group">
              <div class="flex flex-col sm:flex-row gap-4">

                <!-- Left: Reviewer info + stars -->
                <div class="flex items-start gap-3 flex-1 min-w-0">
                  <!-- Avatar placeholder -->
                  <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-500/30 to-fuchsia-500/30 border border-slate-700 flex items-center justify-center font-black text-sm text-white">
                    {{ strtoupper(substr($review->reviewer_name, 0, 1)) }}
                  </div>

                  <div class="min-w-0 flex-1">
                    <!-- Name + badge -->
                    <div class="flex items-center gap-2 flex-wrap">
                      <span class="text-xs font-black text-white">{{ $review->reviewer_name }}</span>
                      @if($review->user)
                        <span class="text-[8px] font-black px-1.5 py-0.5 rounded bg-indigo-500/10 border border-indigo-500/20 text-indigo-400">MEMBER</span>
                      @else
                        <span class="text-[8px] font-black px-1.5 py-0.5 rounded bg-slate-700 border border-slate-600 text-slate-400">GUEST</span>
                      @endif
                      @if($review->is_promoted)
                        <span class="text-[8px] font-black px-1.5 py-0.5 rounded bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">✓ TESTIMONIAL</span>
                      @endif
                    </div>

                    <!-- Stars -->
                    <div class="flex items-center gap-0.5 mt-1">
                      @for($s = 1; $s <= 5; $s++)
                        <span class="text-sm {{ $s <= $review->rating ? 'text-amber-400' : 'text-slate-700' }}">★</span>
                      @endfor
                      <span class="ml-1 text-[10px] font-bold text-slate-500">{{ $review->rating }}/5</span>
                    </div>

                    <!-- Message -->
                    @if($review->message)
                      <p class="mt-1.5 text-xs text-slate-300 font-semibold leading-relaxed">"{{ $review->message }}"</p>
                    @else
                      <p class="mt-1.5 text-[10px] text-slate-600 font-semibold italic">— Tanpa komentar —</p>
                    @endif

                    <!-- Meta -->
                    <div class="flex flex-wrap items-center gap-3 mt-2 text-[9px] font-bold text-slate-500">
                      <span class="flex items-center gap-1"><i data-lucide="gamepad-2" class="h-3 w-3"></i> {{ $review->game->name }}</span>
                      <span class="flex items-center gap-1"><i data-lucide="hash" class="h-3 w-3"></i> {{ $review->transaction->invoice }}</span>
                      <span class="flex items-center gap-1"><i data-lucide="clock" class="h-3 w-3"></i> {{ $review->created_at->diffForHumans() }}</span>
                    </div>
                  </div>
                </div>

                <!-- Right: Action buttons -->
                <div class="flex items-center gap-2 flex-shrink-0 self-start sm:self-center">
                  @if(!$review->is_promoted)
                    <form action="{{ route('admin.reviews.promote', $review->id) }}" method="POST" class="m-0 p-0">
                      @csrf
                      <button type="submit" title="Jadikan Testimonial"
                        class="inline-flex items-center gap-1.5 text-[10px] font-black px-3 py-1.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 hover:bg-amber-500/20 transition cursor-pointer border-none bg-amber-500/10 border border-amber-500/30">
                        <i data-lucide="share-2" class="h-3.5 w-3.5"></i> Jadikan Testimonial
                      </button>
                    </form>
                  @else
                    <span class="inline-flex items-center gap-1.5 text-[10px] font-black px-3 py-1.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400">
                      <i data-lucide="check" class="h-3.5 w-3.5"></i> Sudah Dipromosikan
                    </span>
                  @endif

                  <form action="{{ route('admin.reviews.delete', $review->id) }}" method="POST" class="m-0 p-0"
                        onsubmit="return confirm('Hapus review dari {{ $review->reviewer_name }}?')">
                    @csrf
                    <button type="submit" title="Hapus"
                      class="flex h-8 w-8 items-center justify-center rounded-xl text-rose-400 hover:bg-rose-500/10 border border-transparent hover:border-rose-500/20 transition cursor-pointer bg-transparent border-none">
                      <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                    </button>
                  </form>
                </div>

              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>

  </div>
@endsection
