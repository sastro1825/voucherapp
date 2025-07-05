@extends('layouts.app')

@section('title', 'All Users')

@section('sidebar-title')
    {{-- sidebar logo --}}
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
        <a href="{{ route('admin.users') }}" class="block p-2 bg-gray-200 dark:bg-gray-700 rounded">View All Users</a>
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
    <h1 class="text-3xl font-bold mb-6">All Users</h1>

    @if (session('success'))
    @endif
    @if (session('error'))
    @endif

    {{-- form pencarian pengguna --}}
    <div class="mb-6">
        <form action="{{ route('admin.users') }}" method="GET" class="flex items-center space-x-3">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by username..." 
                   class="w-full max-w-md p-2 border rounded dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 mr-2">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow-sm hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150">Search</button>
            @if ($search)
                <a href="{{ route('admin.users') }}" class="px-4 py two bg-red-600 text-white rounded-lg shadow-sm hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-150">Clear</a>
            @endif
        </form>
    </div>

    {{-- tabel daftar pengguna --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow overflow-x-auto">
        @if ($users->isEmpty())
            <p class="text-center text-gray-500 dark:text-gray-400">No users found.</p>
        @else
            <table class="w-full border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700">
                        <th class="p-2 border dark:border-gray-600">Username</th>
                        <th class="p-2 border dark:border-gray-600">Merchant Name</th>
                        <th class="p-2 border dark:border-gray-600">Nomor WA</th>
                        <th class="p-2 border dark:border-gray-600">Role</th>
                        <th class="p-2 border dark:border-gray-600">Information</th>
                        <th class="p-2 border dark:border-gray-600">Saldo Merchant</th>
                        <th class="p-2 border dark:border-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="border-b dark:border-gray-600">
                            <td class="p-2 text-center">{{ $user->username }}</td>
                            <td class="p-2 text-center">{{ $user->merchant_name ?? '-' }}</td>
                            <td class="p-2 text-center">{{ $user->whatsapp_number ?? '-' }}</td>
                            <td class="p-2 text-center">{{ ucfirst($user->role) }}</td>
                            <td class="p-2 text-center">{{ $user->information ?? '-' }}</td>
                            <td class="p-2 text-center">
                                @if ($user->role === 'merchant')
                                    {{ $user->merchantBalances->first()->remaining_balance ?? 300000 }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-2">
                                {{-- tombol (edit dan hapus) --}}
                                <div class="flex items-center justify-start space-x-2">
                                    <a href="{{ route('admin.user.edit', $user->id) }}" 
                                       class="ml-4 inline-flex items-center w-24 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150">
                                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0l-1.414-1.414a2 2 0 010-2.828z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    @if ($user->role !== 'admin')
                                        <form action="{{ route('admin.user.delete', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center w-24 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-150">
                                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 4v12m4-12v12"></path>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection

@section('scripts')
    {{-- refresh --}}
    @if (session('success') || session('error'))
        <script>
            setTimeout(function() {
                window.location.reload();
            }, 3000);
        </script>
    @endif
@endsection