@extends('layouts.app')

@section('title', 'Redeemed Vouchers')

@section('sidebar-title')
    <div class="flex flex-col items-center mb-4">
        <img src="{{ asset('images/FT.png') }}" alt="Logo" class="h-12 w-auto mb-2">
        <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">Merchant Panel</span>
    </div>
@endsection

@section('sidebar-menu')
    <li>
        <a href="{{ route('merchant.dashboard') }}#redeem-voucher" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Redeem Voucher</a>
    </li>
    <li>
        <a href="{{ route('merchant.redeemed-vouchers') }}" class="block p-2 bg-gray-200 dark:bg-gray-700 rounded">View Redeemed Vouchers</a>
    </li>
    <li>
        <a href="{{ route('profile') }}" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Profil</a>
    </li>
    <li>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full text-left p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Logout</button>
        </form>
    </li>
@endsection

@section('content')
    <h1 class="text-3xl font-bold mb-6">Redeemed Vouchers</h1>
    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700">
                    <th class="p-2 border dark:border-gray-600">Voucher ID</th>
                    <th class="p-2 border dark:border-gray-600">Value</th>
                    <th class="p-2 border dark:border-gray-600">Redeemed At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($redeemed as $item)
                    <tr class="border-b dark:border-gray-600">
                        <td class="p-2">{{ $item->voucher->id }}</td>
                        <td class="p-2">{{ $item->voucher->value }}</td>
                        <td class="p-2">{{ $item->redeemed_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection