<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $company = Setting::where('key_name', 'company_name')->first();
        return view('admin.dashboard', compact('company'));
    }

    public function updateCompany(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $request->validate(['company_name' => 'required']);
        Setting::updateOrCreate(
            ['key_name' => 'company_name'],
            ['value' => $request->company_name]
        );
        return redirect()->back()->with('success', 'Company name updated successfully!');
    }

    public function createVoucher(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $request->validate(['value' => 'required']);
        $voucher_id = 'VCH' . date('Ymd') . rand(100, 999);
        Voucher::create([
            'id' => $voucher_id,
            'company_name' => Setting::where('key_name', 'company_name')->first()->value ?? 'My Company',
            'value' => $request->value,
            'created_date' => now(),
            'expiration_date' => now()->addYear(),
            'status' => 'Active',
            'send_status' => 'not_sent',
        ]);
        return redirect()->back()->with('success', 'Voucher created successfully!');
    }

    public function createMerchant(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
        ]);
        User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => 'merchant',
        ]);
        return redirect()->back()->with('success', 'Merchant created successfully!');
    }

    public function allVouchers(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $filter = $request->query('filter', 'all');
        $query = Voucher::query();

        switch ($filter) {
            case 'active':
                $query->where('status', 'Active');
                break;
            case 'redeemed':
                $query->where('status', 'Redeemed');
                break;
            case 'expired':
                $query->where('expiration_date', '<', now())->where('status', '!=', 'Redeemed');
                break;
            case 'sending':
                $query->where('send_status', 'sending');
                break;
            case 'all':
            default:
                // Tidak ada filter tambahan
                break;
        }

        $vouchers = $query->get();
        return view('admin.vouchers', compact('vouchers', 'filter'));
    }

    public function showVoucher($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $voucher = Voucher::findOrFail($id);
        $qrCode = QrCode::size(150)->generate($voucher->id);
        return view('voucher.show', compact('voucher', 'qrCode'));
    }

    public function sendVoucher($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $voucher = Voucher::findOrFail($id);
        $voucher->update(['send_status' => 'sending']);

        // Simulasi pengiriman ke WhatsApp (gunakan API WhatsApp jika ada)
        $whatsappUrl = "https://wa.me/?text=" . urlencode("Voucher ID: {$voucher->id}\nValue: {$voucher->value}\nQR Code: " . url('/voucher/' . $voucher->id));
        // Dalam produksi, Anda bisa integrasikan API WhatsApp resmi

        $voucher->update(['send_status' => 'sent']);
        return redirect()->back()->with('success', 'Voucher sent to WhatsApp!');
    }

    public function editVoucher(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $voucher = Voucher::findOrFail($id);
        if ($request->isMethod('post')) {
            $request->validate(['value' => 'required|numeric']);
            $voucher->update(['value' => $request->value]);
            return redirect()->route('admin.vouchers')->with('success', 'Voucher updated successfully!');
        }
        return view('voucher.edit', compact('voucher'));
    }

    public function deleteVoucher($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();
        return redirect()->back()->with('success', 'Voucher deleted successfully!');
    }
}