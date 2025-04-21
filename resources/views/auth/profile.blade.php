@extends('layouts.app')

@section('title', 'Profile')

@section('sidebar-title')
    <div class="flex flex-col items-center mb-4">
        <img src="{{ asset('images/FT.png') }}" alt="Logo" class="h-12 w-auto mb-2">
        <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">
            {{ auth()->user()->role === 'admin' ? 'Admin Panel' : 'Merchant Panel' }}
        </span>
    </div>
@endsection

@section('sidebar-menu')
    @if (auth()->user()->role === 'admin')
        <!-- Sidebar untuk Admin -->
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
            <a href="{{ route('admin.users') }}" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">View All Users</a>
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
                    <a href="{{ route('profile') }}" class="block p-2 bg-gray-200 dark:bg-gray-700 rounded">Profil</a>
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
    @else
        <!-- Sidebar untuk Merchant -->
        <li>
            <a href="{{ route('merchant.dashboard') }}#redeem-voucher" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Redeem Voucher</a>
        </li>
        <li>
            <a href="{{ route('merchant.redeemed-vouchers') }}" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">View Redeemed Vouchers</a>
        </li>
        <li>
            <a href="{{ route('profile') }}" class="block p-2 bg-gray-200 dark:bg-gray-700 rounded">Profil</a>
        </li>
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Logout</button>
            </form>
        </li>
    @endif
@endsection

@section('content')
    <!-- Notification -->
    @if (session('notification'))
        <div class="mb-6 p-4 rounded {{ session('notification.type') === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}" id="notification">
            {{ session('notification.message') }}
        </div>
    @endif

    <div class="mb-6 bg-white dark:bg-gray-800 p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Profil {{ auth()->user()->role === 'admin' ? 'Admin' : 'Merchant' }}</h2>
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            <div class="mb-4">
                <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">WhatsApp Number</label>
                <input type="text" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number', auth()->user()->whatsapp_number) }}"
                       class="mt-1 p-2 w-full border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" 
                       placeholder="WhatsApp Number (e.g., 6281234567890)" required>
                @error('whatsapp_number')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Password (optional)</label>
                <input type="password" name="password" id="password"
                       class="mt-1 p-2 w-full border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="mt-1 p-2 w-full border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
            </div>
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                Update Profile
            </button>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        // Auto-hide notification and refresh after 3 seconds if notification exists
        const notification = document.getElementById('notification');
        if (notification) {
            setTimeout(() => {
                notification.style.display = 'none';
                window.location.reload();
            }, 3000);
        }
    </script>
@endsection