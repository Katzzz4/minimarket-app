<x-app-layout>
    <x-slot name="title">Detail Cabang</x-slot>

    {{-- Header Cabang --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-base font-semibold text-gray-800">{{ $branch->name }}</h2>
            <p class="text-xs text-gray-400 mt-0.5">{{ $branch->city }} — {{ $branch->address ?? 'Alamat belum diisi' }}</p>
        </div>
        <a href="{{ route('branches.index') }}" class="text-xs text-gray-500 hover:underline">← Kembali</a>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500 mb-1">Total Produk</p>
            <p class="text-2xl font-semibold text-blue-700">{{ $stocks->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500 mb-1">Total Pegawai</p>
            <p class="text-2xl font-semibold text-blue-700">{{ $employees->count() }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6">

        {{-- Stok Produk --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold mb-4">Stok Produk di {{ $branch->name }}</h3>

            {{-- Search produk --}}
            <input
                type="text"
                id="search-product"
                placeholder="Cari produk..."
                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-300 mb-4"
            >

            <table class="w-full text-xs">
                <thead>
                    <tr class="text-gray-400 border-b border-gray-100">
                        <th class="text-left pb-3 font-medium">#</th>
                        <th class="text-left pb-3 font-medium">Produk</th>
                        <th class="text-left pb-3 font-medium">Kategori</th>
                        <th class="text-right pb-3 font-medium">Stok</th>
                        <th class="text-right pb-3 font-medium">Min</th>
                        <th class="text-left pb-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody id="product-table">
                    @forelse($stocks as $i => $stock)
                    <tr class="border-b border-gray-50 hover:bg-gray-50 product-row">
                        <td class="py-2 text-gray-400">{{ $i + 1 }}</td>
                        <td class="py-2 font-medium product-name">{{ $stock->product->name ?? '-' }}</td>
                        <td class="py-2 text-gray-500">
                            <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                                {{ $stock->product->category->name ?? '-' }}
                            </span>
                        </td>
                        <td class="py-2 text-right font-semibold">{{ $stock->quantity }}</td>
                        <td class="py-2 text-right text-gray-400">{{ $stock->min_stock }}</td>
                        <td class="py-2">
                            @if($stock->quantity <= $stock->min_stock)
                                <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full">Menipis</span>
                            @else
                                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Aman</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-300">
                            <i class="fa-solid fa-box-open text-2xl mb-2 block"></i>
                            Belum ada data stok
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- List Pegawai --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold mb-4">Pegawai {{ $branch->name }}</h3>

            {{-- Filter role --}}
            <div class="flex gap-2 mb-4 flex-wrap">
                <button onclick="filterRole('all')" class="role-btn active text-xs px-3 py-1.5 rounded-full border border-blue-600 bg-blue-600 text-white">
                    Semua
                </button>
                @foreach(['Manajer Toko', 'Supervisor', 'Kasir', 'Pegawai Gudang'] as $role)
                <button onclick="filterRole('{{ $role }}')" class="role-btn text-xs px-3 py-1.5 rounded-full border border-gray-200 text-gray-600 hover:border-blue-400 hover:text-blue-600">
                    {{ $role }}
                </button>
                @endforeach
            </div>

            <table class="w-full text-xs">
                <thead>
                    <tr class="text-gray-400 border-b border-gray-100">
                        <th class="text-left pb-3 font-medium">#</th>
                        <th class="text-left pb-3 font-medium">Nama</th>
                        <th class="text-left pb-3 font-medium">Email</th>
                        <th class="text-left pb-3 font-medium">Role</th>
                    </tr>
                </thead>
                <tbody id="employee-table">
                    @forelse($employees as $i => $employee)
                    <tr class="border-b border-gray-50 hover:bg-gray-50 employee-row"
                        data-role="{{ $employee->getRoleNames()->first() }}">
                        <td class="py-2 text-gray-400">{{ $i + 1 }}</td>
                        <td class="py-2 font-medium">{{ $employee->name }}</td>
                        <td class="py-2 text-gray-500">{{ $employee->email }}</td>
                        <td class="py-2">
                            <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">
                                {{ $employee->getRoleNames()->first() ?? '-' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-300">
                            <i class="fa-solid fa-users text-2xl mb-2 block"></i>
                            Belum ada pegawai
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <script>
        // Filter role pegawai
        function filterRole(role) {
            // Update tombol aktif
            document.querySelectorAll('.role-btn').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                btn.classList.add('border-gray-200', 'text-gray-600');
            });
            event.target.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
            event.target.classList.remove('border-gray-200', 'text-gray-600');

            // Filter baris
            document.querySelectorAll('.employee-row').forEach(row => {
                if (role === 'all' || row.dataset.role === role) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Search produk
        document.getElementById('search-product').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.product-row').forEach(row => {
                const name = row.querySelector('.product-name').textContent.toLowerCase();
                row.style.display = name.includes(q) ? '' : 'none';
            });
        });
    </script>
</x-app-layout>