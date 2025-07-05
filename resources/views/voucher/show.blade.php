@extends('layouts.app')

@section('title', 'Voucher Details')

@section('sidebar-title', 'Admin Panel')

@section('sidebar-menu')
    {{-- menu sidebar admin --}}
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
    {{-- kontainer voucher --}}
    <div class="flex justify-center items-center min-h-screen bg-wrapper">
        <div class="voucher-container">
            <div class="voucher-header">Gift Voucher</div>
            
            {{-- detail voucher --}}
            <div class="voucher-details">
                <p><strong>ID:</strong> <span>{{ $voucher->id }}</span></p>
                <p><strong>Company:</strong> <span>{{ $voucher->company_name }}</span></p>
                <p><strong>Value:</strong> <span>{{ $voucher->value }}</span></p>
                <p><strong>Minimum Pembelanjaan:</strong> <span>{{ $voucher->value * 2 }}</span></p>
                <p><strong>Created:</strong> <span>{{ $voucher->created_date ? $voucher->created_date->format('d M Y') : 'N/A' }}</span></p>
                <p><strong>Expires:</strong> <span>{{ $voucher->expiration_date ? $voucher->expiration_date->format('d M Y') : 'N/A' }}</span></p>
                <p><strong>Status:</strong> <span>{{ $voucher->status }}</span></p>
                @if ($voucher->redeemed_by)
                    <p><strong>Redeemed By:</strong> <span>{{ $voucher->redeemed_by }}</span></p>
                    <p><strong>Redeemed At:</strong> <span>{{ $voucher->redeemed_at ? $voucher->redeemed_at->format('d M Y') : 'N/A' }}</span></p>
                @endif
            </div>

            {{-- pindai barcode --}}
            <div class="barcode-container">
                <h2>Scan Me</h2>
                {!! $qrCode !!}
            </div>

            {{-- tombol cetak --}}
            <div class="no-print">
                <button onclick="window.print()" class="print-button">Print Voucher</button>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    {{-- styling untuk voucher --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        .bg-wrapper {
            background: linear-gradient(135deg, #ffe6f0 0%, #e6f0ff 100%);
        }

        .voucher-container {
            background: linear-gradient(145deg, #fff8e1, #ffebee);
            border: 3px dashed #ff5722;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            padding: 0.75rem;
            width: 300px;
            height: auto;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .voucher-container:hover {
            transform: scale(1.05);
        }

        .voucher-header {
            background: #ff5722;
            color: #fff;
            text-align: center;
            padding: 0.4rem;
            border-radius: 8px 8px 0 0;
            margin: -0.75rem -0.75rem 0.75rem -0.75rem;
            font-size: 1.1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .voucher-details {
            font-size: 0.85rem;
            color: #444;
            margin-bottom: 0.75rem;
        }

        .voucher-details p {
            margin: 0.2rem 0;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            background: rgba(255, 245, 224, 0.9);
            display: flex;
            justify-content: space-between;
        }

        .voucher-details p strong {
            color: #ff5722;
            font-weight: 600;
        }

        .barcode-container {
            text-align: center;
            padding: 0.4rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
        }

        .barcode-container h2 {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.4rem;
        }

        .barcode-container svg, .barcode-container img {
            width: 100px; 
            height: 100px;
            display: block;
            margin: 0 auto;
        }

        .print-button {
            background: linear-gradient(90deg, #ff5722 0%, #ff8a50 100%);
            color: #fff;
            padding: 0.4rem 1.2rem;
            font-size: 0.85rem;
            font-weight: 500;
            border-radius: 15px;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
            margin-top: 0.75rem;
            display: block;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .print-button:hover {
            background: linear-gradient(90deg, #ff8a50 0%, #ff5722 100%);
            transform: scale(1.05);
        }

        .voucher-container::before {
            content: '';
            position: absolute;
            top: -15px;
            left: -15px;
            width: 40px;
            height: 40px;
            background: radial-gradient(circle, rgba(255, 87, 34, 0.3), transparent);
            border-radius: 50%;
        }

        .voucher-container::after {
            content: '';
            position: absolute;
            bottom: -15px;
            right: -15px;
            width: 40px;
            height: 40px;
            background: radial-gradient(circle, rgba(255, 87, 34, 0.3), transparent);
            border-radius: 50%;
        }

        @media print {
            body, .container, .sidebar, header, footer, .bg-wrapper {
                display: none; 
            }
            .voucher-container {
                display: block !important; 
                border: 3px dashed #ff5722;
                box-shadow: none;
                padding: 0.75rem;
                width: 300px;
                margin: 0 auto;
                background: #fff;
                position: static;
            }
            .voucher-header {
                background: #ff5722;
                color: #fff;
            }
            .voucher-details p {
                background: rgba(255, 245, 224, 0.9);
                padding: 0.15rem 0.4rem;
            }
            .barcode-container {
                background: #fff;
                padding: 0.4rem;
            }
            .no-print {
                display: none; /* Sembunyikan tombol print */
            }
            .voucher-container::before, .voucher-container::after {
                display: none;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        window.onload = function() {
            if (window.opener) {
                window.focus();
            }
        }
    </script>
@endsection