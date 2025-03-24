<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/update-company', [AdminController::class, 'updateCompany'])->name('admin.update-company');
    Route::post('/admin/create-voucher', [AdminController::class, 'createVoucher'])->name('admin.create-voucher');
    Route::post('/admin/create-merchant', [AdminController::class, 'createMerchant'])->name('admin.create-merchant');
    Route::get('/admin/vouchers', [AdminController::class, 'allVouchers'])->name('admin.vouchers');
    Route::get('/voucher/{id}', [AdminController::class, 'showVoucher'])->name('voucher.show');
    Route::get('/voucher/{id}/send', [AdminController::class, 'sendVoucher'])->name('voucher.send');
    Route::match(['get', 'post'], '/voucher/{id}/edit', [AdminController::class, 'editVoucher'])->name('voucher.edit');
    Route::get('/voucher/{id}/delete', [AdminController::class, 'deleteVoucher'])->name('voucher.delete');

    Route::get('/merchant/dashboard', [MerchantController::class, 'dashboard'])->name('merchant.dashboard');
    Route::post('/merchant/redeem-voucher', [MerchantController::class, 'redeemVoucher'])->name('merchant.redeem-voucher');
    Route::get('/merchant/redeemed-vouchers', [MerchantController::class, 'redeemedVouchers'])->name('merchant.redeemed-vouchers');
});