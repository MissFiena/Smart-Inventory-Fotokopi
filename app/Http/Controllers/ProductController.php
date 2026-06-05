<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Added with('batches') eager loading to optimize database performance,
        // and filtered by is_active so deleted/archived items stay hidden.
        $products = Product::with('batches')
            ->where('is_active', true)
            ->latest()
            ->paginate(15);
            
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        // 🛠️ FIXED: Removed 'current_stock' and 'expiry_date' from validation
        // because master products are initialized at 0 stock. 
        // Actual quantities and dates must go through Stock Check-In.
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'sku'         => 'nullable|string|unique:products,sku',
            'category'    => 'required|string',
            'unit'        => 'required|string',
            'min_stock'   => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        // Automatically ensure new items are set to active operations status
        $data['is_active'] = true;

        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', '☕ Product registered successfully! Proceed to Stock Check-In to log your first arrival batch.');
    }

    public function show(Product $product)
    {
        // Eager load batches and show recent transaction history
        $product->load('batches');
        $transactions = $product->transactions()->with('user')->latest()->take(20)->get();
        
        return view('products.show', compact('product', 'transactions'));
    }

    public function edit(Product $product)
    {
        // Eager load active batches to feed your right-side breakdown UI panels
        $product->load('batches');
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        // 🛠️ FIXED: Removed 'expiry_date' from validation.
        // Product master records should never hold a standalone expiration timestamp.
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string',
            'unit'        => 'required|string',
            'min_stock'   => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', '✅ Product master configurations updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Instead of a hard delete that crashes historical reports data, 
        // we flip the visibility flag or use standard delete if preferred.
        $product->update(['is_active' => false]);
        
        return back()->with('success', 'Product moved to inactive archive.');
    }
}