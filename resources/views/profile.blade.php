@extends('layouts.app')

@section('title', 'Pengaturan Profil - GameTopup')

@section('content')
  <div class="flex-1 flex flex-col" id="profile-page">
    <div class="flex-1 py-8">
      <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        
        <!-- Navigation Breadcrumb -->
        <a href="{{ route('dashboard') }}" class="group inline-flex items-center gap-2 px-4 py-2.5 text-xs font-black text-slate-600 neup-flat-sm hover:neup-pressed-sm cursor-pointer transition-all mb-8 rounded-xl bg-white text-left decoration-none">
          <i data-lucide="arrow-left" class="h-4 w-4 transition-transform group-hover:-translate-x-1 text-slate-500"></i>
          Kembali ke Dashboard
        </a>

        <!-- Flash Messages / Validation Errors -->
        @if(session('success'))
          <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-100 p-4 text-xs font-bold text-emerald-600 text-left bg-white shadow-sm">
            {{ session('success') }}
          </div>
        @endif

        @if($errors->any())
          <div class="mb-6 rounded-2xl bg-rose-50 border border-rose-100 p-4 text-xs font-bold text-rose-600 text-left bg-white shadow-sm">
            <ul class="list-disc list-inside m-0 p-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <!-- CORE LAYOUT SPLIT FOR PROFILE SETTINGS -->
        <form action="{{ route('profile.update') }}" method="POST" id="form-save-profile" class="space-y-8 m-0 p-0">
          @csrf

          <!-- 1. FORM EDIT DETAIL PROFIL -->
          <div class="rounded-3xl border border-white/50 neup-flat p-5 md:p-6 bg-white text-left font-bold shadow-sm">
            <div class="flex items-center gap-2.5 border-b border-slate-300/40 pb-3.5 mb-5">
              <i data-lucide="user" class="h-5 w-5 text-pink-600"></i>
              <h3 class="text-sm font-black text-slate-800">Edit Detail Profil Akun</h3>
            </div>

            <div class="space-y-5">
              
              <!-- Avatar Display Option -->
              <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3.5">Gambar Karakter Avatar</label>
                <div class="flex flex-wrap gap-3.5 justify-start">
                  @php
                    $firstChar = strtoupper(substr($user->name, 0, 1));
                    $bgColors = [
                        'A' => 'bg-rose-500 text-rose-50 border-rose-600',
                        'B' => 'bg-pink-500 text-pink-50 border-pink-600',
                        'C' => 'bg-fuchsia-500 text-fuchsia-50 border-fuchsia-600',
                        'D' => 'bg-purple-500 text-purple-50 border-purple-600',
                        'E' => 'bg-violet-500 text-violet-50 border-violet-600',
                        'F' => 'bg-indigo-500 text-indigo-50 border-indigo-600',
                        'G' => 'bg-blue-500 text-blue-50 border-blue-600',
                        'H' => 'bg-sky-500 text-sky-50 border-sky-600',
                        'I' => 'bg-cyan-500 text-cyan-50 border-cyan-600',
                        'J' => 'bg-teal-500 text-teal-50 border-teal-600',
                        'K' => 'bg-emerald-500 text-emerald-50 border-emerald-600',
                        'L' => 'bg-green-500 text-green-50 border-green-600',
                        'M' => 'bg-lime-500 text-lime-50 border-lime-600',
                        'N' => 'bg-yellow-500 text-yellow-950 border-yellow-600',
                        'O' => 'bg-amber-500 text-amber-950 border-amber-600',
                        'P' => 'bg-orange-500 text-orange-50 border-orange-600',
                        'Q' => 'bg-red-500 text-red-50 border-red-600',
                        'R' => 'bg-rose-600 text-rose-50 border-rose-700',
                        'S' => 'bg-pink-600 text-pink-50 border-pink-700',
                        'T' => 'bg-fuchsia-600 text-fuchsia-50 border-fuchsia-700',
                        'U' => 'bg-purple-600 text-purple-50 border-purple-700',
                        'V' => 'bg-violet-600 text-violet-50 border-violet-700',
                        'W' => 'bg-indigo-600 text-indigo-50 border-indigo-700',
                        'X' => 'bg-blue-600 text-blue-50 border-blue-700',
                        'Y' => 'bg-sky-600 text-sky-50 border-sky-700',
                        'Z' => 'bg-cyan-600 text-cyan-50 border-cyan-700',
                    ];
                    $avatarClass = $bgColors[$firstChar] ?? 'bg-slate-500 text-slate-50 border-slate-600';
                  @endphp
                  <div class="h-12 w-12 rounded-xl flex items-center justify-center font-black text-lg border ring-4 ring-pink-500/25 shadow-md flex-shrink-0 {{ $avatarClass }}">
                    {{ $firstChar }}
                  </div>
                </div>
              </div>

              <!-- Username input -->
              <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Lengkap (Username)</label>
                <div class="relative">
                  <i data-lucide="user" class="absolute left-3.5 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-slate-400"></i>
                  <input
                    type="text"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    required
                    placeholder="Masukkan Nama Lengkap"
                    class="w-full rounded-2xl border border-white/30 neup-pressed-xs py-3.5 pl-11 pr-4 text-xs font-bold text-slate-800 placeholder:text-slate-400 focus:outline-none bg-transparent"
                  />
                </div>
              </div>

              <!-- Email Display Read-only -->
              <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 opacity-60">Alamat Email (Permanen)</label>
                <div class="relative opacity-60">
                  <i data-lucide="mail" class="absolute left-3.5 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-slate-400"></i>
                  <input
                    type="email"
                    value="{{ $user->email }}"
                    disabled
                    class="w-full rounded-2xl border border-white/30 neup-pressed-xs py-3.5 pl-11 pr-4 text-xs font-bold text-slate-550 bg-slate-100 focus:outline-none cursor-not-allowed border-none"
                  />
                </div>
              </div>

              <!-- Phone input -->
              <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nomor Handphone</label>
                <div class="relative">
                  <i data-lucide="phone" class="absolute left-3.5 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-slate-400"></i>
                  <input
                    type="text"
                    name="phone"
                    value="{{ old('phone', $user->phone) }}"
                    required
                    placeholder="Masukkan Nomor Handphone"
                    class="w-full rounded-2xl border border-white/30 neup-pressed-xs py-3.5 pl-11 pr-4 text-xs font-bold text-slate-800 placeholder:text-slate-400 focus:outline-none bg-transparent"
                  />
                </div>
              </div>

            </div>
          </div>

          <!-- 2. FORM UBAH SANDI AKUN -->
          <div class="rounded-3xl border border-white/50 neup-flat p-5 md:p-6 bg-white text-left font-bold shadow-sm">
            <div class="flex items-center gap-2.5 border-b border-slate-300/40 pb-3.5 mb-5">
              <i data-lucide="lock" class="h-5 w-5 text-pink-600"></i>
              <h3 class="text-sm font-black text-slate-800">Ubah Kata Sandi Keamanan (Opsional)</h3>
            </div>

            <div class="space-y-5">
              
              <!-- Old password input -->
              <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Kata Sandi Saat Ini</label>
                <div class="relative">
                  <i data-lucide="lock" class="absolute left-3.5 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-slate-400"></i>
                  <input
                    type="password"
                    name="current_password"
                    placeholder="••••••••"
                    class="w-full rounded-2xl border border-white/30 neup-pressed-xs py-3.5 pl-11 pr-4 text-xs font-bold text-slate-800 placeholder:text-slate-400 focus:outline-none bg-transparent"
                  />
                </div>
              </div>

              <!-- New password input -->
              <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Kata Sandi Baru (Min. 6 Karakter)</label>
                <div class="relative">
                  <i data-lucide="lock" class="absolute left-3.5 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-slate-400"></i>
                  <input
                    type="password"
                    name="new_password"
                    placeholder="••••••••"
                    class="w-full rounded-2xl border border-white/30 neup-pressed-xs py-3.5 pl-11 pr-4 text-xs font-bold text-slate-800 placeholder:text-slate-400 focus:outline-none bg-transparent"
                  />
                </div>
              </div>

              <!-- New password confirmation -->
              <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Konfirmasi Kata Sandi Baru</label>
                <div class="relative">
                  <i data-lucide="lock" class="absolute left-3.5 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-slate-400"></i>
                  <input
                    type="password"
                    name="new_password_confirmation"
                    placeholder="••••••••"
                    class="w-full rounded-2xl border border-white/30 neup-pressed-xs py-3.5 pl-11 pr-4 text-xs font-bold text-slate-800 placeholder:text-slate-400 focus:outline-none bg-transparent"
                  />
                </div>
              </div>

            </div>
          </div>

          <!-- SUBMIT BUTTON -->
          <button
            type="submit"
            class="w-full font-black tracking-wide uppercase py-4 rounded-2xl transition-all cursor-pointer bg-orange-500 hover:bg-orange-600 text-white neup-orange-flat hover:neup-orange-pressed active:scale-98 text-xs border-none shadow-sm"
          >
            Simpan Seluruh Pembaruan Profil & Keamanan 🛡_
          </button>
        </form>

      </div>
    </div>
  </div>
@endsection
