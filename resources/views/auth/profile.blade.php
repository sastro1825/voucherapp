@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('sidebar-title')
    <div class="flex flex-col items-center mb-4">
        <img src="{{ asset('images/FT.png') }}" alt="Logo" class="h-12 w-auto mb-2">
        @if (Auth::user()->role === 'admin')
            <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">Admin Panel</span>
        @else
            <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">Merchant Panel</span>
        @endif
    </div>
@endsection

@section('sidebar-menu')
    @if (Auth::user()->role === 'admin')
        <li>
            <a href="{{ route('admin.dashboard') }}#update-company" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Update Company Name</a>
        </li>
        <li>
            <a href="{{ route('admin.dashboard') }}#create-voucher" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Create Voucher</a>
        </li>
        <li>
            <a href="{{ route('admin.dashboard') }}#create-merchant" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Create Merchant</a>
        </li>
        <li>
            <a href="{{ route('admin.vouchers') }}" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">View All Vouchers</a>
        </li>
    @else
        <li>
            <a href="{{ route('merchant.dashboard') }}#redeem-voucher" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Redeem Voucher</a>
        </li>
        <li>
            <a href="{{ route('merchant.redeemed-vouchers') }}" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">View Redeemed Vouchers</a>
        </li>
    @endif
    <li>
        <a href="{{ route('profile') }}" class="block p-2 bg-gray-200 dark:bg-gray-700 rounded">Profil</a>
    </li>
    <li>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full text-left p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Logout</button>
        </form>
    </li>
@endsection

@section('content')
    <h1 class="text-3xl font-bold mb-6">Profil Pengguna</h1>

    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
        @if (session('success'))
            <div class="mb-4 text-green-600 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                <input type="text" name="username" id="username" value="{{ Auth::user()->username }}" disabled
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
            </div>
            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                <input type="text" name="role" id="role" value="{{ Auth::user()->role }}" disabled
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
            </div>
            <div class="mb-4">
                <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor WhatsApp</label>
                <input type="text" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number', Auth::user()->whatsapp_number) }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                       placeholder="e.g., 6281234567890" required>
                @error('whatsapp_number')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password Baru (kosongkan jika tidak ingin mengubah)</label>
                <input type="password" name="password" id="password"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">
                Simpan Perubahan
            </button>
        </form>
    </div>
@endsection