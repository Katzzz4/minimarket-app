<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
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
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
        ]);

        $product = Product::create($validated);

        // inisialisasi stok 0 di semua cabang
        Branch::all()->each(function ($branch) use ($product) {
            ProductStock::create([
                'product_id' => $product->id,
                'branch_id' => $branch->id,
                'quantity' => 0,
            ]);
        });

        return redirect()->route('products.index')->with('success', 'Produk ditambahkan.');
    }

    public function show(Product $product)
    {
        $product->load('category', 'stocks.branch');
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
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produk diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete(); // product_stocks ikut terhapus via cascadeOnDelete
        return redirect()->route('products.index')->with('success', 'Produk dihapus.');
    }
}