<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500 mb-2">Transaksi Hari Ini</p>
            <p class="text-2xl font-semibold text-blue-700">{{ $summary['total_transactions_today'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500 mb-2">Penjualan Hari Ini</p>
            <p class="text-2xl font-semibold text-green-700">Rp {{ number_format($summary['total_sales_today'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500 mb-2">Stok Menipis</p>
            <p class="text-2xl font-semibold text-amber-600">{{ $summary['low_stock_products']->count() }}</p>
            <p class="text-xs text-gray-400 mt-1">produk perlu restock</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-sm font-semibold">Transaksi Terbaru</h2>
                <a href="{{ route('transactions.index') }}" class="text-xs text-blue-600 hover:underline">Lihat semua →</a>
            </div>
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-gray-400 border-b border-gray-100">
                        <th class="text-left pb-2 font-normal">Invoice</th>
                        <th class="text-left pb-2 font-normal">Kasir</th>
                        <th class="text-left pb-2 font-normal">Total</th>
                        <th class="text-left pb-2 font-normal">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $trx)
                    <tr class="border-b border-gray-50">
                        <td class="py-2 text-gray-500">{{ $trx->invoice_number }}</td>
                        <td class="py-2">{{ $trx->user->name ?? '-' }}</td>
                        <td class="py-2 font-medium">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                        <td class="py-2 text-gray-400">{{ $trx->created_at->format('H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-4 text-center text-gray-400">Belum ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="text-sm font-semibold mb-4">Stok Menipis</h2>
            @forelse($summary['low_stock_products'] as $stock)
            <div class="flex justify-between items-center py-2 border-b border-gray-50">
                <span class="text-sm">{{ $stock->product->name ?? '-' }}</span>
                <span class="bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded-full">Sisa: {{ $stock->quantity }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">Semua stok aman ✅</p>
            @endforelse
        </div>
    </div>
</x-app-layout>