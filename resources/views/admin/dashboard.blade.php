@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('sidebar-title')
    {{-- sidebar --}}
    <div class="flex flex-col items-center mb-4">
        <img src="{{ asset('images/FT.png') }}" alt="Logo" class="h-12 w-auto mb-2">
        <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">Admin Panel</span>
    </div>
@endsection

@section('sidebar-menu')
    {{-- menu sidebar admin --}}
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
        {{-- dropdown pengaturan sidebar --}}
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
    {{-- judul halaman dashboard --}}
    <h1 class="text-3xl font-bold mb-6">Welcome, {{ Auth::user()->username }}!</h1>
@endsection