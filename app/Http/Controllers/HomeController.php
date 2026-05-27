<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Promo;
use App\Models\Setting;
use App\Models\GameAccount;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $games = Game::with('nominals')->get();
        // Only load active promos on the homepage
        $promos = Promo::where('is_active', true)->get();

        // System configurations
        $shopName = Setting::getVal('shop_name', 'GameTopup');
        $logoUrl = Setting::getVal('logo_url', '');
        
        // Flash sale configurations
        $flashSaleShow = Setting::getVal('flash_sale_show', 'true');
        $flashSaleEnd  = Setting::getVal('flash_sale_end', '');
        $flashSaleTitle = Setting::getVal('flash_sale_title', 'Sabet Diskon Game Terpopuler Akhir Pekan');
        $flashSaleDescription = Setting::getVal('flash_sale_description', 'Diamond, token, dan Welkin Moon ready diskon kilat, instan terkirim secara otomatis.');
        $flashSaleSlug = Setting::getVal('flash_sale_slug', 'mobile-legends');
        $flashSaleButtonText = Setting::getVal('flash_sale_button_text', 'Cek Flash Sale MLBB');

        // Load featured accounts for homepage bonus
        $featuredAccounts = GameAccount::with('game')
            ->where('status', 'available')
            ->where('featured', true)
            ->take(4)
            ->get();

        return view('home', compact(
            'games', 
            'promos', 
            'shopName', 
            'logoUrl', 
            'flashSaleShow',
            'flashSaleEnd', 
            'flashSaleTitle',
            'flashSaleDescription',
            'flashSaleSlug',
            'flashSaleButtonText',
            'featuredAccounts'
        ));
    }

    public function support()
    {
        return view('support');
    }

    public function accountsIndex(Request $request)
    {
        $query = GameAccount::with('game')->where('status', 'available');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('game', function ($g) use ($search) {
                      $g->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Game filter
        if ($request->filled('game_id') && $request->game_id !== 'all') {
            $query->where('game_id', $request->game_id);
        }

        // Rank filter
        if ($request->filled('rank') && $request->rank !== 'all') {
            $query->where('rank', $request->rank);
        }

        // Price Sort
        if ($request->filled('sort')) {
            if ($request->sort === 'price_asc') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort === 'price_desc') {
                $query->orderBy('price', 'desc');
            } else {
                $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $accounts = $query->get();
        $games = Game::whereHas('gameAccounts', function($q) {
            $q->where('status', 'available');
        })->get();
        
        // Get all unique ranks for filter select
        $ranks = GameAccount::where('status', 'available')->distinct()->pluck('rank')->toArray();

        return view('accounts', compact('accounts', 'games', 'ranks'));
    }

    public function accountDetail($slug)
    {
        $account = GameAccount::with('game')->where('slug', $slug)->firstOrFail();
        $paymentMethods = PaymentMethod::all();
        $promos = Promo::where('is_active', true)->get();

        return view('account_detail', compact('account', 'paymentMethods', 'promos'));
    }
}
