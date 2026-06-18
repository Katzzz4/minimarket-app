<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="flex flex-col items-center justify-center py-16 text-center">
        <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mb-4">
            <i class="fa-solid fa-cash-register text-blue-600 text-2xl"></i>
        </div>
        <h2 class="text-lg font-semibold text-gray-800 mb-1">
            Selamat datang, {{ Auth::user()->name }}
        </h2>
        <p class="text-sm text-gray-500 mb-2">
            {{ Auth::user()->branch->name ?? '-' }} &mdash; {{ now()->translatedFormat('l, d M Y') }}
        </p>
        <p class="text-xs text-gray-400 mb-8">Silakan mulai proses transaksi melalui halaman Point of Sale.</p>

        <a href="{{ route('pos') }}"
            class="inline-flex items-center gap-2 bg-blue-600 text-white text-sm px-6 py-3 rounded-lg hover:bg-blue-700 font-medium">
            <i class="fa-solid fa-cash-register"></i> Buka Point of Sale
        </a>
    </div>
</x-app-layout>