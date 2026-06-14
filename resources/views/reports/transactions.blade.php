<x-app-layout>
    <x-slot name="title">Laporan Transaksi</x-slot>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex justify-between items-center mb-5">
            <h2 class="text-sm font-semibold">Laporan Transaksi</h2>
            <div class="flex gap-2">
                <a href="{{ route('reports.transactions.pdf', request()->query()) }}" class="text-xs bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">⬇ Export PDF</a>
                <a href="{{ route('reports.transactions.excel', request()->query()) }}" class="text-xs bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">⬇ Export Excel</a>
            </div>
        </div>

        <form method="GET" class="flex items-center gap-2 mb-5 p-4 bg-gray-50 rounded-lg">
            <span class="text-xs text-gray-500">Tanggal:</span>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="border border-gray-200 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-300">
            <span class="text-xs text-gray-400">s/d</span>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="border border-gray-200 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-300">
            @role('Owner')
            <select name="branch_id" class="border border-gray-200 rounded-lg px-3 py-1.5 text-xs focus:outline-none">
                <option value="">Semua Cabang</option>
                @foreach(\App\Models\Branch::all() as $branch)
                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                @endforeach
            </select>
            @endrole
            <button type="submit" class="bg-blue-600 text-white text-xs px-4 py-1.5 rounded-lg hover:bg-blue-700">Filter</button>
        </form>

        <table class="w-full text-xs">
            <thead>
                <tr class="text-gray-400 border-b border-gray-100">
                    <th class="text-left pb-3 font-normal">Invoice</th>
                    <th class="text-left pb-3 font-normal">Kasir</th>
                    <th class="text-left pb-3 font-normal">Cabang</th>
                    <th class="text-left pb-3 font-normal">Total</th>
                    <th class="text-left pb-3 font-normal">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="py-3 font-medium">{{ $trx->invoice_number }}</td>
                    <td class="py-3">{{ $trx->user->name ?? '-' }}</td>
                    <td class="py-3">{{ $trx->branch->name ?? '-' }}</td>
                    <td class="py-3">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                    <td class="py-3 text-gray-400">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-6 text-center text-gray-400">Tidak ada data transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $transactions->links() }}</div>
    </div>
</x-app-layout>