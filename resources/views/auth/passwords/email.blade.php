@extends('layouts.guest')

@section('content')
    {{-- Form kirim link reset password --}}
    <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center mb-6">Reset Password</h2>
        
        @if (session('status'))
            <div class="mb-4 text-green-600 text-sm text-center">
                {{ session('status') }}
            </div>
        @endif

        @if (session('whatsapp_url'))
            <div class="mb-4 text-blue-600 text-sm text-center">
                Klik <a href="{{ session('whatsapp_url') }}" target="_blank" class="underline">di sini</a> jika tab WhatsApp tidak terbuka otomatis.
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                       required>
                @error('username')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">
                Send Reset Link to WhatsApp
            </button>
        </form>
    </div>

    {{-- Buka link WhatsApp di tab baru --}}
    @if (session('whatsapp_url'))
        <script>
            window.open("{{ session('whatsapp_url') }}", "_blank");
        </script>
    @endif
@endsection