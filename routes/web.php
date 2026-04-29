<?php
// FILE: routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\FaqChatController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminCarController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminAuditController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDriverController;
use App\Http\Controllers\Driver\DriverDashboardController;

// ── Public ────────────────────────────────────────────────────────────────
Route::get('/',           [HomeController::class, 'index'])->name('home');
Route::get('/about',      [HomeController::class, 'about'])->name('about');
Route::get('/contact',    [HomeController::class, 'contact'])->name('contact');
Route::get('/shop',       [CarController::class, 'index'])->name('shop');
Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');

// ── FAQ AI Chat (public — no login required) ──────────────────────────────
// NOTE: Do NOT use /api/ prefix here — in Laravel 12 that prefix is
//       reserved for routes/api.php (which skips web middleware + CSRF).
//       Using /faq-chat keeps it in the web middleware group so CSRF works.
Route::post('/faq-chat', [FaqChatController::class, 'chat'])
    ->middleware(['throttle:15,1'])
    ->name('faq.chat');

// ── Guest Auth ────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register',               [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',              [AuthController::class, 'register'])->name('register.post');
    Route::get('/login',                  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',                 [AuthController::class, 'login'])->name('login.post');
    Route::get('/forgot-password',        [AuthController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password',       [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password',        [AuthController::class, 'resetPassword'])->name('password.store');
});

// ── Auth ───────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout',     [AuthController::class, 'logout'])->name('logout');
    Route::get('/otp/verify',  [AuthController::class, 'showOtp'])->name('otp.verify');
    Route::post('/otp/verify', [AuthController::class, 'verifyOtp'])->name('otp.verify.post');
    Route::post('/otp/resend', [AuthController::class, 'resendOtp'])->name('otp.resend');
});

// ── Customer (auth + email verified) ──────────────────────────────────────
Route::middleware(['auth', 'email.verified'])->group(function () {
    // Orders
    Route::get('/orders',                 [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/buy/{car}',       [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders',                [OrderController::class, 'store'])->name('orders.store');
    Route::post('/orders/pay',            [OrderController::class, 'processPayment'])->name('orders.pay');
    Route::get('/orders/{order}',         [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Cart
    Route::get('/cart',                  [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add',             [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{cartItem}',    [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/cart/{cartItem}',     [CartController::class, 'update'])->name('cart.update');
    Route::get('/cart/checkout',         [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/cart/payment',         [CartController::class, 'payment'])->name('cart.payment');
    Route::post('/cart/process-payment', [CartController::class, 'processPayment'])->name('cart.process-payment');

    // Chat (Customer ↔ Driver)
    Route::get('/chat/{order}',      [MessageController::class, 'show'])->name('chat.show');
    Route::post('/chat/{order}',     [MessageController::class, 'send'])->name('chat.send');
    Route::get('/chat/{order}/poll', [MessageController::class, 'poll'])->name('chat.poll');
    Route::get('/messages/unread',   [MessageController::class, 'unreadCount'])->name('messages.unread');
});

// ── Driver ─────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'driver'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/',                           [DriverDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders',                     [DriverDashboardController::class, 'orders'])->name('orders');
    Route::post('/orders/{order}/delivered',  [DriverDashboardController::class, 'markDelivered'])->name('mark-delivered');
    Route::post('/orders/{order}/cod-paid',   [DriverDashboardController::class, 'markCodPaid'])->name('mark-cod-paid');

    // Driver ↔ Customer chat
    Route::get('/chat/{order}',              [MessageController::class, 'show'])->name('chat');
    Route::post('/chat/{order}',             [MessageController::class, 'send'])->name('chat.send');
    Route::get('/chat/{order}/poll',         [MessageController::class, 'poll'])->name('chat.poll');

    // Driver ↔ Admin chat
    Route::get('/admin-chat/{order}',        [MessageController::class, 'show'])->name('admin-chat');
    Route::post('/admin-chat/{order}',       [MessageController::class, 'send'])->name('admin-chat.send');
    Route::get('/admin-chat/{order}/poll',   [MessageController::class, 'poll'])->name('admin-chat.poll');
});

// ── Admin ──────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    // ── Admin Login (no middleware) ────────────────────────────
    Route::get('/login',  [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');

    // ── Admin Forgot / Reset Password ──
    Route::get('/forgot-password',         [AdminAuthController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password',        [AdminAuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}',  [AdminAuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password',         [AdminAuthController::class, 'resetPassword'])->name('password.update');

    // ── Protected admin panel ──────────────────────────────────
    Route::middleware(['auth', 'admin'])->group(function () {

        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::post('/verify-password', [AdminAuthController::class, 'verifyPassword'])->name('verify-password');
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Cars CRUD
        Route::resource('cars', AdminCarController::class);

        // Orders
        Route::get('orders',                        [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}',                [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('orders/{order}/accept',        [AdminOrderController::class, 'accept'])->name('orders.accept');
        Route::patch('orders/{order}/status',       [AdminOrderController::class, 'updateStatus'])->name('orders.status');
        Route::post('orders/{order}/assign-driver', [AdminOrderController::class, 'assignDriver'])->name('orders.assign-driver');

        // Admin ↔ Driver chat
        Route::get('chat/{order}',       [MessageController::class, 'show'])->name('chat.show');
        Route::post('chat/{order}',      [MessageController::class, 'send'])->name('chat.send');
        Route::get('chat/{order}/poll',  [MessageController::class, 'poll'])->name('chat.poll');

        // Drivers
        Route::resource('drivers', AdminDriverController::class);

        // Users
        Route::get('users',           [AdminUserController::class, 'index'])->name('users.index');
        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // Audit + Reports
        Route::get('audit-logs', [AdminAuditController::class, 'index'])->name('audit.index');
        Route::get('reports',    [AdminReportController::class, 'index'])->name('reports.index');
    });
});