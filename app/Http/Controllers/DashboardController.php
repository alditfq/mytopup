<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $transactions = Transaction::with(['game', 'nominal', 'paymentMethod'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $popularGames = \App\Models\Game::orderBy('rating', 'desc')->take(3)->get();

        return view('dashboard', compact('user', 'transactions', 'popularGames'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;

        if ($request->new_password) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors([
                    'current_password' => 'Password saat ini salah.'
                ]);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('dashboard')->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
