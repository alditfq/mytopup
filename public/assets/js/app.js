// ============================================================
// GameTopup Marketplace — Shared App Utilities
// Shared across all pages (state management, helpers, UI)
// ============================================================

// ── State Management (localStorage-backed) ──────────────────

const AppState = {
  get user() {
    const s = localStorage.getItem('gt_user');
    return s ? JSON.parse(s) : null;
  },
  set user(val) {
    if (val) localStorage.setItem('gt_user', JSON.stringify(val));
    else localStorage.removeItem('gt_user');
  },
  get isLoggedIn() {
    return localStorage.getItem('gt_is_logged') === 'true';
  },
  set isLoggedIn(val) {
    localStorage.setItem('gt_is_logged', String(val));
  },
  get transactions() {
    const s = localStorage.getItem('gt_transactions');
    return s ? JSON.parse(s) : (typeof INITIAL_TRANSACTIONS !== 'undefined' ? INITIAL_TRANSACTIONS : []);
  },
  set transactions(val) {
    localStorage.setItem('gt_transactions', JSON.stringify(val));
  },
  get currentTransaction() {
    const s = localStorage.getItem('gt_current_tx');
    return s ? JSON.parse(s) : null;
  },
  set currentTransaction(val) {
    if (val) localStorage.setItem('gt_current_tx', JSON.stringify(val));
    else localStorage.removeItem('gt_current_tx');
  },
  get selectedGameId() {
    return localStorage.getItem('gt_selected_game') || null;
  },
  set selectedGameId(val) {
    if (val) localStorage.setItem('gt_selected_game', val);
    else localStorage.removeItem('gt_selected_game');
  },
  get language() {
    return localStorage.getItem('gt_lang') || 'ID';
  },
  set language(val) {
    localStorage.setItem('gt_lang', val);
  },
  get chatMessages() {
    const s = localStorage.getItem('gt_chat');
    return s ? JSON.parse(s) : [{
      id: 'welcome-1',
      sender: 'support',
      text: 'Halo! Selamat datang di Layanan Bantuan Client GameTopup. Ada yang bisa kami bantu mengenai transaksi atau kendala top up Anda hari ini?',
      time: '10:30'
    }];
  },
  set chatMessages(val) {
    localStorage.setItem('gt_chat', JSON.stringify(val));
  }
};

// Initialize defaults if first visit
(function initState() {
  if (!localStorage.getItem('gt_transactions') && typeof INITIAL_TRANSACTIONS !== 'undefined') {
    AppState.transactions = INITIAL_TRANSACTIONS;
  }
  if (!localStorage.getItem('gt_user') && typeof DEFAULT_USER !== 'undefined') {
    AppState.user = DEFAULT_USER;
  }
})();

// ── Business Logic ───────────────────────────────────────────

function login(email, password) {
  if (!email || !password) return { success: false, error: 'Email dan password wajib diisi!' };
  const formattedUsername = email.split('@')[0];
  const existing = AppState.user;
  const newUser = {
    username: formattedUsername.charAt(0).toUpperCase() + formattedUsername.slice(1),
    email,
    phone: existing?.phone || '082199887766',
    avatarUrl: existing?.avatarUrl || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&h=150&fit=crop&q=80',
    balance: existing?.balance ?? 120000,
    cashbackSaved: existing?.cashbackSaved ?? 8700,
    totalTransactions: AppState.transactions.length,
    favoriteGames: existing?.favoriteGames || ['mobile-legends']
  };
  AppState.user = newUser;
  AppState.isLoggedIn = true;
  return { success: true };
}

function getBasePath() {
  const path = window.location.pathname;
  if (path.indexOf('/store') === 0) return '/store';
  return '';
}

function logout() {
  AppState.isLoggedIn = false;
  AppState.user = null;
  localStorage.removeItem('gt_user');
  localStorage.removeItem('gt_is_logged');
  window.location.href = getBasePath() + '/';
}

function register(username, email, password) {
  if (!username || !email || !password) return { success: false, error: 'Username, email, dan password wajib diisi!' };
  const newUser = {
    username,
    email,
    phone: '081200000000',
    avatarUrl: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&h=150&fit=crop&q=80',
    balance: 100000,
    cashbackSaved: 0,
    totalTransactions: 0,
    favoriteGames: []
  };
  AppState.user = newUser;
  AppState.isLoggedIn = true;
  return { success: true };
}

