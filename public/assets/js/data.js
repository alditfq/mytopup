// ============================================================
// GameTopup Marketplace — Application Data
// Converted from src/data.ts (TypeScript) → plain JavaScript
// ============================================================

const GAMES = [
  {
    id: 'mobile-legends',
    name: 'Mobile Legends: Bang Bang',
    category: 'mobile',
    thumbnailUrl: 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=400&q=85',
    bannerUrl: 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=1200&q=85',
    rating: 4.8,
    totalSold: '1.2M+',
    developer: 'Moonton',
    idLabel: 'User ID',
    zoneIdLabel: 'Zone ID',
    idHelperText: 'Untuk menemukan User ID Anda, ketuk siluet profil di pojok kiri atas game. User ID berupa angka (contoh: 12345678) dan Zone ID di dalam tanda kurung (contoh: 1234).',
    cashbackPercent: 5,
    hasDiscount: true,
    nominals: [
      { id: 'ml-3', name: '3 Diamonds', price: 1500, discountPrice: 1200 },
      { id: 'ml-12', name: '12 Diamonds', price: 4500, discountPrice: 4000 },
      { id: 'ml-50', name: '50 Diamonds', price: 16500, discountPrice: 15000, isBestSeller: true },
      { id: 'ml-86', name: '86 Diamonds', price: 25000, discountPrice: 22000 },
      { id: 'ml-172', name: '172 Diamonds', price: 50000, discountPrice: 45000 },
      { id: 'ml-257', name: '257 Diamonds', price: 75000, discountPrice: 68000 },
      { id: 'ml-706', name: '706 Diamonds', price: 200000, discountPrice: 185000, isBestSeller: true },
      { id: 'ml-wp', name: 'Weekly Diamond Pass', price: 30000, discountPrice: 27500, isBestSeller: true }
    ]
  },
  {
    id: 'free-fire',
    name: 'Free Fire',
    category: 'mobile',
    thumbnailUrl: 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?w=400&q=85',
    bannerUrl: 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?w=1200&q=85',
    rating: 4.7,
    totalSold: '950K+',
    developer: 'Garena',
    idLabel: 'Player ID',
    idHelperText: 'Untuk menemukan Player ID Anda, ketuk avatar karakter di pojok kiri atas lobi utama game. ID tertera di bawah nickname Anda (contoh: 987654321).',
    cashbackPercent: 3,
    hasDiscount: true,
    nominals: [
      { id: 'ff-5', name: '5 Diamonds', price: 1000 },
      { id: 'ff-50', name: '50 Diamonds', price: 10000, discountPrice: 8500 },
      { id: 'ff-70', name: '70 Diamonds', price: 13000, discountPrice: 11000, isBestSeller: true },
      { id: 'ff-140', name: '140 Diamonds', price: 25000, discountPrice: 21500 },
      { id: 'ff-355', name: '355 Diamonds', price: 60000, discountPrice: 51000, isBestSeller: true },
      { id: 'ff-720', name: '720 Diamonds', price: 120000, discountPrice: 103000 },
      { id: 'ff-m', name: 'Premium Membership bulanan', price: 150000, discountPrice: 139000 }
    ]
  },
  {
    id: 'pubg-mobile',
    name: 'PUBG Mobile',
    category: 'popular',
    thumbnailUrl: 'https://images.unsplash.com/photo-1511512578047-dfb367046420?w=400&q=85',
    bannerUrl: 'https://images.unsplash.com/photo-1511512578047-dfb367046420?w=1200&q=85',
    rating: 4.9,
    totalSold: '880K+',
    developer: 'Tencent Games',
    idLabel: 'Character ID',
    idHelperText: 'Buka profil Anda di kiri atas game. Cari nomor ID karakter yang tercantum di samping nama profil Anda (contoh: 5123456789).',
    cashbackPercent: 4,
    hasDiscount: false,
    nominals: [
      { id: 'pubg-60', name: '60 UC', price: 15000, isBestSeller: true },
      { id: 'pubg-325', name: '325 UC', price: 75000, discountPrice: 72000 },
      { id: 'pubg-660', name: '660 UC', price: 150000, discountPrice: 142000, isBestSeller: true },
      { id: 'pubg-1800', name: '1800 UC', price: 375000, discountPrice: 355000 },
      { id: 'pubg-3850', name: '3850 UC', price: 750000, discountPrice: 710000 }
    ]
  },
  {
    id: 'valorant',
    name: 'Valorant Points',
    category: 'pc',
    thumbnailUrl: 'https://images.unsplash.com/photo-1612287230202-1bf1d85d1bdf?w=400&q=85',
    bannerUrl: 'https://images.unsplash.com/photo-1612287230202-1bf1d85d1bdf?w=1200&q=85',
    rating: 4.9,
    totalSold: '540K+',
    developer: 'Riot Games',
    idLabel: 'Riot ID',
    idHelperText: 'Masukkan Riot ID Anda lengkap dengan tagline (contoh: Radiant#ID1 atau Jett#SEA). Anda dapat melihat Riot ID Anda di launcher Riot Client.',
    cashbackPercent: 5,
    hasDiscount: true,
    nominals: [
      { id: 'val-375', name: '375 VP', price: 45000, discountPrice: 42000 },
      { id: 'val-650', name: '650 VP', price: 75000, discountPrice: 71000 },
      { id: 'val-1350', name: '1350 VP', price: 150000, discountPrice: 139000, isBestSeller: true },
      { id: 'val-2100', name: '2100 VP', price: 220000, discountPrice: 205000 },
      { id: 'val-4000', name: '4000 VP', price: 400000, discountPrice: 375000, isBestSeller: true }
    ]
  },
  {
    id: 'genshin-impact',
    name: 'Genshin Impact',
    category: 'pc',
    thumbnailUrl: 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?w=400&q=85',
    bannerUrl: 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?w=1200&q=85',
    rating: 4.8,
    totalSold: '620K+',
    developer: 'COGNOSPHERE',
    idLabel: 'UID',
    zoneIdLabel: 'Server',
    idHelperText: 'UID tertera di pojok kanan bawah screen game Anda (contoh: 812345678). Pilih Server yang sesuai dengan karakter Anda.',
    cashbackPercent: 2,
    hasDiscount: false,
    nominals: [
      { id: 'gi-blessing', name: 'Blessing of the Welkin Moon', price: 79000, isBestSeller: true },
      { id: 'gi-60', name: '60 Genesis Crystals', price: 15000 },
      { id: 'gi-300', name: '300 + 30 Genesis Crystals', price: 75000, isBestSeller: true },
      { id: 'gi-980', name: '980 + 110 Genesis Crystals', price: 230000 },
      { id: 'gi-1980', name: '1980 + 260 Genesis Crystals', price: 475000 },
      { id: 'gi-3280', name: '3280 + 600 Genesis Crystals', price: 790000 }
    ]
  },
  {
    id: 'honor-of-kings',
    name: 'Honor of Kings',
    category: 'popular',
    thumbnailUrl: 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=400&q=85',
    bannerUrl: 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=1200&q=85',
    rating: 4.7,
    totalSold: '300K+',
    developer: 'Level Infinite',
    idLabel: 'User ID',
    idHelperText: 'Masuk ke dalam game, klik avatar Anda di sudut kiri atas, dan buka tab pengaturan untuk menemukan ID User Anda di bagian bawah.',
    cashbackPercent: 5,
    hasDiscount: true,
    nominals: [
      { id: 'hok-17', name: '17 Tokens', price: 5000, discountPrice: 4200 },
      { id: 'hok-88', name: '88 Tokens', price: 25000, discountPrice: 21000, isBestSeller: true },
      { id: 'hok-257', name: '257 Tokens', price: 75000, discountPrice: 65000 },
      { id: 'hok-432', name: '432 Tokens', price: 120000, discountPrice: 104000 },
      { id: 'hok-864', name: '864 Tokens', price: 240000, discountPrice: 208000, isBestSeller: true }
    ]
  },
  {
    id: 'steam-gift-card',
    name: 'Steam Wallet Voucher (IDR)',
    category: 'voucher',
    thumbnailUrl: 'https://images.unsplash.com/photo-1614680376593-902f74fa0d41?w=400&q=85',
    bannerUrl: 'https://images.unsplash.com/photo-1614680376593-902f74fa0d41?w=1200&q=85',
    rating: 4.9,
    totalSold: '400K+',
    developer: 'Valve Corporation',
    idLabel: 'WhatsApp / Email',
    idHelperText: 'Masukkan nomor WhatsApp atau Email aktif untuk mengirimkan kode voucher fisik.',
    cashbackPercent: 3,
    hasDiscount: true,
    nominals: [
      { id: 'steam-12k', name: 'Rp 12,000 Wallet Code', price: 15000, discountPrice: 14500 },
      { id: 'steam-45k', name: 'Rp 45,000 Wallet Code', price: 55000, discountPrice: 52000 },
      { id: 'steam-60k', name: 'Rp 60,000 Wallet Code', price: 72000, discountPrice: 68500 },
      { id: 'steam-90k', name: 'Rp 90,000 Wallet Code', price: 108000, discountPrice: 101000, isBestSeller: true },
      { id: 'steam-120k', name: 'Rp 120,000 Wallet Code', price: 144000, discountPrice: 135000, isBestSeller: true }
    ]
  },
  {
    id: 'google-play-card',
    name: 'Google Play Gift Card (IDR)',
    category: 'voucher',
    thumbnailUrl: 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=400&q=85',
    bannerUrl: 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=1200&q=85',
    rating: 4.7,
    totalSold: '310K+',
    developer: 'Google LLC',
    idLabel: 'No Handphone',
    idHelperText: 'Masukkan nomor Whatsapp aktif untuk menerima serial PIN Google Play Voucher.',
    cashbackPercent: 2,
    hasDiscount: false,
    nominals: [
      { id: 'gp-20k', name: 'Voucher Google Play Rp 20,000', price: 23000 },
      { id: 'gp-50k', name: 'Voucher Google Play Rp 50,000', price: 56000, isBestSeller: true },
      { id: 'gp-100k', name: 'Voucher Google Play Rp 100,000', price: 112000 },
      { id: 'gp-150k', name: 'Voucher Google Play Rp 150,000', price: 167000 }
    ]
  }
];

