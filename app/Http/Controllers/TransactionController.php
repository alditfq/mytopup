<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Nominal;
use App\Models\PaymentMethod;
use App\Models\Promo;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'target_id' => 'required|string',
            'zone_id' => 'nullable|string',
            'nominal_id' => 'required|exists:nominals,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'promo_code' => 'nullable|string'
        ]);

        $game = Game::findOrFail($request->game_id);
        $nominal = Nominal::findOrFail($request->nominal_id);
        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

        // Calculate base price
        $price = $nominal->discount_price ?? $nominal->price;

        // Apply promo if any
        $discountApplied = 0;
        if ($request->promo_code) {
            $promo = Promo::where('code', strtoupper($request->promo_code))->first();
            if ($promo && $price >= $promo->min_transaction) {
                $discountApplied = $promo->discount_amount;
            }
        }

        // Apply payment method fee
        $fee = $paymentMethod->fee;
        $totalPayment = ($price - $discountApplied) + $fee;
        if ($totalPayment < 0) $totalPayment = 0;

        // Generate Cool Gamer Nickname for checkout simulation
        $gamerTags = ['Pro', 'Slayer', 'Gamer', 'Legend', 'Master', 'Champion', 'Shadow', 'Knight', 'King', 'Raptor', 'Phoenix', 'Ninja'];
        $randomTag = $gamerTags[array_rand($gamerTags)] . rand(10, 99);
        $nickname = Auth::check() ? Auth::user()->name : $randomTag;

        // Generate Invoice Number
        $gameAbbr = strtoupper(substr(str_replace('-', '', $game->slug), 0, 2));
        $invoice = 'INV-' . date('Ymd') . '-' . $gameAbbr . rand(10, 99);

        // Generate simulated VA or QRIS
        $vaNumber = null;
        $qrCodeUrl = null;
        if ($paymentMethod->group === 'bank') {
            $vaNumber = $paymentMethod->account_number ? $paymentMethod->account_number : '8800' . rand(100000, 999999) . rand(100000, 999999);
        } elseif ($paymentMethod->group === 'qris') {
            $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . $invoice;
        }

        // Setup status logs
        $statusLogs = [
            [
                'time' => date('H:i'),
                'message' => 'Invoice berhasil dibuat, menunggu pembayaran via ' . $paymentMethod->name . '.'
            ]
        ];

        // Create transaction
        $transaction = Transaction::create([
            'invoice' => $invoice,
            'game_id' => $game->id,
            'user_id' => Auth::id(),
            'nickname' => $nickname,
            'target_id' => $request->target_id,
            'zone_id' => $request->zone_id,
            'nominal_id' => $nominal->id,
            'nominal_name' => $nominal->name,
            'nominal_price' => $price,
            'discount_applied' => $discountApplied,
            'payment_method_id' => $paymentMethod->id,
            'total_payment' => $totalPayment,
            'status' => 'pending',
            'status_logs' => $statusLogs,
            'qr_code_url' => $qrCodeUrl,
            'va_number' => $vaNumber
        ]);

        return redirect()->route('payment.waiting', $transaction->invoice);
    }

    public function statusPage()
    {
        return view('status');
    }

    public function search(Request $request)
    {
        $request->validate([
            'invoice' => 'required|string'
        ]);

        $transaction = Transaction::with(['game', 'paymentMethod', 'nominal'])
            ->where('invoice', trim($request->invoice))
            ->first();

        if (!$transaction) {
            return back()->withErrors([
                'invoice' => 'Nomor invoice tidak ditemukan.'
            ])->withInput();
        }

        return view('status', compact('transaction'));
    }
}
