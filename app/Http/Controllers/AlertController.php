<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index()
    {
        // 1. Fetch products where total active batch stock is greater than 0 BUT less than or equal to min_stock
        $lowStocks = Product::withSum(['batches as total_stock' => function ($query) {
            $query->where('remaining_quantity', '>', 0)
                  ->where('expiry_date', '>=', now()->toDateString());
        }], 'remaining_quantity')
        ->havingRaw('total_stock > 0 AND total_stock <= min_stock')
        ->get();

        // 2. Fetch products where total active batch stock is exactly 0 (or they have no active batches at all)
        $outStocks = Product::withSum(['batches as total_stock' => function ($query) {
            $query->where('remaining_quantity', '>', 0)
                  ->where('expiry_date', '>=', now()->toDateString());
        }], 'remaining_quantity')
        ->havingRaw('total_stock IS NULL OR total_stock = 0')
        ->get();

        return view('alerts.index', compact('lowStocks', 'outStocks'));
    }
}