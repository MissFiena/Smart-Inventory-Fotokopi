<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockBatch;
use App\Models\Transaction; // Replace with your actual transaction model name if different
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Fetch today's transaction records 
        $todaysTransactions = Transaction::with('product')
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Automated Multi-Batch Low Stock Alert Engine
        // Pull active items and filter via our custom model accessor logic
        $lowStockProducts = Product::where('is_active', true)
            ->get()
            ->filter(function ($product) {
                return $product->stock <= $product->min_stock;
            })
            ->take(6);

        // 3. Dynamic Shelf Expiry Horizon Monitor (Tracking batches, not master products)
        $expiringItems = StockBatch::with('product')
            ->where('remaining_quantity', '>', 0)
            ->whereBetween('expiry_date', [today(), today()->addDays(7)])
            ->orderBy('expiry_date', 'asc')
            ->get();

        // 4. Calculate Operational Performance Metrics
        $allActiveProducts = Product::where('is_active', true)->get();
        
        $stats = [
            'total_products'   => $allActiveProducts->count(),
            'low_stock_count'  => $allActiveProducts->filter(fn($p) => $p->stock <= $p->min_stock)->count(),
            'today_checkins'   => $todaysTransactions->where('type', 'check_in')->count(),
            'expiring_soon'    => $expiringItems->count(),
        ];

        // Ensure you are pulling the 'product' relationship
$expiringSoon = \App\Models\StockBatch::with('product')
    ->where('expiry_date', '>=', now())
    ->where('expiry_date', '<=', now()->addDays(7))
    ->orderBy('expiry_date', 'asc')
    ->get();

        return view('dashboard', compact('todaysTransactions', 'lowStockProducts', 'expiringItems', 'stats', 'expiringSoon'));
    }
}