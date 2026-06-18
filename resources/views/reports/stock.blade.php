<x-app-layout>
    <x-slot name="title">Laporan Stok</x-slot>

    <div class="bg-white rounded-xl border border-gray-200 p-6">

        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <h2 class="text-sm font-semibold text-gray-800">Laporan Stok Barang</h2>
            <div class="flex gap-2">
                <a href="{{ route('reports.stock.pdf', request()->query()) }}"
                   class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 bg-gray-50 border border-gray-200 px-3.5 py-2 rounded-lg hover:bg-gray-100 hover:text-gray-800 transition-colors">
                    <span aria-hidden="true"></span> Export PDF
                </a>
                <a href="{{ route('reports.stock.excel', request()->query()) }}"
                   class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 bg-gray-50 border border-gray-200 px-3.5 py-2 rounded-lg hover:bg-gray-100 hover:text-gray-800 transition-colors">
                    <span aria-hidden="true"></span> Export Excel
                </a>
            </div>
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
            <div class="flex flex-col gap-1">
                <label class="text-[11px] text-gray-500">Tipe</label>
                <select name="type"
                        class="border border-gray-200 rounded-lg px-3 py-1.5 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300">
                    <option value="">Semua Tipe</option>
                    <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Masuk</option>
                    <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Keluar</option>
                    <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                </select>
            </div>
            @role('Owner')
            <div class="flex flex-col gap-1">
                <label class="text-[11px] text-gray-500">Cabang</label>
                <select name="branch_id"
                        class="border border-gray-200 rounded-lg px-3 py-1.5 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300">
                    <option value="">Semua Cabang</option>
                    @foreach(\App\Models\Branch::all() as $branch)
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
                    <th class="text-left pb-3 font-medium">Produk</th>
                    <th class="text-left pb-3 font-medium">Cabang</th>
                    <th class="text-left pb-3 font-medium">Tipe</th>
                    <th class="text-left pb-3 font-medium">Jumlah</th>
                    <th class="text-left pb-3 font-medium">Catatan</th>
                    <th class="text-left pb-3 font-medium">Petugas</th>
                    <th class="text-left pb-3 font-medium">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $m)
                <tr class="border-b border-gray-50 hover:bg-gray-50/80">
                    <td class="py-3 font-medium text-gray-800">{{ $m->product->name ?? '-' }}</td>
                    <td class="py-3 text-gray-600">{{ $m->branch->name ?? '-' }}</td>
                    <td class="py-3">
                        @if($m->type === 'in')
                            <span class="bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full font-medium">Masuk</span>
                        @elseif($m->type === 'out')
                            <span class="bg-rose-50 text-rose-700 px-2 py-0.5 rounded-full font-medium">Keluar</span>
                        @else
                            <span class="bg-amber-50 text-amber-700 px-2 py-0.5 rounded-full font-medium">Adjustment</span>
                        @endif
                    </td>
                    <td class="py-3 text-gray-700">{{ $m->quantity }}</td>
                    <td class="py-3 text-gray-500">{{ $m->note ?? '-' }}</td>
                    <td class="py-3 text-gray-600">{{ $m->user->name ?? '-' }}</td>
                    <td class="py-3 text-gray-400">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-8 text-center text-gray-400">Tidak ada data mutasi stok</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>

        <div class="mt-4">{{ $movements->links() }}</div>
    </div>
</x-app-layout>