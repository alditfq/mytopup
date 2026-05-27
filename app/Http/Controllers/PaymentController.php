<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function waiting($invoice)
    {
        $transaction = Transaction::with(['game', 'paymentMethod', 'nominal'])
            ->where('invoice', $invoice)
            ->firstOrFail();

        if ($transaction->status === 'success') {
            return redirect()->route('payment.success', $transaction->invoice);
        }

        return view('waiting', compact('transaction'));
    }

    public function confirmPaid($invoice)
    {
        $transaction = Transaction::with(['game', 'paymentMethod', 'nominal'])
            ->where('invoice', $invoice)
            ->firstOrFail();

        if ($transaction->status === 'pending') {
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
        $transaction = Transaction::with(['game', 'paymentMethod', 'nominal'])
            ->where('invoice', $invoice)
            ->firstOrFail();

        $alreadyReviewed = Review::where('transaction_id', $transaction->id)->exists();

        return view('success', compact('transaction', 'alreadyReviewed'));
    }
}

