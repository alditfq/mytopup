<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Nominal;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\GameAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TransactionService
{
    protected $promoService;

    public function __construct(PromoService $promoService)
    {
        $this->promoService = $promoService;
    }

    /**
     * Process a checkout order.
     *
     * @param array $data
     * @param int|null $userId
     * @return Transaction
     * @throws \Exception
     */
    public function processCheckout(array $data, ?int $userId): Transaction
    {
        $gameId = $data['game_id'];
        $paymentMethodId = $data['payment_method_id'];
        $gameAccountId = $data['game_account_id'] ?? null;
        $nominalId = $data['nominal_id'] ?? null;
        $targetId = $data['target_id'] ?? null;
        $zoneId = $gameAccountId ? null : ($data['zone_id'] ?? null);
        $promoCode = $data['promo_code'] ?? null;

        $game = Game::findOrFail($gameId);
        $paymentMethod = PaymentMethod::findOrFail($paymentMethodId);

        // Define exact target_id string for duplicate check
        $finalTargetId = $targetId;

        // 1. Prevent Duplicate Checkout Exploit (10 seconds lock)
        if ($userId) {
            $duplicateExists = Transaction::where('user_id', $userId)
                ->where('game_id', $gameId)
                ->where('target_id', $finalTargetId)
                ->where('status', 'pending')
                ->where('created_at', '>=', now()->subSeconds(10))
                ->exists();

            if ($duplicateExists) {
                throw ValidationException::withMessages([
                    'checkout' => 'Permintaan checkout ganda terdeteksi. Harap tunggu beberapa detik sebelum mengirimkan pesanan kembali.'
                ]);
            }
        }

        // 2. Fetch secure prices directly from DB (exploit prevention)
        if ($gameAccountId) {
            $account = GameAccount::findOrFail($gameAccountId);
            if ($account->status !== 'available') {
                throw new \Exception('Akun game ini sudah tidak tersedia.');
            }
            $price = $account->price;
            $nominal = null;
        } else {
            $nominal = Nominal::findOrFail($nominalId);
            $price = $nominal->discount_price ?? $nominal->price;
        }

        // Secure positive price check
        if ($price < 0) {
            throw new \Exception('Harga produk tidak valid.');
        }

        // 3. Process promo code dynamically using PromoService
        $discountApplied = 0;
        $appliedPromo = null;
        if ($promoCode) {
            $promo = $this->promoService->validatePromo($promoCode, $price);
            if ($promo) {
                $discountApplied = $promo->discount_amount;
                $appliedPromo = $promo;
            }
        }

        // 4. Calculate secure totals (exploit prevention: no negative payments)
        $fee = $paymentMethod->fee;
        if ($fee < 0) $fee = 0;

        $totalPayment = ($price - $discountApplied) + $fee;
        if ($totalPayment < 0) {
            $totalPayment = 0;
        }

        // 5. Generate metadata & payment credentials
        $gamerTags = ['Pro', 'Slayer', 'Gamer', 'Legend', 'Master', 'Champion', 'Shadow', 'Knight', 'King', 'Raptor', 'Phoenix', 'Ninja'];
        $randomTag = $gamerTags[array_rand($gamerTags)] . rand(10, 99);
        $nickname = Auth::check() ? Auth::user()->name : $randomTag;

        $gameAbbr = strtoupper(substr(str_replace('-', '', $game->slug), 0, 2));
        $invoice = 'INV-' . date('Ymd') . '-' . $gameAbbr . rand(100, 999);

        // Generate simulated VA or QRIS
        $vaNumber = null;
        $qrCodeUrl = null;
        if ($paymentMethod->group === 'bank') {
            $vaNumber = $paymentMethod->account_number ? $paymentMethod->account_number : '8800' . rand(100000, 999999) . rand(100000, 999999);
        } elseif ($paymentMethod->group === 'qris') {
            $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . $invoice;
        }

        $statusLogs = [
            [
                'time' => date('H:i'),
                'message' => 'Invoice berhasil dibuat, menunggu pembayaran via ' . $paymentMethod->name . '.'
            ]
        ];

        // 6. Save new transaction securely
        $transaction = Transaction::create([
            'invoice' => $invoice,
            'game_id' => $game->id,
            'user_id' => $userId,
            'nickname' => $nickname,
            'target_id' => $finalTargetId,
            'zone_id' => $gameAccountId ? null : $zoneId,
            'nominal_id' => $gameAccountId ? null : $nominal->id,
            'game_account_id' => $gameAccountId ? $account->id : null,
            'nominal_name' => $gameAccountId ? ('Pembelian Akun: ' . $account->title) : $nominal->name,
            'nominal_price' => $price,
            'discount_applied' => $discountApplied,
            'payment_method_id' => $paymentMethod->id,
            'total_payment' => $totalPayment,
            'status' => 'pending',
            'status_logs' => $statusLogs,
            'qr_code_url' => $qrCodeUrl,
            'va_number' => $vaNumber
        ]);

        if ($appliedPromo) {
            $appliedPromo->increment('uses_count');
        }

        return $transaction;
    }
}