const PAYMENT_METHODS = [
  {
    id: 'dana',
    name: 'DANA',
    group: 'e-wallet',
    fee: 0,
    accountNumber: '0812-3456-7890',
    instructions: [
      'Buka aplikasi DANA di smartphone Anda.',
      'Pilih menu Kirim atau Scan QR.',
      'Konfirmasi detail tagihan yang muncul.',
      'Masukkan PIN DANA Anda untuk mengonfirmasi transaksi.',
      'Selesai! Status pembayaran akan otomatis diperbarui.'
    ]
  },
  {
    id: 'ovo',
    name: 'OVO',
    group: 'e-wallet',
    fee: 200,
    accountNumber: '0812-3456-7890',
    instructions: [
      'Pastikan Anda memiliki aplikasi OVO dengan saldo mencukupi.',
      'Anda akan menerima notifikasi Push Payment di handphone Anda.',
      'Buka notifikasi tersebut atau buka aplikasi OVO Anda.',
      'Setujui transaksi pembayaran dan masukkan PIN OVO.',
      'Pembayaran Anda akan terdeteksi otomatis.'
    ]
  },
  {
    id: 'qris',
    name: 'QRIS (All E-Wallet)',
    group: 'qris',
    fee: 0,
    instructions: [
      'Simpan gambar QR Code yang ditampilkan.',
      'Buka e-wallet pilihan Anda (Gopay, OVO, Dana, LinkAja, ShopeePay) atau Mobile Banking.',
      'Pilih opsi "Scan QR" / "Unggah Gambar QR dari Galeri".',
      'Lakukan pembayaran sesuai total nominal.',
      'Pembayaran Anda diverifikasi secara otomatis dalam beberapa detik.'
    ]
  },
  {
    id: 'bca-va',
    name: 'BCA Virtual Account',
    group: 'bank',
    fee: 1000,
    accountNumber: '1280081234567890',
    instructions: [
      'Buka m-BCA dari handphone Anda atau kunjungi ATM BCA terdekat.',
      'Pilih Transfer, lalu masuk ke Transfer ke Virtual Account.',
      'Masukkan nomor Virtual Account BCA yang ditunjukkan pada tagihan.',
      'Konfirmasi jumlah transfer sesuai invoice.',
      'Masukkan PIN transaksi m-BCA atau ATM Anda untuk memproses.'
    ]
  },
  {
    id: 'mandiri-va',
    name: 'Mandiri Virtual Account',
    group: 'bank',
    fee: 1000,
    accountNumber: '8902281234567890',
    instructions: [
      "Log in ke aplikasi Mandiri Livin' atau gunakan mesin ATM Mandiri.",
      'Pilih Pembayaran / Bayar Baru, lalu pilih Multi Payment.',
      'Masukkan kode instansi penyedia jasa atau cari virtual bank.',
      'Masukkan nomor Virtual Account Mandiri Anda.',
      'Periksa nominal pembayaran dan setujui transaksi.'
    ]
  }
];

