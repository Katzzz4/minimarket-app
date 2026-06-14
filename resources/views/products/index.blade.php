<x-app-layout>
    <x-slot name="title">Produk</x-slot>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex justify-between items-center mb-5">
            <h2 class="text-sm font-semibold">Daftar Produk</h2>
            <a href="{{ route('products.create') }}" class="text-xs bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Tambah Produk</a>
        </div>
        <table class="w-full text-xs">
            <thead>
                <tr class="text-gray-400 border-b border-gray-100">
                    <th class="text-left pb-3 font-normal">Nama Produk</th>
                    <th class="text-left pb-3 font-normal">SKU</th>
                    <th class="text-left pb-3 font-normal">Kategori</th>
                    <th class="text-left pb-3 font-normal">Harga Jual</th>
                    <th class="text-left pb-3 font-normal">Harga Modal</th>
                    <th class="text-left pb-3 font-normal">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="py-3 font-medium">{{ $product->name }}</td>
                    <td class="py-3 text-gray-500">{{ $product->sku }}</td>
                    <td class="py-3">{{ $product->category->name ?? '-' }}</td>
                    <td class="py-3">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="py-3">Rp {{ number_format($product->cost_price, 0, ',', '.') }}</td>
                    <td class="py-3 flex gap-2">
                        <a href="{{ route('products.edit', $product->id) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-6 text-center text-gray-400">Belum ada produk</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $products->links() }}</div>
    </div>
</x-app-layout>