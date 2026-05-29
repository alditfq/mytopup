<?php

namespace App\Services;

use App\Models\Transaction;
use App\Mail\AccountDeliveryMail;
use App\Mail\TopupSuccessMail;
use Illuminate\Support\Facades\Mail;

class FulfillmentService
{
    /**
     * Deliver game account credentials to buyer.
     *
     * @param Transaction $tx
     * @param string $email
     * @param string $password
     * @param string|null $notes
     * @param string $adminName
     * @return Transaction
     */
    public function deliverAccount(Transaction $tx, string $email, string $password, ?string $notes, string $adminName): Transaction
    {
        $recipientEmail = $tx->target_id;
        $buyerName = $tx->user ? $tx->user->name : $tx->nickname;

        Mail::to($recipientEmail)->send(new AccountDeliveryMail(
            $tx->invoice,
            $buyerName,
            $tx->game->name,
            $tx->gameAccount->title,
            $email,
            $password,
            $notes
        ));

        $tx->status = 'delivered';
        $tx->delivered_at = now();
        $tx->delivered_by = $adminName;

        $logs = $tx->status_logs;
        $logs[] = [
            'time' => date('H:i'),
            'message' => 'Admin secara manual mengirimkan detail kredensial akun via email ke ' . $recipientEmail . ' (DELIVERED).'
        ];
        $tx->status_logs = $logs;
        
        $tx->save();

        return $tx;
    }

    /**
     * Send topup success confirmation email.
     *
     * @param Transaction $tx
     * @return void
     */
    public function sendTopupSuccessEmail(Transaction $tx): void
    {
        if ($tx->user && $tx->user->email) {
            try {
                Mail::to($tx->user->email)->send(new TopupSuccessMail($tx));
            } catch (\Exception $e) {
                // Log the exception locally but do not interrupt the transaction success flow
                logger()->error('Failed sending topup email for transaction ' . $tx->invoice . ': ' . $e->getMessage());
            }
        }
    }
}
