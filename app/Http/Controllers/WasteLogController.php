<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\WasteLog;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WasteLogController extends Controller
{
    public function index()
    {
        $wasteLogs = WasteLog::with(['product', 'user'])->latest()->paginate(15);
        
        // Only provide items to the dropdown selection that have available multi-batch stock
        $products = Product::where('is_active', true)
            ->whereHas('batches', function ($query) {
                $query->where('remaining_quantity', '>', 0)
                      ->where('expiry_date', '>=', now()->toDateString());
            })
            ->orderBy('name')
            ->get();
            
        return view('waste.index', compact('wasteLogs', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'reason'     => 'required|in:expired,damaged,lost,other',
            'quantity'   => 'required|integer|min:1',
            'notes'      => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $requestedQty = $request->quantity;

        // 🛡️ Guard Boundary: Validate total accumulated batch quantities
        if ($requestedQty > $product->stock) {
            return back()->withErrors([
                'quantity' => 'Insufficient stock balance to process waste logging! Only ' . $product->stock . ' ' . $product->unit . ' remains active.'
            ])->withInput();
        }

        // ⚡ MULTI-TABLE ACID TRANSACTION ENCAPSULATION
        DB::transaction(function () use ($product, $requestedQty, $request) {
            $needed = $requestedQty;

            // 🎯 FIFO ENGINE: Fetch active inventory lines starting with the closest expiration date
            $activeBatches = $product->batches()
                ->where('remaining_quantity', '>', 0)
                ->where('expiry_date', '>=', now()->toDateString())
                ->orderBy('expiry_date', 'asc')
                ->get();

            foreach ($activeBatches as $batch) {
                if ($needed <= 0) {
                    break;
                }

                if ($batch->remaining_quantity >= $needed) {
                    // Drains balance completely from this batch record line
                    $batch->decrement('remaining_quantity', $needed);
                    $needed = 0;
                } else {
                    // Subdues current line record and rolls structural balance forward
                    $needed -= $batch->remaining_quantity;
                    $batch->update(['remaining_quantity' => 0]);
                }
            }

            // 📝 1. Populate the localized Café Waste ledger entry 
            WasteLog::create([
                'product_id' => $product->id,
                'user_id'    => auth()->id(),
                'reason'     => $request->reason,
                'quantity'   => $requestedQty,
                'notes'      => $request->notes,
            ]);

            // 📑 2. Synchronize with the global operations Transactions monitor
            Transaction::create([
                'product_id' => $product->id,
                'user_id'    => auth()->id(),
                'type'       => 'waste',
                'quantity'   => $requestedQty,
                'notes'      => 'Waste Log [' . ucfirst($request->reason) . ']: ' . ($request->notes ?? 'No additional details provided.'),
            ]);
        });

        return back()->with('success', '🔴 Operational Loss Recorded: -' . $requestedQty . ' ' . $product->unit . '');
    }
}