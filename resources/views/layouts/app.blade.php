<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — JayuMart' : 'JayuMart' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-200 flex flex-col shrink-0">

            <!-- Logo -->
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="text-lg font-bold text-gray-900">JayuMart</div>
                <div class="text-xs text-gray-500">Sistem Manajemen Toko</div>
            </div>

            <!-- Menu -->
            <nav class="flex-1 px-4 py-4 overflow-y-auto">

                <p class="text-xs text-gray-400 uppercase font-semibold px-2 mb-2">Utama</p>

                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm mb-1
                {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fa-solid fa-house w-4 text-center"></i> Dashboard
                </a>

                {{-- Transaksi: Owner, Manajer Toko, Supervisor --}}
                @hasanyrole('Owner|Manajer Toko|Supervisor')
                <a href="{{ route('transactions.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm mb-1
                {{ request()->routeIs('transactions.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fa-solid fa-receipt w-4 text-center"></i> Transaksi
                </a>
                @endhasanyrole

                <p class="text-xs text-gray-400 uppercase font-semibold px-2 mt-4 mb-2">Manajemen</p>

                {{-- Produk: semua role kecuali Kasir & Pegawai Gudang --}}
                @hasanyrole('Owner|Manajer Toko|Supervisor')
                <a href="{{ route('products.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm mb-1
                {{ request()->routeIs('products.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fa-solid fa-box w-4 text-center"></i> Produk
                </a>
                @endhasanyrole

                {{-- Cabang & Pegawai: Owner only --}}
                @role('Owner')
                <a href="{{ route('branches.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm mb-1
                {{ request()->routeIs('branches.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fa-solid fa-store w-4 text-center"></i> Cabang
                </a>
                <a href="{{ route('users.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm mb-1
                {{ request()->routeIs('users.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fa-solid fa-users w-4 text-center"></i> Pegawai
                </a>
                @endrole

                @role('Kasir')
                <a href="{{ route('pos') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm mb-1
                {{ request()->routeIs('pos') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fa-solid fa-cash-register w-4 text-center"></i> Point of Sale
                </a>
                @endrole

                {{-- Laporan: Owner & Manajer Toko --}}
                @hasanyrole('Owner|Manajer Toko')
                <p class="text-xs text-gray-400 uppercase font-semibold px-2 mt-4 mb-2">Laporan</p>
                <a href="{{ route('reports.transactions') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm mb-1
                {{ request()->routeIs('reports.transactions') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fa-solid fa-file-invoice w-4 text-center"></i> Laporan Transaksi
                </a>
                <a href="{{ route('reports.stock') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm mb-1
                {{ request()->routeIs('reports.stock') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fa-solid fa-print w-4 text-center"></i> Laporan Stok
                </a>
                @endhasanyrole

            </nav>

            <!-- User Info -->
            <div class="px-4 py-4 border-t border-gray-200">
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center text-sm font-bold text-white">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-500">{{ Auth::user()->getRoleNames()->first() }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Logout" class="text-gray-400 hover:text-red-500">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </div>

        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- Header -->
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    @isset($title)
                        <h1 class="text-base font-semibold text-gray-800">{{ $title }}</h1>
                    @endisset

                    <span
                        class="inline-flex items-center gap-1 text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">
                        <i class="fa-solid fa-circle text-green-500 text-[6px]"></i>
                        @role('Owner') Semua Cabang @endrole
                        @hasanyrole('Manajer Toko|Supervisor|Kasir|Pegawai Gudang')
                        {{ Auth::user()->branch->name ?? '-' }}
                        @endhasanyrole
                    </span>
                </div>

                <div class="text-sm text-gray-500">
                    {{ now()->translatedFormat('l, d M Y') }}
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6 overflow-auto">
                {{ $slot }}
            </main>

        </div>
    </div>
</body>

</html>