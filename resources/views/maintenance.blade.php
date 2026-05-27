<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Under Maintenance - GameTopup</title>
  
  <!-- Stylesheets -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
</head>
<body class="min-h-screen flex items-center justify-center neup-bg text-slate-800 font-sans p-4">

  <div class="w-full max-w-lg text-center animate-fade-in">
    
    <!-- Big Neomorphic Gear Icon -->
    <div class="inline-flex h-24 w-24 items-center justify-center rounded-3xl text-indigo-600 neup-flat mb-8 bg-white border border-white/60 shadow-md">
      <i data-lucide="wrench" class="h-10 w-10 animate-pulse"></i>
    </div>

    <!-- Alert details -->
    <h1 class="text-3xl font-black tracking-tight text-slate-850">
      Toko Sedang Dalam <span class="bg-gradient-to-r from-orange-500 to-rose-500 bg-clip-text text-transparent">Pemeliharaan</span>
    </h1>
    
    <div class="mt-6 rounded-3xl border border-white/50 p-6 md:p-8 neup-flat bg-white shadow-sm font-bold text-slate-650 max-w-md mx-auto text-xs leading-relaxed">
      <p class="mb-4">
        Kami sedang melakukan pembaruan rutin pada peladen (server) dan sistem pengisian otomatis kami demi meningkatkan kecepatan transaksi top-up Anda.
      </p>
      <p class="text-indigo-650 font-black">
        ✓ Estimasi selesai: Kurang dari 30 menit.
      </p>
    </div>

    <p class="text-[10px] text-slate-450 mt-8 font-black uppercase tracking-widest leading-none">
      GameTopup System Administrator • Terpercaya & Aman
    </p>

  </div>

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      if (window.lucide) {
        window.lucide.createIcons();
      }
    });
  </script>
</body>
</html>
