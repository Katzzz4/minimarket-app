<x-app-layout>
    <x-slot name="title">Point of Sale</x-slot>

    <div class="grid grid-cols-3 gap-6">

        {{-- Daftar Produk --}}
        <div class="col-span-2">
            <div class="mb-4">
                <input
                    type="text"
                    id="search"
                    placeholder="Cari produk..."
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                >
            </div>

            <div class="grid grid-cols-3 gap-3" id="product-grid">
                @foreach($products as $stock)
                <div
                    class="product-card bg-white border border-gray-200 rounded-xl p-4 cursor-pointer hover:border-blue-400 hover:shadow-sm transition-all"
                    data-id="{{ $stock->product->id }}"
                    data-name="{{ $stock->product->name }}"
                    data-price="{{ $stock->product->price }}"
                    data-stock="{{ $stock->quantity }}"
                    onclick="addToCart(this)"
                >
                    <div class="text-sm font-medium text-gray-800 mb-1">{{ $stock->product->name }}</div>
                    <div class="text-xs text-gray-400 mb-2">Stok: {{ $stock->quantity }}</div>
                    <div class="text-sm font-semibold text-blue-600">
                        Rp {{ number_format($stock->product->price, 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Keranjang --}}
        <div class="col-span-1">
            <div class="bg-white border border-gray-200 rounded-xl p-4 sticky top-6">
                <h2 class="text-sm font-semibold mb-4">Keranjang Belanja</h2>

                <div id="cart-items" class="space-y-2 mb-4 min-h-[120px]">
                    <p class="text-xs text-gray-400 text-center py-6" id="empty-msg">Belum ada item</p>
                </div>

                <div class="border-t border-gray-100 pt-3 mb-4">
                    <div class="flex justify-between text-sm font-semibold">
                        <span>Total</span>
                        <span id="total-display">Rp 0</span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-xs text-gray-600 mb-1">Uang Dibayar (Rp)</label>
                    <input
                        type="number"
                        id="paid-input"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                        placeholder="0"
                        oninput="updateChange()"
                        min="0"
                    >
                </div>

                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Kembalian</span>
                        <span id="change-display" class="font-semibold text-green-700">Rp 0</span>
                    </div>
                </div>

                <form id="pos-form" action="{{ route('transactions.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="paid" id="paid-hidden">
                    <div id="items-hidden"></div>
                    <button
                        type="button"
                        onclick="submitTransaction()"
                        class="w-full bg-blue-600 text-white text-sm py-2.5 rounded-lg hover:bg-blue-700 font-medium"
                    >
                        Proses Transaksi
                    </button>
                </form>

                <button
                    onclick="clearCart()"
                    class="w-full mt-2 text-xs text-gray-400 hover:text-red-500 py-1"
                >
                    Kosongkan Keranjang
                </button>
            </div>
        </div>
    </div>

    <script>
        let cart = {};
        let total = 0;

        function addToCart(el) {
            const id = el.dataset.id;
            const name = el.dataset.name;
            const price = parseInt(el.dataset.price);
            const stock = parseInt(el.dataset.stock);

            if (cart[id] && cart[id].quantity >= stock) {
                alert('Stok tidak cukup!');
                return;
            }

            if (cart[id]) {
                cart[id].quantity++;
            } else {
                cart[id] = { name, price, quantity: 1, stock };
            }

            renderCart();
        }

        function removeFromCart(id) {
            delete cart[id];
            renderCart();
        }

        function changeQty(id, delta) {
            if (!cart[id]) return;
            cart[id].quantity += delta;
            if (cart[id].quantity <= 0) {
                delete cart[id];
            } else if (cart[id].quantity > cart[id].stock) {
                cart[id].quantity = cart[id].stock;
                alert('Stok tidak cukup!');
            }
            renderCart();
        }

        function renderCart() {
            const container = document.getElementById('cart-items');
            const emptyMsg = document.getElementById('empty-msg');
            const keys = Object.keys(cart);

            total = 0;
            container.innerHTML = '';

            if (keys.length === 0) {
                container.innerHTML = '<p class="text-xs text-gray-400 text-center py-6">Belum ada item</p>';
                document.getElementById('total-display').textContent = 'Rp 0';
                updateChange();
                return;
            }

            keys.forEach(id => {
                const item = cart[id];
                const subtotal = item.price * item.quantity;
                total += subtotal;

                container.innerHTML += `
                    <div class="flex items-center justify-between gap-2 text-xs border-b border-gray-50 pb-2">
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-gray-800 truncate">${item.name}</div>
                            <div class="text-gray-400">Rp ${item.price.toLocaleString('id-ID')}</div>
                        </div>
                        <div class="flex items-center gap-1">
                            <button onclick="changeQty('${id}', -1)" class="w-6 h-6 rounded bg-gray-100 hover:bg-gray-200 text-gray-600">−</button>
                            <span class="w-5 text-center font-medium">${item.quantity}</span>
                            <button onclick="changeQty('${id}', 1)" class="w-6 h-6 rounded bg-gray-100 hover:bg-gray-200 text-gray-600">+</button>
                            <button onclick="removeFromCart('${id}')" class="w-6 h-6 rounded bg-red-50 hover:bg-red-100 text-red-500 ml-1">×</button>
                        </div>
                    </div>
                `;
            });

            document.getElementById('total-display').textContent = 'Rp ' + total.toLocaleString('id-ID');
            updateChange();
        }

        function updateChange() {
            const paid = parseInt(document.getElementById('paid-input').value) || 0;
            const change = paid - total;
            const el = document.getElementById('change-display');
            if (change < 0) {
                el.textContent = '- Rp ' + Math.abs(change).toLocaleString('id-ID');
                el.classList.remove('text-green-700');
                el.classList.add('text-red-600');
            } else {
                el.textContent = 'Rp ' + change.toLocaleString('id-ID');
                el.classList.remove('text-red-600');
                el.classList.add('text-green-700');
            }
        }

        function submitTransaction() {
            const keys = Object.keys(cart);
            if (keys.length === 0) { alert('Keranjang masih kosong!'); return; }

            const paid = parseInt(document.getElementById('paid-input').value) || 0;
            if (paid < total) { alert('Uang dibayar kurang dari total!'); return; }

            document.getElementById('paid-hidden').value = paid;

            const itemsContainer = document.getElementById('items-hidden');
            itemsContainer.innerHTML = '';
            keys.forEach((id, i) => {
                itemsContainer.innerHTML += `
                    <input type="hidden" name="items[${i}][product_id]" value="${id}">
                    <input type="hidden" name="items[${i}][quantity]" value="${cart[id].quantity}">
                `;
            });

            document.getElementById('pos-form').submit();
        }

        function clearCart() {
            cart = {};
            total = 0;
            renderCart();
            document.getElementById('paid-input').value = '';
            document.getElementById('change-display').textContent = 'Rp 0';
        }

        // Search produk
        document.getElementById('search').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.product-card').forEach(card => {
                const name = card.dataset.name.toLowerCase();
                card.style.display = name.includes(q) ? '' : 'none';
            });
        });
    </script>
</x-app-layout>