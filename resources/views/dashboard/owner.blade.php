<x-app-layout>
    <x-slot name="title">Dashboard Owner</x-slot>
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500 mb-2">Total Transaksi Hari Ini</p>
            <p class="text-2xl font-semibold text-blue-700">{{ $summary['total_transactions_today'] }}</p>
            <p class="text-xs text-gray-400 mt-1">dari {{ $summary['total_branches'] }} cabang</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500 mb-2">Pendapatan Hari Ini</p>
            <p class="text-2xl font-semibold text-green-700">Rp {{ number_format($summary['total_sales_today'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">dari semua cabang</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500 mb-2">Stok Menipis</p>
            <p class="text-2xl font-semibold text-amber-600">{{ $summary['total_low_stock'] }}</p>
            <p class="text-xs text-gray-400 mt-1">perlu restock segera</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500 mb-2">Cabang Aktif</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $summary['total_branches'] }} / {{ $summary['total_branches'] }}</p>
            <p class="text-xs text-gray-400 mt-1">semua beroperasi</p>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-sm font-semibold">Transaksi Terbaru</h2>
                <a href="{{ route('transactions.index') }}" class="text-xs text-blue-600 hover:underline">Lihat semua →</a>
            </div>
            <table class="w-full text-xs">
                <thead><tr class="text-gray-400 border-b border-gray-100">
                    <th class="text-left pb-2 font-normal">Invoice</th>
                    <th class="text-left pb-2 font-normal">Kasir</th>
                    <th class="text-left pb-2 font-normal">Cabang</th>
                    <th class="text-left pb-2 font-normal">Total</th>
                </tr></thead>
                <tbody>
                    @forelse($recentTransactions as $trx)
                    <tr class="border-b border-gray-50">
                        <td class="py-2 text-gray-500">{{ $trx->invoice_number }}</td>
                        <td class="py-2">{{ $trx->user->name ?? '-' }}</td>
                        <td class="py-2">{{ $trx->branch->name ?? '-' }}</td>
                        <td class="py-2 font-medium">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-4 text-center text-gray-400">Belum ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-sm font-semibold">Ringkasan per Cabang</h2>
                <a href="{{ route('branches.index') }}" class="text-xs text-blue-600 hover:underline">Detail →</a>
            </div>
            <table class="w-full text-xs">
                <thead><tr class="text-gray-400 border-b border-gray-100">
                    <th class="text-left pb-2 font-normal">Cabang</th>
                    <th class="text-left pb-2 font-normal">Kota</th>
                    <th class="text-left pb-2 font-normal">Transaksi</th>
                    <th class="text-left pb-2 font-normal">Penjualan</th>
                    <th class="text-left pb-2 font-normal">Stok Menipis</th>
                </tr></thead>
                <tbody>
                    @forelse($branches as $branch)
                    <tr class="border-b border-gray-50">
                        <td class="py-2 font-medium">{{ $branch['name'] }}</td>
                        <td class="py-2 text-gray-500">{{ $branch['city'] }}</td>
                        <td class="py-2">{{ $branch['total_transactions_today'] }}</td>
                        <td class="py-2">Rp {{ number_format($branch['total_sales_today'], 0, ',', '.') }}</td>
                        <td class="py-2">
                            @if($branch['low_stock_count'] > 0)
                                <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full">{{ $branch['low_stock_count'] }}</span>
                            @else
                                <span class="text-green-600">Aman</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-4 text-center text-gray-400">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>