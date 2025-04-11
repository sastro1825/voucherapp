<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $credentials = $request->only('username', 'password');
        Log::info('Login attempt', $credentials);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            Log::info('Login failed for username: ' . $request->username);
            throw ValidationException::withMessages([
                'username' => [trans('auth.failed')],
            ]);
        }

        $user = Auth::user();
        Log::info('Login successful for user: ' . $user->username);
        $request->session()->regenerate();
        Log::info('Session regenerated, user_id: ' . Auth::id() . ', role: ' . $user->role);

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'merchant') {
            return redirect()->route('merchant.dashboard');
        }

        return redirect('/login');
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users',
            'whatsapp_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'password' => [
                'required',
                'string',
                'min:8', // Minimal 8 karakter
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', // Harus ada huruf kecil, huruf besar, angka, dan karakter khusus
                'confirmed',
            ],
        ], [
            'username.required' => 'Username harus diisi.',
            'username.string' => 'Username harus berupa teks.',
            'username.unique' => 'Username sudah digunakan, silakan pilih username lain.',
            'whatsapp_number.required' => 'Nomor WhatsApp harus diisi.',
            'whatsapp_number.regex' => 'Nomor WhatsApp hanya boleh berisi angka, spasi, tanda minus, plus, atau tanda kurung.',
            'whatsapp_number.min' => 'Nomor WhatsApp minimal 10 karakter.',
            'password.required' => 'Password harus diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf kecil, huruf besar, angka, dan karakter khusus (seperti @$!%*?&).',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'username' => $request->username,
            'whatsapp_number' => $request->whatsapp_number,
            'password' => Hash::make($request->password),
            'role' => 'merchant', // Default role untuk registrasi adalah merchant
        ]);

        Log::info('User registered successfully', ['username' => $user->username]);

        // Login otomatis setelah registrasi
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('merchant.dashboard')->with('success', 'Registration successful! Welcome, ' . $user->username);
    }

    public function showResetRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'username' => 'required|string|exists:users,username',
        ]);

        $user = User::where('username', $request->username)->first();
        $token = Str::random(60);

        // Simpan token di tabel password_resets
        DB::table('password_resets')->updateOrInsert(
            ['username' => $user->username],
            ['token' => $token, 'created_at' => now()]
        );

        // Buat URL reset password
        $resetUrl = route('password.reset', ['token' => $token, 'username' => $user->username]);

        // Kirim pesan WhatsApp menggunakan API Wablas
        $whatsappNumber = $user->whatsapp_number;
        if (!str_starts_with($whatsappNumber, '62')) {
            $whatsappNumber = '62' . ltrim($whatsappNumber, '0'); // Pastikan format nomor benar
        }
        $message = "Reset your password here: $resetUrl";

        $curl = curl_init();
        $token = env('WABLAS_API_TOKEN');
        $secretKey = env('WABLAS_SECRET_KEY'); // Tambahkan secret key jika diperlukan
        $authHeader = $secretKey ? "$token.$secretKey" : $token; // Kombinasi token dan secret key

        $payload = [
            "data" => [
                [
                    "phone" => $whatsappNumber,
                    "message" => $message,
                    "isGroup" => false,
                ]
            ]
        ];

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: $authHeader",
            "Content-Type: application/json",
        ]);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($curl, CURLOPT_URL, env('WABLAS_API_URL'));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // Nonaktifkan untuk pengujian lokal
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // Aktifkan di produksi

        $result = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            Log::error('Failed to send WhatsApp message via Wablas', ['error' => $error]);
            return redirect()->back()->withErrors(['username' => 'Gagal mengirim pesan WhatsApp: ' . $error]);
        }

        $response = json_decode($result, true);
        if (!isset($response['status']) || $response['status'] !== 'success') {
            Log::error('Wablas API response error', ['response' => $response]);
            $errorMessage = $response['message'] ?? 'Unknown error';
            return redirect()->back()->withErrors(['username' => 'Gagal mengirim pesan WhatsApp: ' . $errorMessage]);
        }

        Log::info('Reset link sent to WhatsApp via Wablas', ['username' => $user->username, 'url' => $resetUrl, 'response' => $response]);

        return redirect()->back()->with('status', 'Link reset telah dikirim ke nomor WhatsApp Anda.');
    }

    public function showResetForm($token, $username)
    {
        return view('auth.passwords.reset', compact('token', 'username'));
    }

    public function reset(Request $request)
    {
        $request->validate([
            'username' => 'required|string|exists:users,username',
            'password' => [
                'required',
                'string',
                'min:8', // Minimal 8 karakter
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', // Harus ada huruf kecil, huruf besar, angka, dan karakter khusus
                'confirmed',
            ],
            'token' => 'required|string',
        ], [
            'username.required' => 'Username harus diisi.',
            'username.exists' => 'Username tidak ditemukan.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf kecil, huruf besar, angka, dan karakter khusus (seperti @$!%*?&).',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'token.required' => 'Token diperlukan.',
        ]);

        $reset = DB::table('password_resets')
            ->where('username', $request->username)
            ->where('token', $request->token)
            ->first();

        if (!$reset || now()->diffInMinutes($reset->created_at) > 60) {
            return redirect()->route('password.request')->withErrors(['username' => 'Token tidak valid atau telah kedaluwarsa.']);
        }

        $user = User::where('username', $request->username)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_resets')->where('username', $request->username)->delete();

        Log::info('Password reset successful', ['username' => $user->username]);

        return redirect()->route('login')->with('status', 'Kata sandi telah berhasil direset.');
    }

    // Fungsi baru untuk halaman profil
    public function showProfile()
    {
        return view('auth.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'whatsapp_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                'confirmed',
            ],
        ], [
            'whatsapp_number.required' => 'Nomor WhatsApp harus diisi.',
            'whatsapp_number.regex' => 'Nomor WhatsApp hanya boleh berisi angka, spasi, tanda minus, plus, atau tanda kurung.',
            'whatsapp_number.min' => 'Nomor WhatsApp minimal 10 karakter.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf kecil, huruf besar, angka, dan karakter khusus (seperti @$!%*?&).',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user->whatsapp_number = $request->whatsapp_number;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        Log::info('Profile updated successfully', ['username' => $user->username]);

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }
}