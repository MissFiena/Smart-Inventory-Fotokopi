<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\StockBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // ── CHECK-IN ──────────────────────────────
    public function checkInForm()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('transactions.checkin', compact('products')); // Pointing to your layout-aligned view folder
    }

    public function checkIn(Request $request)
    {
        // Added dynamic expiry_date validation requirement 
        $request->validate([
            'product_id'  => 'required|exists:products,id',
            'quantity'    => 'required|integer|min:1',
            'expiry_date' => 'required|date|after_or_equal:today',
            'notes'       => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Run both creations safely under a database transaction wrapper
        DB::transaction(function () use ($request, $product) {
            // 📦 1. Create the isolated stock batch row
            StockBatch::create([
                'product_id'         => $product->id,
                'initial_quantity'   => $request->quantity,
                'remaining_quantity' => $request->quantity, // Initially same as initial
                'expiry_date'        => $request->expiry_date,
                'notes'              => $request->notes,
            ]);

            // 📑 2. Record the global system history log row
            Transaction::create([
                'product_id' => $product->id,
                'user_id'    => auth()->id(),
                'type'       => 'check_in',
                'quantity'   => $request->quantity,
                'notes'      => $request->notes,
            ]);
        });

        return redirect()->route('dashboard')
            ->with('success', '✅ Check-In successful: +' . $request->quantity . ' ' . $product->unit . ' of ' . $product->name . ' queued into storage.');
    }

    // ── CHECK-OUT ─────────────────────────────
    public function checkOutForm()
    {
        // Filter out items that do not have active batch volumes available
        $products = Product::where('is_active', true)
            ->whereHas('batches', function ($query) {
                $query->where('remaining_quantity', '>', 0)
                      ->where('expiry_date', '>=', now()->toDateString());
            })
            ->orderBy('name')
            ->get();

        return view('transactions.checkout', compact('products')); // Pointing to your layout-aligned view folder
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'type'       => 'required|in:out,waste', // Dynamically support allocation styles we introduced in forms
            'notes'      => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);
        $requestedQty = $request->quantity;

        // Verify global calculated capacity across all separate batches first
        if ($requestedQty > $product->stock) {
            return back()->withErrors([
                'quantity' => 'Insufficient inventory! Only ' . $product->stock . ' total ' . $product->unit . ' available across unexpired active batches.'
            ])->withInput();
        }

        // ⚡ ENGINE: FIRST-IN, FIRST-OUT (FIFO) BATCH REDUCTION PIPELINE
        DB::transaction(function () use ($product, $requestedQty, $request) {
            $needed = $requestedQty;

            // Gather active batches sorting by earliest expiry date first (FIFO priority)
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
                    // This batch can completely satisfy the remaining balance demand
                    $batch->decrement('remaining_quantity', $needed);
                    $needed = 0;
                } else {
                    // Drain this batch completely to 0 and advance the remainder deficit to the next batch
                    $needed -= $batch->remaining_quantity;
                    $batch->update(['remaining_quantity' => 0]);
                }
            }

            // Record transaction trail entry log with specific type mapping (out vs waste)
            Transaction::create([
                'product_id' => $product->id,
                'user_id'    => auth()->id(),
                'type'       => $request->type === 'waste' ? 'waste' : 'check_out',
                'quantity'   => $requestedQty,
                'notes'      => $request->notes,
            ]);
        });

        $actionWord = $request->type === 'waste' ? '🔴 Waste Logged' : '✅ Check-Out';
        return redirect()->route('dashboard')
            ->with('success', $actionWord . ': -' . $requestedQty . ' ' . $product->unit . ' from ' . $product->name . ' (FIFO depletion sequence verified).');
    }

    public function history()
    {
        $transactions = Transaction::with(['product', 'user'])->latest()->paginate(20);
        return view('transactions.history', compact('transactions'));
    }
}