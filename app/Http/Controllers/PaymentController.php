<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Review;
use App\Models\User;
use App\Models\GameAccount;
use App\Services\FulfillmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $fulfillmentService;

    public function __construct(FulfillmentService $fulfillmentService)
    {
        $this->fulfillmentService = $fulfillmentService;
    }
    public function waiting($invoice)
    {
        $transaction = Transaction::with(['game', 'paymentMethod', 'nominal', 'gameAccount'])
            ->where('invoice', $invoice)
            ->firstOrFail();

        if ($transaction->status === 'success' || ($transaction->game_account_id && in_array($transaction->status, ['waiting_delivery', 'delivered']))) {
            return redirect()->route('payment.success', $transaction->invoice);
        }

        // Check ownership if user_id is set
        if ($transaction->user_id) {
            if (!Auth::check() || (Auth::id() !== $transaction->user_id && Auth::user()->role !== 'admin')) {
                abort(403, 'Unauthorized action.');
            }
        }

        return view('waiting', compact('transaction'));
    }

    public function confirmPaid($invoice)
    {
        $transaction = Transaction::with(['game', 'paymentMethod', 'nominal', 'gameAccount'])
            ->where('invoice', $invoice)
            ->firstOrFail();

        // Check ownership if user_id is set
        if ($transaction->user_id) {
            if (!Auth::check() || (Auth::id() !== $transaction->user_id && Auth::user()->role !== 'admin')) {
                abort(403, 'Unauthorized action.');
            }
        }

        if ($transaction->status === 'pending') {
            if ($transaction->game_account_id) {
                $transaction->status = 'waiting_delivery';

                // Mark game account as sold
                $account = GameAccount::find($transaction->game_account_id);
                if ($account) {
                    $account->status = 'sold';
                    $account->save();
                }

                // Add simulated log entries
                $logs = $transaction->status_logs;
                $logs[] = [
                    'time' => date('H:i'),
                    'message' => 'Pembayaran sebesar Rp ' . number_format($transaction->total_payment, 0, ',', '.') . ' diterima oleh sistem.'
                ];
                $logs[] = [
                    'time' => date('H:i', strtotime('+1 minute')),
                    'message' => 'Akun game berhasil dialokasikan dan dikunci untuk Anda.'
                ];
                $logs[] = [
                    'time' => date('H:i', strtotime('+1 minute')),
                    'message' => 'Menunggu Admin mengirimkan kredensial login via email tujuan Anda (' . $transaction->target_id . ').'
                ];
                $transaction->status_logs = $logs;
            } else {
                $transaction->status = 'success';

                // Add simulated log entries
                $logs = $transaction->status_logs;
                $logs[] = [
                    'time' => date('H:i'),
                    'message' => 'Pembayaran sebesar Rp ' . number_format($transaction->total_payment, 0, ',', '.') . ' diterima oleh sistem.'
                ];
                $logs[] = [
                    'time' => date('H:i', strtotime('+1 minute')),
                    'message' => 'Sistem sedang memproses pesanan Anda untuk ID ' . $transaction->target_id . '.'
                ];
                $logs[] = [
                    'time' => date('H:i', strtotime('+1 minute')),
                    'message' => 'Item ' . $transaction->nominal_name . ' berhasil dikirimkan ke ID ' . $transaction->target_id . '.'
                ];
                $logs[] = [
                    'time' => date('H:i', strtotime('+1 minute')),
                    'message' => 'Transaksi sukses diselesaikan.'
                ];
                $transaction->status_logs = $logs;
            }
            $transaction->save();

            // Reward cashback to user if authenticated
            if ($transaction->user_id) {
                $user = User::find($transaction->user_id);
                if ($user) {
                    $cashbackPercent = $transaction->game->cashback_percent;
                    $cashbackAmount = intval(($transaction->nominal_price * $cashbackPercent) / 100);

                    $user->cashback_saved += $cashbackAmount;
                    $user->save();
                }
            }

            // Send topup success email for non-account orders
            if (!$transaction->game_account_id && $transaction->status === 'success') {
                $transaction->load(['game', 'nominal', 'paymentMethod', 'user']);
                $this->fulfillmentService->sendTopupSuccessEmail($transaction);
            }
        }

        return redirect()->route('payment.success', $transaction->invoice)->with('success', 'Pembayaran berhasil disimulasikan!');
    }

    public function checkStatus($invoice)
    {
        $transaction = Transaction::where('invoice', $invoice)->firstOrFail();
        return response()->json([
            'status' => $transaction->status
        ]);
    }

    public function success($invoice)
    {
        $transaction = Transaction::with(['game', 'paymentMethod', 'nominal', 'gameAccount'])
            ->where('invoice', $invoice)
            ->firstOrFail();

        // Check transaction status
        if ($transaction->game_account_id) {
            if (!in_array($transaction->status, ['waiting_delivery', 'delivered'])) {
                return redirect()->route('payment.waiting', $transaction->invoice);
            }
        } else {
            if ($transaction->status !== 'success') {
                return redirect()->route('payment.waiting', $transaction->invoice);
            }
        }

        // Check ownership if user_id is set
        if ($transaction->user_id) {
            if (!Auth::check() || (Auth::id() !== $transaction->user_id && Auth::user()->role !== 'admin')) {
                abort(403, 'Unauthorized action.');
            }
        }

        $alreadyReviewed = Review::where('transaction_id', $transaction->id)->exists();

        // Get 4 recommended games dynamically
        $recommendedGames = \App\Models\Game::orderBy('total_sold', 'desc')->take(4)->get();

        return view('success', compact('transaction', 'alreadyReviewed', 'recommendedGames'));
    }
}

