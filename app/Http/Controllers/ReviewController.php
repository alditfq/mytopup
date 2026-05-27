<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $invoice)
    {
        $request->validate([
            'rating'        => 'required|integer|min:1|max:5',
            'message'       => 'nullable|string|max:500',
            'reviewer_name' => 'required|string|max:100',
        ]);

        $transaction = Transaction::with('game')
            ->where('invoice', $invoice)
            ->where('status', 'success')
            ->firstOrFail();

        // Prevent duplicate reviews for the same transaction
        if (Review::where('transaction_id', $transaction->id)->exists()) {
            return back()->with('review_error', 'Anda sudah memberikan ulasan untuk transaksi ini.');
        }

        Review::create([
            'transaction_id' => $transaction->id,
            'user_id'        => Auth::id(),
            'game_id'        => $transaction->game_id,
            'reviewer_name'  => $request->reviewer_name,
            'rating'         => $request->rating,
            'message'        => $request->message,
            'is_promoted'    => false,
        ]);

        // Recalculate average rating for the game and update
        $averageRating = Review::where('game_id', $transaction->game_id)->avg('rating');
        if ($averageRating) {
            $game = $transaction->game;
            $game->rating = round($averageRating, 1);
            $game->save();
        }

        return back()->with('review_success', 'Terima kasih! Ulasan Anda telah berhasil dikirim. 🎉');
    }
}
