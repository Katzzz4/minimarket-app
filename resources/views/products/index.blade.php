<x-app-layout>
    <x-slot name="title">Produk</x-slot>

    <div class="bg-white rounded-xl border border-gray-200 p-6">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-sm font-semibold text-gray-800">Daftar Produk</h2>
                <p class="text-xs text-gray-400 mt-0.5">Total {{ $products->total() }} produk terdaftar</p>
            </div>
            @role('Owner')
            <a href="{{ route('products.create') }}"
                class="flex items-center gap-2 text-xs font-medium bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                <i class="fa-solid fa-plus"></i> Tambah Produk
            </a>
            @endrole
        </div>

        {{-- Search --}}
        <form method="GET" class="flex items-center gap-3 mb-6 p-4 bg-gray-50 border border-gray-100 rounded-lg">
            <div class="flex-1 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[11px]"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama produk atau SKU..."
                    class="w-full border border-gray-200 rounded-lg pl-8 pr-3 py-1.5 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300">
            </div>
            <button type="submit"
                class="bg-indigo-600 text-white text-xs font-medium px-4 py-1.5 rounded-lg hover:bg-indigo-700 transition-colors">
                Cari
            </button>
            @if(request('search'))
            <a href="{{ route('products.index') }}"
                class="text-xs text-gray-500 hover:text-gray-700 transition-colors">
                Reset
            </a>
            @endif
        </form>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-gray-400 border-b border-gray-100 uppercase tracking-wide text-[10px]">
                        <th class="text-left pb-3 font-medium px-2">#</th>
                        <th class="text-left pb-3 font-medium px-2">Nama Produk</th>
                        <th class="text-left pb-3 font-medium px-2">SKU</th>
                        <th class="text-left pb-3 font-medium px-2">Kategori</th>
                        <th class="text-right pb-3 font-medium px-2">Harga Jual</th>
                        @role('Owner')
                        <th class="text-right pb-3 font-medium px-2">Harga Modal</th>
                        @endrole
                        <th class="text-center pb-3 font-medium px-2">Stok</th>
                        <th class="text-center pb-3 font-medium px-2">Status</th>
                        @role('Owner')
                        <th class="text-center pb-3 font-medium px-2">Aksi</th>
                        @endrole
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $i => $product)
                        @php
                            $stock    = $product->stocks->first();
                            $qty      = $stock->quantity  ?? 0;
                            $minStock = $stock->min_stock ?? 0;
                            $menipis  = $qty <= $minStock;
                        @endphp
                        <tr class="border-b border-gray-50 hover:bg-gray-50/80">
                            <td class="py-3 px-2 text-gray-400">{{ $products->firstItem() + $i }}</td>
                            <td class="py-3 px-2 font-medium text-gray-800">{{ $product->name }}</td>
                            <td class="py-3 px-2 text-gray-400">{{ $product->sku }}</td>
                            <td class="py-3 px-2">
                                @if($product->category)
                                    <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-medium">
                                        {{ $product->category->name }}
                                    </span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-2 text-right font-medium text-emerald-700">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            @role('Owner')
                            <td class="py-3 px-2 text-right text-gray-500">
                                Rp {{ number_format($product->cost_price, 0, ',', '.') }}
                            </td>
                            @endrole
                            <td class="py-3 px-2 text-center text-gray-700 font-medium">
                                {{ $qty }}
                            </td>
                            <td class="py-3 px-2 text-center">
                                @if($menipis)
                                    <span class="bg-rose-50 text-rose-700 px-2 py-0.5 rounded-full font-medium">Menipis</span>
                                @else
                                    <span class="bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full font-medium">Aman</span>
                                @endif
                            </td>
                            @role('Owner')
                            <td class="py-3 px-2">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('products.show', $product->id) }}"
                                        class="text-xs font-medium text-gray-600 bg-gray-100 px-2.5 py-1 rounded-md hover:bg-gray-200 transition-colors">
                                        Detail
                                    </a>
                                    <a href="{{ route('products.edit', $product->id) }}"
                                        class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md hover:bg-indigo-100 transition-colors">
                                        Edit
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus produk {{ $product->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="text-xs font-medium text-rose-600 bg-rose-50 px-2.5 py-1 rounded-md hover:bg-rose-100 transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endrole
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-10 text-center text-gray-300">
                                <i class="fa-solid fa-box-open text-2xl mb-2 block"></i>
                                @if(request('search'))
                                    Produk "{{ request('search') }}" tidak ditemukan
                                @else
                                    Belum ada produk
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $products->links() }}
        </div>

    </div>
</x-app-layout>