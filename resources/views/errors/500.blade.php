<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internal Server Error</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-md text-center">
        <h2 class="text-2xl font-bold text-red-600 mb-4">500 - Internal Server Error</h2>
        <p class="text-gray-600">Maaf, terjadi kesalahan pada server. Silakan coba lagi nanti.</p>
        <a href="{{ url('/') }}" class="mt-4 inline-block bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>