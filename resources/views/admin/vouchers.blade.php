@extends('layouts.app')

@section('title', 'All Vouchers')

@section('sidebar-title')
    <div class="flex flex-col items-center mb-4">
        <img src="{{ asset('images/FT.png') }}" alt="Logo" class="h-12 w-auto mb-2">
        <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">Admin Panel</span>
    </div>
@endsection

@section('sidebar-menu')
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
    <h1 class="text-3xl font-bold mb-6">All Vouchers</h1>

    <!-- Notification -->
    @if (session('success'))
        <div id="notification"></div>
    @endif
    @if (session('error'))
        <div id="notification"></div>
    @endif
    @if (session('warning'))
        <div id="notification"></div>
    @endif

    <!-- Filter -->
    <div class="mb-6">
        <label for="filter" class="mr-2">Filter by Status:</label>
        <select id="filter" onchange="window.location.href='{{ route('admin.vouchers') }}?filter='+this.value" 
                class="p-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
            <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All</option>
            <option value="active" {{ $filter == 'active' ? 'selected' : '' }}>Active</option>
            <option value="redeemed" {{ $filter == 'redeemed' ? 'selected' : '' }}>Redeemed</option>
            <option value="expired" {{ $filter == 'expired' ? 'selected' : '' }}>Expired</option>
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
                    <th class="p-2 border dark:border-gray-600">Actions</th>
                    <th class="p-2 border dark:border-gray-600">Kirim ke WhatsApp</th>
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
                        <td class="p-2">
                            <div class="flex flex-row space-x-2">
                                <a href="{{ route('voucher.send', $voucher->id) }}" 
                                   class="bg-green-600 text-white px-3 py-1.5 rounded hover:bg-green-700 transition text-sm font-medium">
                                    Open
                                </a>
                                <a href="{{ route('voucher.edit', $voucher->id) }}" 
                                   class="bg-yellow-600 text-white px-3 py-1.5 rounded hover:bg-yellow-700 transition text-sm font-medium">
                                    Edit
                                </a>
                                <form action="{{ route('voucher.delete', $voucher->id) }}" method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus voucher ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-600 text-white px-3 py-1.5 rounded hover:bg-red-700 transition text-sm font-medium">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                        <td class="p-2">
                            @if ($voucher->sent_to)
                                <span class="text-yellow-600 dark:text-yellow-400 text-sm">
                                    Sudah dikirim ke {{ $voucher->sent_to }}
                                </span>
                            @else
                                <button type="button" 
                                        class="open-whatsapp-modal bg-blue-600 text-white px-3 py-1.5 rounded hover:bg-blue-700 transition text-sm font-medium"
                                        data-voucher-id="{{ $voucher->id }}">
                                    Kirim ke WhatsApp
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal untuk Input Username -->
    <div id="whatsappModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Kirim Link Voucher ke WhatsApp</h2>
            <form id="whatsappForm" method="GET">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username Merchant</label>
                    <input type="text" 
                           name="username" 
                           id="username" 
                           class="mt-1 p-2 w-full border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" 
                           placeholder="Masukkan username merchant" 
                           required>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" 
                            id="closeModal" 
                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @if (session('public_url'))
        <script>
            window.open("{{ session('public_url') }}", "_blank");
        </script>
    @endif
    <script>
        // Define messages from Blade session
        const notificationMessages = {
            success: @json(session('success')),
            error: @json(session('error')),
            warning: @json(session('warning'))
        };

        const modal = document.getElementById('whatsappModal');
        const closeModalBtn = document.getElementById('closeModal');
        const form = document.getElementById('whatsappForm');

        // Buka modal saat tombol "Kirim ke WhatsApp" diklik
        document.querySelectorAll('.open-whatsapp-modal').forEach(button => {
            button.addEventListener('click', () => {
                const voucherId = button.getAttribute('data-voucher-id');
                // Set action form secara dinamis
                const url = '{{ route("voucher.send-to-merchant", ["voucherId" => "voucherIdPlaceholder", "username" => "usernamePlaceholder"]) }}'
                    .replace('voucherIdPlaceholder', voucherId)
                    .replace('usernamePlaceholder', ''); // Akan diisi oleh input
                form.action = url.replace('/usernamePlaceholder', '') + '/' + document.getElementById('username').value;
                modal.classList.remove('hidden');
            });
        });

        // Tutup modal saat tombol "Batal" diklik
        closeModalBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            form.reset();
        });

        // Update action form saat username diinput
        document.getElementById('username').addEventListener('input', () => {
            const username = document.getElementById('username').value;
            const voucherId = form.action.match(/voucher\/(.+?)\/send-to-merchant/)[1];
            const url = '{{ route("voucher.send-to-merchant", ["voucherId" => "voucherIdPlaceholder", "username" => "usernamePlaceholder"]) }}'
                .replace('voucherIdPlaceholder', voucherId)
                .replace('usernamePlaceholder', username);
            form.action = url;
        });

        // Tutup modal saat klik di luar modal
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
                form.reset();
            }
        });

        // Auto-refresh after 3 seconds for specific messages
        const specificMessages = [
            'Voucher can be open',
            'Voucher updated successfully!',
            'Voucher deleted successfully!',
            'Merchant dengan username tersebut tidak ditemukan.',
            'Nomor WhatsApp merchant tidak ditemukan.',
            'Voucher ini sudah pernah dikirim ke merchant'
        ];

        const notification = document.getElementById('notification');
        if (notification) {
            const currentMessage = notificationMessages.success || notificationMessages.error || notificationMessages.warning;
            if (currentMessage && specificMessages.some(msg => currentMessage.includes(msg))) {
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            }
        }
    </script>
@endsection