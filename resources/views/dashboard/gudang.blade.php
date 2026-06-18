<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500 mb-2">Total Produk di Cabang</p>
            <p class="text-2xl font-semibold text-blue-700">{{ $totalProducts }}</p>
            <p class="text-xs text-gray-400 mt-1">produk terdaftar</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500 mb-2">Stok Menipis</p>
            <p class="text-2xl font-semibold text-amber-600">{{ $lowStocks->count() }}</p>
            <p class="text-xs text-gray-400 mt-1">perlu restock segera</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-sm font-semibold">Produk Stok Menipis</h2>
            <a href="{{ route('stock.index') }}"
                class="text-xs bg-blue-600 text-white px-3 py-1.5 rounded-lg hover:bg-blue-700">
                + Input Stok
            </a>
        </div>

        @forelse($lowStocks as $stock)
        <div class="flex justify-between items-center py-2 border-b border-gray-50">
            <span class="text-sm text-gray-800">{{ $stock->product->name ?? '-' }}</span>
            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-400">Min: {{ $stock->min_stock }}</span>
                <span class="bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded-full">
                    Sisa: {{ $stock->quantity }}
                </span>
            </div>
        </div>
        @empty
        <p class="text-sm text-gray-400 text-center py-6">Semua stok aman ✅</p>
        @endforelse
    </div>
</x-app-layout>