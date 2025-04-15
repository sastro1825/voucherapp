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

Route::get('/voucher/public/{id}', [AdminController::class, 'showPublicVoucher'])->name('voucher.public');

// Rute dengan Middleware Auth
Route::middleware(['auth'])->group(function () {
    // Rute Admin
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // Update Company Name
    Route::get('/admin/update-company', [AdminController::class, 'showUpdateCompanyForm'])->name('admin.update-company');
    Route::post('/admin/update-company', [AdminController::class, 'updateCompany'])->name('admin.update-company.submit');
    // Create Voucher
    Route::get('/admin/create-voucher', [AdminController::class, 'showCreateVoucherForm'])->name('admin.create-voucher');
    Route::post('/admin/create-voucher', [AdminController::class, 'createVoucher'])->name('admin.create-voucher.submit');
    // Create Merchant
    Route::get('/admin/create-merchant', [AdminController::class, 'showCreateMerchantForm'])->name('admin.create-merchant');
    Route::post('/admin/create-merchant', [AdminController::class, 'createMerchant'])->name('admin.create-merchant.submit');
    // View All Vouchers
    Route::get('/admin/vouchers', [AdminController::class, 'allVouchers'])->name('admin.vouchers');
    Route::get('/voucher/{id}', [AdminController::class, 'showVoucher'])->name('voucher.show');
    Route::get('/voucher/{id}/send', [AdminController::class, 'sendVoucher'])->name('voucher.send');
    Route::match(['get', 'post'], '/voucher/{id}/edit', [AdminController::class, 'editVoucher'])->name('voucher.edit');
    Route::delete('/voucher/{id}/delete', [AdminController::class, 'deleteVoucher'])->name('voucher.delete');
    // Rute untuk mengirim link voucher ke WhatsApp
    Route::get('/voucher/{voucherId}/send-to-merchant/{username}', [AdminController::class, 'sendVoucherLinkToMerchant'])->name('voucher.send-to-merchant');

    // Rute Merchant
    Route::get('/merchant/dashboard', [MerchantController::class, 'dashboard'])->name('merchant.dashboard');
    Route::post('/merchant/redeem-voucher', [MerchantController::class, 'redeemVoucher'])->name('merchant.redeem-voucher');
    Route::get('/merchant/redeemed-vouchers', [MerchantController::class, 'redeemedVouchers'])->name('merchant.redeemed-vouchers');

    // Rute Profil Pengguna
    Route::get('/profile', [AuthenticatedSessionController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [AuthenticatedSessionController::class, 'updateProfile'])->name('profile.update');
});