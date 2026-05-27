@extends('layouts.admin')

@section('title', 'Admin Panel - Manajemen Pengguna')

@section('content')
  <div class="text-left animate-fade-in">
    <!-- Top Header -->
    <div class="border-b border-slate-800 pb-5 mb-8">
      <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-[10px] font-extrabold uppercase text-cyan-400 shadow-sm shadow-blue-500/5">
        <i data-lucide="users" class="h-3.5 w-3.5"></i> USER DIRECTORY
      </span>
      <h1 class="text-2xl font-black mt-2 tracking-tight text-white">Manajemen Pengguna</h1>
      <p class="text-xs text-slate-400 mt-1 font-semibold">Kelola seluruh data member terdaftar, aktifkan/tangguhkan akun, reset kata sandi, dan telusuri transaksi masuk per pengguna.</p>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
      <div class="mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-4 text-xs font-bold text-emerald-400 shadow-md">
        ✓ {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="mb-6 rounded-2xl bg-rose-500/10 border border-rose-500/30 p-4 text-xs font-bold text-rose-400 shadow-md">
        ✕ {{ session('error') }}
      </div>
    @endif

    <!-- FILTER & SEARCH PANEL -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <form action="{{ route('admin.users') }}" method="GET" class="w-full grid grid-cols-1 sm:grid-cols-4 gap-3 font-semibold">
        <!-- Search field -->
        <div class="relative w-full sm:col-span-2">
          <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari nama, email, atau telepon..."
            class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-2.5 pl-4 pr-10 text-xs font-semibold focus:outline-none placeholder:text-slate-500"
          />
          <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-transparent border-none text-slate-500 hover:text-slate-350 cursor-pointer flex items-center justify-center">
            <i data-lucide="search" class="h-4 w-4"></i>
          </button>
        </div>

        <!-- Role selector -->
        <div>
          <select name="role" onchange="this.form.submit()" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-2.5 px-4 text-xs font-bold focus:outline-none cursor-pointer">
            <option value="all" {{ request('role') === 'all' || !request('role') ? 'selected' : '' }}>Semua Hak Akses</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Member/User</option>
          </select>
        </div>

        <!-- Status selector -->
        <div class="flex gap-2">
          <select name="status" onchange="this.form.submit()" class="w-full rounded-2xl border border-slate-700 bg-slate-800 text-white py-2.5 px-4 text-xs font-bold focus:outline-none cursor-pointer">
            <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>Semua Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Ditangguhkan</option>
          </select>

          @if(request()->filled('search') || request()->filled('role') || request()->filled('status'))
            <a href="{{ route('admin.users') }}" class="flex items-center justify-center rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 hover:bg-rose-500/25 px-3 cursor-pointer decoration-none transition-all active:scale-95 shadow-sm" title="Reset Filter">
              <i data-lucide="x" class="h-4 w-4"></i>
            </a>
          @endif
        </div>
      </form>
    </div>

    <!-- USERS DIRECTORY TABLE -->
    <div class="rounded-3xl border border-slate-800 p-5 md:p-6 bg-[#111827]/75 backdrop-blur-xl shadow-xl overflow-hidden mb-6">
      <div class="overflow-x-auto w-full">
        <table class="w-full text-slate-350 text-xs font-semibold border-collapse text-left">
          <thead>
            <tr class="border-b border-slate-800 text-[10px] uppercase tracking-wider text-slate-400">
              <th class="pb-3.5 pl-2">Pengguna</th>
              <th class="pb-3.5">Detail Kontak</th>
              <th class="pb-3.5">Saldo Dompet</th>
              <th class="pb-3.5">Total Belanja</th>
              <th class="pb-3.5">Status Akun</th>
              <th class="pb-3.5 text-right pr-4">Aksi Kontrol</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800">
            @forelse($users as $usr)
              <tr class="hover:bg-slate-900/40 transition-colors">
                <!-- User Profile -->
                <td class="py-4 pl-2">
                  <div class="flex items-center gap-3">
                    @php
                      $firstChar = strtoupper(substr($usr->name, 0, 1));
                      $bgColors = [
                          'A' => 'from-rose-600/30 to-rose-500/10 text-rose-400 border-rose-800/40',
                          'B' => 'from-pink-600/30 to-pink-500/10 text-pink-400 border-pink-800/40',
                          'C' => 'from-fuchsia-600/30 to-fuchsia-500/10 text-fuchsia-400 border-fuchsia-800/40',
                          'D' => 'from-purple-600/30 to-purple-500/10 text-purple-400 border-purple-800/40',
                          'E' => 'from-violet-600/30 to-violet-500/10 text-violet-400 border-violet-800/40',
                          'F' => 'from-indigo-600/30 to-indigo-500/10 text-indigo-400 border-indigo-800/40',
                          'G' => 'from-blue-600/30 to-blue-500/10 text-blue-400 border-blue-800/40',
                          'H' => 'from-sky-600/30 to-sky-500/10 text-sky-400 border-sky-800/40',
                          'I' => 'from-cyan-600/30 to-cyan-500/10 text-cyan-400 border-cyan-800/40',
                          'J' => 'from-teal-600/30 to-teal-500/10 text-teal-400 border-teal-800/40',
                          'K' => 'from-emerald-600/30 to-emerald-500/10 text-emerald-450 border-emerald-800/40',
                          'L' => 'from-green-600/30 to-green-500/10 text-green-400 border-green-800/40',
                          'M' => 'from-lime-600/30 to-lime-500/10 text-lime-400 border-lime-800/40',
                          'N' => 'from-yellow-600/30 to-yellow-500/10 text-yellow-300 border-yellow-800/40',
                          'O' => 'from-amber-600/30 to-amber-500/10 text-amber-300 border-amber-800/40',
                          'P' => 'from-orange-600/30 to-orange-500/10 text-orange-400 border-orange-800/40',
                          'Q' => 'from-red-600/30 to-red-500/10 text-red-400 border-red-800/40',
                          'R' => 'from-rose-600/35 to-rose-700/15 text-rose-350 border-rose-750/40',
                          'S' => 'from-pink-600/35 to-pink-700/15 text-pink-350 border-pink-750/40',
                          'T' => 'from-fuchsia-600/35 to-fuchsia-700/15 text-fuchsia-350 border-fuchsia-750/40',
                          'U' => 'from-purple-600/35 to-purple-700/15 text-purple-350 border-purple-750/40',
                          'V' => 'from-violet-600/35 to-violet-700/15 text-violet-350 border-violet-750/40',
                          'W' => 'from-indigo-600/35 to-indigo-700/15 text-indigo-350 border-indigo-750/40',
                          'X' => 'from-blue-600/35 to-blue-700/15 text-blue-350 border-blue-750/40',
                          'Y' => 'from-sky-600/35 to-sky-700/15 text-sky-350 border-sky-700/40',
                          'Z' => 'from-cyan-600/35 to-cyan-700/15 text-cyan-350 border-cyan-700/40',
                      ];
                      $gradClass = $bgColors[$firstChar] ?? 'from-slate-600/30 to-slate-500/10 text-slate-350 border-slate-750/40';
                    @endphp
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br border flex items-center justify-center font-black text-sm flex-shrink-0 {{ $gradClass }}">
                      {{ $firstChar }}
                    </div>
                    <div>
                      <p class="font-black text-white leading-tight">{{ $usr->name }}</p>
                      <p class="text-[9px] text-cyan-400 font-black uppercase mt-0.5 leading-none tracking-wider">{{ $usr->role }}</p>
                    </div>
                  </div>
                </td>
                
                <!-- Contacts -->
                <td class="py-4">
                  <p class="text-white font-bold leading-normal">{{ $usr->email }}</p>
                  <p class="text-[9px] text-slate-450 font-mono mt-0.5 leading-none">{{ $usr->phone ?? '-' }}</p>
                </td>
                
                <!-- Balances -->
                <td class="py-4 font-mono font-black text-emerald-400">
                  Rp {{ number_format($usr->balance, 0, ',', '.') }}
                  <span class="block text-[8px] text-slate-500 font-sans mt-0.5 font-bold">Cashback Saved: Rp {{ number_format($usr->cashback_saved, 0, ',', '.') }}</span>
                </td>
                
                <!-- Transaction counts -->
                <td class="py-4 font-bold">
                  <button onclick="toggleTxDrawer({{ $usr->id }})" class="border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-200 font-extrabold px-2.5 py-1.5 rounded-lg text-[9px] cursor-pointer transition-all flex items-center gap-1.5 shadow-sm active:scale-95">
                    <i data-lucide="shopping-bag" class="h-3 w-3"></i> {{ $usr->transactions_count }} Transaksi →
                  </button>
                </td>
                
                <!-- Status -->
                <td class="py-4">
                  @if($usr->is_suspended)
                    <span class="rounded bg-rose-500/10 border border-rose-500/20 px-2.5 py-0.5 text-[8px] font-black text-rose-400 uppercase tracking-wider">SUSPENDED</span>
                  @else
                    <span class="rounded bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 text-[8px] font-black text-emerald-400 uppercase tracking-wider">ACTIVE</span>
                  @endif
                </td>
                
                <!-- Controls -->
                <td class="py-4 text-right pr-4">
                  <div class="inline-flex gap-2">
                    <button onclick="togglePasswordForm({{ $usr->id }})" class="border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-100 font-extrabold rounded-xl px-3 py-2 text-[10px] cursor-pointer transition-all active:scale-95 shadow-sm">
                      Reset Sandi
                    </button>
                    @if($usr->role !== 'admin')
                      <form action="{{ route('admin.users.toggle-suspend', $usr->id) }}" method="POST" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="border border-slate-700 font-extrabold rounded-xl px-3 py-2 text-[10px] cursor-pointer transition-all active:scale-95 shadow-sm bg-slate-800 hover:bg-slate-700 {{ $usr->is_suspended ? 'text-emerald-400' : 'text-rose-400' }}">
                          {{ $usr->is_suspended ? 'Aktifkan' : 'Suspend' }}
                        </button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>

              <!-- PASSWORD RESET FORM DRAWER -->
              <tr id="password-row-{{ $usr->id }}" class="hidden bg-slate-900/10">
                <td colspan="6" class="p-5 border-t border-b border-slate-800">
                  <div class="max-w-md text-left font-bold text-slate-300 p-2">
                    <h4 class="text-xs font-black text-white uppercase tracking-wider mb-3 flex items-center gap-1.5">
                      <i data-lucide="lock" class="h-4 w-4 text-cyan-400"></i> Setel Ulang Kata Sandi: {{ $usr->name }}
                    </h4>
                    <form action="{{ route('admin.users.reset-password', $usr->id) }}" method="POST" class="flex flex-col gap-3">
                      @csrf
                      <div class="grid grid-cols-2 gap-3">
                        <div class="flex flex-col gap-1">
                          <label class="text-[9px] uppercase tracking-wider text-slate-400">Kata Sandi Baru</label>
                          <input type="password" name="password" required placeholder="Min. 6 Karakter" class="rounded-xl border border-slate-700 bg-slate-800 text-white py-2 px-3 text-xs font-semibold focus:outline-none">
                        </div>
                        <div class="flex flex-col gap-1">
                          <label class="text-[9px] uppercase tracking-wider text-slate-400">Konfirmasi Sandi</label>
                          <input type="password" name="password_confirmation" required placeholder="Konfirmasi Sandi" class="rounded-xl border border-slate-700 bg-slate-800 text-white py-2 px-3 text-xs font-semibold focus:outline-none">
                        </div>
                      </div>
                      <div class="flex gap-2 justify-end mt-1">
                        <button type="button" onclick="togglePasswordForm({{ $usr->id }})" class="border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-300 font-extrabold rounded-lg px-4 py-2 text-[10px] cursor-pointer">Batal</button>
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-cyan-500 border-none text-white font-extrabold rounded-lg px-4 py-2 text-[10px] cursor-pointer hover:shadow-md active:scale-95 transition-all">Reset Password</button>
                      </div>
                    </form>
                  </div>
                </td>
              </tr>

              <!-- TRANSACTIONS LIST DRAWER -->
              <tr id="tx-row-{{ $usr->id }}" class="hidden bg-slate-900/10">
                <td colspan="6" class="p-5 border-t border-b border-slate-800">
                  <div class="text-left font-bold text-slate-300 p-2 w-full">
                    <h4 class="text-xs font-black text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                      <i data-lucide="shopping-bag" class="h-4.5 w-4.5 text-cyan-400"></i> Riwayat Transaksi Belanja Pengguna: {{ $usr->name }}
                    </h4>
                    
                    <div class="space-y-3 max-h-72 overflow-y-auto pr-2 scrollbar-none">
                      @forelse($usr->transactions as $tx)
                        <div class="border border-slate-800 rounded-xl p-3.5 bg-slate-900/30 flex items-center justify-between gap-4">
                          <div class="text-left">
                            <div class="flex items-center gap-2">
                              <span class="text-xs font-mono font-black text-white">{{ $tx->invoice }}</span>
                              @if($tx->status === 'success')
                                <span class="rounded bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 text-[7px] font-black text-emerald-400 uppercase tracking-wider">PAID</span>
                              @elseif($tx->status === 'failed')
                                <span class="rounded bg-rose-500/10 border border-rose-500/20 px-2 py-0.5 text-[7px] font-black text-rose-400 uppercase tracking-wider">FAILED</span>
                              @else
                                <span class="rounded bg-amber-500/10 border border-amber-500/20 px-2 py-0.5 text-[7px] font-black text-amber-400 uppercase tracking-wider">PENDING</span>
                              @endif
                            </div>
                            <p class="text-[9px] text-slate-400 font-bold mt-1">
                              {{ $tx->game->name }} • {{ $tx->nominal_name }} • Target ID: {{ $tx->target_id }}{{ $tx->zone_id ? ' (' . $tx->zone_id . ')' : '' }}
                            </p>
                          </div>
                          <div class="text-right">
                            <p class="text-xs font-mono font-black text-white">Rp {{ number_format($tx->total_payment, 0, ',', '.') }}</p>
                            <p class="text-[8px] text-slate-500 mt-1 font-semibold">{{ $tx->created_at->format('d M Y, H:i') }} WIB</p>
                          </div>
                        </div>
                      @empty
                        <p class="text-xs text-slate-500 font-bold py-6 text-center">Pengguna belum pernah melakukan transaksi.</p>
                      @endforelse
                    </div>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="py-12 text-center text-slate-500 font-bold">Belum ada pengguna terdaftar di sistem.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- PAGINATION LINKS -->
    @if($users->hasPages())
      <div class="mt-4 p-4 rounded-3xl bg-[#111827]/75 backdrop-blur-xl border border-slate-800 flex justify-center text-slate-350 shadow-xl">
        {{ $users->appends(request()->query())->links() }}
      </div>
    @endif
  </div>

  <script>
    function togglePasswordForm(id) {
      const row = document.getElementById(`password-row-${id}`);
      if (row) {
        row.classList.toggle('hidden');
      }
    }

    function toggleTxDrawer(id) {
      const row = document.getElementById(`tx-row-${id}`);
      if (row) {
        row.classList.toggle('hidden');
      }
    }
  </script>
@endsection
