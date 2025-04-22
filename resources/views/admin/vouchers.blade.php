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
        <div id="notification">
        </div>
    @endif
    @if (session('error'))
        <div id="notification" >
        </div>
    @endif
    @if (session('warning'))
        <div id="notification" >
        </div>
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
                        <td class="p-2">{{ \Carbon\Carbon::parse($voucher->created_date)->timezone('Asia/Jakarta')->format('Y-m-d') }}</td>
                        <td class="p-2">{{ \Carbon\Carbon::parse($voucher->expiration_date)->timezone('Asia/Jakarta')->format('Y-m-d') }}</td>
                        <td class="p-2">{{ $voucher->status }}</td>
                        <td class="p-2">{{ $voucher->redeemed_by ?? '-' }}</td>
                        <td class="p-2">{{ $voucher->redeemed_at ? \Carbon\Carbon::parse($voucher->redeemed_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s') : '-' }}</td>
                        <td class="p-2">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('voucher.send', $voucher->id) }}" 
                                   class="inline-flex items-center w-24 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-150">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Open
                                </a>
                                <a href="{{ route('voucher.edit', $voucher->id) }}" 
                                   class="inline-flex items-center w-24 px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-yellow-700 dark:bg-yellow-500 dark:hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition duration-150">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0l-1.414-1.414a2 2 0 010-2.828z"></path>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('voucher.delete', $voucher->id) }}" method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus voucher ini?')">
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
                            </div>
                        </td>
                        <td class="p-2">
                            <div class="flex items-center justify-start gap-4">
                                <button type="button" 
                                        class="inline-flex items-center min-w-[96px] px-4 py-2 text-white text-sm font-medium rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150 flex-shrink-0
                                        {{ $voucher->sent_status ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 open-whatsapp-modal' }}"
                                        @if ($voucher->sent_status)
                                            style="background-color: #9CA3AF;" disabled
                                        @else
                                            style="background-color: #2563EB;"
                                        @endif
                                        data-voucher-id="{{ $voucher->id }}">
                                    <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.074-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.099-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                    WhatsApp
                                </button>
                                @if ($voucher->sent_to)
                                    <span class="text-yellow-600 dark:text-yellow-400 text-sm">
                                        Ke: {{ $voucher->sent_to }}
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal untuk Input Nomor WhatsApp -->
    <div id="whatsappModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Kirim Link Voucher ke WhatsApp</h2>
            <form id="whatsappForm" method="GET">
                <div class="mb-4">
                    <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor WhatsApp</label>
                    <input type="text" 
                           name="whatsapp_number" 
                           id="whatsapp_number" 
                           class="mt-1 p-2 w-full border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" 
                           placeholder="Masukkan nomor WhatsApp (contoh: 628234567890)" 
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
                const url = '{{ route("voucher.send-to-whatsapp", ["voucherId" => "voucherIdPlaceholder", "whatsappNumber" => "whatsappNumberPlaceholder"]) }}'
                    .replace('voucherIdPlaceholder', voucherId)
                    .replace('whatsappNumberPlaceholder', ''); // Akan diisi oleh input
                form.action = url.replace('/whatsappNumberPlaceholder', '') + '/' + document.getElementById('whatsapp_number').value;
                modal.classList.remove('hidden');
            });
        });

        // Tutup modal saat tombol "Batal" diklik
        closeModalBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            form.reset();
        });

        // Update action form saat nomor WhatsApp diinput
        document.getElementById('whatsapp_number').addEventListener('input', () => {
            const whatsappNumber = document.getElementById('whatsapp_number').value;
            const voucherId = form.action.match(/voucher\/(.+?)\/send-to-whatsapp/)[1];
            const url = '{{ route("voucher.send-to-whatsapp", ["voucherId" => "voucherIdPlaceholder", "whatsappNumber" => "whatsappNumberPlaceholder"]) }}'
                .replace('voucherIdPlaceholder', voucherId)
                .replace('whatsappNumberPlaceholder', whatsappNumber);
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
            'Nomor WhatsApp tidak valid.',
            'Voucher ini sudah pernah dikirim ke nomor',
            'Link voucher berhasil dikirim ke WhatsApp.'
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