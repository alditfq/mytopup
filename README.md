<div align="center">

# 🎮 MyTopup
### Marketplace Top-Up Game Modern — Production-Ready Laravel Application

[![PHP](https://img.shields.io/badge/PHP-8.2-purple?logo=php)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-12-red?logo=laravel)](https://laravel.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-blue?logo=mysql)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)

> **MyTopup** adalah platform marketplace top-up game yang lengkap, aman, dan scalable. Mendukung top-up diamonds, UC, Robux, dan ratusan game lainnya — plus marketplace akun game premium dengan sistem pengiriman berbasis email admin.

</div>

---

## ✨ Fitur Utama

| Kategori | Fitur |
|---|---|
| 🛒 **Marketplace** | Katalog game, nominal top-up, akun game premium |
| 💳 **Pembayaran** | QRIS, e-wallet, transfer bank, dengan simulasi konfirmasi |
| 📧 **Email** | Notifikasi topup sukses & pengiriman kredensial akun otomatis |
| 🎁 **Promo** | Kode voucher diskon dengan batas pemakaian & tanggal kedaluwarsa |
| ⭐ **Ulasan** | Review transaksi dengan rating bintang |
| 🔐 **Auth** | Register, login, profil user dengan update password |
| 👑 **Admin Panel** | Dashboard analytics, manajemen game/nominal/akun/promo/user |
| 📊 **Analytics** | Chart pendapatan 30 hari, volume transaksi, game terlaris |
| 🛡️ **Security** | Admin suspension, proteksi double-checkout, custom error pages |

---

## 🏗️ Arsitektur Sistem

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php          # Login, register, logout
│   │   ├── HomeController.php          # Halaman beranda, katalog akun
│   │   ├── TransactionController.php   # Checkout, detail transaksi
│   │   ├── PaymentController.php       # Konfirmasi bayar, halaman sukses
│   │   ├── DashboardController.php     # Dashboard user, profil
│   │   └── AdminController.php         # Full CRUD admin panel
│   ├── Requests/                       # Form Requests (validation layer)
│   │   ├── LoginRequest.php
│   │   ├── RegisterRequest.php
│   │   ├── CheckoutRequest.php
│   │   ├── StoreGameRequest.php
│   │   ├── UpdateGameRequest.php
│   │   ├── StoreAccountRequest.php
│   │   ├── UpdateAccountRequest.php
│   │   └── DeliverAccountRequest.php
│   └── Middleware/
│       ├── AdminMiddleware.php          # Auth + suspension check
│       └── MaintenanceMiddleware.php
├── Services/                           # Business Logic Layer
│   ├── PromoService.php                # Validasi & aplikasi kode promo
│   ├── TransactionService.php          # Checkout logic + anti-spam lock
│   └── FulfillmentService.php          # Email delivery (topup + akun)
├── Mail/
│   ├── TopupSuccessMail.php            # Notifikasi top-up berhasil
│   └── AccountDeliveryMail.php         # Pengiriman kredensial akun
└── Models/
    ├── User.php
    ├── Game.php
    ├── Nominal.php
    ├── Transaction.php                  # + delivered_at, delivered_by
    ├── GameAccount.php
    ├── PaymentMethod.php
    ├── Promo.php
    ├── Review.php
    └── Setting.php
```

---

## 🗄️ Skema Database (ERD)

```
users
├── id, name, email, password, phone
├── role (user|admin), is_suspended
└── cashback_saved

games
├── id, slug, name, category, developer
├── thumbnail_url, banner_url
├── id_label, zone_id_label, id_helper_text
├── cashback_percent, has_discount

nominals
├── id, game_id (FK), item_id, name
├── price, discount_price
└── is_best_seller, tag

game_accounts
├── id, game_id (FK), slug, title, description
├── rank, level, skin_count
├── login_method, bind_status
├── price, images (JSON), account_data (encrypted)
├── status (available|sold), featured

transactions
├── id, invoice (unique), user_id (FK nullable)
├── game_id (FK), nominal_id (FK), game_account_id (FK nullable)
├── payment_method_id (FK)
├── target_id, zone_id, nickname, server
├── nominal_name, nominal_price, original_price
├── discount_amount, promo_code_used, total_payment
├── status (pending|success|failed|waiting_delivery|delivered)
├── status_logs (JSON)
├── delivered_at, delivered_by     ← (system: email delivery tracking)
└── timestamps

payment_methods
├── id, slug, name, group (qris|e-wallet|bank)
├── fee, account_number
├── instructions (JSON), image

promos
├── id, title, code (unique), description, image
├── discount_type (nominal|percent), discount_amount
├── min_transaction, max_uses, uses_count
├── expiry_date, is_active, claim_url

reviews
├── id, transaction_id (FK), user_id (FK)
├── rating (1-5), comment
└── timestamps

settings
└── id, key (unique), value
```

---

## 🔄 Flow Transaksi

### Topup Diamond / UC / dll
```
User pilih game → pilih nominal → input ID game
→ pilih payment method → checkout
→ halaman waiting (konfirmasi bayar)
→ [Simulasi] klik "Sudah Bayar"
→ status = success → email konfirmasi dikirim
→ halaman success
```

### Pembelian Akun Game
```
User pilih akun game → checkout
→ halaman waiting
→ [Simulasi] klik "Sudah Bayar"
→ status = waiting_delivery → akun dikunci
→ Admin membuka detail transaksi
→ Admin isi kredensial (email + password + notes)
→ klik "Kirim Akun" → FulfillmentService mengirim email
→ status = delivered → email kredensial dikirim ke buyer
```

---

## 🚀 Instalasi & Setup

### Prerequisites
- PHP 8.2+
- MySQL 8.0+
- Composer
- XAMPP / Laragon (local)
- Git

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/alditfq/mytopup.git
cd mytopup

# 2. Install dependencies
composer install

# 3. Salin file environment
cp .env.example .env
php artisan key:generate

# 4. Konfigurasi .env
APP_NAME=MyTopup
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=store_laravel
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=log          # log untuk dev | smtp untuk production

# 5. Jalankan migrasi & seeder
php artisan migrate
php artisan db:seed       # (jika ada seeder)

# 6. Jalankan server
php artisan serve
```

### Konfigurasi Email (Production)

Untuk production, ganti `MAIL_MAILER=log` menjadi:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your@gmail.com
MAIL_FROM_NAME="MyTopup"
```

> **Note:** Di mode `log`, semua email ditulis ke `storage/logs/laravel.log`

---

## 👤 Akun Default

| Role | Email | Password |
|---|---|---|
| Admin | admin@demo.com | password |
| User | user@demo.com | password |

> **Note:** Sesuaikan dengan seeder yang ada. Untuk keamanan production, ubah password default setelah instalasi.

---

## 🛡️ Fitur Keamanan

- **Admin Middleware**: Blokir akun admin yang di-suspend (auto logout)
- **Form Requests**: Validasi input terpusat di semua endpoint
- **Anti Double Checkout**: Lock 10 detik per user di `TransactionService`
- **Price Integrity**: Harga diambil dari database, bukan dari input user
- **CSRF Protection**: Semua form dilindungi Laravel CSRF
- **Custom Error Pages**: 403, 404, 500 bertema dark gaming UI

---

## 📧 Sistem Email

| Mail Class | Trigger | Penerima |
|---|---|---|
| `TopupSuccessMail` | Payment confirmed (non-akun) | User buyer |
| `AccountDeliveryMail` | Admin klik "Kirim Akun" | User buyer |

Template email: `resources/views/emails/`

---

## 🎨 Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | PHP 8.2, Laravel 12 |
| Frontend | Blade Templates, Vanilla CSS, Vanilla JS |
| Database | MySQL |
| Icons | Lucide Icons |
| Fonts | Google Fonts (Outfit, Inter) |
| Mail | Laravel Mail (log/SMTP) |

---

## 📁 Struktur Direktori Penting

```
public/
├── assets/
│   ├── css/style.css           # Stylesheet utama (custom design system)
│   ├── js/script.js            # Frontend JavaScript
│   └── img/                    # Static assets
└── uploads/
    ├── games/                  # Thumbnail & banner game
    ├── accounts/               # Gambar akun game
    ├── payments/               # Logo metode pembayaran
    ├── promos/                 # Banner promo
    └── settings/               # Logo toko

resources/views/
├── layouts/
│   ├── app.blade.php           # Layout utama (SEO + meta tags)
│   └── admin.blade.php         # Layout admin panel
├── partials/                   # Komponen reusable (navbar, footer)
├── emails/                     # Template email responsif
├── errors/                     # Halaman error kustom (403, 404, 500)
└── admin/                      # View admin panel
```

---

## 🧪 Testing

```bash
# Jalankan semua test
php artisan test

# Clear semua cache
php artisan view:clear
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

---

## 📸 Screenshots

### User Interface
- **Homepage**: Katalog game dengan promo carousel
- **Game Detail**: Pilih nominal & checkout
- **Payment Waiting**: Simulasi pembayaran
- **Dashboard User**: Riwayat transaksi & profil

### Admin Panel
- **Dashboard**: Analytics & revenue chart
- **Manajemen Game**: CRUD game & nominal
- **Transaksi**: Detail & delivery akun game
- **Promo**: Kelola kode voucher

---

## 📋 Panduan Demo / Presentasi

### Skenario 1: Top-up Diamond
1. Buka beranda → pilih game (Mobile Legends)
2. Pilih nominal → input User ID → pilih payment
3. Checkout → klik "Sudah Bayar" di halaman waiting
4. Lihat email di `storage/logs/laravel.log` → cek `TopupSuccessMail`

### Skenario 2: Beli Akun Game
1. Buka menu Akun Game → pilih akun
2. Checkout → konfirmasi bayar → status `waiting_delivery`
3. Login sebagai Admin → buka Transaksi → klik detail
4. Isi form "Kirim Akun" (email + password)
5. Cek log email untuk `AccountDeliveryMail`

### Skenario 3: Admin Panel
1. Login `/admin` → lihat dashboard (revenue chart, recent transactions)
2. Kelola game, nominal, promo, metode pembayaran
3. Suspend user → coba login user → lihat blokir

---

## 🤝 Contributing

Kontribusi sangat diterima! Silakan:
1. Fork repository ini
2. Buat branch fitur baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

Lihat [CONTRIBUTING.md](CONTRIBUTING.md) untuk panduan lengkap.

---

## 📝 License

Project ini menggunakan lisensi MIT. Lihat file [LICENSE](LICENSE) untuk detail.

---

## 📞 Contact & Support

- **Repository**: [github.com/alditfq/mytopup](https://github.com/alditfq/mytopup)
- **Issues**: [GitHub Issues](https://github.com/alditfq/mytopup/issues)
- **Discussions**: [GitHub Discussions](https://github.com/alditfq/mytopup/discussions)

---

<div align="center">

### Made with ❤️ using **Laravel 12** & **PHP 8.2**

⭐ Star this repo if you find it helpful!

</div>
