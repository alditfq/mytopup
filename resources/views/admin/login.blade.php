<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Security Portal - GameTopup</title>
  
  <!-- Stylesheets -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
</head>
<body class="min-h-screen flex items-center justify-center bg-[#090e1a] text-slate-100 font-sans p-4 relative overflow-hidden">

  <!-- BACKGROUND GLOW EFFECTS -->
  <div class="absolute inset-0 pointer-events-none overflow-hidden -z-10">
    <div class="absolute top-[-10%] left-[-10%] w-[60%] h-[60%] bg-blue-500/10 rounded-full blur-[180px]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[60%] h-[60%] bg-cyan-500/10 rounded-full blur-[180px]"></div>
  </div>

  <div class="w-full max-w-md animate-fade-in text-left relative z-10">
    
    <!-- Branding logo -->
    <div class="text-center mb-8">
      <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl text-white bg-gradient-to-r from-blue-600 to-cyan-500 shadow-lg shadow-blue-500/20 mb-4">
        <i data-lucide="shield-check" class="h-7 w-7"></i>
      </div>
      <h1 class="text-xl font-black tracking-tight text-white">
        Portal Keamanan <span class="bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">Admin</span>
      </h1>
      <p class="text-xs text-slate-400 mt-1.5 font-semibold">Silakan masuk untuk mengelola katalog game dan transaksi pelanggan.</p>
    </div>

    <!-- Error/Validation alert notices -->
    @if($errors->any())
      <div class="mb-6 rounded-2xl bg-rose-500/10 border border-rose-500/20 p-4 text-xs font-bold text-rose-400 shadow-md">
        <ul class="list-disc list-inside m-0 p-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @if(session('error'))
      <div class="mb-6 rounded-2xl bg-rose-500/10 border border-rose-500/20 p-4 text-xs font-bold text-rose-400 shadow-md">
        {{ session('error') }}
      </div>
    @endif

    @if(session('success'))
      <div class="mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 p-4 text-xs font-bold text-emerald-400 shadow-md">
        ✓ {{ session('success') }}
      </div>
    @endif

    <!-- LOGIN FORM CARD -->
    <div class="rounded-3xl border border-slate-800 p-6 md:p-8 bg-[#111827]/75 backdrop-blur-xl shadow-2xl">
      <form action="{{ route('admin.login') }}" method="POST" class="space-y-5 m-0 p-0">
        @csrf

        <!-- Email input -->
        <div class="space-y-1.5">
          <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider">Alamat Email Admin</label>
          <div class="relative">
            <i data-lucide="mail" class="absolute left-3.5 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-slate-500"></i>
            <input
              type="email"
              name="email"
              value="{{ old('email') }}"
              required
              placeholder="admin@gametopup.com"
              class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 pl-11 pr-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500"
            />
          </div>
        </div>

        <!-- Password input -->
        <div class="space-y-1.5">
          <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider">Kata Sandi Pengaman</label>
          <div class="relative">
            <i data-lucide="lock" class="absolute left-3.5 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-slate-500"></i>
            <input
              type="password"
              name="password"
              required
              placeholder="••••••••"
              class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-3.5 pl-11 pr-4 text-xs font-semibold focus:outline-none placeholder:text-slate-500"
            />
          </div>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          class="w-full bg-gradient-to-r from-blue-600 to-cyan-500 border-none text-white font-black tracking-wide uppercase py-4 rounded-2xl text-xs cursor-pointer hover:shadow-lg hover:shadow-blue-500/20 active:scale-95 transition-all flex items-center justify-center gap-2 mt-2"
        >
          <i data-lucide="log-in" class="h-4 w-4"></i> Masuk ke Dashboard 🛡_
        </button>
      </form>
    </div>

    <!-- Back to Store trigger -->
    <div class="text-center mt-6">
      <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-slate-300 hover:text-white border border-slate-800 hover:border-slate-700 transition-all rounded-xl bg-slate-800/40 hover:bg-slate-800/80 cursor-pointer decoration-none shadow-md">
        <i data-lucide="arrow-left" class="h-4 w-4 text-slate-400"></i>
        Kembali ke Toko
      </a>
    </div>

  </div>

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      if (window.lucide) {
        window.lucide.createIcons();
      }
    });
  </script>
</body>
</html>
