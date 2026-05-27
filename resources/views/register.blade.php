@extends('layouts.app')

@section('title', 'Daftar - GameTopup')

@section('content')
  <div class="flex-1 flex items-center justify-center" id="register-page">
    <div class="w-full max-w-md px-4 py-12">
      
      <!-- Brand Header -->
      <div class="text-center mb-8">
        <a href="{{ route('home') }}" class="inline-flex cursor-pointer h-14 w-14 items-center justify-center rounded-2xl border border-white/50 neup-flat text-pink-655 mb-3.5 hover:neup-pressed-xs transition-colors bg-white decoration-none">
          <i data-lucide="gamepad-2" class="h-7 w-7 text-pink-600"></i>
        </a>
        <h1 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">Daftar Akun Baru</h1>
        <p class="text-xs text-slate-500 mt-1 font-black leading-relaxed">Mulai perjalanan gaming Anda hemat. Daftar untuk menikmati koin cashback instan.</p>
      </div>

      <!-- REGISTER FORM CARD -->
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

        <form action="{{ route('register') }}" method="POST" id="register-form-submit" class="space-y-5 text-left m-0 p-0">
          @csrf

          <!-- Username Input -->
          <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Lengkap (Username)</label>
            <div class="relative">
              <i data-lucide="user" class="absolute left-3.5 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-slate-400"></i>
              <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="cth: Alex Cahya"
                required
                class="w-full rounded-2xl border border-white/30 neup-pressed-xs py-3.5 pl-11 pr-4 text-xs font-bold text-slate-800 placeholder:text-slate-400 focus:outline-none bg-transparent"
              />
            </div>
          </div>

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

          <!-- Phone Input -->
          <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nomor Handphone</label>
            <div class="relative">
              <i data-lucide="phone" class="absolute left-3.5 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-slate-400"></i>
              <input
                type="text"
                name="phone"
                value="{{ old('phone') }}"
                placeholder="cth: 081234567890"
                required
                class="w-full rounded-2xl border border-white/30 neup-pressed-xs py-3.5 pl-11 pr-4 text-xs font-bold text-slate-800 placeholder:text-slate-400 focus:outline-none bg-transparent"
              />
            </div>
          </div>

          <!-- Password Input -->
          <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Kata Sandi (Password)</label>
            <div class="relative">
              <i data-lucide="lock" class="absolute left-3.5 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-slate-400"></i>
              <input
                type="password"
                name="password"
                id="register-password"
                placeholder="Min. 6 karakter"
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

          <!-- Confirm Password Input -->
          <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Konfirmasi Kata Sandi</label>
            <div class="relative">
              <i data-lucide="lock" class="absolute left-3.5 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-slate-400"></i>
              <input
                type="password"
                name="password_confirmation"
                placeholder="Ulangi kata sandi"
                required
                class="w-full rounded-2xl border border-white/30 neup-pressed-xs py-3.5 pl-11 pr-11 text-xs font-bold text-slate-800 placeholder:text-slate-400 focus:outline-none bg-transparent"
              />
            </div>
          </div>

          <!-- Terms Toggle -->
          <div class="flex items-start gap-2 pt-1 text-left">
            <input
              type="checkbox"
              required
              checked
              class="h-4.5 w-4.5 mt-0.5 rounded border border-white/40 text-pink-600 bg-transparent focus:ring-0 cursor-pointer"
            />
            <span class="text-[10px] text-slate-500 font-extrabold leading-normal select-none">
              Saya menyetujui <a href="#" class="text-pink-600 hover:underline">Syarat & Ketentuan Layanan</a> serta <a href="#" class="text-pink-600 hover:underline">Kebijakan Privasi</a> GameTopup.
            </span>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            id="btn-submit-register"
            class="w-full font-black tracking-wide uppercase py-3.5 rounded-2xl transition-all cursor-pointer bg-orange-500 hover:bg-orange-600 text-white neup-orange-flat hover:neup-orange-pressed active:scale-[0.98] text-xs border-none"
          >
            Daftar Akun Baru 🚀
          </button>

        </form>

      </div>

      <!-- Login redirect -->
      <p class="mt-6 text-center text-xs text-slate-500 font-bold">
        Sudah punya akun GameTopup? 
        <a href="{{ route('login') }}" class="text-pink-600 hover:underline hover:text-pink-700 font-black">
          Masuk ke Akun
        </a>
      </p>

    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const toggleBtn = document.getElementById('show-password-trigger');
      const passwordInput = document.getElementById('register-password');
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
