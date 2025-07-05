@extends('layouts.app')

@section('title', 'Merchant Dashboard')

@section('sidebar-title')
    {{-- sidebar logo --}}
    <div class="flex flex-col items-center mb-4">
        <img src="{{ asset('images/FT.png') }}" alt="Logo" class="h-12 w-auto mb-2">
        <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">Merchant Panel</span>
    </div>
@endsection

@section('sidebar-menu')
    {{-- menu sidebar merchant --}}
    <li>
        <a href="#redeem-voucher" class="block p-2 bg-gray-200 dark:bg-gray-700 rounded">Redeem Voucher</a>
    </li>
    <li>
        <a href="{{ route('merchant.redeemed-vouchers') }}" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">View Redeemed Vouchers</a>
    </li>
    <li>
        <a href="{{ route('profile') }}" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Profil</a>
    </li>
    <li>
        {{-- Bagian tombol logout di sidebar --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full text-left p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">Logout</button>
        </form>
    </li>
@endsection

@section('content')
    {{-- notifikasi --}}
    @if (session('success'))
        <div id="registration-notification">
            {{ session('success') }}
        </div>
    @endif
    @if (session('notification'))
        <div class="mb-6 p-4 rounded {{ session('notification.type') === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}" id="notification">
            {{ session('notification.message') }}
        </div>
    @endif

    {{-- judul halaman dashboard --}}
    <h1 class="text-3xl font-bold mb-6">Welcome, {{ Auth::user()->username }}!</h1>

    {{-- tampilan saldo voucher --}}
    <div class="mb-6 bg-white dark:bg-gray-800 p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-2">Saldo Voucher Bulan Ini</h2>
        <p class="text-lg font-bold">Sisa Saldo: {{ $remainingBalance }}</p>
    </div>

    {{-- form redeem voucher --}}
    <div id="redeem-voucher" class="mb-6 bg-white dark:bg-gray-800 p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-2">Redeem Voucher</h2>
        
        {{-- input manual voucher --}}
        <form method="POST" action="{{ route('merchant.redeem-voucher') }}" id="redeem-form" onsubmit="return confirm('Apakah Anda yakin ingin redeem voucher ini?')">
            @csrf
            <input type="text" name="voucher_id" id="voucher_id" class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" 
                   placeholder="Enter voucher ID or scan barcode" required>
            @error('voucher_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
            <button type="submit" class="mt-2 bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
                Redeem
            </button>
        </form>

        {{-- pindai barcode --}}
        <div class="mt-4">
            <button id="start-scan" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Scan Barcode</button>
            <div id="scanner-container" class="mt-4 hidden">
                <video id="barcode-scanner" class="w-full max-w-md"></video>
                <button id="stop-scan" class="mt-2 bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700">Stop Scan</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- skrip untuk pendai barcode --}}
    <script src="https://unpkg.com/@zxing/library@latest"></script>
    <script>
        const startScanButton = document.getElementById('start-scan');
        const stopScanButton = document.getElementById('stop-scan');
        const scannerContainer = document.getElementById('scanner-container');
        const video = document.getElementById('barcode-scanner');
        const voucherIdInput = document.getElementById('voucher_id');
        const redeemForm = document.getElementById('redeem-form');

        let codeReader = null;

        startScanButton.addEventListener('click', () => {
            scannerContainer.classList.remove('hidden');
            startScanButton.classList.add('hidden');

            codeReader = new ZXing.BrowserMultiFormatReader();
            codeReader.decodeFromVideoDevice(null, 'barcode-scanner', (result, err) => {
                if (result) {
                    voucherIdInput.value = result.text;
                    stopScanning();
                    if (confirm('Apakah Anda yakin ingin redeem voucher ini?')) {
                        redeemForm.submit();
                    }
                }
                if (err && !(err instanceof ZXing.NotFoundException)) {
                    console.error(err);
                }
            });
        });

        stopScanButton.addEventListener('click', () => {
            stopScanning();
        });

        function stopScanning() {
            if (codeReader) {
                codeReader.reset();
                codeReader = null;
            }
            scannerContainer.classList.add('hidden');
            startScanButton.classList.remove('hidden');
        }

        {{-- refresh --}}
        const notification = document.getElementById('notification');
        const registrationNotification = document.getElementById('registration-notification');
        if (notification || registrationNotification) {
            setTimeout(() => {
                window.location.reload();
            }, 3000);
        }
    </script>
@endsection