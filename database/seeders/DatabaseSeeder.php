<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Game;
use App\Models\Nominal;
use App\Models\PaymentMethod;
use App\Models\Promo;
use App\Models\Transaction;
use App\Models\GameAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users
        $user = User::create([
            'name' => 'Demo User',
            'email' => 'user@demo.com',
            'phone' => '081234567890',
            'balance' => 0,
            'cashback_saved' => 0,
            'role' => 'user',
            'password' => Hash::make('password'),
        ]);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@demo.com',
            'phone' => '081111111111',
            'balance' => 0,
            'cashback_saved' => 0,
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        // 1.5 Seed system configurations
        \App\Models\Setting::create(['key' => 'shop_name', 'value' => 'MyTopup']);
        \App\Models\Setting::create(['key' => 'logo_url', 'value' => '']);
        \App\Models\Setting::create(['key' => 'marquee_text', 'value' => '']);
        \App\Models\Setting::create(['key' => 'flash_sale_end', 'value' => date('Y-m-d H:i:s', strtotime('+2 days'))]);
        \App\Models\Setting::create(['key' => 'is_maintenance', 'value' => 'false']);
        \App\Models\Setting::create(['key' => 'marquee_active', 'value' => 'false']);

        // 2. Seed Games and Nominals
        $gamesData = [
            [
                'slug' => 'mobile-legends',
                'name' => 'Mobile Legends: Bang Bang',
                'category' => 'mobile',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=400&q=85',
                'banner_url' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=1200&q=85',
                'rating' => 4.8,
                'total_sold' => '1.2M+',
                'developer' => 'Moonton',
                'id_label' => 'User ID',
                'zone_id_label' => 'Zone ID',
                'id_helper_text' => 'Untuk menemukan User ID Anda, ketuk siluet profil di pojok kiri atas game. User ID berupa angka (contoh: 12345678) dan Zone ID di dalam tanda kurung (contoh: 1234).',
                'cashback_percent' => 5,
                'has_discount' => true,
                'nominals' => [
                    ['item_id' => 'ml-3', 'name' => '3 Diamonds', 'price' => 1500, 'discount_price' => 1200],
                    ['item_id' => 'ml-12', 'name' => '12 Diamonds', 'price' => 4500, 'discount_price' => 4000],
                    ['item_id' => 'ml-50', 'name' => '50 Diamonds', 'price' => 16500, 'discount_price' => 15000, 'is_best_seller' => true],
                    ['item_id' => 'ml-86', 'name' => '86 Diamonds', 'price' => 25000, 'discount_price' => 22000],
                    ['item_id' => 'ml-172', 'name' => '172 Diamonds', 'price' => 50000, 'discount_price' => 45000],
                    ['item_id' => 'ml-257', 'name' => '257 Diamonds', 'price' => 75000, 'discount_price' => 68000],
                    ['item_id' => 'ml-706', 'name' => '706 Diamonds', 'price' => 200000, 'discount_price' => 185000, 'is_best_seller' => true],
                    ['item_id' => 'ml-wp', 'name' => 'Weekly Diamond Pass', 'price' => 30000, 'discount_price' => 27500, 'is_best_seller' => true]
                ]
            ],
            [
                'slug' => 'free-fire',
                'name' => 'Free Fire',
                'category' => 'mobile',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?w=400&q=85',
                'banner_url' => 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?w=1200&q=85',
                'rating' => 4.7,
                'total_sold' => '950K+',
                'developer' => 'Garena',
                'id_label' => 'Player ID',
                'zone_id_label' => null,
                'id_helper_text' => 'Untuk menemukan Player ID Anda, ketuk avatar karakter di pojok kiri atas lobi utama game. ID tertera di bawah nickname Anda (contoh: 987654321).',
                'cashback_percent' => 3,
                'has_discount' => true,
                'nominals' => [
                    ['item_id' => 'ff-5', 'name' => '5 Diamonds', 'price' => 1000, 'discount_price' => null],
                    ['item_id' => 'ff-50', 'name' => '50 Diamonds', 'price' => 10000, 'discount_price' => 8500],
                    ['item_id' => 'ff-70', 'name' => '70 Diamonds', 'price' => 13000, 'discount_price' => 11000, 'is_best_seller' => true],
                    ['item_id' => 'ff-140', 'name' => '140 Diamonds', 'price' => 25000, 'discount_price' => 21500],
                    ['item_id' => 'ff-355', 'name' => '355 Diamonds', 'price' => 60000, 'discount_price' => 51000, 'is_best_seller' => true],
                    ['item_id' => 'ff-720', 'name' => '720 Diamonds', 'price' => 120000, 'discount_price' => 103000],
                    ['item_id' => 'ff-m', 'name' => 'Premium Membership bulanan', 'price' => 150000, 'discount_price' => 139000]
                ]
            ],
            [
                'slug' => 'pubg-mobile',
                'name' => 'PUBG Mobile',
                'category' => 'popular',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?w=400&q=85',
                'banner_url' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?w=1200&q=85',
                'rating' => 4.9,
                'total_sold' => '880K+',
                'developer' => 'Tencent Games',
                'id_label' => 'Character ID',
                'zone_id_label' => null,
                'id_helper_text' => 'Buka profil Anda di kiri atas game. Cari nomor ID karakter yang tercantum di samping nama profil Anda (contoh: 5123456789).',
                'cashback_percent' => 4,
                'has_discount' => false,
                'nominals' => [
                    ['item_id' => 'pubg-60', 'name' => '60 UC', 'price' => 15000, 'discount_price' => null, 'is_best_seller' => true],
                    ['item_id' => 'pubg-325', 'name' => '325 UC', 'price' => 75000, 'discount_price' => 72000],
                    ['item_id' => 'pubg-660', 'name' => '660 UC', 'price' => 150000, 'discount_price' => 142000, 'is_best_seller' => true],
                    ['item_id' => 'pubg-1800', 'name' => '1800 UC', 'price' => 375000, 'discount_price' => 355000],
                    ['item_id' => 'pubg-3850', 'name' => '3850 UC', 'price' => 750000, 'discount_price' => 710000]
                ]
            ],
            [
                'slug' => 'valorant',
                'name' => 'Valorant Points',
                'category' => 'pc',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1612287230202-1bf1d85d1bdf?w=400&q=85',
                'banner_url' => 'https://images.unsplash.com/photo-1612287230202-1bf1d85d1bdf?w=1200&q=85',
                'rating' => 4.9,
                'total_sold' => '540K+',
                'developer' => 'Riot Games',
                'id_label' => 'Riot ID',
                'zone_id_label' => null,
                'id_helper_text' => 'Masukkan Riot ID Anda lengkap dengan tagline (contoh: Radiant#ID1 atau Jett#SEA). Anda dapat melihat Riot ID Anda di launcher Riot Client.',
                'cashback_percent' => 5,
                'has_discount' => true,
                'nominals' => [
                    ['item_id' => 'val-375', 'name' => '375 VP', 'price' => 45000, 'discount_price' => 42000],
                    ['item_id' => 'val-650', 'name' => '650 VP', 'price' => 75000, 'discount_price' => 71000],
                    ['item_id' => 'val-1350', 'name' => '1350 VP', 'price' => 150000, 'discount_price' => 139000, 'is_best_seller' => true],
                    ['item_id' => 'val-2100', 'name' => '2100 VP', 'price' => 220000, 'discount_price' => 205000],
                    ['item_id' => 'val-4000', 'name' => '4000 VP', 'price' => 400000, 'discount_price' => 375000, 'is_best_seller' => true]
                ]
            ],
            [
                'slug' => 'genshin-impact',
                'name' => 'Genshin Impact',
                'category' => 'pc',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?w=400&q=85',
                'banner_url' => 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?w=1200&q=85',
                'rating' => 4.8,
                'total_sold' => '620K+',
                'developer' => 'COGNOSPHERE',
                'id_label' => 'UID',
                'zone_id_label' => 'Server',
                'id_helper_text' => 'UID tertera di pojok kanan bawah screen game Anda (contoh: 812345678). Pilih Server yang sesuai dengan karakter Anda.',
                'cashback_percent' => 2,
                'has_discount' => false,
                'nominals' => [
                    ['item_id' => 'gi-blessing', 'name' => 'Blessing of the Welkin Moon', 'price' => 79000, 'discount_price' => null, 'is_best_seller' => true],
                    ['item_id' => 'gi-60', 'name' => '60 Genesis Crystals', 'price' => 15000, 'discount_price' => null],
                    ['item_id' => 'gi-300', 'name' => '300 + 30 Genesis Crystals', 'price' => 75000, 'discount_price' => null, 'is_best_seller' => true],
                    ['item_id' => 'gi-980', 'name' => '980 + 110 Genesis Crystals', 'price' => 230000, 'discount_price' => null],
                    ['item_id' => 'gi-1980', 'name' => '1980 + 260 Genesis Crystals', 'price' => 475000, 'discount_price' => null],
                    ['item_id' => 'gi-3280', 'name' => '3280 + 600 Genesis Crystals', 'price' => 790000, 'discount_price' => null]
                ]
            ],
            [
                'slug' => 'honor-of-kings',
                'name' => 'Honor of Kings',
                'category' => 'popular',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=400&q=85',
                'banner_url' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=1200&q=85',
                'rating' => 4.7,
                'total_sold' => '300K+',
                'developer' => 'Level Infinite',
                'id_label' => 'User ID',
                'zone_id_label' => null,
                'id_helper_text' => 'Masuk ke dalam game, klik avatar Anda di sudut kiri atas, dan buka tab pengaturan untuk menemukan ID User Anda di bagian bawah.',
                'cashback_percent' => 5,
                'has_discount' => true,
                'nominals' => [
                    ['item_id' => 'hok-17', 'name' => '17 Tokens', 'price' => 5000, 'discount_price' => 4200],
                    ['item_id' => 'hok-88', 'name' => '88 Tokens', 'price' => 25000, 'discount_price' => 21000, 'is_best_seller' => true],
                    ['item_id' => 'hok-257', 'name' => '257 Tokens', 'price' => 75000, 'discount_price' => 65000],
                    ['item_id' => 'hok-432', 'name' => '432 Tokens', 'price' => 120000, 'discount_price' => 104000],
                    ['item_id' => 'hok-864', 'name' => '864 Tokens', 'price' => 240000, 'discount_price' => 208000, 'is_best_seller' => true]
                ]
            ],
            [
                'slug' => 'steam-gift-card',
                'name' => 'Steam Wallet Voucher (IDR)',
                'category' => 'voucher',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1614680376593-902f74fa0d41?w=400&q=85',
                'banner_url' => 'https://images.unsplash.com/photo-1614680376593-902f74fa0d41?w=1200&q=85',
                'rating' => 4.9,
                'total_sold' => '400K+',
                'developer' => 'Valve Corporation',
                'id_label' => 'WhatsApp / Email',
                'zone_id_label' => null,
                'id_helper_text' => 'Masukkan nomor WhatsApp atau Email aktif untuk mengirimkan kode voucher fisik.',
                'cashback_percent' => 3,
                'has_discount' => true,
                'nominals' => [
                    ['item_id' => 'steam-12k', 'name' => 'Rp 12,000 Wallet Code', 'price' => 15000, 'discount_price' => 14500],
                    ['item_id' => 'steam-45k', 'name' => 'Rp 45,000 Wallet Code', 'price' => 55000, 'discount_price' => 52000],
                    ['item_id' => 'steam-60k', 'name' => 'Rp 60,000 Wallet Code', 'price' => 72000, 'discount_price' => 68500],
                    ['item_id' => 'steam-90k', 'name' => 'Rp 90,000 Wallet Code', 'price' => 108000, 'discount_price' => 101000, 'is_best_seller' => true],
                    ['item_id' => 'steam-120k', 'name' => 'Rp 120,000 Wallet Code', 'price' => 144000, 'discount_price' => 135000, 'is_best_seller' => true]
                ]
            ],
            [
                'slug' => 'google-play-card',
                'name' => 'Google Play Gift Card (IDR)',
                'category' => 'voucher',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=400&q=85',
                'banner_url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=1200&q=85',
                'rating' => 4.7,
                'total_sold' => '310K+',
                'developer' => 'Google LLC',
                'id_label' => 'No Handphone',
                'zone_id_label' => null,
                'id_helper_text' => 'Masukkan nomor Whatsapp aktif untuk menerima serial PIN Google Play Voucher.',
                'cashback_percent' => 2,
                'has_discount' => false,
                'nominals' => [
                    ['item_id' => 'gp-20k', 'name' => 'Voucher Google Play Rp 20,000', 'price' => 23000, 'discount_price' => null],
                    ['item_id' => 'gp-50k', 'name' => 'Voucher Google Play Rp 50,000', 'price' => 56000, 'discount_price' => null, 'is_best_seller' => true],
                    ['item_id' => 'gp-100k', 'name' => 'Voucher Google Play Rp 100,000', 'price' => 112000, 'discount_price' => null],
                    ['item_id' => 'gp-150k', 'name' => 'Voucher Google Play Rp 150,000', 'price' => 167000, 'discount_price' => null]
                ]
            ]
        ];

        foreach ($gamesData as $gData) {
            $nominals = $gData['nominals'];
            unset($gData['nominals']);

            $game = Game::create($gData);

            foreach ($nominals as $nData) {
                $nData['game_id'] = $game->id;
                $nData['is_best_seller'] = $nData['is_best_seller'] ?? false;
                Nominal::create($nData);
            }
        }

        // 3. Seed Payment Methods
        $paymentMethodsData = [
            [
                'slug' => 'dana',
                'name' => 'DANA',
                'group' => 'e-wallet',
                'fee' => 0,
                'account_number' => '0812-3456-7890',
                'image' => 'https://images.unsplash.com/photo-1579621970795-87facc2f976d?w=100&h=100&fit=crop&q=80',
                'instructions' => [
                    'Buka aplikasi DANA di smartphone Anda.',
                    'Pilih menu Kirim atau Scan QR.',
                    'Konfirmasi detail tagihan yang muncul.',
                    'Masukkan PIN DANA Anda untuk mengonfirmasi transaksi.',
                    'Selesai! Status pembayaran akan otomatis diperbarui.'
                ]
            ],
            [
                'slug' => 'ovo',
                'name' => 'OVO',
                'group' => 'e-wallet',
                'fee' => 200,
                'account_number' => '0812-3456-7890',
                'image' => 'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?w=100&h=100&fit=crop&q=80',
                'instructions' => [
                    'Pastikan Anda memiliki aplikasi OVO dengan saldo mencukupi.',
                    'Anda akan menerima notifikasi Push Payment di handphone Anda.',
                    'Buka notifikasi tersebut atau buka aplikasi OVO Anda.',
                    'Setujui transaksi pembayaran dan masukkan PIN OVO.',
                    'Pembayaran Anda akan terdeteksi otomatis.'
                ]
            ],
            [
                'slug' => 'qris',
                'name' => 'QRIS (All E-Wallet)',
                'group' => 'qris',
                'fee' => 0,
                'account_number' => null,
                'image' => 'https://images.unsplash.com/photo-1601597111158-2fceff292cdc?w=100&h=100&fit=crop&q=80',
                'instructions' => [
                    'Simpan gambar QR Code yang ditampilkan.',
                    'Buka e-wallet pilihan Anda (Gopay, OVO, Dana, LinkAja, ShopeePay) atau Mobile Banking.',
                    'Pilih opsi "Scan QR" / "Unggah Gambar QR dari Galeri".',
                    'Lakukan pembayaran sesuai total nominal.',
                    'Pembayaran Anda diverifikasi secara otomatis dalam beberapa detik.'
                ]
            ],
            [
                'slug' => 'bca-va',
                'name' => 'BCA Virtual Account',
                'group' => 'bank',
                'fee' => 1000,
                'account_number' => '1280081234567890',
                'image' => 'https://images.unsplash.com/photo-1621416894569-0f39ed31d247?w=100&h=100&fit=crop&q=80',
                'instructions' => [
                    'Buka m-BCA dari handphone Anda atau kunjungi ATM BCA terdekat.',
                    'Pilih Transfer, lalu masuk ke Transfer ke Virtual Account.',
                    'Masukkan nomor Virtual Account BCA yang ditunjukkan pada tagihan.',
                    'Konfirmasi jumlah transfer sesuai invoice.',
                    'Masukkan PIN transaksi m-BCA atau ATM Anda untuk memproses.'
                ]
            ],
            [
                'slug' => 'mandiri-va',
                'name' => 'Mandiri Virtual Account',
                'group' => 'bank',
                'fee' => 1000,
                'account_number' => '8902281234567890',
                'image' => 'https://images.unsplash.com/photo-1563013544-824ae1d704d3?w=100&h=100&fit=crop&q=80',
                'instructions' => [
                    "Log in ke aplikasi Mandiri Livin' atau gunakan mesin ATM Mandiri.",
                    'Pilih Pembayaran / Bayar Baru, lalu pilih Multi Payment.',
                    'Masukkan kode instansi penyedia jasa atau cari virtual bank.',
                    'Masukkan nomor Virtual Account Mandiri Anda.',
                    'Periksa nominal pembayaran dan setujui transaksi.'
                ]
            ]
        ];

        foreach ($paymentMethodsData as $pm) {
            PaymentMethod::create($pm);
        }

        // 4. Seed Promos
        $promosData = [
            [
                'title' => 'Promo Spesial Top Up Game',
                'image' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800&q=80',
                'code' => 'WELCOME10',
                'description' => 'Dapatkan diskon untuk transaksi pertama Anda.',
                'discount_amount' => 10000,
                'min_transaction' => 50000,
                'discount_type' => 'nominal',
                'expiry_date' => date('Y-m-d H:i:s', strtotime('+30 days')),
                'max_uses' => 100,
                'uses_count' => 0,
                'is_active' => true,
                'claim_url' => '/game/mobile-legends'
            ]
        ];

        foreach ($promosData as $promo) {
            Promo::create($promo);
        }

        // 7. Seed Transactions - Empty for clean production demo

        // 8. Seed Game Accounts
        $ml = Game::where('slug', 'mobile-legends')->first();
        $pubg = Game::where('slug', 'pubg-mobile')->first();

        if ($ml) {
            GameAccount::create([
                'game_id' => $ml->id,
                'title' => 'Mobile Legends Mythical Glory - 150+ Skins',
                'slug' => 'mobile-legends-mythical-glory-150-skins',
                'description' => "Akun Mobile Legends premium:\n- Rank: Mythical Glory\n- 150+ Skins (Epic, Special, Elite)\n- All Heroes Unlocked\n- Emblem Level Max\n- Win Rate 65%+\n- Login aman via Moonton ID",
                'rank' => 'Mythical Glory',
                'level' => 75,
                'skin_count' => 150,
                'login_method' => 'Moonton ID',
                'bind_status' => 'Clean Unbind',
                'price' => 650000,
                'images' => [
                    'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800&q=80',
                    'https://images.unsplash.com/photo-1511512578047-dfb367046420?w=800&q=80'
                ],
                'account_data' => encrypt("Email: account@example.com\nPassword: SecurePass123\nRecovery: recovery@example.com"),
                'status' => 'available',
                'featured' => true
            ]);
        }

        if ($pubg) {
            GameAccount::create([
                'game_id' => $pubg->id,
                'title' => 'PUBG Mobile Conqueror - Premium Skins',
                'slug' => 'pubg-mobile-conqueror-premium-skins',
                'description' => "Akun PUBG Mobile premium:\n- Rank: Conqueror\n- M416 Glacier Skin\n- Pharaoh Set\n- 80+ Weapon Skins\n- Level 80+\n- Login via Facebook",
                'rank' => 'Conqueror',
                'level' => 82,
                'skin_count' => 85,
                'login_method' => 'Facebook',
                'bind_status' => 'Single Bind',
                'price' => 950000,
                'images' => [
                    'https://images.unsplash.com/photo-1511512578047-dfb367046420?w=800&q=80',
                    'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?w=800&q=80'
                ],
                'account_data' => encrypt("Facebook: account@example.com\nPassword: SecurePass123\n2FA Code: XXXX-XXXX-XXXX"),
                'status' => 'available',
                'featured' => true
            ]);
        }
    }
}
