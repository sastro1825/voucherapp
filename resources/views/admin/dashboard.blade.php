@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('sidebar-title', 'Admin Panel')

@section('sidebar-menu')
    <li>
        <a href="#" @click="$refs.updateCompany.classList.remove('hidden'); $refs.createVoucher.classList.add('hidden'); $refs.createMerchant.classList.add('hidden')" 
           class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Update Company Name</a>
    </li>
    <li>
        <a href="#" @click="$refs.updateCompany.classList.add('hidden'); $refs.createVoucher.classList.remove('hidden'); $refs.createMerchant.classList.add('hidden')" 
           class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Create Voucher</a>
    </li>
    <li>
        <a href="#" @click="$refs.updateCompany.classList.add('hidden'); $refs.createVoucher.classList.add('hidden'); $refs.createMerchant.classList.remove('hidden')" 
           class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Create Merchant</a>
    </li>
    <li>
        <a href="{{ route('admin.vouchers') }}" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">View All Vouchers</a>
    </li>
    <li>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full text-left p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Logout</button>
        </form>
    </li>
@endsection

@section('content')
    <h1 class="text-3xl font-bold mb-6">Welcome, {{ Auth::user()->username }}!</h1>

    <!-- Update Company Name -->
    <div x-ref="updateCompany" class="mb-6 bg-white dark:bg-gray-800 p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-2">Update Company Name</h2>
        <form method="POST" action="{{ route('admin.update-company') }}">
            @csrf
            <input type="text" name="company_name" value="{{ $company->value ?? '' }}"
                   class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" 
                   placeholder="Enter company name" required>
            <button type="submit" class="mt-2 bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                Update
            </button>
        </form>
    </div>

    <!-- Create Voucher -->
    <div x-ref="createVoucher" class="mb-6 bg-white dark:bg-gray-800 p-6 rounded shadow hidden">
        <h2 class="text-xl font-semibold mb-2">Create Voucher</h2>
        <form method="POST" action="{{ route('admin.create-voucher') }}">
            @csrf
            <input type="number" name="value" class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" 
                   placeholder="Voucher value" required>
            <button type="submit" class="mt-2 bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
                Create Voucher
            </button>
        </form>
    </div>

    <!-- Create Merchant -->
    <div x-ref="createMerchant" class="mb-6 bg-white dark:bg-gray-800 p-6 rounded shadow hidden">
        <h2 class="text-xl font-semibold mb-2">Create Merchant</h2>
        <form method="POST" action="{{ route('admin.create-merchant') }}">
            @csrf
            <input type="text" name="username" class="w-full p-2 border rounded mb-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" 
                   placeholder="Username" required>
            <input type="password" name="password" class="w-full p-2 border rounded mb-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" 
                   placeholder="Password" required>
            <button type="submit" class="mt-2 bg-purple-600 text-white py-2 px-4 rounded hover:bg-purple-700">
                Create Merchant
            </button>
        </form>
    </div>
@endsection