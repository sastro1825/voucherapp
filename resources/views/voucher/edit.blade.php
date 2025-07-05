@extends('layouts.app')

@section('title', 'Edit Voucher')

@section('sidebar-title', 'Admin Panel')

@section('sidebar-menu')
    {{-- menu sidebar admin --}}
    <li>
        <a href="{{ route('admin.create-voucher') }}" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Create Voucher</a>
    </li>
    <li>
        <a href="{{ route('admin.create-merchant') }}" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Create Merchant</a>
    </li>
    <li>
        <a href="{{ route('admin.vouchers') }}" class="block p-2 bg-gray-200 dark:bg-gray-700 rounded">View All Vouchers</a>
    </li>
    <li>
        <a href="{{ route('admin.users') }}" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">View All Users</a>
    </li>
    {{-- dropdown pengaturan di sidebar --}}
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
    <h1 class="text-3xl font-bold mb-6">Edit Voucher: {{ $voucher->id }}</h1>
    {{-- formulir edit voucher --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
        <form method="POST" action="{{ route('voucher.edit', $voucher->id) }}">
            @csrf
            <div class="mb-4">
                <label for="value" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Voucher Value</label>
                <input type="number" name="value" id="value" value="{{ $voucher->value }}"
                       class="mt-1 block w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" required>
                @error('value')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            {{-- tombol submit dan cancel --}}
            <div class="flex space-x-6">
                <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition">
                    Update Voucher
                </button>
                <a href="{{ route('admin.vouchers') }}">
                    <button type="button" class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 transition">
                        Cancel
                    </button>
                </a>
            </div>
        </form>
    </div>
@endsection