<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher - {{ $voucher->id }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{-- styling untuk voucher --}}
    <style>
        body {
            background: linear-gradient(135deg, #ffe6f0 0%, #e6f0ff 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
        }

        .voucher-container {
            background: linear-gradient(145deg, #ffffff, #f0f4ff);
            border: 3px dashed #ff6b6b;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            padding: 1rem;
            width: 320px;
            height: auto;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .voucher-container:hover {
            transform: scale(1.05);
        }

        .voucher-header {
            background: #ff6b6b;
            color: #fff;
            text-align: center;
            padding: 0.5rem;
            border-radius: 10px 10px 0 0;
            margin: -1rem -1rem 1rem -1rem;
            font-size: 1.25rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .voucher-details {
            font-size: 0.9rem;
            color: #333;
            margin-bottom: 1rem;
        }

        .voucher-details p {
            margin: 0.3rem 0;
            padding: 0.2rem 0.5rem;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: space-between;
        }

        .voucher-details p strong {
            color: #ff6b6b;
            font-weight: 600;
        }

        .terms {
            text-align: center;
            font-size: 0.8rem;
            color: #ff6b6b;
            margin: 0.5rem 0;
            font-weight: 500;
        }

        .barcode-container {
            text-align: center;
            padding: 0.5rem;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .barcode-container h2 {
            font-size: 1rem;
            color: #555;
            margin-bottom: 0.5rem;
        }

        .barcode-container svg, .barcode-container img {
            width: 120px;
            height: 120px;
            display: block;
            margin: 0 auto;
        }

        .print-button {
            background: linear-gradient(90deg, #ff6b6b 0%, #ff8787 100%);
            color: #fff;
            padding: 0.5rem 1.5rem;
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: 20px;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
            margin-top: 1rem;
            display: block;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .print-button:hover {
            background: linear-gradient(90deg, #ff8787 0%, #ff6b6b 100%);
            transform: scale(1.05);
        }

        .voucher-container::before {
            content: '';
            position: absolute;
            top: -20px;
            left: -20px;
            width: 50px;
            height: 50px;
            background: radial-gradient(circle, rgba(255, 107, 107, 0.3), transparent);
            border-radius: 50%;
        }

        .voucher-container::after {
            content: '';
            position: absolute;
            bottom: -20px;
            right: -20px;
            width: 50px;
            height: 50px;
            background: radial-gradient(circle, rgba(255, 107, 107, 0.3), transparent);
            border-radius: 50%;
        }

        @media print {
            body {
                background: none;
                margin: 0;
                padding: 0;
                display: block;
            }
            .voucher-container {
                background: linear-gradient(145deg, #ffffff, #f0f4ff);
                border: 3px dashed #ff6b6b;
                border-radius: 15px;
                box-shadow: none;
                padding: 1rem;
                width: 320px;
                margin: 0 auto;
                position: static;
                overflow: visible;
                page-break-inside: avoid;
            }
            .voucher-header {
                background: #ff6b6b;
                color: #fff;
                margin: -1rem -1rem 1rem -1rem;
                border-radius: 10px 10px 0 0;
            }
            .voucher-details p {
                background: rgba(255, 255, 255, 0.8);
                padding: 0.2rem 0.5rem;
            }
            .barcode-container {
                background: #fff;
                padding: 0.5rem;
                border-radius: 10px;
            }
            .barcode-container svg, .barcode-container img {
                width: 120px;
                height: 120px;
            }
            .no-print {
                display: none;
            }
            .voucher-container::before, .voucher-container::after {
                display: block;
            }
        }
    </style>
</head>
<body>
    {{-- kontainer voucher --}}
    <div class="voucher-container">
        <div class="voucher-header">Gift Voucher</div>
        
        {{-- detail voucher --}}
        <div class="voucher-details">
            <p><strong>ID:</strong> <span>{{ $voucher->id }}</span></p>
            <p><strong>Company:</strong> <span>{{ $voucher->company_name }}</span></p>
            <p><strong>Value:</strong> <span>{{ $voucher->value }}</span></p>
            <p><strong class="merchant-label">Tukarkan pada:</strong><span>{{ $voucher->merchant ? ($voucher->merchant->merchant_name ?: 'No Merchant Name') : 'Unknown Merchant' }}</span></p>
            <style>
            .merchant-label {
                margin-right: 20px;
            }
            </style>
            <p><strong class="merchant-label1">Alamat:</strong><span>{{ $voucher->merchant ? ($voucher->merchant->information ?: 'No information') : 'Unknown Merchant' }}</span></p>
            <style>
            .merchant-label1 {
                margin-right: 40px;
            }
            </style>
            <p><strong>Expired:</strong> <span>{{ $voucher->expiration_date ? $voucher->expiration_date->format('d M Y') : 'N/A' }}</span></p>
            <p><strong>Status:</strong> <span>{{ $voucher->status }}</span></p>
            @if ($voucher->redeemed_by)
                <p><strong>Redeemed By:</strong> <span>{{ $voucher->redeemed_by }}</span></p>
            @endif
        </div>

        {{-- syarat dan ketentuan --}}
        <div class="terms">
            Syarat dan Ketentuan Berlaku: Minimum Pembelanjaan {{ $voucher->value * 2 }}
        </div>

        {{-- pindai barcode --}}
        <div class="barcode-container">
            <h2>Scan Me</h2>
            {!! $qrCode !!}
        </div>

        {{-- cetak --}}
        <div class="no-print">
            <button onclick="window.print()" class="print-button">Print Voucher</button>
        </div>
    </div>

    <script>
        window.onload = function() {
            if (window.opener) {
                window.focus();
            }
        }
    </script>
</body>
</html>