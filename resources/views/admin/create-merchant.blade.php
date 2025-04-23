@extends('layouts.app')

@section('title', 'Create Merchant')

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
        <a href="{{ route('admin.create-merchant') }}" class="block p-2 bg-gray-200 dark:bg-gray-700 rounded">Create Merchant</a>
    </li>
    <li>
        <a href="{{ route('admin.vouchers') }}" class="block p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">View All Vouchers</a>
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
    <div class="mb-6 bg-white dark:bg-gray-800 p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Create Merchant</h2>
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif
        <form method="POST" action="{{ route('admin.create-merchant.submit') }}" onsubmit="return confirm('Apakah Anda yakin ingin membuat merchant ini?')">
            @csrf
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}" class="w-full p-2 border rounded mb-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" 
                       placeholder="Username" required>
                @error('username')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                <!-- Wrapper div to ensure relative positioning -->
                <div style="position: relative;">
                    <!-- Input with padding-right to avoid text overlapping icon, no black background -->
                    <input type="password" name="password" id="password" 
                           style="width: 100%; padding: 0.5rem; padding-right: 2.5rem; border: 1px solid #d1d5db; border-radius: 0.25rem; margin-bottom: 0.5rem; background-color: #ffffff; color: #111827;" 
                           class="dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600" 
                           placeholder="Password" required>
                    <!-- Button with absolute positioning on the right -->
                    <button type="button" onclick="togglePassword()" 
                            style="position: absolute; top: 50%; right: 0.75rem; transform: translateY(-50%); color: #6b7280; cursor: pointer;">
                        <svg id="eye-icon" style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">WhatsApp Number</label>
                <input type="text" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number') }}" class="w-full p-2 border rounded mb-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" 
                       placeholder="WhatsApp Number (e.g., 6281234567890)" required>
                @error('whatsapp_number')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="information" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Information</label>
                <textarea name="information" id="information" class="w-full p-2 border rounded mb-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" 
                          placeholder="Additional information about the merchant">{{ old('information') }}</textarea>
                @error('information')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="mt-2 bg-purple-600 text-white py-2 px-4 rounded hover:bg-purple-700">
                Create Merchant
            </button>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        // Function to toggle password visibility
        function togglePassword() {
            // Get the password input element
            const passwordInput = document.getElementById('password');
            // Get the eye icon SVG element
            const eyeIcon = document.getElementById('eye-icon');
            
            // Check if the input type is password
            if (passwordInput.type === 'password') {
                // Change input type to text to show password
                passwordInput.type = 'text';
                // Update eye icon to "eye-slash" (indicating password is visible)
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                `;
            } else {
                // Change input type back to password to hide password
                passwordInput.type = 'password';
                // Update eye icon to "eye" (indicating password is hidden)
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }
    </script>
    @if (session('success') || session('error'))
        <script>
            // Automatically reload the page after 3 seconds if success or error message is present
            setTimeout(function() {
                window.location.reload();
            }, 3000);
        </script>
    @endif
@endsection