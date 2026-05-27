@extends('layouts.app')

@section('title', 'Masuk - GameTopup')

@section('content')
  <div class="flex-1 flex items-center justify-center" id="login-page">
    <div class="w-full max-w-md px-4 py-12">
      
      <!-- Brand Header -->
      <div class="text-center mb-8">
        <a href="{{ route('home') }}" class="inline-flex cursor-pointer h-14 w-14 items-center justify-center rounded-2xl border border-white/50 neup-flat text-pink-650 mb-3.5 hover:neup-pressed-xs transition-colors bg-white decoration-none">
          <i data-lucide="gamepad-2" class="h-7 w-7 text-pink-600"></i>
        </a>
        <h1 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">Selamat Datang Kembali</h1>
        <p class="text-xs text-slate-500 mt-1 font-black leading-relaxed">Masuk untuk melihat riwayat top-up game dan klaim kupon spesial Anda.</p>
      </div>

      <!-- LOGIN FORM CARD -->
      <div class="rounded-3xl border border-white/50 neup-flat p-6 md:p-8 mb-6 font-bold bg-white shadow-sm">
        
        <!-- Error Alerts Container -->
        @if($errors->any())
          <div class="mb-5 rounded-2xl bg-rose-50 border border-rose-100 p-4 text-xs font-bold text-rose-600 text-left bg-white">
            <ul class="list-disc list-inside m-0 p-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('login') }}" method="POST" id="login-form-submit" class="space-y-5 text-left m-0 p-0">
          @csrf

          <!-- Email Input -->
          <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Alamat Email</label>
            <div class="relative">
              <i data-lucide="mail" class="absolute left-3.5 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-slate-400"></i>
              <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="nama@email.com"
                required
                class="w-full rounded-2xl border border-white/30 neup-pressed-xs py-3.5 pl-11 pr-4 text-xs font-bold text-slate-800 placeholder:text-slate-400 focus:outline-none bg-transparent"
              />
            </div>
          </div>

          <!-- Password Input -->
          <div>
            <div class="flex justify-between mb-1.5">
              <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Kata Sandi</label>
            </div>
            
            <div class="relative">
              <i data-lucide="lock" class="absolute left-3.5 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-slate-400"></i>
              <input
                type="password"
                name="password"
                id="login-password"
                placeholder="••••••••"
                required
                class="w-full rounded-2xl border border-white/30 neup-pressed-xs py-3.5 pl-11 pr-11 text-xs font-bold text-slate-800 placeholder:text-slate-400 focus:outline-none bg-transparent"
              />
              <button
                type="button"
                id="show-password-trigger"
                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-650 cursor-pointer bg-transparent border-none"
              >
                <i data-lucide="eye" id="password-eye-icon" class="h-4.5 w-4.5"></i>
              </button>
            </div>
          </div>

          <!-- Remember Me Toggle -->
          <div class="flex items-center justify-between pt-1">
            <label class="flex items-center gap-2 cursor-pointer select-none">
              <input
                type="checkbox"
                name="remember"
                class="h-4.5 w-4.5 rounded border border-white/40 text-pink-600 bg-transparent focus:ring-0 cursor-pointer"
              />
              <span class="text-[11px] text-slate-500 font-extrabold">Ingat Saya di Browser Ini</span>
            </label>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            id="btn-submit-login"
            class="w-full font-black tracking-wide uppercase py-3.5 rounded-2xl transition-all cursor-pointer bg-orange-500 hover:bg-orange-600 text-white neup-orange-flat hover:neup-orange-pressed active:scale-[0.98] text-xs border-none"
          >
            Masuk ke Akun 🚀
          </button>

        </form>

        <!-- Divider -->
        <div class="my-6 flex items-center justify-between">
          <span class="w-1/5 border-b border-slate-300/40"></span>
          <span class="w-1/5 border-b border-slate-300/40"></span>
        </div>

      </div>

      <!-- Register redirect -->
      <p class="mt-6 text-center text-xs text-slate-500 font-bold">
        Belum punya akun GameTopup? 
        <a href="{{ route('register') }}" class="text-pink-600 hover:underline hover:text-pink-700 font-black">
          Daftar Sekarang
        </a>
      </p>

    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const toggleBtn = document.getElementById('show-password-trigger');
      const passwordInput = document.getElementById('login-password');
      const eyeIcon = document.getElementById('password-eye-icon');

      if (toggleBtn && passwordInput && eyeIcon) {
        toggleBtn.addEventListener('click', () => {
          const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
          passwordInput.setAttribute('type', type);
          eyeIcon.setAttribute('data-lucide', type === 'password' ? 'eye' : 'eye-off');
          if (window.lucide) window.lucide.createIcons();
        });
      }
    });
  </script>
@endsection
