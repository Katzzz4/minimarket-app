<x-app-layout>
    <x-slot name="title">Manajemen Stok</x-slot>

    <div class="grid grid-cols-3 gap-6">

        {{-- Form Input Stok --}}
        <div class="col-span-1">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-sm font-semibold mb-4">Input Stok</h2>

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-50 text-green-700 text-xs rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('stock.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Produk</label>
                        <select name="product_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $stock)
                            <option value="{{ $stock->product->id }}" {{ old('product_id') == $stock->product->id ? 'selected' : '' }}>
                                {{ $stock->product->name }} (Stok: {{ $stock->quantity }})
                            </option>
                            @endforeach
                        </select>
                        @error('product_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Tipe</label>
                        <select name="type" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>Masuk</option>
                            <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>Keluar</option>
                            <option value="adjustment" {{ old('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                        </select>
                        @error('type')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Jumlah</label>
                        <input type="number" name="quantity" value="{{ old('quantity') }}" min="1"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                            placeholder="0" required>
                        @error('quantity')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Catatan (opsional)</label>
                        <textarea name="note" rows="2"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                            placeholder="Keterangan tambahan...">{{ old('note') }}</textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 text-white text-sm py-2 rounded-lg hover:bg-blue-700 font-medium">
                        Simpan
                    </button>
                </form>
            </div>
        </div>

        {{-- Riwayat Mutasi Stok --}}
        <div class="col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-sm font-semibold">Riwayat Mutasi Stok</h2>

                    {{-- Filter --}}
                    <form method="GET" class="flex items-center gap-2">
                        <select name="type" class="border border-gray-200 rounded-lg px-3 py-1.5 text-xs focus:outline-none">
                            <option value="">Semua Tipe</option>
                            <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Masuk</option>
                            <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Keluar</option>
                            <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                        </select>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="border border-gray-200 rounded-lg px-3 py-1.5 text-xs focus:outline-none">
                        <span class="text-xs text-gray-400">s/d</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="border border-gray-200 rounded-lg px-3 py-1.5 text-xs focus:outline-none">
                        <button type="submit"
                            class="bg-blue-600 text-white text-xs px-3 py-1.5 rounded-lg hover:bg-blue-700">
                            Filter
                        </button>
                    </form>
                </div>

                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-gray-400 border-b border-gray-100">
                            <th class="text-left pb-3 font-medium px-2">#</th>
                            <th class="text-left pb-3 font-medium px-2">Produk</th>
                            <th class="text-left pb-3 font-medium px-2">Tipe</th>
                            <th class="text-left pb-3 font-medium px-2">Jumlah</th>
                            <th class="text-left pb-3 font-medium px-2">Catatan</th>
                            <th class="text-left pb-3 font-medium px-2">Oleh</th>
                            <th class="text-left pb-3 font-medium px-2">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $i => $m)
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="py-3 px-2 text-gray-400">{{ $movements->firstItem() + $i }}</td>
                            <td class="py-3 px-2 font-medium">{{ $m->product->name ?? '-' }}</td>
                            <td class="py-3 px-2">
                                @if($m->type === 'in')
                                    <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Masuk</span>
                                @elseif($m->type === 'out')
                                    <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full">Keluar</span>
                                @else
                                    <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">Adjustment</span>
                                @endif
                            </td>
                            <td class="py-3 px-2">{{ $m->quantity }}</td>
                            <td class="py-3 px-2 text-gray-500">{{ $m->note ?? '-' }}</td>
                            <td class="py-3 px-2">{{ $m->user->name ?? '-' }}</td>
                            <td class="py-3 px-2 text-gray-400">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-gray-300">
                                <i class="fa-solid fa-box-open text-2xl mb-2 block"></i>
                                Belum ada mutasi stok
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $movements->links() }}</div>
            </div>
        </div>

    </div>
</x-app-layout>