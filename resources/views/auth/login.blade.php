<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — JayuMart</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-sm">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-blue-600 mb-4">
                <i class="fa-solid fa-store text-white text-xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">JayuMart</h1>
            <p class="text-sm text-gray-500 mt-1">Sistem Manajemen Toko</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

            <h2 class="text-base font-semibold text-gray-800 mb-6">Masuk ke akun Anda</h2>

            {{-- Session Error --}}
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-50 text-green-700 text-xs rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-envelope text-gray-300 text-xs"></i>
                        </div>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full border border-gray-200 rounded-lg pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('email') border-red-300 @enderror"
                            placeholder="nama@email.com"
                            required
                            autofocus
                        >
                    </div>
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="block text-xs font-medium text-gray-600">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:underline">
                                Lupa password?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-300 text-xs"></i>
                        </div>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="w-full border border-gray-200 rounded-lg pl-9 pr-10 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('password') border-red-300 @enderror"
                            placeholder="••••••••"
                            required
                        >
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-300 hover:text-gray-500"
                        >
                            <i class="fa-solid fa-eye text-xs" id="eye-icon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center gap-2 mb-6">
                    <input
                        type="checkbox"
                        name="remember"
                        id="remember"
                        class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-300"
                    >
                    <label for="remember" class="text-xs text-gray-600">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white text-sm font-medium py-2.5 rounded-lg hover:bg-blue-700 transition-colors"
                >
                    Masuk
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            &copy; {{ date('Y') }} JayuMart. All rights reserved.
        </p>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>

</body>
</html>