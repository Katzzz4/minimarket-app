<x-app-layout>
    <x-slot name="title">Detail Produk</x-slot>

    <div class="max-w-2xl">
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-base font-semibold">{{ $product->name }}</h2>
                    <p class="text-xs text-gray-400 mt-0.5">SKU: {{ $product->sku }}</p>
                </div>
                @role('Owner')
                <a href="{{ route('products.edit', $product->id) }}" class="text-xs bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Edit</a>
                @endrole
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-xs text-gray-400 mb-1">Kategori</p>
                    <p class="text-sm">{{ $product->category->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-1">Harga Jual</p>
                    <p class="text-sm font-medium text-green-700">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-1">Harga Modal</p>
                    <p class="text-sm">Rp {{ number_format($product->cost_price, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Stok per cabang -->
            <h3 class="text-xs font-semibold text-gray-500 mb-3">Stok per Cabang</h3>
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-gray-400 border-b border-gray-100">
                        <th class="text-left pb-2 font-normal">Cabang</th>
                        <th class="text-left pb-2 font-normal">Kota</th>
                        <th class="text-left pb-2 font-normal">Stok</th>
                        <th class="text-left pb-2 font-normal">Min. Stok</th>
                        <th class="text-left pb-2 font-normal">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($product->stocks as $stock)
                    <tr class="border-b border-gray-50">
                        <td class="py-2 font-medium">{{ $stock->branch->name ?? '-' }}</td>
                        <td class="py-2 text-gray-500">{{ $stock->branch->city ?? '-' }}</td>
                        <td class="py-2">{{ $stock->quantity }}</td>
                        <td class="py-2">{{ $stock->min_stock }}</td>
                        <td class="py-2">
                            @if($stock->quantity <= $stock->min_stock)
                                <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full">Menipis</span>
                            @else
                                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Aman</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-4 text-center text-gray-400">Belum ada data stok</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <a href="{{ route('products.index') }}" class="text-sm text-gray-500 hover:underline">← Kembali</a>
    </div>
</x-app-layout>