@extends('layouts.app')

@section('title', 'Merchant Dashboard')

@section('sidebar-title', 'Merchant Panel')

@section('sidebar-menu')
    <li>
        <a href="#redeem-voucher" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Redeem Voucher</a>
    </li>
    <li>
        <a href="{{ route('merchant.redeemed-vouchers') }}" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">View Redeemed Vouchers</a>
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

    <!-- Redeem Voucher -->
    <div id="redeem-voucher" class="mb-6 bg-white dark:bg-gray-800 p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-2">Redeem Voucher</h2>
        <form method="POST" action="{{ route('merchant.redeem-voucher') }}">
            @csrf
            <input type="text" name="voucher_id" class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" 
                   placeholder="Enter voucher ID" required>
            <button type="submit" class="mt-2 bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
                Redeem
            </button>
        </form>
    </div>
@endsection