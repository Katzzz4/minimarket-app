<x-app-layout>
    <x-slot name="title">Detail Cabang</x-slot>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-base font-semibold text-gray-800">{{ $branch->name }}</h2>
            <p class="text-xs text-gray-400 mt-0.5">{{ $branch->city }} — {{ $branch->address ?? 'Alamat belum diisi' }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('branches.edit', $branch->id) }}"
                class="text-xs border border-gray-200 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-50">
                <i class="fa-solid fa-pen mr-1"></i> Edit Cabang
            </a>
            <a href="{{ route('branches.index') }}" class="text-xs text-gray-400 hover:underline">← Kembali</a>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500 mb-1">Penjualan Hari Ini</p>
            <p class="text-xl font-semibold text-green-700">Rp {{ number_format($totalSalesToday, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $totalTransactionsToday }} transaksi</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500 mb-1">Penjualan Bulan Ini</p>
            <p class="text-xl font-semibold text-blue-700">Rp {{ number_format($totalSalesMonth, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ now()->translatedFormat('F Y') }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500 mb-1">Total Penjualan</p>
            <p class="text-xl font-semibold text-gray-800">Rp {{ number_format($totalSalesAll, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Semua waktu</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500 mb-1">Stok Menipis</p>
            <p class="text-xl font-semibold text-amber-600">
                {{ $stocks->filter(fn($s) => $s->quantity <= $s->min_stock)->count() }}
            </p>
            <p class="text-xs text-gray-400 mt-1">dari {{ $stocks->count() }} produk</p>
        </div>
    </div>

    {{-- Transaksi Terbaru --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-semibold">Transaksi Terbaru</h3>
            <a href="{{ route('transactions.index', ['branch_id' => $branch->id]) }}"
                class="text-xs text-blue-600 hover:underline">Lihat semua →</a>
        </div>
        <table class="w-full text-xs">
            <thead>
                <tr class="text-gray-400 border-b border-gray-100">
                    <th class="text-left pb-2 font-medium">Invoice</th>
                    <th class="text-left pb-2 font-medium">Kasir</th>
                    <th class="text-right pb-2 font-medium">Total</th>
                    <th class="text-left pb-2 font-medium">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTransactions as $trx)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-2 font-medium text-blue-600">{{ $trx->invoice_number }}</td>
                        <td class="py-2 text-gray-600">{{ $trx->user->name ?? '-' }}</td>
                        <td class="py-2 text-right font-medium">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                        <td class="py-2 text-gray-400">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-6 text-center text-gray-300">Belum ada transaksi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="grid grid-cols-2 gap-6">

        {{-- Stok Produk --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold mb-4">Stok Produk</h3>

            <input type="text" id="search-product" placeholder="Cari produk..."
                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-300 mb-4">

            <table class="w-full text-xs">
                <thead>
                    <tr class="text-gray-400 border-b border-gray-100">
                        <th class="text-left pb-2 font-medium">#</th>
                        <th class="text-left pb-2 font-medium">Produk</th>
                        <th class="text-left pb-2 font-medium">Kategori</th>
                        <th class="text-right pb-2 font-medium">Stok</th>
                        <th class="text-left pb-2 font-medium">Status</th>
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
                            <td colspan="5" class="py-8 text-center text-gray-300">
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
            <h3 class="text-sm font-semibold mb-4">Pegawai ({{ $employees->count() }})</h3>

            {{-- Filter role --}}
            <div class="flex gap-2 mb-4 flex-wrap">
                <button onclick="filterRole('all', this)"
                    class="role-btn text-xs px-3 py-1.5 rounded-full border bg-blue-600 text-white border-blue-600">
                    Semua
                </button>
                @foreach(['Manajer Toko', 'Supervisor', 'Kasir', 'Pegawai Gudang'] as $role)
                    <button onclick="filterRole('{{ $role }}', this)"
                        class="role-btn text-xs px-3 py-1.5 rounded-full border border-gray-200 text-gray-600 hover:border-blue-400 hover:text-blue-600">
                        {{ $role }}
                    </button>
                @endforeach
            </div>

            <table class="w-full text-xs">
                <thead>
                    <tr class="text-gray-400 border-b border-gray-100">
                        <th class="text-left pb-2 font-medium">#</th>
                        <th class="text-left pb-2 font-medium">Nama</th>
                        <th class="text-left pb-2 font-medium">Role</th>
                    </tr>
                </thead>
                <tbody id="employee-table">
                    @forelse($employees as $i => $employee)
                        <tr class="border-b border-gray-50 hover:bg-gray-50 employee-row"
                            data-role="{{ $employee->getRoleNames()->first() }}">
                            <td class="py-2 text-gray-400">{{ $i + 1 }}</td>
                            <td class="py-2">
                                <div class="font-medium text-gray-800">{{ $employee->name }}</div>
                                <div class="text-gray-400">{{ $employee->email }}</div>
                            </td>
                            <td class="py-2">
                                @php $roleName = $employee->getRoleNames()->first() @endphp
                                @if($roleName === 'Manajer Toko')
                                    <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">{{ $roleName }}</span>
                                @elseif($roleName === 'Supervisor')
                                    <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full">{{ $roleName }}</span>
                                @elseif($roleName === 'Kasir')
                                    <span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">{{ $roleName }}</span>
                                @else
                                    <span
                                        class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded-full">{{ $roleName ?? '-' }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-gray-300">
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
        function filterRole(role, btn) {
            document.querySelectorAll('.role-btn').forEach(b => {
                b.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                b.classList.add('border-gray-200', 'text-gray-600');
            });
            btn.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
            btn.classList.remove('border-gray-200', 'text-gray-600');

            document.querySelectorAll('.employee-row').forEach(row => {
                row.style.display = (role === 'all' || row.dataset.role === role) ? '' : 'none';
            });
        }

        document.getElementById('search-product').addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.product-row').forEach(row => {
                const name = row.querySelector('.product-name').textContent.toLowerCase();
                row.style.display = name.includes(q) ? '' : 'none';
            });
        });
    </script>
</x-app-layout>