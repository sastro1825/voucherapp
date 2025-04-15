<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\RedeemedVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MerchantController extends Controller
{
    public function dashboard()
    {
        if (!Auth::check() || Auth::user()->role !== 'merchant') {
            return redirect('/login');
        }
        return view('merchant.dashboard');
    }

    public function redeemVoucher(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'merchant') {
            return redirect('/login');
        }

        $request->validate(['voucher_id' => 'required']);
        $voucher = Voucher::find($request->voucher_id);

        if (!$voucher) {
            return redirect()->back()->with('notification', ['type' => 'error', 'message' => 'Voucher not found!']);
        }

        if ($voucher->status !== 'Active' || $voucher->expiration_date < now()) {
            $voucher->status = $voucher->expiration_date < now() ? 'Expired' : $voucher->status;
            $voucher->save();
            return redirect()->back()->with('notification', ['type' => 'error', 'message' => 'Voucher is invalid or expired!']);
        }

        $voucher->update([
            'status' => 'Redeemed',
            'redeemed_by' => Auth::user()->username,
            'redeemed_at' => now(),
        ]);

        RedeemedVoucher::create([
            'voucher_id' => $voucher->id,
            'user_id' => Auth::id(),
            'redeemed_at' => now(),
        ]);

        return redirect()->back()->with('notification', ['type' => 'success', 'message' => 'Voucher redeemed successfully!']);
    }

    public function redeemedVouchers()
    {
        if (!Auth::check() || Auth::user()->role !== 'merchant') {
            return redirect('/login');
        }
        $redeemed = RedeemedVoucher::where('user_id', Auth::id())->with('voucher')->get();
        return view('merchant.redeemed', compact('redeemed'));
    }
}