const PROMOS = [
  {
    id: 'promo-1',
    title: 'Kejutan Cashback 10% Semua Game!',
    image: 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800&q=80',
    code: 'CSHBKNEW',
    description: 'Dapatkan cashback langsung hingga Rp 25.000 untuk transaksi pertama menggunakan kode promo ini.',
    discountAmount: 25000,
    minTransaction: 30000
  },
  {
    id: 'promo-2',
    title: 'Diskon Akhir Pekan Hemat s/d 15 Ribu!',
    image: 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?w=800&q=80',
    code: 'WEEKENDGAMER',
    description: 'Nikmati potongan harga langsung Rp 15.000 khusus transaksi di hari Sabtu & Minggu.',
    discountAmount: 15000,
    minTransaction: 50000
  },
  {
    id: 'promo-3',
    title: 'Event Spektakuler Garena Unlimted!',
    image: 'https://images.unsplash.com/photo-1612287230202-1bf1d85d1bdf?w=800&q=80',
    code: 'GARENASPEKTA',
    description: 'Potongan harga Rp 10.000 untuk pengisian Free Fire & Call of Duty Mobile.',
    discountAmount: 10000,
    minTransaction: 20000
  }
];

const FAQS = [
  {
    category: 'general',
    question: 'Bagaimana cara melakukan top up di GameTopup?',
    answer: 'Cukup pilih game yang ingin Anda top up, masukkan User ID & Zone ID (jika ada), pilih jumlah nominal item yang diinginkan, pilih metode pembayaran, masukkan kode voucher promo jika ada, dan klik Beli Sekarang. Lakukan pembayaran sesuai petunjuk pembayaran.'
  },
  {
    category: 'general',
    question: 'Berapa lama proses pengisian diamond/item game?',
    answer: 'Hampir seluruh transaksi kami diselesaikan secara otomatis dalam waktu 1-3 menit setelah pembayaran Anda berhasil didepositkan. Jika ada antrian server game, proses terkadang dapat membutuhkan waktu hingga 15 menit.'
  },
  {
    category: 'payment',
    question: 'Metode pembayaran apa saja yang didukung?',
    answer: 'Kami mendukung berbagai pilihan metode pembayaran instan populer di Indonesia, meliputi E-Wallet (DANA, OVO, ShopeePay), QRIS Kode Standar nasional Indonesia, dan Virtual Account Transfer bank utama (BCA, Mandiri, BNI, BRI).'
  },
  {
    category: 'payment',
    question: 'Apakah ada tambahan biaya admin?',
    answer: 'Kami menerapkan biaya admin yang transparan dan sangat minim. Untuk QRIS gratis biaya admin, OVO dikenakan Rp 200, dan Transfer Virtual Account bank dikenakan Rp 1.000 per transaksi.'
  },
  {
    category: 'refund',
    question: 'Dapatkah saya membatalkan atau me-refund transaksi?',
    answer: 'Transaksi game top-up bersifat final dan langsung diproses secara otomatis setelah pembayaran terdeteksi. Silakan periksa kembali kecocokan User ID dan server Anda sebelum membuat pesanan, karena transaksi yang salah kirim akibat kesalahan input User ID tidak dapat dikembalikan atau di-refund.'
  }
];

