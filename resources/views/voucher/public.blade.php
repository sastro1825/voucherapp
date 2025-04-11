<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher - {{ $voucher->id }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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
            width: 320px; /* Ukuran kecil seperti kupon */
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
            width: 120px; /* Ukuran barcode lebih kecil */
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

        /* Dekorasi tambahan */
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

        /* Media Query untuk Cetak */
        @media print {
            body {
                background: none; /* Hilangkan background gradient saat cetak */
                margin: 0;
                padding: 0;
                display: block;
            }
            .voucher-container {
                background: linear-gradient(145deg, #ffffff, #f0f4ff); /* Pertahankan gradient */
                border: 3px dashed #ff6b6b; /* Pertahankan border dashed */
                border-radius: 15px; /* Pertahankan border radius */
                box-shadow: none; /* Hilangkan shadow saat cetak */
                padding: 1rem;
                width: 320px; /* Ukuran tetap */
                margin: 0 auto;
                position: static; /* Hilangkan posisi relatif */
                overflow: visible; /* Pastikan tidak ada overflow */
                page-break-inside: avoid; /* Hindari pemisahan halaman */
            }
            .voucher-header {
                background: #ff6b6b; /* Pertahankan warna header */
                color: #fff; /* Pertahankan warna teks */
                margin: -1rem -1rem 1rem -1rem; /* Pertahankan margin */
                border-radius: 10px 10px 0 0; /* Pertahankan radius */
            }
            .voucher-details p {
                background: rgba(255, 255, 255, 0.8); /* Pertahankan background detail */
                padding: 0.2rem 0.5rem; /* Pertahankan padding */
            }
            .barcode-container {
                background: #fff; /* Pertahankan background barcode */
                padding: 0.5rem; /* Pertahankan padding */
                border-radius: 10px; /* Pertahankan radius */
            }
            .barcode-container svg, .barcode-container img {
                width: 120px; /* Pertahankan ukuran barcode */
                height: 120px;
            }
            .no-print {
                display: none; /* Sembunyikan tombol print saat cetak */
            }
            .voucher-container::before, .voucher-container::after {
                display: block; /* Pertahankan dekorasi saat cetak */
            }
        }
    </style>
</head>
<body>
    <div class="voucher-container">
        <div class="voucher-header">Gift Voucher</div>
        
        <div class="voucher-details">
            <p><strong>ID:</strong> <span>{{ $voucher->id }}</span></p>
            <p><strong>Company:</strong> <span>{{ $voucher->company_name }}</span></p>
            <p><strong>Value:</strong> <span>{{ $voucher->value }}</span></p>
            <p><strong>Expires:</strong> <span>{{ date('d M Y', strtotime($voucher->expiration_date)) }}</span></p>
            <p><strong>Status:</strong> <span>{{ $voucher->status }}</span></p>
            @if ($voucher->redeemed_by)
                <p><strong>Redeemed By:</strong> <span>{{ $voucher->redeemed_by }}</span></p>
            @endif
        </div>

        <div class="barcode-container">
            <h2>Scan Me</h2>
            {!! $qrCode !!}
        </div>

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