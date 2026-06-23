<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('batches')
            ->where('is_active', true);

        // Apply category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $cat = $request->category;
            // If the category doesn't have a dash (e.g., 'barista'), 
            // it matches anything starting with 'barista-'
            if (strpos($cat, '-') === false) {
                $query->where('category', 'like', $cat . '-%');
            } else {
                // Exact match for subcategories (e.g., 'barista-syrups')
                $query->where('category', $cat);
            }
        }

        // Apply search filter (Removed SKU)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        $products = $query->latest()->paginate(15);
    
        // Keep search/category parameters in pagination links
        $products->appends($request->all());

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        // Removed SKU from validation
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string',
            'unit'        => 'required|string',
            'min_stock'   => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $data['is_active'] = true;

        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', '☕ Product registered successfully!');
    }

    public function show(Product $product)
    {
        $product->load('batches');
        $transactions = $product->transactions()->with('user')->latest()->take(20)->get();
        
        return view('products.show', compact('product', 'transactions'));
    }

    public function edit(Product $product)
    {
        $product->load('batches');
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        // Removed SKU from validation
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
        $product->update(['is_active' => false]);
        
        return back()->with('success', 'Product moved to inactive archive.');
    }

    public function showBatches($id)
    {
        $product = Product::with(['batches' => function($query) {
            $query->where('remaining_volume', '>', 0)
                  ->orderBy('expiry_date', 'asc');
        }])->findOrFail($id);

        return view('products.batches', compact('product'));
    }
}