const TESTIMONIALS = [
  {
    name: 'Rian Hidayat',
    rating: 5,
    game: 'Mobile Legends',
    text: 'Baru pertama kali coba top up weekly pass di sini, kaget langsung masuk dalam waktu kurang dari 30 detik! Murah banget lagi dibanding lapak sebelah.',
    avatar: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100&h=100&fit=crop&q=80'
  },
  {
    name: 'Siti Sarah',
    rating: 5,
    game: 'Valorant',
    text: 'Sangat recommended buat beli Valorant Points. CS ramah dan ketika ada kendala saat sistem maintain, langsung ditangani di live chat dengan sigap. Bintang 5 pokoknya.',
    avatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop&q=80'
  },
  {
    name: 'Kevin Wijaya',
    rating: 5,
    game: 'Genshin Impact',
    text: 'Top up Blessing dapet diskon gila pake kode WEEKENDGAMER. Terpercaya, terbukti aman anti minus crystals. Sukses terus!',
    avatar: 'https://images.unsplash.com/photo-1599566150163-29194dcaad36?w=100&h=100&fit=crop&q=80'
  }
];

// ── Initial data ──
const INITIAL_TRANSACTIONS = [
  {
    invoice: 'INV-20260520-FF91',
    gameId: 'free-fire',
    gameName: 'Free Fire',
    gameIcon: 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?w=400&q=85',
    userId: '928532918',
    nickname: 'GarenaSlayer',
    nominalName: '140 Diamonds',
    nominalPrice: 25000,
    discountApplied: 3500,
    paymentMethod: PAYMENT_METHODS[0],
    totalPayment: 21500,
    status: 'success',
    createdAt: '2026-05-20T14:22:00Z',
    statusLogs: [
      { time: '14:22', message: 'Invoice berhasil dibuat, menunggu pembayaran di DANA.' },
      { time: '14:23', message: 'Pembayaran diterima oleh sistem.' },
      { time: '14:23', message: 'Diamonds sedang dikirimkan ke Player ID 928532918.' },
      { time: '14:24', message: 'Transaksi berhasil diselesaikan.' }
    ]
  },
  {
    invoice: 'INV-20260523-ML32',
    gameId: 'mobile-legends',
    gameName: 'Mobile Legends: Bang Bang',
    gameIcon: 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=400&q=85',
    userId: '88162534',
    zoneId: '2105',
    nickname: 'ProSavage99',
    nominalName: '86 Diamonds',
    nominalPrice: 25000,
    discountApplied: 3000,
    paymentMethod: PAYMENT_METHODS[2],
    totalPayment: 22000,
    status: 'success',
    createdAt: '2026-05-23T09:15:00Z',
    statusLogs: [
      { time: '09:15', message: 'Pesanan dibuat. QR Code diunduh.' },
      { time: '09:16', message: 'Pembayaran diselesaikan via bca-mobile.' },
      { time: '09:17', message: 'Pengisian diamonds sukses diselesaikan.' }
    ]
  }
];

const DEFAULT_USER = {
  username: 'Alex Cahya',
  email: 'alex.cahya@gmail.com',
  phone: '081234567890',
  avatarUrl: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&h=150&fit=crop&q=80',
  balance: 145000,
  cashbackSaved: 15500,
  totalTransactions: 2,
  favoriteGames: ['mobile-legends', 'free-fire']
};

const SAMPLE_AVATARS = [
  'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&h=150&fit=crop&q=80',
  'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&h=150&fit=crop&q=80',
  'https://images.unsplash.com/photo-1599566150163-29194dcaad36?w=150&h=150&fit=crop&q=80',
  'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop&q=80',
  'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=150&h=150&fit=crop&q=80'
];
