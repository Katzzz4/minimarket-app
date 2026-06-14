<x-app-layout>
    <x-slot name="title">Transaksi</x-slot>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex justify-between items-center mb-5">
            <h2 class="text-sm font-semibold">Daftar Transaksi</h2>
            <form method="GET" class="flex items-center gap-2">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="border border-gray-200 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-300">
                <span class="text-xs text-gray-400">s/d</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="border border-gray-200 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-300">
                @role('Owner')
                <select name="branch_id" class="border border-gray-200 rounded-lg px-3 py-1.5 text-xs focus:outline-none">
                    <option value="">Semua Cabang</option>
                    @foreach($branches ?? [] as $branch)
                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
                @endrole
                <button type="submit" class="bg-blue-600 text-white text-xs px-4 py-1.5 rounded-lg hover:bg-blue-700">Filter</button>
            </form>
        </div>
        <table class="w-full text-xs">
            <thead>
                <tr class="text-gray-400 border-b border-gray-100">
                    <th class="text-left pb-3 font-normal">Invoice</th>
                    <th class="text-left pb-3 font-normal">Kasir</th>
                    <th class="text-left pb-3 font-normal">Cabang</th>
                    <th class="text-left pb-3 font-normal">Total</th>
                    <th class="text-left pb-3 font-normal">Bayar</th>
                    <th class="text-left pb-3 font-normal">Kembalian</th>
                    <th class="text-left pb-3 font-normal">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="py-3 font-medium text-blue-600">{{ $trx->invoice_number }}</td>
                    <td class="py-3">{{ $trx->user->name ?? '-' }}</td>
                    <td class="py-3">{{ $trx->branch->name ?? '-' }}</td>
                    <td class="py-3">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                    <td class="py-3">Rp {{ number_format($trx->paid, 0, ',', '.') }}</td>
                    <td class="py-3">Rp {{ number_format($trx->change, 0, ',', '.') }}</td>
                    <td class="py-3 text-gray-400">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-6 text-center text-gray-400">Belum ada transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $transactions->links() }}</div>
    </div>
</x-app-layout>