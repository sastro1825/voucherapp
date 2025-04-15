<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

// Redirect root (/) ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rute Publik (tanpa middleware auth)
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
// Terapkan rate limiting: maksimal 5 percobaan per menit
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:5,1');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Rute Registrasi
Route::get('/register', [AuthenticatedSessionController::class, 'showRegisterForm'])->name('register');
// Terapkan rate limiting: maksimal 5 percobaan per menit
Route::post('/register', [AuthenticatedSessionController::class, 'register'])->name('register.store')->middleware('throttle:5,1');

// Rute Reset Password
Route::get('/password/reset', [AuthenticatedSessionController::class, 'showResetRequestForm'])->name('password.request');
Route::post('/password/email', [AuthenticatedSessionController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}/{username}', [AuthenticatedSessionController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [AuthenticatedSessionController::class, 'reset'])->name('password.update');

// Rute Voucher Publik
Route::get('/voucher/public/{id}', [AdminController::class, 'showPublicVoucher'])->name('voucher.public');

// Rute yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    // Rute untuk Admin
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/create-voucher', [AdminController::class, 'showCreateVoucherForm'])->name('admin.create-voucher');
        Route::post('/create-voucher', [AdminController::class, 'createVoucher'])->name('admin.create-voucher.submit');
        Route::get('/create-merchant', [AdminController::class, 'showCreateMerchantForm'])->name('admin.create-merchant');
        Route::post('/create-merchant', [AdminController::class, 'createMerchant'])->name('admin.create-merchant.submit');
        Route::get('/vouchers', [AdminController::class, 'allVouchers'])->name('admin.vouchers');
        Route::get('/update-company', [AdminController::class, 'showUpdateCompanyForm'])->name('admin.update-company');
        Route::post('/update-company', [AdminController::class, 'updateCompany'])->name('admin.update-company.submit');
    });

    // Rute untuk Voucher (Admin)
    Route::prefix('voucher')->middleware('role:admin')->group(function () {
        Route::get('{id}/send', [AdminController::class, 'sendVoucher'])->name('voucher.send');
        Route::get('{id}/edit', [AdminController::class, 'editVoucher'])->name('voucher.edit');
        Route::put('{id}/update', [AdminController::class, 'editVoucher'])->name('voucher.update');
        Route::delete('{id}/delete', [AdminController::class, 'deleteVoucher'])->name('voucher.delete');
        Route::get('{voucherId}/send-to-merchant/{username}', [AdminController::class, 'sendVoucherLinkToMerchant'])->name('voucher.send-to-merchant');
    });

    // Rute untuk Merchant
    Route::prefix('merchant')->middleware('role:merchant')->group(function () {
        Route::get('/dashboard', [MerchantController::class, 'dashboard'])->name('merchant.dashboard');
        Route::post('/redeem-voucher', [MerchantController::class, 'redeemVoucher'])->name('merchant.redeem-voucher');
        Route::get('/redeemed-vouchers', [MerchantController::class, 'redeemedVouchers'])->name('merchant.redeemed-vouchers');
    });

    // Rute untuk Profil (berlaku untuk Admin dan Merchant)
    Route::get('/profile', [AuthenticatedSessionController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [AuthenticatedSessionController::class, 'updateProfile'])->name('profile.update');
});