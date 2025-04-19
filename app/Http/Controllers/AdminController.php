<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        return view('admin.dashboard');
    }

    public function showUpdateCompanyForm()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $company = Setting::where('key_name', 'company_name')->first();
        return view('admin.update-company', compact('company'));
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
        return redirect()->route('admin.update-company')->with('success', 'Company name updated successfully!');
    }

    public function showCreateVoucherForm()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        return view('admin.create-voucher');
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
        ]);
        return redirect()->route('admin.create-voucher')->with('success', 'Voucher created successfully!');
    }

    public function showCreateMerchantForm()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        return view('admin.create-merchant');
    }

    public function createMerchant(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'whatsapp_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'information' => 'nullable|string',
        ], [
            'username.required' => 'Username harus diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf kecil, huruf besar, angka, dan karakter khusus (seperti @$!%*?&).',
            'whatsapp_number.required' => 'Nomor WhatsApp harus diisi.',
            'whatsapp_number.regex' => 'Nomor WhatsApp hanya boleh berisi angka, spasi, tanda minus, plus, atau tanda kurung.',
            'whatsapp_number.min' => 'Nomor WhatsApp minimal 10 karakter.',
        ]);

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'whatsapp_number' => $request->whatsapp_number,
            'information' => $request->information,
            'role' => 'merchant',
        ]);
        return redirect()->route('admin.create-merchant')->with('success', 'Merchant created successfully!');
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
            default:
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
        $publicUrl = url('/voucher/public/' . $voucher->id);
        $whatsappUrl = "https://wa.me/?text=" . urlencode("Your Voucher Details: {$publicUrl}");
        return redirect()->back()->with('success', 'Voucher can be opened')->with('public_url', $publicUrl);
    }

    public function showPublicVoucher($id)
    {
        $voucher = Voucher::findOrFail($id);
        $qrCode = QrCode::size(300)->generate($voucher->id);
        return view('voucher.public', compact('voucher', 'qrCode'));
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

    public function sendVoucherLinkToMerchant($voucherId, $username)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $voucher = Voucher::findOrFail($voucherId);
        $voucherLink = url("/voucher/public/{$voucher->id}");
        $merchant = User::where('username', $username)->where('role', 'merchant')->first();
        if (!$merchant) {
            return redirect()->back()->with('error', 'Merchant dengan username tersebut tidak ditemukan.');
        }
        $whatsappNumber = $merchant->whatsapp_number;
        if (!$whatsappNumber) {
            return redirect()->back()->with('error', 'Nomor WhatsApp merchant tidak ditemukan.');
        }
        if ($voucher->sent_to === $username) {
            return redirect()->back()->with('warning', "Voucher ini sudah pernah dikirim ke merchant {$username}.");
        }
        if (!str_starts_with($whatsappNumber, '62')) {
            $whatsappNumber = '62' . ltrim($whatsappNumber, '0');
        }
        $message = "Halo {$merchant->username},\nBerikut adalah link voucher yang dapat Anda gunakan:\n{$voucherLink}\nTerima kasih!";
        $token = env('WABLAS_API_TOKEN');
        $secretKey = env('WABLAS_SECRET_KEY');
        $authHeader = $secretKey ? "$token.$secretKey" : $token;
        $payload = [
            "data" => [
                [
                    "phone" => $whatsappNumber,
                    "message" => $message,
                    "isGroup" => false,
                ]
            ]
        ];
        $response = Http::withHeaders([
            "Authorization" => $authHeader,
            "Content-Type" => "application/json",
        ])->post(env('WABLAS_API_URL'), $payload);
        Log::info('WABLAS API response', ['response' => $response->json()]);
        if ($response->successful()) {
            $responseData = $response->json();
            if (isset($responseData['status']) && $responseData['status'] === 'success') {
                $voucher->update(['sent_to' => $username]);
                return redirect()->back()->with('success', 'Link voucher berhasil dikirim ke WhatsApp merchant.');
            } elseif (isset($responseData['message']) && str_contains($responseData['message'], 'pending')) {
                return redirect()->back()->with('warning', 'Pesan sedang dalam antrian untuk dikirim ke WhatsApp. Silakan tunggu beberapa saat.');
            } else {
                $errorMessage = $responseData['message'] ?? 'Unknown error';
                Log::error('Failed to send WhatsApp message via WABLAS', ['error' => $errorMessage]);
                return redirect()->back()->with('error', 'Gagal mengirim link voucher ke WhatsApp: ' . $errorMessage);
            }
        } else {
            $errorMessage = $response->json()['message'] ?? 'Unknown error';
            Log::error('Failed to send WhatsApp message via WABLAS', ['error' => $errorMessage]);
            return redirect()->back()->with('error', 'Gagal mengirim link voucher ke WhatsApp: ' . $errorMessage);
        }
    }

    public function allUsers(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $search = $request->query('search');
        $query = User::select('id', 'username', 'whatsapp_number', 'information', 'role');
        if ($search) {
            $query->where('username', 'like', '%' . $search . '%');
        }
        $users = $query->get();
        return view('admin.users', compact('users', 'search'));
    }

    public function editUser(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $user = User::findOrFail($id);
        if ($request->isMethod('post')) {
            $request->validate([
                'username' => 'required|unique:users,username,' . $id,
                'whatsapp_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'information' => 'nullable|string',
                'password' => 'nullable|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            ], [
                'username.required' => 'Username harus diisi.',
                'username.unique' => 'Username sudah digunakan.',
                'whatsapp_number.required' => 'Nomor WhatsApp harus diisi.',
                'whatsapp_number.regex' => 'Nomor WhatsApp hanya boleh berisi angka, spasi, tanda minus, plus, atau tanda kurung.',
                'whatsapp_number.min' => 'Nomor WhatsApp minimal 10 karakter.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.regex' => 'Password harus mengandung huruf kecil, huruf besar, angka, dan karakter khusus (seperti @$!%*?&).',
            ]);

            $user->username = $request->username;
            $user->whatsapp_number = $request->whatsapp_number;
            $user->information = $request->information;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            return redirect()->route('admin.users')->with('success', 'User updated successfully!');
        }
        return view('admin.edit-user', compact('user'));
    }

    public function deleteUser($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }
        $user = User::findOrFail($id);
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Cannot delete an admin user.');
        }
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully!');
    }
}