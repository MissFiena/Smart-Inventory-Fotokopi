<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\WasteLog;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Define clean reporting boundaries, fallback defaults to current month
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to   = $request->get('to',   now()->toDateString());

        // 1. Fetch structural chronological ledger trail records
        $transactions = Transaction::with(['product', 'user'])
            ->whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->latest()
            ->paginate(20);

        // 2. Top 5 most active ingredient extractions
        // Note: Filters by standard usage logs to calculate exact café floor utilization
        $topProducts = Product::with(['batches']) // Eager load multi-batch states cleanly
            ->withSum([
                'transactions as total_out' => fn($q) => $q
                    ->where('type', 'check_out')
                    ->whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ], 'quantity')
            ->orderByDesc('total_out')
            ->take(5)
            ->get();

        // 3. Loss / Spillage Analysis Records
        $wasteSummary = WasteLog::with(['product', 'user'])
            ->whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->latest()
            ->get();

        // 4. Calculate Summary Analytical Cards for your templates
        $totalCheckIns  = Transaction::where('type', 'check_in')->whereBetween('created_at', [$from, $to . ' 23:59:59'])->sum('quantity');
        $totalCheckOuts = Transaction::where('type', 'check_out')->whereBetween('created_at', [$from, $to . ' 23:59:59'])->sum('quantity');
        $totalWaste     = $wasteSummary->sum('quantity');

        return view('reports.index', compact(
            'transactions', 
            'topProducts', 
            'wasteSummary', 
            'from', 
            'to',
            'totalCheckIns',
            'totalCheckOuts',
            'totalWaste'
        ));
    }
}