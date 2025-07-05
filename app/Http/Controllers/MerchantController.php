<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\RedeemedVoucher;
use App\Models\MerchantBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MerchantController extends Controller
{
    // Tampilkan dashboard merchant
    public function dashboard()
    {
        if (!Auth::check() || Auth::user()->role !== 'merchant') {
            return redirect('/login');
        }
        $year = Carbon::now('Asia/Jakarta')->year;
        $month = Carbon::now('Asia/Jakarta')->month;
        $balance = MerchantBalance::where('merchant_id', Auth::id())
            ->where('year', $year)
            ->where('month', $month)
            ->first();
        $voucherUsedThisMonth = $balance ? $balance->used_balance : 0;
        $remainingBalance = $balance ? $balance->remaining_balance : 1000000000;
        return view('merchant.dashboard', compact('voucherUsedThisMonth', 'remainingBalance'));
    }

    // Proses penukaran voucher
    public function redeemVoucher(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'merchant') {
            return redirect('/login');
        }
        $request->validate(['voucher_id' => 'required']);
        $voucher = Voucher::find($request->voucher_id);
        if (!$voucher) {
            return redirect()->back()->with('notification', ['type' => 'error', 'message' => 'Voucher tidak ditemukan!']);
        }
        if ($voucher->status !== 'Active' || Carbon::parse($voucher->expiration_date, 'Asia/Jakarta') < Carbon::now('Asia/Jakarta')) {
            $voucher->status = Carbon::parse($voucher->expiration_date, 'Asia/Jakarta') < Carbon::now('Asia/Jakarta') ? 'Expired' : $voucher->status;
            $voucher->save();
            return redirect()->back()->with('notification', ['type' => 'error', 'message' => 'Voucher tidak valid atau kadaluarsa!']);
        }
        if ($voucher->merchant_id !== Auth::id()) {
            return redirect()->back()->with('notification', ['type' => 'error', 'message' => 'Anda tidak berhak menukar voucher ini!']);
        }
        $voucher->update([
            'status' => 'Redeemed',
            'redeemed_by' => Auth::user()->username,
            'redeemed_at' => Carbon::now('Asia/Jakarta'),
        ]);
        RedeemedVoucher::create([
            'voucher_id' => $voucher->id,
            'user_id' => Auth::id(),
            'redeemed_at' => Carbon::now('Asia/Jakarta'),
        ]);
        return redirect()->back()->with('notification', ['type' => 'success', 'message' => 'Voucher berhasil ditukar!']);
    }

    // Daftar voucher yang sudah ditukar
    public function redeemedVouchers()
    {
        if (!Auth::check() || Auth::user()->role !== 'merchant') {
            return redirect('/login');
        }
        $redeemed = RedeemedVoucher::where('user_id', Auth::id())->with('voucher')->get();
        return view('merchant.redeemed', compact('redeemed'));
    }
}