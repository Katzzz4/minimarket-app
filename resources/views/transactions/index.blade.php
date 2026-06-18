<x-app-layout>
    <x-slot name="title">Transaksi</x-slot>

    <div class="bg-white rounded-xl border border-gray-200 p-6">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-sm font-semibold text-gray-800">Daftar Transaksi</h2>
        </div>

        <!-- Filter -->
        <form method="GET" class="flex flex-wrap items-end gap-3 mb-6 p-4 bg-gray-50 border border-gray-100 rounded-lg">
            <div class="flex flex-col gap-1">
                <label class="text-[11px] text-gray-500">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                       class="border border-gray-200 rounded-lg px-3 py-1.5 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-[11px] text-gray-500">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                       class="border border-gray-200 rounded-lg px-3 py-1.5 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300">
            </div>
            @role('Owner')
            <div class="flex flex-col gap-1">
                <label class="text-[11px] text-gray-500">Cabang</label>
                <select name="branch_id"
                        class="border border-gray-200 rounded-lg px-3 py-1.5 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300">
                    <option value="">Semua Cabang</option>
                    @foreach($branches ?? [] as $branch)
                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            @endrole
            <button type="submit"
                    class="bg-indigo-600 text-white text-xs font-medium px-4 py-1.5 rounded-lg hover:bg-indigo-700 transition-colors">
                Filter
            </button>
        </form>

        <!-- Table -->
        <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr class="text-gray-400 border-b border-gray-100 uppercase tracking-wide text-[10px]">
                    <th class="text-left pb-3 font-medium">Invoice</th>
                    <th class="text-left pb-3 font-medium">Kasir</th>
                    <th class="text-left pb-3 font-medium">Cabang</th>
                    <th class="text-left pb-3 font-medium">Total</th>
                    <th class="text-left pb-3 font-medium">Bayar</th>
                    <th class="text-left pb-3 font-medium">Kembalian</th>
                    <th class="text-left pb-3 font-medium">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                <tr class="border-b border-gray-50 hover:bg-gray-50/80">
                    <td class="py-3 font-medium text-gray-800">{{ $trx->invoice_number }}</td>
                    <td class="py-3 text-gray-600">{{ $trx->user->name ?? '-' }}</td>
                    <td class="py-3 text-gray-600">{{ $trx->branch->name ?? '-' }}</td>
                    <td class="py-3 text-gray-700 font-medium">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                    <td class="py-3 text-gray-600">Rp {{ number_format($trx->paid, 0, ',', '.') }}</td>
                    <td class="py-3 text-gray-600">Rp {{ number_format($trx->change, 0, ',', '.') }}</td>
                    <td class="py-3 text-gray-400">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-8 text-center text-gray-400">Belum ada transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>

        <div class="mt-4">{{ $transactions->links() }}</div>
    </div>
</x-app-layout>