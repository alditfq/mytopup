<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Nominal;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Promo;
use App\Models\Setting;
use App\Models\MarqueeItem;
use App\Models\Faq;
use App\Models\Testimonial;
use App\Models\Review;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $totalRevenue        = Transaction::where('status', 'success')->sum('total_payment');
        $totalTransactions   = Transaction::count();
        $totalUsers          = User::count();
        $pendingTransactions = Transaction::where('status', 'pending')->count();
        $successTransactions = Transaction::where('status', 'success')->count();
        $failedTransactions  = Transaction::where('status', 'failed')->count();

        $transactions = Transaction::with(['game', 'nominal', 'paymentMethod'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $popularGames = Transaction::selectRaw('game_id, COUNT(*) as sales_count, SUM(total_payment) as revenue')
            ->where('status', 'success')
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

            $rev = Transaction::where('status', 'success')
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
        $request->validate([
            'status' => 'required|in:success,failed,pending'
        ]);

        $transaction = Transaction::findOrFail($id);
        $oldStatus = $transaction->status;
        $transaction->status = $request->status;

        $logs = $transaction->status_logs;
        $logs[] = [
            'time' => date('H:i'),
            'message' => 'Status pesanan diubah oleh Admin dari ' . strtoupper($oldStatus) . ' menjadi ' . strtoupper($request->status) . '.'
        ];
        $transaction->status_logs = $logs;
        $transaction->save();

        return back()->with('success', 'Status transaksi #' . $transaction->invoice . ' berhasil diperbarui menjadi ' . $request->status);
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

    public function storeGame(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'banner' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'developer' => 'required|string',
            'id_label' => 'required|string',
            'zone_id_label' => 'nullable|string',
            'id_helper_text' => 'required|string',
            'cashback_percent' => 'required|integer|min:0|max:100',
        ]);

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

        $slug = Str::slug($request->name);

        Game::create([
            'slug' => $slug,
            'name' => $request->name,
            'category' => $request->category,
            'thumbnail_url' => $thumbnailUrl,
            'banner_url' => $bannerUrl,
            'developer' => $request->developer,
            'id_label' => $request->id_label,
            'zone_id_label' => $request->zone_id_label,
            'id_helper_text' => $request->id_helper_text,
            'cashback_percent' => $request->cashback_percent,
            'has_discount' => false
        ]);

        return back()->with('success', 'Game baru berhasil ditambahkan!');
    }

    public function updateGame(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'developer' => 'required|string',
            'id_label' => 'required|string',
            'zone_id_label' => 'nullable|string',
            'id_helper_text' => 'required|string',
            'cashback_percent' => 'required|integer|min:0|max:100',
        ]);

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
            'slug' => Str::slug($request->name),
            'name' => $request->name,
            'category' => $request->category,
            'thumbnail_url' => $thumbnailUrl,
            'banner_url' => $bannerUrl,
            'developer' => $request->developer,
            'id_label' => $request->id_label,
            'zone_id_label' => $request->zone_id_label,
            'id_helper_text' => $request->id_helper_text,
            'cashback_percent' => $request->cashback_percent,
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

    public function toggleUserSuspend($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role === 'admin') {
            return back()->with('error', 'Anda tidak dapat menangguhkan sesama akun Admin!');
        }

        $user->is_suspended = !$user->is_suspended;
        $user->save();

        $status = $user->is_suspended ? 'ditangguhkan (SUSPENDED)' : 'diaktifkan kembali (ACTIVE)';
        return back()->with('success', 'Akun ' . $user->name . ' berhasil ' . $status . '!');
    }

    public function resetUserPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::findOrFail($id);
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Kata sandi untuk ' . $user->name . ' berhasil disetel ulang!');
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
    // SETTINGS & CONFIGURATIONS
    // ==========================================
    public function settings()
    {
        $shopName = Setting::getVal('shop_name', 'GameTopup');
        $logoUrl = Setting::getVal('logo_url', '');
        $marqueeText = Setting::getVal('marquee_text', '');
        $flashSaleEnd = Setting::getVal('flash_sale_end', '');
        $isMaintenance = Setting::getVal('is_maintenance', 'false');

        return view('admin.settings', compact('shopName', 'logoUrl', 'marqueeText', 'flashSaleEnd', 'isMaintenance'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'marquee_text' => 'nullable|string',
            'flash_sale_end' => 'nullable|date_format:Y-m-d\TH:i',
            'is_maintenance' => 'required|in:true,false'
        ]);

        Setting::setVal('shop_name', $request->shop_name);
        
        $logoUrl = Setting::getVal('logo_url', '');
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = time() . '_logo_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $fileName);
            $logoUrl = '/uploads/settings/' . $fileName;
        }
        Setting::setVal('logo_url', $logoUrl);
        
        Setting::setVal('marquee_text', $request->marquee_text ?? '');
        
        if ($request->flash_sale_end) {
            $dateTime = date('Y-m-d H:i:s', strtotime($request->flash_sale_end));
            Setting::setVal('flash_sale_end', $dateTime);
        }

        Setting::setVal('is_maintenance', $request->is_maintenance);

        return back()->with('success', 'Konfigurasi sistem berhasil diperbarui!');
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

    public function exportReport()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=rekap-transaksi-" . date('Ymd') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $transactions = Transaction::with(['game', 'user', 'paymentMethod'])
            ->orderBy('created_at', 'desc')
            ->get();

        $callback = function() use($transactions) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'Invoice', 
                'Nama Game', 
                'Nominal Diamond/Item', 
                'Target Player ID', 
                'Zone/Server ID', 
                'Total Pembayaran (IDR)', 
                'Saluran Pembayaran', 
                'Nama Akun Pembeli', 
                'Status Transaksi', 
                'Tanggal'
            ]);

            foreach ($transactions as $tx) {
                fputcsv($file, [
                    $tx->invoice,
                    $tx->game->name,
                    $tx->nominal_name,
                    $tx->target_id,
                    $tx->zone_id ?? '-',
                    $tx->total_payment,
                    $tx->paymentMethod->name,
                    $tx->user ? $tx->user->name : ($tx->nickname ?? 'Guest Customer'),
                    strtoupper($tx->status),
                    $tx->created_at->format('Y-m-d H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ==========================================
    // TRANSACTION AUDITING DETAILS
    // ==========================================
    public function transactionDetail($id)
    {
        $tx = Transaction::with(['game', 'nominal', 'paymentMethod', 'user'])
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

        $tx->status_logs = $logs;
        $tx->save();

        return back()->with('success', 'Pesanan #' . $tx->invoice . ' berhasil dibatalkan dan direfund! Status diubah menjadi FAILED.');
    }

    // ==========================================
    // FAQ CRUD MANAGEMENT
    // ==========================================
    public function faqs(Request $request)
    {
        $query = Faq::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $faqs = $query->orderBy('sort_order', 'asc')->get();
        return view('admin.faqs', compact('faqs'));
    }

    public function storeFaq(Request $request)
    {
        $request->validate([
            'category' => 'required|string|in:General,Payment,Refund,Account,Promotion,Technical',
            'question' => 'required|string',
            'answer' => 'required|string',
            'sort_order' => 'required|integer|min:0'
        ]);

        Faq::create([
            'category' => strtolower($request->category),
            'slug' => Str::slug($request->question),
            'question' => $request->question,
            'answer' => $request->answer,
            'sort_order' => $request->sort_order,
            'is_active' => true
        ]);

        return back()->with('success', 'FAQ baru berhasil ditambahkan!');
    }

    public function updateFaq(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|string|in:General,Payment,Refund,Account,Promotion,Technical',
            'question' => 'required|string',
            'answer' => 'required|string',
            'sort_order' => 'required|integer|min:0'
        ]);

        $faq = Faq::findOrFail($id);
        $faq->update([
            'category' => strtolower($request->category),
            'slug' => Str::slug($request->question),
            'question' => $request->question,
            'answer' => $request->answer,
            'sort_order' => $request->sort_order
        ]);

        return back()->with('success', 'FAQ berhasil diperbarui!');
    }

    public function deleteFaq($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return back()->with('success', 'FAQ berhasil dihapus!');
    }

    public function toggleFaq($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->is_active = !$faq->is_active;
        $faq->save();

        $status = $faq->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', 'FAQ berhasil ' . $status . '!');
    }

    // ==========================================
    // TESTIMONIAL CRUD MANAGEMENT
    // ==========================================
    public function testimonials(Request $request)
    {
        $query = Testimonial::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhere('game_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('filter') && $request->filter !== 'all') {
            if ($request->filter === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->filter === 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->filter === 'featured') {
                $query->where('is_featured', true);
            }
        }

        $testimonials = $query->orderBy('created_at', 'desc')->get();
        return view('admin.testimonials', compact('testimonials'));
    }

    public function storeTestimonial(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'game_name' => 'required|string|max:255',
            'message' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'avatar_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        $avatarUrl = 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100&h=100&fit=crop&q=80';
        if ($request->hasFile('avatar_file')) {
            $file = $request->file('avatar_file');
            $fileName = time() . '_avatar_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $fileName);
            $avatarUrl = '/uploads/avatars/' . $fileName;
        }

        Testimonial::create([
            'user_id' => null,
            'username' => $request->username,
            'game_name' => $request->game_name,
            'message' => $request->message,
            'rating' => $request->rating,
            'avatar' => $avatarUrl,
            'is_approved' => true,
            'is_featured' => false
        ]);

        return back()->with('success', 'Testimonial baru berhasil ditambahkan!');
    }

    public function updateTestimonial(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'game_name' => 'required|string|max:255',
            'message' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'avatar_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        $testimonial = Testimonial::findOrFail($id);
        
        $avatarUrl = $testimonial->avatar;
        if ($request->hasFile('avatar_file')) {
            $file = $request->file('avatar_file');
            $fileName = time() . '_avatar_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $fileName);
            $avatarUrl = '/uploads/avatars/' . $fileName;
        }

        $testimonial->update([
            'username' => $request->username,
            'game_name' => $request->game_name,
            'message' => $request->message,
            'rating' => $request->rating,
            'avatar' => $avatarUrl
        ]);

        return back()->with('success', 'Testimonial berhasil diperbarui!');
    }

    public function deleteTestimonial($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();

        return back()->with('success', 'Testimonial berhasil dihapus!');
    }

    public function toggleTestimonialApprove($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->is_approved = !$testimonial->is_approved;
        $testimonial->save();

        $status = $testimonial->is_approved ? 'disetujui (APPROVED)' : 'ditolak (PENDING)';
        return back()->with('success', 'Testimonial berhasil ' . $status . '!');
    }

    public function toggleTestimonialFeatured($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->is_featured = !$testimonial->is_featured;
        $testimonial->save();

        $status = $testimonial->is_featured ? 'dijadikan unggulan (FEATURED)' : 'dihapus dari unggulan';
        return back()->with('success', 'Testimonial berhasil ' . $status . '!');
    }

    // ==========================================
    // SUPPORT LIVE CHAT DASHBOARD
    // ==========================================
    public function chatDashboard()
    {
        return view('admin.chat');
    }

    public function chatConversations()
    {
        $conversations = ChatConversation::with(['user', 'messages'])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function($c) {
                $latestMsgObj = $c->messages->sortByDesc('created_at')->first();
                $c->latest_message = $latestMsgObj ? $latestMsgObj->message : 'Belum ada pesan.';
                $c->latest_message_time = $latestMsgObj ? $latestMsgObj->created_at->format('H:i') : '';
                $c->unread_count = $c->messages->where('sender_type', 'customer')->where('is_read', false)->count();
                return $c;
            });

        return response()->json($conversations);
    }

    public function chatMessages($conversationId)
    {
        $conversation = ChatConversation::with('user')->findOrFail($conversationId);
        
        if (!$conversation->assigned_admin_id) {
            $conversation->assigned_admin_id = auth()->id();
            $conversation->save();
        }

        $messages = ChatMessage::where('conversation_id', $conversationId)
            ->orderBy('created_at', 'asc')
            ->get();

        ChatMessage::where('conversation_id', $conversationId)
            ->where('sender_type', 'customer')
            ->update(['is_read' => true]);

        $txHistory = [];
        if ($conversation->user_id) {
            $txHistory = Transaction::where('user_id', $conversation->user_id)
                ->with('game')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        } else {
            $txHistory = Transaction::where('nickname', 'like', "%{$conversation->guest_name}%")
                ->with('game')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        return response()->json([
            'conversation' => $conversation,
            'messages' => $messages,
            'tx_history' => $txHistory
        ]);
    }

    public function sendSupportMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:chat_conversations,id',
            'message' => 'required|string'
        ]);

        $msg = ChatMessage::create([
            'conversation_id' => $request->conversation_id,
            'sender_type' => 'admin',
            'sender_id' => auth()->id(),
            'message' => $request->message,
            'is_read' => false
        ]);

        $conversation = ChatConversation::findOrFail($request->conversation_id);
        $conversation->touch();

        return response()->json([
            'status' => 'success',
            'message' => $msg
        ]);
    }

    public function closeConversation($conversationId)
    {
        $conversation = ChatConversation::findOrFail($conversationId);
        $conversation->status = 'closed';
        $conversation->save();

        return response()->json([
            'status' => 'success'
        ]);
    }

    // ==========================================
    // MARQUEE MANAGEMENT (Multi-Item)
    // ==========================================
    public function marquee()
    {
        $marqueeActive = Setting::getVal('marquee_active', 'true');
        $items = MarqueeItem::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.marquee', compact('marqueeActive', 'items'));
    }

    public function updateMarquee(Request $request)
    {
        $request->validate([
            'marquee_active' => 'required|in:true,false',
        ]);

        Setting::setVal('marquee_active', $request->marquee_active);

        return back()->with('success', 'Status marquee berhasil diperbarui!');
    }

    public function storeMarqueeItem(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:500',
        ]);

        $maxOrder = MarqueeItem::max('sort_order') ?? 0;

        MarqueeItem::create([
            'text'       => $request->text,
            'is_active'  => true,
            'sort_order' => $maxOrder + 1,
        ]);

        return back()->with('success', 'Item marquee baru berhasil ditambahkan!');
    }

    public function toggleMarqueeItem($id)
    {
        $item = MarqueeItem::findOrFail($id);
        $item->is_active = !$item->is_active;
        $item->save();

        $status = $item->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', 'Item marquee berhasil ' . $status . '!');
    }

    public function deleteMarqueeItem($id)
    {
        $item = MarqueeItem::findOrFail($id);
        $item->delete();

        return back()->with('success', 'Item marquee berhasil dihapus!');
    }

    public function sortMarqueeItem(Request $request, $id)
    {
        $request->validate(['direction' => 'required|in:up,down']);

        $item = MarqueeItem::findOrFail($id);

        if ($request->direction === 'up') {
            $swap = MarqueeItem::where('sort_order', '<', $item->sort_order)
                ->orderBy('sort_order', 'desc')->first();
        } else {
            $swap = MarqueeItem::where('sort_order', '>', $item->sort_order)
                ->orderBy('sort_order', 'asc')->first();
        }

        if ($swap) {
            [$item->sort_order, $swap->sort_order] = [$swap->sort_order, $item->sort_order];
            $item->save();
            $swap->save();
        }

        return back()->with('success', 'Urutan marquee berhasil diubah!');
    }

    // ==========================================
    // USER REVIEW MODERATION
    // ==========================================
    public function reviews(Request $request)
    {
        $query = Review::with(['game', 'transaction', 'user'])->orderBy('created_at', 'desc');

        if ($request->filled('filter')) {
            if ($request->filter === 'promoted') {
                $query->where('is_promoted', true);
            } elseif ($request->filter === 'pending') {
                $query->where('is_promoted', false);
            }
        }

        $reviews = $query->get();
        return view('admin.reviews', compact('reviews'));
    }

    public function promoteReview($id)
    {
        $review = Review::with(['game', 'user'])->findOrFail($id);

        if ($review->is_promoted) {
            return back()->with('error', 'Review ini sudah dipromosikan menjadi testimonial!');
        }

        // Get user avatar if available
        $avatar = $review->user
            ? ($review->user->avatar ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100&h=100&fit=crop&q=80')
            : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100&h=100&fit=crop&q=80';

        Testimonial::create([
            'user_id'     => $review->user_id,
            'username'    => $review->reviewer_name,
            'avatar'      => $avatar,
            'game_name'   => $review->game->name,
            'message'     => $review->message ?? 'Pelayanan top up sangat cepat dan memuaskan!',
            'rating'      => $review->rating,
            'is_approved' => true,
            'is_featured' => false,
        ]);

        $review->is_promoted = true;
        $review->save();

        return back()->with('success', 'Review dari ' . $review->reviewer_name . ' berhasil dijadikan testimonial!');
    }

    public function deleteReview($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return back()->with('success', 'Review berhasil dihapus!');
    }
}
