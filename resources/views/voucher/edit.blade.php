@extends('layouts.app')

@section('title', 'Edit Voucher')

@section('sidebar-title', 'Admin Panel')

@section('sidebar-menu')
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
        <a href="{{ route('admin.vouchers') }}" class="block p-2 bg-gray-200 dark:bg-gray-700 rounded">View All Vouchers</a>
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
            <div class="flex space-x-4">
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