function updateProfile(username, phone, avatarUrl) {
  const user = AppState.user;
  if (!user) return { success: false };
  AppState.user = { ...user, username, phone, avatarUrl };
  return { success: true };
}

function changePassword(oldPw, newPw) {
  if (!oldPw || !newPw) return { success: false, error: 'Semua kolom password harus diisi.' };
  if (newPw.length < 6) return { success: false, error: 'Password baru minimal harus 6 karakter.' };
  return { success: true };
}

function createTransaction(gameId, userId, zoneId, nickname, nominalId, paymentMethodId, promoCode) {
  const game = GAMES.find(g => g.id === gameId);
  const nominal = game.nominals.find(n => n.id === nominalId);
  const payment = PAYMENT_METHODS.find(p => p.id === paymentMethodId);

  const basePrice = nominal.discountPrice || nominal.price;
  let discountAmt = 0;

  const codeUpper = (promoCode || '').toUpperCase();
  if (codeUpper === 'CSHBKNEW' && basePrice >= 30000) discountAmt = 25000;
  else if (codeUpper === 'WEEKENDGAMER' && basePrice >= 50000) discountAmt = 15000;
  else if (codeUpper === 'GARENASPEKTA' && basePrice >= 20000 && (gameId === 'free-fire' || gameId === 'pubg-mobile')) discountAmt = 10000;

  const priceAfterPromo = Math.max(1000, basePrice - discountAmt);
  const finalAmount = priceAfterPromo + payment.fee;

  const randomNum = Math.floor(1000 + Math.random() * 9000);
  const dateStr = new Date().toISOString().slice(0, 10).replace(/-/g, '');
  const prefix = game.name.substring(0, 2).toUpperCase();
  const invoice = `INV-${dateStr}-${prefix}${randomNum}`;

  let vaNumber;
  let qrCodeUrl;
  if (payment.group === 'bank') {
    vaNumber = payment.accountNumber || `12800${userId.slice(0, 5)}${randomNum}`;
  } else if (payment.group === 'qris') {
    qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=GAMETOPUP-QRIS-MOCK-${invoice}`;
  }

  const now = new Date();
  const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

  const newTx = {
    invoice,
    gameId,
    gameName: game.name,
    gameIcon: game.thumbnailUrl,
    userId,
    zoneId: zoneId || undefined,
    nickname,
    nominalName: nominal.name,
    nominalPrice: nominal.price,
    discountApplied: (nominal.price - basePrice) + discountAmt,
    paymentMethod: payment,
    totalPayment: finalAmount,
    status: 'pending',
    createdAt: now.toISOString(),
    statusLogs: [
      { time: timeStr, message: 'Pesanan berhasil dibuat. Menunggu penyelesaian pembayaran oleh Pengguna.' }
    ],
    vaNumber,
    qrCodeUrl
  };

  const txs = AppState.transactions;
  AppState.transactions = [newTx, ...txs];
  AppState.currentTransaction = newTx;

  const user = AppState.user;
  if (user) {
    AppState.user = {
      ...user,
      totalTransactions: user.totalTransactions + 1,
      cashbackSaved: user.cashbackSaved + Math.round((nominal.price * (game.cashbackPercent || 0)) / 100)
    };
  }

  return newTx;
}

function simulatePaymentSuccess(invoice) {
  const now = new Date();
  const t0 = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
  const t1 = new Date(Date.now() + 2000).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
  const t2 = new Date(Date.now() + 4000).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

  const txs = AppState.transactions.map(tx => {
    if (tx.invoice === invoice) {
      return {
        ...tx,
        status: 'success',
        statusLogs: [
          ...tx.statusLogs,
          { time: t0, message: 'Pembayaran terdeteksi sukses. Memproses pesanan.' },
          { time: t1, message: 'Menghubungkan ke API server game...' },
          { time: t2, message: `Sukses mengirimkan [${tx.nominalName}] ke ID: ${tx.userId}${tx.zoneId ? ` (${tx.zoneId})` : ''}. Voucher/Diamond telah aktif.` }
        ]
      };
    }
    return tx;
  });
  AppState.transactions = txs;

  const ct = AppState.currentTransaction;
  if (ct && ct.invoice === invoice) {
    AppState.currentTransaction = {
      ...ct,
      status: 'success',
      statusLogs: [
        ...ct.statusLogs,
        { time: t0, message: 'Pembayaran terdeteksi sukses. Pesanan diproses.' },
        { time: t2, message: `Sukses mengirimkan [${ct.nominalName}] ke ID: ${ct.userId}. Voucher/Diamond telah aktif.` }
      ]
    };
  }
}

function toggleFavoriteGame(gameId) {
  const user = AppState.user;
  if (!user) return;
  const isFav = user.favoriteGames.includes(gameId);
  const updatedFavs = isFav
    ? user.favoriteGames.filter(id => id !== gameId)
    : [...user.favoriteGames, gameId];
  AppState.user = { ...user, favoriteGames: updatedFavs };
}

function sendChatMessage(text) {
  if (!text.trim()) return;
  const now = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
  const msgs = AppState.chatMessages;

  const userMsg = { id: `chat-${Date.now()}`, sender: 'user', text, time: now };
  AppState.chatMessages = [...msgs, userMsg];

  setTimeout(() => {
    let reply = 'Terima kasih telah menghubungi kami. Tim Support kami akan segera meninjau pesan Anda. Mohon ditunggu.';
    const lower = text.toLowerCase();
    if (lower.includes('bayar') || lower.includes('qris') || lower.includes('transfer')) {
      reply = 'Untuk kendala pembayaran: Pastikan nominal yang ditransfer sesuai dengan digit unik invoice Anda. Jika saldo sudah terpotong namun status belum berubah dalam 10 menit, kirimkan bukti transfer beserta Invoice ID di sini.';
    } else if (lower.includes('belum masuk') || lower.includes('diamond') || lower.includes('gagal')) {
      reply = 'Jika diamond atau item Anda belum masuk setelah 5 menit pembayaran berhasil, silakan berikan Invoice ID Anda agar kami bantu melakukan push-delivery manual.';
    } else if (lower.includes('refund') || lower.includes('salah')) {
      reply = 'Halo! Sesuai syarat & ketentuan, transaksi top-up instan ke akun game yang salah akibat kesalahan ketik User ID tidak bisa dibatalkan. Namun jika berstatus gagal dari sistem kami, kami kembalikan dana penuh ke balance akun Anda.';
    } else if (lower.includes('diskon') || lower.includes('promo') || lower.includes('kode')) {
      reply = 'Anda bisa menggunakan kode promo aktif: WEEKENDGAMER untuk diskon Rp 15.000 (Min. Rp 50.000) atau CSHBKNEW untuk diskon Rp 25.000 bagi pendaftar baru!';
    } else if (lower.includes('halo') || lower.includes('pagi') || lower.includes('siang') || lower.includes('malam')) {
      reply = 'Halo! Ada yang bisa kami bantu? Jika ada kendala terkait pesanan top up game Anda, harap sertakan ID Invoice atau UID game Anda ya.';
    }

    const replyTime = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    const supportMsg = { id: `support-${Date.now()}`, sender: 'support', text: reply, time: replyTime };
    AppState.chatMessages = [...AppState.chatMessages, supportMsg];

    // Trigger chat UI refresh if on support page
    if (typeof refreshChatUI === 'function') refreshChatUI();
  }, 1200);
}

function searchInvoice(invoiceId) {
  return AppState.transactions.find(t => t.invoice.toLowerCase() === invoiceId.toLowerCase().trim()) || null;
}

// ── Formatting Helpers ───────────────────────────────────────

function formatRupiah(amount) {
  return 'Rp ' + amount.toLocaleString('id-ID');
}

function getStartingPrice(game) {
  const prices = game.nominals.map(n => n.discountPrice || n.price);
  return Math.min(...prices);
}

function getStatusConfig(status) {
  switch (status) {
    case 'success':  return { label: '🎉 BERHASIL (PAID)', textClass: 'text-emerald-600', badgeClass: 'success' };
    case 'processing': return { label: '⚡ SEDANG DIPROSES', textClass: 'text-indigo-600', badgeClass: 'processing' };
    case 'failed':   return { label: '✕ GAGAL', textClass: 'text-rose-600', badgeClass: 'failed' };
    default:         return { label: '⏳ MENUNGGU BAYAR', textClass: 'text-amber-705', badgeClass: 'pending' };
  }
}

// ── Toast Notification ───────────────────────────────────────

function showToast(message) {
  let toast = document.getElementById('global-toast');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'global-toast';
    toast.className = 'toast';
    toast.innerHTML = `
      <div style="width:1.5rem;height:1.5rem;border-radius:0.5rem;background:rgba(236,72,153,0.1);display:flex;align-items:center;justify-content:center;border:1px solid #ff007f;color:#ff007f;font-weight:900;font-size:0.75rem;flex-shrink:0">★</div>
      <p style="font-size:0.75rem;font-weight:900;color:#f1f5f9;margin:0" id="toast-text"></p>
    `;
    document.body.appendChild(toast);
  }
  document.getElementById('toast-text').textContent = message;
  toast.classList.add('visible');
  clearTimeout(toast._timer);
  toast._timer = setTimeout(() => toast.classList.remove('visible'), 2500);
}

// ── Copy to Clipboard ────────────────────────────────────────

function copyToClipboard(text, btn, originalHTML) {
  navigator.clipboard.writeText(text).then(() => {
    if (btn) {
      btn.innerHTML = `<svg style="width:0.875rem;height:0.875rem;margin-right:0.25rem" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Tersalin`;
      btn.style.color = '#059669';
      setTimeout(() => {
        btn.innerHTML = originalHTML;
        btn.style.color = '';
      }, 2000);
    }
  });
}

// ── SVG Icons ────────────────────────────────────────────────

const ICONS = {
  gamepad: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M11 4H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2h-4M11 4V2m0 2h2m-2 0H9m8 4H7m4 4v4m-2-2h4"/></svg>`,
  gamepad2: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><line x1="6" y1="12" x2="10" y2="12"/><line x1="8" y1="10" x2="8" y2="14"/><circle cx="15" cy="11" r="1"/><circle cx="17" cy="13" r="1"/><path stroke-linecap="round" stroke-linejoin="round" d="M2 12C2 6.48 6.48 2 12 2s10 4.48 10 10-4.48 10-10 10S2 17.52 2 12z"/></svg>`,
  search: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>`,
  star: `<svg viewBox="0 0 24 24" width="100%" height="100%"><path fill="currentColor" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>`,
  tag: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>`,
  flame: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/></svg>`,
  clock: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`,
  arrowRight: `<svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" width="100%" height="100%"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>`,
  arrowLeft: `<svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" width="100%" height="100%"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>`,
  chevronDown: `<svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" width="100%" height="100%"><polyline points="6 9 12 15 18 9"/></svg>`,
  chevronUp: `<svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" width="100%" height="100%"><polyline points="18 15 12 9 6 15"/></svg>`,
  chevronRight: `<svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" width="100%" height="100%"><polyline points="9 18 15 12 9 6"/></svg>`,
  menu: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>`,
  close: `<svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" width="100%" height="100%"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`,
  user: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>`,
  logout: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>`,
  globe: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>`,
  clipboard: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>`,
  helpCircle: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`,
  message: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>`,
  sparkles: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>`,
  shieldCheck: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>`,
  shield: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>`,
  check: `<svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" width="100%" height="100%"><polyline points="20 6 9 17 4 12"/></svg>`,
  checkCircle: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
  checkDouble: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7 12l2 2 4-4"/></svg>`,
  copy: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>`,
  qrCode: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h1v1h-1zM17 14h1v1h-1zM20 14h1v1h-1zM14 17h1v1h-1zM17 17h1v1h-1zM20 17h1v1h-1zM14 20h1v1h-1zM17 20h1v1h-1zM20 20h1v1h-1z"/><path d="M5 5h3v3H5zM16 5h3v3h-3zM5 16h3v3H5z"/></svg>`,
  alertCircle: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`,
  alertTriangle: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`,
  refreshCw: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path stroke-linecap="round" stroke-linejoin="round" d="M3.51 9a9 9 0 0114.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0020.49 15"/></svg>`,
  eye: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`,
  eyeOff: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`,
  mail: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>`,
  lock: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M7 11V7a5 5 0 0110 0v4"/></svg>`,
  gift: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7H7.5a2.5 2.5 0 010-5C11 2 12 7 12 7zM12 7h4.5a2.5 2.5 0 000-5C13 2 12 7 12 7z"/></svg>`,
  ticket: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>`,
  info: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>`,
  heart: `<svg fill="currentColor" viewBox="0 0 24 24" width="100%" height="100%"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>`,
  heartOutline: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>`,
  wallet: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h14a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 9V7a2 2 0 012-2h14a2 2 0 012 2v2"/><circle cx="17" cy="13" r="1" fill="currentColor"/></svg>`,
  landmark: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><line x1="3" y1="22" x2="21" y2="22"/><line x1="6" y1="18" x2="6" y2="11"/><line x1="10" y1="18" x2="10" y2="11"/><line x1="14" y1="18" x2="14" y2="11"/><line x1="18" y1="18" x2="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg>`,
  smartphone: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>`,
  save: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>`,
  key: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 11-7.778 7.778 5.5 5.5 0 017.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>`,
  send: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>`,
  bot: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><rect x="3" y="11" width="18" height="10" rx="2"/><circle cx="12" cy="5" r="2"/><path d="M12 7v4"/><line x1="8" y1="16" x2="8" y2="16"/><line x1="16" y1="16" x2="16" y2="16"/></svg>`,
  phone: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 8.81 19.79 19.79 0 01.01 2.18 2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>`,
  instagram: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>`,
  youtube: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 00-1.95 1.96A29 29 0 001 12a29 29 0 00.46 5.58A2.78 2.78 0 003.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.4a2.78 2.78 0 001.95-1.96A29 29 0 0023 12a29 29 0 00-.46-5.58z"/><polygon fill="white" points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/></svg>`,
  twitter: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/></svg>`,
  receipt: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><polyline points="6 2 3 6 3 22 21 22 21 6 18 2"/><line x1="15" y1="2" x2="15" y2="6"/><line x1="9" y1="2" x2="9" y2="6"/><rect x="5" y="14" width="14" height="4"/></svg>`,
  messageSquare: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="100%" height="100%"><path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>`
};

function icon(name, cls = '', style = '') {
  const svgStr = ICONS[name] || ICONS.alertCircle;
  // Inject class and style into the SVG
  return svgStr
    .replace('<svg ', `<svg class="${cls}" style="${style}" `)
    .replace(/width="100%" height="100%"/, '');
}

// ── Navbar Initialization ────────────────────────────────────

function initNavbar() {
  const mobileBtn = document.getElementById('mobile-menu-trigger');
  const drawer = document.getElementById('mobile-drawer');
  const mobileMenuIcon = document.getElementById('mobile-menu-icon');

  if (mobileBtn && drawer) {
    mobileBtn.addEventListener('click', () => {
      drawer.classList.toggle('open');
      const isOpen = drawer.classList.contains('open');
      mobileMenuIcon.innerHTML = isOpen ? ICONS.close : ICONS.menu;
    });
  }

  // Language toggle
  document.querySelectorAll('.lang-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      AppState.language = btn.dataset.lang;
      location.reload();
    });
  });

  // Logout buttons
  document.querySelectorAll('.btn-logout').forEach(btn => {
    btn.addEventListener('click', logout);
  });

  // User dropdown
  const userTrigger = document.getElementById('user-menu-trigger');
  const userDropdown = document.getElementById('user-dropdown');
  if (userTrigger && userDropdown) {
    userTrigger.addEventListener('click', (e) => {
      e.stopPropagation();
      userDropdown.classList.toggle('open');
    });
    document.addEventListener('click', () => userDropdown.classList.remove('open'));
  }

  // Lang dropdown
  const langTrigger = document.getElementById('lang-dropdown-trigger');
  const langDropdown = document.getElementById('lang-dropdown');
  if (langTrigger && langDropdown) {
    langTrigger.addEventListener('click', (e) => {
      e.stopPropagation();
      langDropdown.classList.toggle('open');
    });
    document.addEventListener('click', () => langDropdown.classList.remove('open'));
  }
}

// ── Run on DOM ready ─────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  initNavbar();
  initLandingPage();
});

// ── Landing Page Initialization ──────────────────────────────
function initLandingPage() {
  renderPromoCarousel();
  renderCategoryFilters();
  renderGameGrid();
  setupGameSearch();
  startCountdownTimer();
}

function renderPromoCarousel() {
  const container = document.getElementById('promo-slides-container');
  const dotsContainer = document.getElementById('slider-dots-container');
  if (!container) return;

  const promos = [
    {
      title: 'Kejutan Cashback 10% Semua Game!',
      subtitle: 'Gunakan kode CSHBKNEW untuk diskon Rp 25.000 transaksi pertama',
      image: 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800&q=80',
      code: 'CSHBKNEW',
      badge: '🔥 PROMO TERBARU'
    },
    {
      title: 'Diskon Akhir Pekan Hemat s/d 15 Ribu!',
      subtitle: 'Pakai kode WEEKENDGAMER, berlaku Sabtu & Minggu',
      image: 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?w=800&q=80',
      code: 'WEEKENDGAMER',
      badge: '⚡ AKHIR PEKAN'
    },
    {
      title: 'Event Spektakuler Garena Unlimted!',
      subtitle: 'Dapatkan bonus extra untuk setiap top up selama event berlangsung',
      image: 'https://images.unsplash.com/photo-1612287230202-1bf1d85d1bdf?w=800&q=80',
      code: 'GARENA100',
      badge: '💜 GARENA EVENT'
    }
  ];

  container.innerHTML = promos.map((promo, idx) => `
    <div class="promo-slide ${idx === 0 ? 'active' : ''}" id="slide-${idx}" 
         style="background-image:linear-gradient(135deg,rgba(0,0,0,0.55),rgba(0,0,0,0.3)),url('${promo.image}');
                background-size:cover;background-position:center;
                padding:2rem;display:${idx === 0 ? 'flex' : 'none'};flex-direction:column;justify-content:center;height:100%">
      <div style="color:#f1f5f9;display:flex;flex-direction:column;gap:0.75rem">
        <span style="display:inline-flex;align-items:center;gap:0.375rem;padding:0.25rem 0.75rem;border-radius:9999px;background:rgba(255,0,127,0.25);border:1px solid rgba(255,0,127,0.4);font-size:0.625rem;font-weight:900;text-transform:uppercase;letter-spacing:0.05em;color:#ff007f;width:fit-content">${promo.badge}</span>
        <h3 style="font-size:1.25rem;font-weight:900;color:white;margin:0;line-height:1.2">${promo.title}</h3>
        <p style="font-size:0.75rem;color:rgba(255,255,255,0.7);font-weight:600;margin:0">${promo.subtitle}</p>
        <button onclick="window.location.href='/game/mobile-legends'" class="neup-orange-flat" style="padding:0.5rem 1.25rem;font-size:0.75rem;font-weight:900;color:white;border-radius:9999px;cursor:pointer;width:fit-content;margin-top:0.5rem;border:none">Klaim Promo →</button>
      </div>
    </div>
  `).join('');

  dotsContainer.innerHTML = promos.map((_, idx) => `
    <button class="promo-dot ${idx === 0 ? 'active' : ''}" id="dot-${idx}" 
            onclick="goToSlide(${idx})" aria-label="Slide ${idx + 1}"
            style="width:0.75rem;height:0.75rem;border-radius:50%;border:none;cursor:pointer;background:${idx === 0 ? '#ff007f' : '#cbd5e1'};transition:all 0.2s"></button>
  `).join('');
}

function renderCategoryFilters() {
  const container = document.getElementById('category-filters-container');
  if (!container) return;

  const categories = ['Semua', 'Mobile', 'PC', 'Console', 'Populer'];
  container.innerHTML = categories.map(cat => `
    <button class="category-btn ${cat === 'Semua' ? 'active' : ''}" 
            onclick="filterByCategory('${cat}')"
            style="padding:0.625rem 1.125rem;border-radius:0.875rem;border:1px solid #e2e8f0;background:${cat === 'Semua' ? '#ff007f' : 'white'};color:${cat === 'Semua' ? 'white' : '#475569'};font-size:0.75rem;font-weight:700;cursor:pointer;transition:all 0.2s;white-space:nowrap">
      ${cat}
    </button>
  `).join('');
}

function renderGameGrid(games = null) {
  const container = document.getElementById('game-grid');
  if (!container) return;

  const gamesToRender = games || GAMES.slice(0, 8);
  container.innerHTML = gamesToRender.map(game => `
    <div class="game-card neup-flat" onclick="window.location.href='/game/${game.id}'" 
         style="cursor:pointer;border-radius:1.5rem;padding:0.75rem;padding-bottom:1.125rem;transition:all 0.3s ease;border:1px solid rgba(255,255,255,0.5);display:flex;flex-direction:column">
      <div style="position:relative;border-radius:1rem;overflow:hidden;height:9rem;margin-bottom:0.75rem;background:#f1f5f9">
        <img src="${game.thumbnailUrl}" alt="${game.name}" style="width:100%;height:100%;object-fit:cover">
        <button onclick="event.stopPropagation(); toggleFav(this, '${game.id}')" 
                style="position:absolute;top:0.5rem;right:0.5rem;background:#1e293b;border:none;color:#cbd5e1;cursor:pointer;width:1.75rem;height:1.75rem;border-radius:0.5rem;display:flex;align-items:center;justify-content:center;font-size:1rem;transition:all 0.2s"
                title="Tambah ke favorit">♡</button>
      </div>
      <h3 style="font-size:0.75rem;font-weight:700;color:#1e293b;margin:0;line-height:1.25">${game.name}</h3>
      <p style="font-size:0.625rem;color:#64748b;margin:0.25rem 0 0;font-weight:500">⭐ ${game.rating} • ${game.totalSold}</p>
      <div style="margin-top:0.625rem;padding-top:0.625rem;border-top:1px solid rgba(203,213,225,0.3);display:flex;align-items:center;justify-content:space-between">
        <span style="font-size:0.625rem;color:#94a3b8;font-weight:600">Mulai dari</span>
        <span style="font-size:0.75rem;font-weight:900;color:#ff007f">Rp ${game.nominals[0].price.toLocaleString('id-ID')}</span>
      </div>
    </div>
  `).join('');
}

function setupGameSearch() {
  const searchInput = document.getElementById('game-search');
  if (!searchInput) return;

  searchInput.addEventListener('input', (e) => {
    const query = e.target.value.toLowerCase();
    if (!query) {
      renderGameGrid();
      return;
    }
    const filtered = GAMES.filter(g => g.name.toLowerCase().includes(query));
    if (filtered.length === 0) {
      const container = document.getElementById('game-grid');
      if (container) container.innerHTML = '<p style="grid-column:1/-1;text-center;padding:3rem;color:#94a3b8">Game tidak ditemukan</p>';
    } else {
      renderGameGrid(filtered);
    }
  });
}

function goToSlide(idx) {
  const slides = document.querySelectorAll('.promo-slide');
  const dots = document.querySelectorAll('.promo-dot');
  slides.forEach((s, i) => s.style.display = i === idx ? 'flex' : 'none');
  dots.forEach((d, i) => d.style.background = i === idx ? '#ff007f' : '#cbd5e1');
}

function filterByCategory(category) {
  const buttons = document.querySelectorAll('.category-btn');
  buttons.forEach(btn => {
    btn.style.background = btn.textContent.trim() === category ? '#ff007f' : 'white';
    btn.style.color = btn.textContent.trim() === category ? 'white' : '#475569';
  });

  let filtered = GAMES;
  if (category !== 'Semua') {
    filtered = GAMES.filter(g => g.category === category.toLowerCase());
  }
  renderGameGrid(filtered);
}

function toggleFav(btn, gameId) {
  btn.textContent = btn.textContent === '♡' ? '♥' : '♡';
  btn.style.color = btn.textContent === '♥' ? '#f43f5e' : '#cbd5e1';
}

function startCountdownTimer() {
  const hoursEl = document.getElementById('cd-hours');
  const minutesEl = document.getElementById('cd-minutes');
  const secondsEl = document.getElementById('cd-seconds');
  
  if (!hoursEl || !minutesEl || !secondsEl) return;

  setInterval(() => {
    let h = parseInt(hoursEl.textContent);
    let m = parseInt(minutesEl.textContent);
    let s = parseInt(secondsEl.textContent);

    if (s > 0) s--;
    else if (m > 0) { m--; s = 59; }
    else if (h > 0) { h--; m = 59; s = 59; }
    else { h = 23; m = 59; s = 59; }

    hoursEl.textContent = String(h).padStart(2, '0');
    minutesEl.textContent = String(m).padStart(2, '0');
    secondsEl.textContent = String(s).padStart(2, '0');
  }, 1000);
}
