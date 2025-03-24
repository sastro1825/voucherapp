<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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

        return redirect('/login'); // Fallback ke login
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login'); // Diubah ke '/login'
    }
}