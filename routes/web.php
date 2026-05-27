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
Route::post('/support/chat', [HomeController::class, 'sendChatMessage'])->name('support.chat');
Route::get('/support/chat/messages', [HomeController::class, 'getChatMessages'])->name('support.chat.messages');

// Detail Game Checkout
Route::get('/game/{slug}', [GameController::class, 'show'])->name('game.detail');
Route::post('/checkout', [TransactionController::class, 'checkout'])->name('checkout');

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
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
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
Route::post('/admin/login', [AuthController::class, 'adminLogin']);

// Admin Dashboard (Admin Only)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Transactions Audit & Details
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('admin.transactions');
    Route::get('/transactions/{id}', [AdminController::class, 'transactionDetail'])->name('admin.transactions.detail');
    Route::post('/transactions/{id}/status', [AdminController::class, 'updateTransactionStatus'])->name('admin.transactions.update-status');
    Route::post('/transactions/{id}/deliver', [AdminController::class, 'deliverTransaction'])->name('admin.transactions.deliver');
    Route::post('/transactions/{id}/refund', [AdminController::class, 'refundTransaction'])->name('admin.transactions.refund');
    
    // Users Management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/{id}/toggle-suspend', [AdminController::class, 'toggleUserSuspend'])->name('admin.users.toggle-suspend');
    Route::post('/users/{id}/reset-password', [AdminController::class, 'resetUserPassword'])->name('admin.users.reset-password');
    
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
    
    // Settings & Configuration
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/settings/update', [AdminController::class, 'updateSettings'])->name('admin.settings.update');

    // Marquee Management
    Route::get('/marquee', [AdminController::class, 'marquee'])->name('admin.marquee');
    Route::post('/marquee/update', [AdminController::class, 'updateMarquee'])->name('admin.marquee.update');
    Route::post('/marquee/items', [AdminController::class, 'storeMarqueeItem'])->name('admin.marquee.items.store');
    Route::post('/marquee/items/{id}/toggle', [AdminController::class, 'toggleMarqueeItem'])->name('admin.marquee.items.toggle');
    Route::post('/marquee/items/{id}/delete', [AdminController::class, 'deleteMarqueeItem'])->name('admin.marquee.items.delete');
    Route::post('/marquee/items/{id}/sort', [AdminController::class, 'sortMarqueeItem'])->name('admin.marquee.items.sort');
    
    // Reports & Charts
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/reports/export', [AdminController::class, 'exportReport'])->name('admin.reports.export');

    // FAQ Management
    Route::get('/faqs', [AdminController::class, 'faqs'])->name('admin.faqs');
    Route::post('/faqs', [AdminController::class, 'storeFaq'])->name('admin.faqs.store');
    Route::post('/faqs/{id}/update', [AdminController::class, 'updateFaq'])->name('admin.faqs.update');
    Route::post('/faqs/{id}/delete', [AdminController::class, 'deleteFaq'])->name('admin.faqs.delete');
    Route::post('/faqs/{id}/toggle', [AdminController::class, 'toggleFaq'])->name('admin.faqs.toggle');

    // Testimonial Management
    Route::get('/testimonials', [AdminController::class, 'testimonials'])->name('admin.testimonials');
    Route::post('/testimonials', [AdminController::class, 'storeTestimonial'])->name('admin.testimonials.store');
    Route::post('/testimonials/{id}/update', [AdminController::class, 'updateTestimonial'])->name('admin.testimonials.update');
    Route::post('/testimonials/{id}/delete', [AdminController::class, 'deleteTestimonial'])->name('admin.testimonials.delete');
    Route::post('/testimonials/{id}/toggle-approve', [AdminController::class, 'toggleTestimonialApprove'])->name('admin.testimonials.toggle-approve');
    Route::post('/testimonials/{id}/toggle-featured', [AdminController::class, 'toggleTestimonialFeatured'])->name('admin.testimonials.toggle-featured');

    // User Review Management
    Route::get('/reviews', [AdminController::class, 'reviews'])->name('admin.reviews');
    Route::post('/reviews/{id}/promote', [AdminController::class, 'promoteReview'])->name('admin.reviews.promote');
    Route::post('/reviews/{id}/delete', [AdminController::class, 'deleteReview'])->name('admin.reviews.delete');

    // Live Chat Support Console
    Route::get('/chat', [AdminController::class, 'chatDashboard'])->name('admin.chat');
    Route::get('/chat/conversations', [AdminController::class, 'chatConversations'])->name('admin.chat.conversations');
    Route::get('/chat/messages/{conversationId}', [AdminController::class, 'chatMessages'])->name('admin.chat.messages');
    Route::post('/chat/send', [AdminController::class, 'sendSupportMessage'])->name('admin.chat.send');
    Route::post('/chat/close/{conversationId}', [AdminController::class, 'closeConversation'])->name('admin.chat.close');
});
