<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\PaymentMethod;
use App\Models\Promo;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function show($slug)
    {
        $game = Game::with('nominals')->where('slug', $slug)->firstOrFail();
        $paymentMethods = PaymentMethod::all();
        $promos = Promo::all();

        return view('detail', compact('game', 'paymentMethods', 'promos'));
    }
}
