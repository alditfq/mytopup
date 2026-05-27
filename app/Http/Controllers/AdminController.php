<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Nominal;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Promo;
use App\Models\Review;
use App\Models\GameAccount;
use App\Services\FulfillmentService;
use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Requests\DeliverAccountRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountDeliveryMail;

class AdminController extends Controller
{
    protected $fulfillmentService;

    public function __construct(FulfillmentService $fulfillmentService)
    {
        $this->fulfillmentService = $fulfillmentService;
    }
    public function index()
    {
        $totalRevenue        = Transaction::whereIn('status', ['success', 'delivered'])->sum('total_payment');
        $totalTransactions   = Transaction::count();
        $totalUsers          = User::count();
        $pendingTransactions = Transaction::whereIn('status', ['pending', 'waiting_delivery'])->count();
        $successTransactions = Transaction::whereIn('status', ['success', 'delivered'])->count();
        $failedTransactions  = Transaction::where('status', 'failed')->count();

        $transactions = Transaction::with(['game', 'nominal', 'paymentMethod'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $popularGames = Transaction::selectRaw('game_id, COUNT(*) as sales_count, SUM(total_payment) as revenue')
            ->whereIn('status', ['success', 'delivered'])
            ->groupBy('game_id')
            ->with('game')
            ->orderBy('sales_count', 'desc')
            ->take(5)
            ->get();

        // --- Build 7-day real chart data ---
        $chartLabels   = [];
        $chartRevenue  = [];
        $chartTxVolume = [];

        for ($i = 6; $i >= 0; $i--) {
            $date  = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->locale('id')->isoFormat('ddd D/M');

            $rev = Transaction::whereIn('status', ['success', 'delivered'])
                ->whereDate('created_at', $date)
                ->sum('total_payment');

            $vol = Transaction::whereDate('created_at', $date)->count();

            $chartLabels[]   = $label;
            $chartRevenue[]  = (int) $rev;
            $chartTxVolume[] = (int) $vol;
        }

        return view('admin.index', compact(
            'totalRevenue',
            'totalTransactions',
            'totalUsers',
            'pendingTransactions',
            'successTransactions',
            'failedTransactions',
            'transactions',
            'popularGames',
            'chartLabels',
            'chartRevenue',
            'chartTxVolume'
        ));
    }

    public function transactions(Request $request)
    {
        $query = Transaction::query()->with(['game', 'nominal', 'paymentMethod']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice', 'like', "%{$search}%")
                  ->orWhere('target_id', 'like', "%{$search}%")
                  ->orWhere('zone_id', 'like', "%{$search}%")
                  ->orWhere('nickname', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('game_id') && $request->game_id !== 'all') {
            $query->where('game_id', $request->game_id);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);
        $games = Game::all();

        return view('admin.transactions', compact('transactions', 'games'));
    }

    public function updateTransactionStatus(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        // Tentukan status yang diizinkan berdasarkan tipe transaksi
        if ($transaction->game_account_id) {
            // Pembelian akun game: status 'success' tidak berlaku
            $allowed = ['pending', 'waiting_delivery', 'delivered', 'failed'];
        } else {
            // Topup item biasa: 'waiting_delivery' dan 'delivered' tidak berlaku
            $allowed = ['pending', 'success', 'failed'];
        }

        $request->validate([
            'status' => ['required', 'in:' . implode(',', $allowed)],
        ]);

        $oldStatus = $transaction->status;
        $transaction->status = $request->status;

        $logs = $transaction->status_logs;
        $logs[] = [
            'time'    => date('H:i'),
            'message' => 'Status pesanan diubah oleh Admin dari ' . strtoupper($oldStatus) . ' menjadi ' . strtoupper($request->status) . '.',
        ];
        $transaction->status_logs = $logs;
        $transaction->save();

        return back()->with('success', 'Status transaksi #' . $transaction->invoice . ' berhasil diperbarui menjadi ' . strtoupper($request->status) . '.');
    }

    // ==========================================
    // GAMES CRUD
    // ==========================================
    public function games(Request $request)
    {
        $query = Game::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('developer', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $games = $query->orderBy('created_at', 'desc')->get();
        return view('admin.games', compact('games'));
    }

    public function storeGame(StoreGameRequest $request)
    {
        $validated = $request->validated();

        $thumbnailUrl = '';
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $fileName = time() . '_thumb_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/games'), $fileName);
            $thumbnailUrl = '/uploads/games/' . $fileName;
        }

        $bannerUrl = '';
        if ($request->hasFile('banner')) {
            $file = $request->file('banner');
            $fileName = time() . '_banner_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/games'), $fileName);
            $bannerUrl = '/uploads/games/' . $fileName;
        }

