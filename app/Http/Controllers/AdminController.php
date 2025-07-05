<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use App\Models\Voucher;
use App\Models\MerchantBalance;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    // Tampilkan dashboard admin
    public function dashboard()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        return view('admin.dashboard');
    }

    // Formulir update nama perusahaan
    public function showUpdateCompanyForm()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $company = Setting::where('key_name', 'company_name')->first();
        return view('admin.update-company', compact('company'));
    }

    // Update nama perusahaan
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
        return redirect()->route('admin.update-company')->with('success', 'Nama perusahaan berhasil diperbarui!');
    }

    // Formulir buat voucher
    public function showCreateVoucherForm()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $merchants = User::where('role', 'merchant')->get();
        return view('admin.create-voucher', compact('merchants'));
    }

    // Buat voucher baru
    public function createVoucher(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $request->validate([
            'value' => 'required|numeric|min:1',
            'merchant_id' => 'required|exists:users,id',
        ]);
        $merchant = User::findOrFail($request->merchant_id);
        $year = Carbon::now('Asia/Jakarta')->year;
        $month = Carbon::now('Asia/Jakarta')->month;
        $balance = MerchantBalance::firstOrCreate(
            ['merchant_id' => $merchant->id, 'year' => $year, 'month' => $month],
            ['used_balance' => 0, 'remaining_balance' => 1000000000]
        );
        $newVoucherValue = $request->value;
        $newUsedBalance = $balance->used_balance + $newVoucherValue;
        if ($newUsedBalance > 1000000000) {
            return redirect()->back()->with('error', 'Limit saldo terlampaui!');
        }
        $balance->update(['used_balance' => $newUsedBalance, 'remaining_balance' => 1000000000 - $newUsedBalance]);
        $voucher_id = 'VCH' . Carbon::now('Asia/Jakarta')->format('Ymd') . rand(100, 999);
        Voucher::create([
            'id' => $voucher_id,
            'company_name' => Setting::where('key_name', 'company_name')->first()->value ?? 'My Company',
            'value' => $newVoucherValue,
            'merchant_id' => $merchant->id,
            'created_date' => Carbon::now('Asia/Jakarta'),
            'expiration_date' => Carbon::now('Asia/Jakarta')->addMonths(3),
            'status' => 'Active',
        ]);
        return redirect()->route('admin.create-voucher')->with('success', 'Voucher berhasil dibuat!');
    }

    // Formulir buat merchant
    public function showCreateMerchantForm()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        return view('admin.create-merchant');
    }

    // Buat merchant baru
    public function createMerchant(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $request->validate([
            'username' => 'required|unique:users',
            'merchant_name' => 'required|string|max:255',
            'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'whatsapp_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'information' => 'nullable|string',
            'remaining_balance' => 'required|numeric|min:0|max:1000000000',
        ], [
            'username.required' => 'Username harus diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'merchant_name.required' => 'Nama merchant harus diisi.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus punya huruf kecil, besar, angka, dan karakter khusus.',
            'whatsapp_number.required' => 'Nomor WhatsApp harus diisi.',
            'whatsapp_number.regex' => 'Nomor WhatsApp hanya boleh angka, spasi, atau tanda khusus.',
            'whatsapp_number.min' => 'Nomor WhatsApp minimal 10 karakter.',
            'remaining_balance.required' => 'Saldo merchant harus diisi.',
            'remaining_balance.numeric' => 'Saldo merchant harus angka.',
            'remaining_balance.min' => 'Saldo merchant tidak boleh kurang dari 0.',
            'remaining_balance.max' => 'Saldo merchant maksimal 1 miliar.',
        ]);
        DB::beginTransaction();
        try {
            $user = User::create([
                'username' => $request->username,
                'merchant_name' => $request->merchant_name,
                'password' => Hash::make($request->password),
                'whatsapp_number' => $request->whatsapp_number,
                'information' => $request->information,
                'role' => 'merchant',
            ]);
            MerchantBalance::create([
                'merchant_id' => $user->id,
                'year' => Carbon::now('Asia/Jakarta')->year,
                'month' => Carbon::now('Asia/Jakarta')->month,
                'used_balance' => 1000000000 - $request->remaining_balance,
                'remaining_balance' => $request->remaining_balance,
            ]);
            DB::commit();
            return redirect()->route('admin.create-merchant')->with('success', 'Merchant berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal membuat merchant: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat merchant. Coba lagi.');
        }
    }

    // Daftar semua voucher
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
                $query->where('expiration_date', '<', Carbon::now('Asia/Jakarta'))->where('status', '!=', 'Redeemed');
                break;
        }
        $vouchers = $query->get();
        return view('admin.vouchers', compact('vouchers', 'filter'));
    }

    // Detail voucher dengan QR code
    public function showVoucher($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $voucher = Voucher::findOrFail($id);
        $qrCode = QrCode::size(150)->generate($voucher->id);
        return view('voucher.show', compact('voucher', 'qrCode'));
    }

    // Kirim link voucher
    public function sendVoucher($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $voucher = Voucher::findOrFail($id);
        $publicUrl = url('/voucher/public/' . $voucher->id);
        $whatsappUrl = "https://wa.me/?text=" . urlencode("Your Voucher Details: {$publicUrl}");
        return redirect()->back()->with('success', 'Link voucher dapat dibuka')->with('public_url', $publicUrl);
    }

    // Halaman publik voucher
    public function showPublicVoucher($id)
    {
        $voucher = Voucher::findOrFail($id);
        $qrCode = QrCode::size(300)->generate($voucher->id);
        return view('voucher.public', compact('voucher', 'qrCode'));
    }

    // Edit voucher
    public function editVoucher(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $voucher = Voucher::findOrFail($id);
        if ($request->isMethod('post')) {
            $request->validate(['value' => 'required|numeric']);
            $voucher->update(['value' => $request->value]);
            return redirect()->route('admin.vouchers')->with('success', 'Voucher berhasil diperbarui!');
        }
        return view('voucher.edit', compact('voucher'));
    }

    // Hapus voucher
    public function deleteVoucher($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $voucher = Voucher::findOrFail($id);
        $merchant_id = $voucher->merchant_id;
        $voucherValue = $voucher->value;
        $createdDate = Carbon::parse($voucher->getRawOriginal('created_date'), 'Asia/Jakarta');
        $year = $createdDate->year;
        $month = $createdDate->month;
        $balance = MerchantBalance::where('merchant_id', $merchant_id)
            ->where('year', $year)
            ->where('month', $month)
            ->first();
        if ($balance) {
            $newUsedBalance = max(0, $balance->used_balance - $voucherValue);
            $balance->update([
                'used_balance' => $newUsedBalance,
                'remaining_balance' => 1000000000 - $newUsedBalance,
            ]);
        }
        $voucher->delete();
        return redirect()->back()->with('success', 'Voucher berhasil dihapus!');
    }

    // Kirim link voucher ke WhatsApp
    public function sendVoucherLinkToWhatsApp($voucherId, $whatsappNumber)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $voucher = Voucher::findOrFail($voucherId);
        $merchant = User::findOrFail($voucher->merchant_id);
        $voucherLink = url("/voucher/public/{$voucher->id}");
        if (!preg_match('/^([0-9\s\-\+\(\)]*)$/', $whatsappNumber) || strlen($whatsappNumber) < 10) {
            return redirect()->back()->with('error', 'Nomor WhatsApp tidak valid.');
        }
        if ($voucher->sent_to === $whatsappNumber) {
            return redirect()->back()->with('warning', "Voucher sudah dikirim ke {$whatsappNumber}.");
        }
        if (!str_starts_with($whatsappNumber, '62')) {
            $whatsappNumber = '62' . ltrim($whatsappNumber, '0');
        }
        $merchantName = !empty($merchant->merchant_name) ? $merchant->merchant_name : $merchant->username;
        $merchantInfo = !empty($merchant->information) ? $merchant->information : $merchant->username;
        $minimumPurchase = number_format($voucher->value * 2, 0, ',', '.');
        $expirationDate = Carbon::parse($voucher->expiration_date, 'Asia/Jakarta')->format('d-m-Y');
        $message = "Yth. Pelanggan,\n\nSelamat! Anda dapat voucher belanja.\nLink: {$voucherLink}\n\nSyarat:\n- Gunakan di: {$merchantName}\n- Alamat: {$merchantInfo}\n- Min. belanja: Rp {$minimumPurchase}\n- Berlaku hingga: {$expirationDate}\n\nTerima kasih";
        $token = env('WABLAS_API_TOKEN');
        $secretKey = env('WABLAS_SECRET_KEY');
        $authHeader = $secretKey ? "$token.$secretKey" : $token;
        $payload = [
            "data" => [
                ["phone" => $whatsappNumber, "message" => $message, "isGroup" => false]
            ]
        ];
        $response = Http::withHeaders(["Authorization" => $authHeader, "Content-Type" => "application/json"])
            ->post(env('WABLAS_API_URL'), $payload);
        Log::info('WABLAS API response', ['response' => $response->json()]);
        if ($response->successful()) {
            $voucher->update(['sent_to' => $whatsappNumber, 'sent_status' => 'sent', 'sent_at' => Carbon::now('Asia/Jakarta')]);
            return redirect()->back()->with('success', 'Link voucher berhasil dikirim ke WhatsApp.');
        } else {
            $errorMessage = $response->json()['message'] ?? 'Unknown error';
            Log::error('Gagal kirim WhatsApp via WABLAS', ['error' => $errorMessage]);
            return redirect()->back()->with('error', 'Gagal kirim link voucher: ' . $errorMessage);
        }
    }

    // Daftar semua pengguna
    public function allUsers(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $search = $request->query('search');
        $year = Carbon::now('Asia/Jakarta')->year;
        $month = Carbon::now('Asia/Jakarta')->month;
        $query = User::select('id', 'username', 'merchant_name', 'whatsapp_number', 'information', 'role')
            ->with(['merchantBalances' => function ($query) use ($year, $month) {
                $query->where('year', $year)->where('month', $month);
            }]);
        if ($search) {
            $query->where('username', 'like', '%' . $search . '%');
        }
        $users = $query->get();
        return view('admin.users', compact('users', 'search'));
    }

    // Edit pengguna
    public function editUser(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $user = User::findOrFail($id);
        $year = Carbon::now('Asia/Jakarta')->year;
        $month = Carbon::now('Asia/Jakarta')->month;
        if ($request->isMethod('post')) {
            $rules = [
                'username' => 'required|unique:users,username,' . $id,
                'whatsapp_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'information' => 'nullable|string',
                'password' => 'nullable|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            ];
            if ($user->role === 'merchant') {
                $rules['merchant_name'] = 'required|string|max:255';
                $rules['remaining_balance'] = 'required|numeric|min:0|max:1000000000';
            }
            $messages = [
                'username.required' => 'Username harus diisi.',
                'username.unique' => 'Username sudah digunakan.',
                'merchant_name.required' => 'Nama merchant harus diisi.',
                'whatsapp_number.required' => 'Nomor WhatsApp harus diisi.',
                'whatsapp_number.regex' => 'Nomor WhatsApp hanya boleh angka, spasi, atau tanda khusus.',
                'whatsapp_number.min' => 'Nomor WhatsApp minimal 10 karakter.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.regex' => 'Password harus punya huruf kecil, besar, angka, dan karakter khusus.',
                'remaining_balance.required' => 'Saldo merchant harus diisi.',
                'remaining_balance.numeric' => 'Saldo merchant harus angka.',
                'remaining_balance.min' => 'Saldo merchant tidak boleh kurang dari 0.',
                'remaining_balance.max' => 'Saldo merchant maksimal 1 miliar.',
            ];
            $request->validate($rules, $messages);
            $user->username = $request->username;
            $user->merchant_name = $request->merchant_name;
            $user->whatsapp_number = $request->whatsapp_number;
            $user->information = $request->information;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
            if ($user->role === 'merchant') {
                $balance = MerchantBalance::firstOrCreate(
                    ['merchant_id' => $user->id, 'year' => $year, 'month' => $month],
                    ['used_balance' => 0, 'remaining_balance' => 1000000000]
                );
                $newRemainingBalance = $request->remaining_balance;
                $newUsedBalance = 1000000000 - $newRemainingBalance;
                $balance->update(['used_balance' => $newUsedBalance, 'remaining_balance' => $newRemainingBalance]);
            }
            return redirect()->route('admin.users')->with('success', 'Pengguna berhasil diperbarui!');
        }
        $balance = null;
        if ($user->role === 'merchant') {
            $balance = MerchantBalance::where('merchant_id', $user->id)
                ->where('year', $year)
                ->where('month', $month)
                ->first();
        }
        return view('admin.edit-user', compact('user', 'balance'));
    }

    // Hapus pengguna
    public function deleteUser($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $user = User::findOrFail($id);
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Tidak bisa hapus admin.');
        }
        $user->delete();
        return redirect()->back()->with('success', 'Pengguna berhasil dihapus!');
    }
}