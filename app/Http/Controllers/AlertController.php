<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AlertController extends Controller
{
public function index()
{
    // 1. Low Stocks
    $lowStocks = Product::withSum(['batches as total_stock' => function ($query) {
        $query->where('remaining_quantity', '>', 0)
              ->where(function($q) {
                  $q->where('expiry_date', '>=', now()->toDateString())
                    ->orWhereNull('expiry_date');
              });
    }], 'remaining_quantity')
    ->havingRaw('total_stock > 0 AND total_stock <= min_stock')
    ->get();

    // 2. Out of Stocks
    $outStocks = Product::withSum(['batches as total_stock' => function ($query) {
        $query->where('remaining_quantity', '>', 0);
    }], 'remaining_quantity')
    ->havingRaw('total_stock IS NULL OR total_stock = 0')
    ->get();

    // 3. Expiring Soon: Filter by category to ensure we only target ingredients
    // 3. Expiring Soon
$expiringSoon = \App\Models\StockBatch::with('product')
        ->whereHas('product', function($query) {
            $query->whereNotIn('category', ['barista-packaging', 'kitchen-packaging']);
        })
        ->where('remaining_quantity', '>', 0)
        ->whereBetween('expiry_date', [now()->toDateString(), now()->addDays(7)->toDateString()])
        ->orderBy('expiry_date', 'asc')
        ->get();

// 4. Already Expired
$expired = \App\Models\StockBatch::with('product') // Added this
    ->whereHas('product', function($query) {
        $query->whereNotIn('category', ['barista-packaging', 'kitchen-packaging']);
    })
    ->where('remaining_quantity', '>', 0)
    ->where('expiry_date', '<', now()->toDateString())
    ->get();

    return view('alerts.index', compact('lowStocks', 'outStocks', 'expiringSoon'));
}
}