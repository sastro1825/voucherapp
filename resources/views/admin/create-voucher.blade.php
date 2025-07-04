@extends('layouts.app')

@section('title', 'Create Voucher')

@section('sidebar-title')
    {{-- // judul sidebar dengan logo --}}
    <div class="flex flex-col items-center mb-4">
        <img src="{{ asset('images/FT.png') }}" alt="Logo" class="h-12 w-auto mb-2">
        <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">Admin Panel</span>
    </div>
@endsection

@section('sidebar-menu')
    {{-- // menu sidebar admin --}}
    <li>
        <a href="{{ route('admin.create-voucher') }}" class="block p-2 bg-gray-200 dark:bg-gray-700 rounded">Create Voucher</a>
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
        {{-- // dropdown pengaturan sidebar --}}
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
        {{-- // logout sidebar --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full text-left p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Logout</button>
        </form>
    </li>
@endsection

@section('content')
    {{-- // form pembuatan voucher --}}
    <div class="mb-6 bg-white dark:bg-gray-800 p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Create Voucher</h2>
        @if (session('success'))
        @endif
        @if (session('error'))
        @endif
        <form method="POST" action="{{ route('admin.create-voucher.submit') }}" onsubmit="return confirm('Apakah Anda yakin ingin membuat voucher ini?')">
            @csrf
            <div class="mb-4">
                <label for="merchant_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Merchant</label>
                <select name="merchant_id" id="merchant_id" class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" required>
                    <option value="">-- Pilih Merchant --</option>
                    @foreach ($merchants as $merchant)
                        <option value="{{ $merchant->id }}">{{ $merchant->username }}</option>
                    @endforeach
                </select>
                @error('merchant_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="value" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nilai Voucher</label>
                <input type="number" name="value" id="value" class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" 
                       placeholder="Voucher value" required>
                @error('value')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="mt-2 bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
                Create Voucher
            </button>
        </form>
    </div>
@endsection

@section('scripts')
    @if (session('success') || session('error'))
        <script>
            setTimeout(function() {
                window.location.reload();
            }, 3000);
        </script>
    @endif
@endsection
