@extends('layouts.app')

@section('title', 'Edit User')

@section('sidebar-title')
    <div class="flex flex-col items-center mb-4">
        <img src="{{ asset('images/FT.png') }}" alt="Logo" class="h-12 w-auto mb-2">
        <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">Admin Panel</span>
    </div>
@endsection

@section('sidebar-menu')
    <li>
        <a href="{{ route('admin.create-voucher') }}" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Create Voucher</a>
    </li>
    <li>
        <a href="{{ route('admin.create-merchant') }}" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Create Merchant</a>
    </li>
    <li>
        <a href="{{ route('admin.vouchers') }}" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">View All Vouchers</a>
    </li>
    <li>
        <a href="{{ route('admin.users') }}" class="block p-2 bg-gray-200 dark:bg-gray-700 rounded">View All Users</a>
    </li>
    <li x-data="{ showSetting: false }">
        <button @click="showSetting = !showSetting" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded w-full text-left flex justify-between items-center">
            Setting
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <ul x-show="showSetting" class="pl-4">
            <li>
                <a href="{{ route('profile') }}" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Profil</a>
            </li>
            <li>
                <a href="{{ route('admin.update-company') }}" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Update Company Name</a>
            </li>
        </ul>
    </li>
    <li>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full text-left p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Logout</button>
        </form>
    </li>
@endsection

@section('content')
    <div class="mb-6 bg-white dark:bg-gray-800 p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Edit User: {{ $user->username }}</h2>
        @if (session('success'))
        @endif
        @if (session('error'))
        @endif
        <form method="POST" action="{{ route('admin.user.edit', $user->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin memperbarui user ini?')">
            @csrf
            @method('POST')
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" class="w-full p-2 border rounded mb-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" 
                       placeholder="Username" required>
                @error('username')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            @if ($user->role === 'merchant')
                <div class="mb-4">
                    <label for="merchant_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Merchant Name</label>
                    <input type="text" name="merchant_name" id="merchant_name" value="{{ old('merchant_name', $user->merchant_name) }}" 
                           class="w-full p-2 border rounded mb-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" 
                           placeholder="Merchant Name" required>
                    @error('merchant_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            @endif
            <div class="mb-4">
                <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">WhatsApp Number</label>
                <input type="text" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number', $user->whatsapp_number) }}" 
                       class="w-full p-2 border rounded mb-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" 
                       placeholder="WhatsApp Number (e.g., 6281234567890)" required>
                @error('whatsapp_number')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="information" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Information</label>
                <textarea name="information" id="information" class="w-full p-2 border rounded mb-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" 
                          placeholder="Additional information about the user">{{ old('information', $user->information) }}</textarea>
                @error('information')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            @if ($user->role === 'merchant')
                <div class="mb-4">
                    <label for="remaining_balance" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Merchant Balance (Current Month)</label>
                    <input type="number" name="remaining_balance" id="remaining_balance" 
                           value="{{ old('remaining_balance', $balance ? $balance->remaining_balance : 1000000000) }}" 
                           class="w-full p-2 border rounded mb-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" 
                           placeholder="Enter remaining balance (0 - 1000000000)" min="0" max="1000000000" required>
                    @error('remaining_balance')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            @endif
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Password (optional)</label>
                <input type="password" name="password" id="password" class="w-full p-2 border rounded mb-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" 
                       placeholder="New Password">
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                       class="w-full p-2 border rounded mb-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" 
                       placeholder="Confirm New Password">
            </div>
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                Update User
            </button>
            <a href="{{ route('admin.users') }}" class="ml-4 bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-700">
                Cancel
            </a>
        </form>
    </div>
@endsection