        $slug = Str::slug($validated['name']);

        Game::create([
            'slug' => $slug,
            'name' => $validated['name'],
            'category' => $validated['category'],
            'thumbnail_url' => $thumbnailUrl,
            'banner_url' => $bannerUrl,
            'developer' => $validated['developer'],
            'id_label' => $validated['id_label'],
            'zone_id_label' => $validated['zone_id_label'] ?? null,
            'id_helper_text' => $validated['id_helper_text'],
            'cashback_percent' => $validated['cashback_percent'],
            'has_discount' => false
        ]);

        return back()->with('success', 'Game baru berhasil ditambahkan!');
    }

    public function updateGame(UpdateGameRequest $request, $id)
    {
        $validated = $request->validated();
        $game = Game::findOrFail($id);

        $thumbnailUrl = $game->thumbnail_url;
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $fileName = time() . '_thumb_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/games'), $fileName);
            $thumbnailUrl = '/uploads/games/' . $fileName;
        }

        $bannerUrl = $game->banner_url;
        if ($request->hasFile('banner')) {
            $file = $request->file('banner');
            $fileName = time() . '_banner_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/games'), $fileName);
            $bannerUrl = '/uploads/games/' . $fileName;
        }

        $game->update([
            'slug' => Str::slug($validated['name']),
            'name' => $validated['name'],
            'category' => $validated['category'],
            'thumbnail_url' => $thumbnailUrl,
            'banner_url' => $bannerUrl,
            'developer' => $validated['developer'],
            'id_label' => $validated['id_label'],
            'zone_id_label' => $validated['zone_id_label'] ?? null,
            'id_helper_text' => $validated['id_helper_text'],
            'cashback_percent' => $validated['cashback_percent'],
        ]);

        return back()->with('success', 'Data game ' . $game->name . ' berhasil diperbarui!');
    }

    public function deleteGame($id)
    {
        $game = Game::findOrFail($id);
        $game->delete();

        return back()->with('success', 'Game beserta seluruh nominal itemnya berhasil dihapus!');
    }

    // ==========================================
    // NOMINALS CRUD
    // ==========================================
    public function nominals(Request $request)
    {
        $query = Nominal::query()->with('game');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('game_id') && $request->game_id !== 'all') {
            $query->where('game_id', $request->game_id);
        }

        $nominals = $query->orderBy('created_at', 'desc')->get();
        $games = Game::all();
        
        return view('admin.nominals', compact('nominals', 'games'));
    }

    public function storeNominalDirect(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'discount_price' => 'nullable|integer|min:0',
            'is_best_seller' => 'boolean',
            'tag' => 'nullable|string|max:50'
        ]);

        $game = Game::findOrFail($request->game_id);
        $itemId = Str::slug($game->name) . '-' . Str::slug($request->name);

        Nominal::create([
            'game_id' => $game->id,
            'item_id' => $itemId,
            'name' => $request->name,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'is_best_seller' => $request->has('is_best_seller') ? true : false,
            'tag' => $request->tag
        ]);

        if ($request->discount_price && $request->discount_price < $request->price) {
            $game->has_discount = true;
            $game->save();
        }

        return back()->with('success', 'Item nominal baru berhasil ditambahkan untuk game ' . $game->name . '!');
    }

    public function updateNominal(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'discount_price' => 'nullable|integer|min:0',
            'is_best_seller' => 'boolean',
            'tag' => 'nullable|string|max:50'
        ]);

        $nominal = Nominal::findOrFail($id);
        $game = Game::findOrFail($nominal->game_id);
        $itemId = Str::slug($game->name) . '-' . Str::slug($request->name);

        $nominal->update([
            'item_id' => $itemId,
            'name' => $request->name,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'is_best_seller' => $request->has('is_best_seller') ? true : false,
            'tag' => $request->tag
        ]);

        // Recalculate game discount state
        $hasDiscount = Nominal::where('game_id', $game->id)->whereNotNull('discount_price')->exists();
        $game->has_discount = $hasDiscount;
        $game->save();

        return back()->with('success', 'Nominal item ' . $nominal->name . ' berhasil diperbarui!');
    }

    public function deleteNominal($id)
    {
        $nominal = Nominal::findOrFail($id);
        $gameId = $nominal->game_id;
        $nominal->delete();

        // Recalculate game discount state
        $game = Game::find($gameId);
        if ($game) {
            $hasDiscount = Nominal::where('game_id', $game->id)->whereNotNull('discount_price')->exists();
            $game->has_discount = $hasDiscount;
            $game->save();
        }

        return back()->with('success', 'Nominal item berhasil dihapus!');
    }

    // ==========================================
    // PAYMENT METHODS CRUD
    // ==========================================
    public function paymentMethods()
    {
        $paymentMethods = PaymentMethod::orderBy('created_at', 'desc')->get();
        return view('admin.payments', compact('paymentMethods'));
    }

    public function storePaymentMethod(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'group' => 'required|string|in:qris,e-wallet,bank',
            'fee' => 'required|integer|min:0',
            'account_number' => 'nullable|string',
            'instructions' => 'required|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = time() . '_pay_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/payments'), $fileName);
            $imageUrl = '/uploads/payments/' . $fileName;
        }

        // Convert newline instructions string into array
        $instructionsArray = array_filter(
            array_map('trim', explode("\n", $request->instructions))
        );

        $slug = Str::slug($request->name);

        PaymentMethod::create([
            'slug' => $slug,
            'name' => $request->name,
            'group' => $request->group,
            'fee' => $request->fee,
            'account_number' => $request->account_number,
            'instructions' => $instructionsArray,
            'image' => $imageUrl
        ]);

        return back()->with('success', 'Metode pembayaran baru berhasil ditambahkan!');
    }

    public function updatePaymentMethod(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'group' => 'required|string|in:qris,e-wallet,bank',
            'fee' => 'required|integer|min:0',
            'account_number' => 'nullable|string',
            'instructions' => 'required|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        // Convert newline instructions string into array
        $instructionsArray = array_filter(
            array_map('trim', explode("\n", $request->instructions))
        );

        $pm = PaymentMethod::findOrFail($id);

        $imageUrl = $pm->image;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = time() . '_pay_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/payments'), $fileName);
            $imageUrl = '/uploads/payments/' . $fileName;
        }

        $pm->update([
            'slug' => Str::slug($request->name),
            'name' => $request->name,
            'group' => $request->group,
            'fee' => $request->fee,
            'account_number' => $request->account_number,
            'instructions' => $instructionsArray,
            'image' => $imageUrl
        ]);

        return back()->with('success', 'Metode pembayaran ' . $pm->name . ' berhasil diperbarui!');
    }

    public function deletePaymentMethod($id)
    {
        $pm = PaymentMethod::findOrFail($id);
        $pm->delete();

        return back()->with('success', 'Metode pembayaran berhasil dihapus!');
    }

    // ==========================================
    // USERS MANAGEMENT
    // ==========================================
    public function users(Request $request)
    {
        $query = User::query()->with(['transactions.game'])->withCount('transactions');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $isSuspended = $request->status === 'suspended' ? true : false;
            $query->where('is_suspended', $isSuspended);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users', compact('users'));
    }

    // ==========================================
    // PROMOS CRUD
    // ==========================================
    public function promos()
    {
        $promos = Promo::orderBy('created_at', 'desc')->get();
        return view('admin.promos', compact('promos'));
    }

    public function storePromo(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image_file' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'code' => 'required|string|max:50|unique:promos,code',
            'description' => 'required|string',
            'discount_amount' => 'required|integer|min:0',
            'min_transaction' => 'required|integer|min:0',
            'discount_type' => 'required|in:nominal,percent',
            'expiry_date' => 'nullable|date',
            'max_uses' => 'required|integer|min:1',
            'claim_url' => 'nullable|string|max:255',
        ]);

        $imageUrl = '';
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = time() . '_promo_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/promos'), $fileName);
            $imageUrl = '/uploads/promos/' . $fileName;
        }

        Promo::create([
            'title' => $request->title,
            'image' => $imageUrl,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'discount_amount' => $request->discount_amount,
            'min_transaction' => $request->min_transaction,
            'discount_type' => $request->discount_type,
            'expiry_date' => $request->expiry_date,
            'max_uses' => $request->max_uses,
            'uses_count' => 0,
            'is_active' => true,
            'claim_url' => $request->claim_url
        ]);

        return back()->with('success', 'Kode voucher baru berhasil diterbitkan!');
    }

    public function updatePromo(Request $request, $id)
    {
        $promo = Promo::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'code' => 'required|string|max:50|unique:promos,code,' . $promo->id,
            'description' => 'required|string',
            'discount_amount' => 'required|integer|min:0',
            'min_transaction' => 'required|integer|min:0',
            'discount_type' => 'required|in:nominal,percent',
            'expiry_date' => 'nullable|date',
            'max_uses' => 'required|integer|min:1',
            'claim_url' => 'nullable|string|max:255',
        ]);

        $imageUrl = $promo->image;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = time() . '_promo_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/promos'), $fileName);
            $imageUrl = '/uploads/promos/' . $fileName;
        }

        $promo->update([
            'title' => $request->title,
            'image' => $imageUrl,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'discount_amount' => $request->discount_amount,
            'min_transaction' => $request->min_transaction,
            'discount_type' => $request->discount_type,
            'expiry_date' => $request->expiry_date,
            'max_uses' => $request->max_uses,
            'claim_url' => $request->claim_url
        ]);

        return back()->with('success', 'Kode voucher ' . $promo->code . ' berhasil diperbarui!');
    }

    public function deletePromo($id)
    {
        $promo = Promo::findOrFail($id);
        $promo->delete();

        return back()->with('success', 'Kode voucher berhasil dihapus secara permanen!');
    }

    public function togglePromo($id)
    {
        $promo = Promo::findOrFail($id);
        $promo->is_active = !$promo->is_active;
        $promo->save();

        $status = $promo->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', 'Kode voucher ' . $promo->code . ' berhasil ' . $status . '!');
    }

    // ==========================================
    // ANALYTICS & CSV REPORTS
    // ==========================================
    public function reports()
    {
        // --- 30-day daily revenue + transaction volume ---
        $chartLabels      = [];
        $chartRevenue     = [];
        $chartTxVolume    = [];
        $chartSuccess     = [];
        $chartPending     = [];
        $chartFailed      = [];

        for ($i = 29; $i >= 0; $i--) {
            $date  = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->format('d/m');

            $rev     = Transaction::where('status', 'success')->whereDate('created_at', $date)->sum('total_payment');
            $success = Transaction::where('status', 'success')->whereDate('created_at', $date)->count();
            $pending = Transaction::where('status', 'pending')->whereDate('created_at', $date)->count();
            $failed  = Transaction::where('status', 'failed')->whereDate('created_at', $date)->count();

            $chartLabels[]   = $label;
            $chartRevenue[]  = (int) $rev;
            $chartTxVolume[] = $success + $pending + $failed;
            $chartSuccess[]  = $success;
            $chartPending[]  = $pending;
            $chartFailed[]   = $failed;
        }

        // --- Payment method donut data ---
        $popularPayments = Transaction::selectRaw('payment_method_id, COUNT(*) as count')
            ->where('status', 'success')
            ->groupBy('payment_method_id')
            ->with('paymentMethod')
            ->orderBy('count', 'desc')
            ->get();

        $paymentLabels = $popularPayments->pluck('paymentMethod.name')->toArray();
        $paymentCounts = $popularPayments->pluck('count')->toArray();

        // --- Best selling games ---
        $bestGames = Transaction::selectRaw('game_id, COUNT(*) as sales_count, SUM(total_payment) as revenue')
            ->where('status', 'success')
            ->groupBy('game_id')
            ->with('game')
            ->orderBy('revenue', 'desc')
            ->take(5)
            ->get();

        // --- Summary stats ---
        $totalRevenue30d = array_sum($chartRevenue);
        $totalTx30d      = array_sum($chartTxVolume);
        $avgOrderValue   = $totalTx30d > 0 ? round($totalRevenue30d / array_sum($chartSuccess) ?: 1) : 0;

        return view('admin.reports', compact(
            'chartLabels', 'chartRevenue', 'chartTxVolume',
            'chartSuccess', 'chartPending', 'chartFailed',
            'popularPayments', 'paymentLabels', 'paymentCounts',
            'bestGames',
            'totalRevenue30d', 'totalTx30d', 'avgOrderValue'
        ));
    }

    // ==========================================
    // TRANSACTION AUDITING DETAILS
    // ==========================================
    public function transactionDetail($id)
    {
        $tx = Transaction::with(['game', 'nominal', 'paymentMethod', 'user', 'gameAccount'])
            ->findOrFail($id);

        return view('admin.transaction_detail', compact('tx'));
    }

    public function deliverTransaction(Request $request, $id)
    {
        $tx = Transaction::findOrFail($id);
        
        if ($tx->status === 'success') {
            return back()->with('error', 'Pesanan ini sudah sukses terkirim!');
        }

        $oldStatus = $tx->status;
        $tx->status = 'success';

        $logs = $tx->status_logs;
        $logs[] = [
            'time' => date('H:i'),
            'message' => 'Admin secara manual memproses pengiriman produk (DELIVERED). Status diubah menjadi SUCCESS.'
        ];
        $tx->status_logs = $logs;
        $tx->save();

        if ($tx->user) {
            $cashback = round(($tx->total_payment * $tx->game->cashback_percent) / 100);
            $tx->user->increment('cashback_saved', $cashback);
        }

        return back()->with('success', 'Pesanan #' . $tx->invoice . ' berhasil dikirim secara manual! Status diubah menjadi SUCCESS.');
    }

    public function refundTransaction(Request $request, $id)
    {
        $tx = Transaction::findOrFail($id);
        
        if ($tx->status === 'failed') {
            return back()->with('error', 'Pesanan ini sudah dibatalkan/direfund!');
        }

        $tx->status = 'failed';

        $logs = $tx->status_logs;
        $logs[] = [
            'time' => date('H:i'),
            'message' => 'Admin secara manual melakukan pembatalan dan refund dana transaksi (REFUNDED). Status diubah menjadi FAILED.'
        ];
        $tx->status_logs = $logs;
        $tx->save();

        return back()->with('success', 'Pesanan #' . $tx->invoice . ' berhasil dibatalkan dan direfund! Status diubah menjadi FAILED.');
    }

    public function deliverAccount(DeliverAccountRequest $request, $id)
    {
        $tx = Transaction::with(['game', 'user', 'gameAccount'])->findOrFail($id);

        if (!$tx->game_account_id) {
            return back()->with('error', 'Transaksi ini bukan pembelian akun game!');
        }

        if ($tx->status === 'delivered') {
            return back()->with('error', 'Akun game untuk pesanan ini sudah terkirim!');
        }

        $validated = $request->validated();

        try {
            $this->fulfillmentService->deliverAccount(
                $tx,
                $validated['account_email'],
                $validated['account_password'],
                $validated['notes'] ?? null,
                auth()->user()->name ?? 'Admin'
            );

            return back()->with('success', 'Kredensial akun game berhasil dikirim ke email pembeli! Status transaksi diubah menjadi DELIVERED.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirimkan email: ' . $e->getMessage());
        }
    }

    // ==========================================
    // GAME ACCOUNTS CRUD
    // ==========================================
    public function accounts(Request $request)
    {
        $query = GameAccount::query()->with('game');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->filled('game_id') && $request->game_id !== 'all') {
            $query->where('game_id', $request->game_id);
        }

        $accounts = $query->orderBy('created_at', 'desc')->get();
        $games = Game::all();

        return view('admin.accounts', compact('accounts', 'games'));
    }

    public function storeAccount(StoreAccountRequest $request)
    {
        $validated = $request->validated();

        $uploadedImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $fileName = time() . '_acc_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/accounts'), $fileName);
                $uploadedImages[] = '/uploads/accounts/' . $fileName;
            }
        }

        $slug = Str::slug($validated['title']) . '-' . Str::random(5);

        GameAccount::create([
            'game_id'      => $validated['game_id'],
            'title'        => $validated['title'],
            'slug'         => $slug,
            'description'  => $validated['description'] ?? null,
            'rank'         => $validated['rank'],
            'level'        => $validated['level'],
            'skin_count'   => $validated['skin_count'],
            'login_method' => $validated['login_method'],
            'bind_status'  => $validated['bind_status'],
            'price'        => $validated['price'],
            'images'       => $uploadedImages,
            'account_data' => $validated['account_data'],
            'status'       => 'available',
            'featured'     => $request->has('featured') ? true : false,
        ]);

        return back()->with('success', 'Akun game baru berhasil dipublikasikan!');
    }

    public function updateAccount(UpdateAccountRequest $request, $id)
    {
        $validated = $request->validated();
        $account = GameAccount::findOrFail($id);

        $uploadedImages = $account->images;
        if ($request->hasFile('images')) {
            $uploadedImages = [];
            foreach ($request->file('images') as $file) {
                $fileName = time() . '_acc_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/accounts'), $fileName);
                $uploadedImages[] = '/uploads/accounts/' . $fileName;
            }
        }

        $account->update([
            'game_id'      => $validated['game_id'],
            'title'        => $validated['title'],
            'slug'         => Str::slug($validated['title']) . '-' . Str::random(3),
            'description'  => $validated['description'] ?? null,
            'rank'         => $validated['rank'],
            'level'        => $validated['level'],
            'skin_count'   => $validated['skin_count'],
            'login_method' => $validated['login_method'],
            'bind_status'  => $validated['bind_status'],
            'price'        => $validated['price'],
            'images'       => $uploadedImages,
            'account_data' => $validated['account_data'],
            'featured'     => $request->has('featured') ? true : false,
        ]);

        return back()->with('success', 'Akun game ' . $account->title . ' berhasil diperbarui!');
    }

    public function deleteAccount($id)
    {
        $account = GameAccount::findOrFail($id);
        $account->delete();

        return back()->with('success', 'Akun game berhasil dihapus dari marketplace!');
    }

    public function toggleAccount(Request $request, $id)
    {
        $account = GameAccount::findOrFail($id);
        
        if ($request->type === 'featured') {
            $account->featured = !$account->featured;
            $account->save();
            $msg = 'Status unggulan (featured) akun ' . ($account->featured ? 'diaktifkan' : 'dinonaktifkan') . '.';
        } else {
            $account->status = ($account->status === 'available') ? 'sold' : 'available';
            $account->save();
            $msg = 'Status penjualan akun diubah menjadi ' . strtoupper($account->status) . '.';
        }

        return back()->with('success', $msg);
    }

    public function flashSale()
    {
        $flashSaleShow = \App\Models\Setting::getVal('flash_sale_show', 'true');
        $flashSaleTitle = \App\Models\Setting::getVal('flash_sale_title', 'Sabet Diskon Game Terpopuler Akhir Pekan');
        $flashSaleDescription = \App\Models\Setting::getVal('flash_sale_description', 'Diamond, token, dan Welkin Moon ready diskon kilat, instan terkirim secara otomatis.');
        $flashSaleSlug = \App\Models\Setting::getVal('flash_sale_slug', 'mobile-legends');
        $flashSaleButtonText = \App\Models\Setting::getVal('flash_sale_button_text', 'Cek Flash Sale MLBB');
        
        // Convert 'YYYY-MM-DD HH:MM:SS' format to 'YYYY-MM-DDTHH:MM' for HTML5 datetime-local input
        $rawEnd = \App\Models\Setting::getVal('flash_sale_end', '');
        $flashSaleEnd = $rawEnd ? str_replace(' ', 'T', substr($rawEnd, 0, 16)) : '';

        $games = Game::orderBy('name', 'asc')->get();

        return view('admin.flash-sale', compact(
            'flashSaleShow',
            'flashSaleTitle',
            'flashSaleDescription',
            'flashSaleSlug',
            'flashSaleButtonText',
            'flashSaleEnd',
            'games'
        ));
    }

    public function updateFlashSale(Request $request)
    {
        $request->validate([
            'flash_sale_show'        => 'required|in:true,false',
            'flash_sale_title'       => 'required|string|max:255',
            'flash_sale_description' => 'required|string',
            'flash_sale_slug'        => 'required|string|exists:games,slug',
            'flash_sale_button_text' => 'required|string|max:255',
            'flash_sale_end'         => 'nullable|string',
        ]);

        \App\Models\Setting::setVal('flash_sale_show', $request->flash_sale_show);
        \App\Models\Setting::setVal('flash_sale_title', $request->flash_sale_title);
        \App\Models\Setting::setVal('flash_sale_description', $request->flash_sale_description);
        \App\Models\Setting::setVal('flash_sale_slug', $request->flash_sale_slug);
        \App\Models\Setting::setVal('flash_sale_button_text', $request->flash_sale_button_text);
        
        $flashSaleEnd = $request->flash_sale_end ? str_replace('T', ' ', $request->flash_sale_end) : '';
        \App\Models\Setting::setVal('flash_sale_end', $flashSaleEnd);

        return back()->with('success', 'Konfigurasi Flash Sale berhasil diperbarui!');
    }

}
