<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Katalog Beranda & General
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/support', [HomeController::class, 'support'])->name('support');
Route::get('/accounts', [HomeController::class, 'accountsIndex'])->name('accounts.index');
Route::get('/accounts/{slug}', [HomeController::class, 'accountDetail'])->name('accounts.detail');

// Detail Game Checkout
Route::get('/game/{slug}', [GameController::class, 'show'])->name('game.detail');
Route::post('/checkout', [TransactionController::class, 'checkout'])->name('checkout')->middleware('throttle:5,1');

// Lacak Pesanan
Route::get('/transaction-status', [TransactionController::class, 'statusPage'])->name('status');
Route::get('/transaction-status/search', [TransactionController::class, 'search'])->name('status.search');

// Transaksi & Pembayaran
Route::get('/payment-waiting/{invoice}', [PaymentController::class, 'waiting'])->name('payment.waiting');
Route::post('/payment-waiting/{invoice}/confirm', [PaymentController::class, 'confirmPaid'])->name('payment.confirm');
Route::get('/payment-waiting/{invoice}/check-status', [PaymentController::class, 'checkStatus'])->name('payment.check-status');
Route::get('/success/{invoice}', [PaymentController::class, 'success'])->name('payment.success');
Route::post('/success/{invoice}/review', [ReviewController::class, 'store'])->name('review.store');

// Halaman Lama redirect to new
Route::redirect('/game-detail', '/');
Route::redirect('/payment-waiting', '/');
Route::redirect('/success', '/');

// Otentikasi (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:3,1');
});

// Otentikasi (Authenticated Member Only)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
});

// Admin Login (General Guest-safe)
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->middleware('throttle:5,1');

// Admin Dashboard (Admin Only)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Transactions Audit & Details
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('admin.transactions');
    Route::get('/transactions/{id}', [AdminController::class, 'transactionDetail'])->name('admin.transactions.detail');
    Route::post('/transactions/{id}/status', [AdminController::class, 'updateTransactionStatus'])->name('admin.transactions.update-status');
    Route::post('/transactions/{id}/deliver', [AdminController::class, 'deliverTransaction'])->name('admin.transactions.deliver');
    Route::post('/transactions/{id}/deliver-account', [AdminController::class, 'deliverAccount'])->name('admin.transactions.deliver-account');
    Route::post('/transactions/{id}/refund', [AdminController::class, 'refundTransaction'])->name('admin.transactions.refund');
    
    // Users Management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    
    // Promos CRUD (Extended)
    Route::get('/promos', [AdminController::class, 'promos'])->name('admin.promos');
    Route::post('/promos', [AdminController::class, 'storePromo'])->name('admin.promos.store');
    Route::post('/promos/{id}/update', [AdminController::class, 'updatePromo'])->name('admin.promos.update');
    Route::post('/promos/{id}/delete', [AdminController::class, 'deletePromo'])->name('admin.promos.delete');
    Route::post('/promos/{id}/toggle', [AdminController::class, 'togglePromo'])->name('admin.promos.toggle');
    
    // Games CRUD
    Route::get('/games', [AdminController::class, 'games'])->name('admin.games');
    Route::post('/games', [AdminController::class, 'storeGame'])->name('admin.games.store');
    Route::post('/games/{id}/update', [AdminController::class, 'updateGame'])->name('admin.games.update');
    Route::post('/games/{id}/delete', [AdminController::class, 'deleteGame'])->name('admin.games.delete');
    
    // Nominals CRUD
    Route::get('/nominals', [AdminController::class, 'nominals'])->name('admin.nominals');
    Route::post('/nominals', [AdminController::class, 'storeNominalDirect'])->name('admin.nominals.store');
    Route::post('/nominals/{id}/update', [AdminController::class, 'updateNominal'])->name('admin.nominals.update');
    Route::post('/nominals/{id}/delete', [AdminController::class, 'deleteNominal'])->name('admin.nominals.delete');
    
    // Payment Methods CRUD
    Route::get('/payment-methods', [AdminController::class, 'paymentMethods'])->name('admin.payment-methods');
    Route::post('/payment-methods', [AdminController::class, 'storePaymentMethod'])->name('admin.payment-methods.store');
    Route::post('/payment-methods/{id}/update', [AdminController::class, 'updatePaymentMethod'])->name('admin.payment-methods.update');
    Route::post('/payment-methods/{id}/delete', [AdminController::class, 'deletePaymentMethod'])->name('admin.payment-methods.delete');
    
    // Game Accounts CRUD
    Route::get('/accounts', [AdminController::class, 'accounts'])->name('admin.accounts');
    Route::post('/accounts', [AdminController::class, 'storeAccount'])->name('admin.accounts.store');
    Route::post('/accounts/{id}/update', [AdminController::class, 'updateAccount'])->name('admin.accounts.update');
    Route::post('/accounts/{id}/delete', [AdminController::class, 'deleteAccount'])->name('admin.accounts.delete');
    Route::post('/accounts/{id}/toggle', [AdminController::class, 'toggleAccount'])->name('admin.accounts.toggle');
    // Reports & Charts
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');

    // Flash Sale Management
    Route::get('/flash-sale', [AdminController::class, 'flashSale'])->name('admin.flash-sale');
    Route::post('/flash-sale', [AdminController::class, 'updateFlashSale'])->name('admin.flash-sale.update');
});
