@extends('layouts.app')

@section('title', 'All Vouchers')

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
    <h1 class="text-3xl font-bold mb-6">All Vouchers</h1>

    <!-- Filter -->
    <div class="mb-6">
        <label for="filter" class="mr-2">Filter by Status:</label>
        <select id="filter" onchange="window.location.href='{{ route('admin.vouchers') }}?filter='+this.value" 
                class="p-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
            <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All</option>
            <option value="active" {{ $filter == 'active' ? 'selected' : '' }}>Active</option>
            <option value="redeemed" {{ $filter == 'redeemed' ? 'selected' : '' }}>Redeemed</option>
            <option value="expired" {{ $filter == 'expired' ? 'selected' : '' }}>Expired</option>
            <option value="sending" {{ $filter == 'sending' ? 'selected' : '' }}>Sending</option>
        </select>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow overflow-x-auto">
        <table class="w-full border-collapse text-sm">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700">
                    <th class="p-2 border dark:border-gray-600">ID</th>
                    <th class="p-2 border dark:border-gray-600">Company Name</th>
                    <th class="p-2 border dark:border-gray-600">Value</th>
                    <th class="p-2 border dark:border-gray-600">Created</th>
                    <th class="p-2 border dark:border-gray-600">Expires</th>
                    <th class="p-2 border dark:border-gray-600">Status</th>
                    <th class="p-2 border dark:border-gray-600">Redeemed By</th>
                    <th class="p-2 border dark:border-gray-600">Redeemed At</th>
                    <th class="p-2 border dark:border-gray-600">Send Status</th>
                    <th class="p-2 border dark:border-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vouchers as $voucher)
                    <tr class="border-b dark:border-gray-600">
                        <td class="p-2">{{ $voucher->id }}</td>
                        <td class="p-2">{{ $voucher->company_name }}</td>
                        <td class="p-2">{{ $voucher->value }}</td>
                        <td class="p-2">{{ $voucher->created_date }}</td>
                        <td class="p-2">{{ $voucher->expiration_date }}</td>
                        <td class="p-2">{{ $voucher->status }}</td>
                        <td class="p-2">{{ $voucher->redeemed_by ?? '-' }}</td>
                        <td class="p-2">{{ $voucher->redeemed_at ?? '-' }}</td>
                        <td class="p-2">{{ ucfirst(str_replace('_', ' ', $voucher->send_status)) }}</td>
                        <td class="p-2 flex space-x-2">
                            <a href="{{ route('voucher.show', $voucher->id) }}" class="text-blue-600 hover:underline">Print</a>
                            <a href="{{ route('voucher.send', $voucher->id) }}" class="text-green-600 hover:underline">Send</a>
                            <a href="{{ route('voucher.edit', $voucher->id) }}" class="text-yellow-600 hover:underline">Edit</a>
                            <a href="{{ route('voucher.delete', $voucher->id) }}" 
                               onclick="return confirm('Are you sure you want to delete this voucher?')" 
                               class="text-red-600 hover:underline">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection