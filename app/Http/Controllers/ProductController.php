<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $user     = auth()->user();
        $branchId = $user->branch_id;

        $products = Product::with(['category', 'stocks' => function ($q) use ($user, $branchId) {
                if (!$user->hasRole('Owner')) {
                    $q->where('branch_id', $branchId);
                }
            }])
            ->when(!$user->hasRole('Owner'), function ($q) use ($branchId) {
                $q->whereHas('stocks', fn($s) => $s->where('branch_id', $branchId));
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('sku', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'sku'         => 'required|string|unique:products,sku',
            'category_id' => 'nullable|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'cost_price'  => 'required|numeric|min:0',
        ]);

        $product = Product::create($validated);

        // Inisialisasi stok 0 di semua cabang
        Branch::all()->each(function ($branch) use ($product) {
            ProductStock::create([
                'product_id' => $product->id,
                'branch_id'  => $branch->id,
                'quantity'   => 0,
            ]);
        });

        return redirect()->route('products.index')->with('success', 'Produk ditambahkan.');
    }

    public function show(Product $product)
    {
        $user     = auth()->user();
        $branchId = $user->branch_id;

        // Manajer & Supervisor hanya boleh lihat produk cabang mereka
        if (!$user->hasRole('Owner')) {
            $hasStock = $product->stocks()
                ->where('branch_id', $branchId)
                ->exists();

            if (!$hasStock) {
                abort(403, 'Produk ini tidak tersedia di cabang Anda.');
            }

            $product->load(['category', 'stocks' => function ($q) use ($branchId) {
                $q->where('branch_id', $branchId)->with('branch');
            }]);
        } else {
            $product->load('category', 'stocks.branch');
        }

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'sku'         => 'required|string|unique:products,sku,' . $product->id,
            'category_id' => 'nullable|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'cost_price'  => 'required|numeric|min:0',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produk diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk dihapus.');
    }
}