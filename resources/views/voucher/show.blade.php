@extends('layouts.app')

@section('title', 'Voucher Details')

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
    <h1 class="text-3xl font-bold mb-6">Voucher Details</h1>
    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
        <p><strong>ID:</strong> {{ $voucher->id }}</p>
        <p><strong>Company:</strong> {{ $voucher->company_name }}</p>
        <p><strong>Value:</strong> {{ $voucher->value }}</p>
        <p><strong>Status:</strong> {{ $voucher->status }}</p>
        <p><strong>Created:</strong> {{ $voucher->created_date }}</p>
        <p><strong>Expires:</strong> {{ $voucher->expiration_date }}</p>
        <div class="mt-4">
            {!! $qrCode !!}
        </div>
    </div>
@endsection