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
        return view('transactions.checkin', compact('products'));
    }

    public function checkIn(Request $request)
    {
        // 1. Updated validation: expiry_date is now nullable
        $request->validate([
            'product_id'  => 'required|exists:products,id',
            'quantity'    => 'required|integer|min:1',
            'expiry_date' => 'nullable|date|after_or_equal:today', 
            'notes'       => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);

        DB::transaction(function () use ($request, $product) {
            StockBatch::create([
                'product_id'         => $product->id,
                'initial_quantity'   => $request->quantity,
                'remaining_quantity' => $request->quantity,
                'expiry_date'        => $request->expiry_date, // Can now be NULL
                'notes'              => $request->notes,
            ]);

            Transaction::create([
                'product_id' => $product->id,
                'user_id'    => auth()->id(),
                'type'       => 'check_in',
                'quantity'   => $request->quantity,
                'notes'      => $request->notes,
            ]);
        });

        return redirect()->route('dashboard')
            ->with('success', '✅ Check-In successful: +' . $request->quantity . ' ' . $product->unit . ' of ' . $product->name);
    }

    // ── CHECK-OUT ─────────────────────────────
    public function checkOutForm()
    {
        // 2. Updated filter: include items even if expiry_date is NULL
        $products = Product::where('is_active', true)
            ->whereHas('batches', function ($query) {
                $query->where('remaining_quantity', '>', 0)
                      ->where(function($q) {
                          $q->where('expiry_date', '>=', now()->toDateString())
                            ->orWhereNull('expiry_date');
                      });
            })
            ->orderBy('name')
            ->get();

        return view('transactions.checkout', compact('products'));
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'type'       => 'required|in:out,waste',
            'notes'      => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);
        $requestedQty = $request->quantity;

        if ($requestedQty > $product->stock) {
            return back()->withErrors(['quantity' => 'Insufficient inventory!'])->withInput();
        }

        // 3. Updated FIFO Engine: handle NULL expiry dates safely
        DB::transaction(function () use ($product, $requestedQty, $request) {
            $needed = $requestedQty;

            $activeBatches = $product->batches()
                ->where('remaining_quantity', '>', 0)
                ->where(function($q) {
                    $q->where('expiry_date', '>=', now()->toDateString())
                      ->orWhereNull('expiry_date');
                })
                // NULLs usually appear at the end or start; sorting by expiry_date handles them
                ->orderByRaw('expiry_date IS NULL ASC') 
                ->orderBy('expiry_date', 'asc')
                ->get();

            foreach ($activeBatches as $batch) {
                if ($needed <= 0) break;

                if ($batch->remaining_quantity >= $needed) {
                    $batch->decrement('remaining_quantity', $needed);
                    $needed = 0;
                } else {
                    $needed -= $batch->remaining_quantity;
                    $batch->update(['remaining_quantity' => 0]);
                }
            }

            Transaction::create([
                'product_id' => $product->id,
                'user_id'    => auth()->id(),
                'type'       => $request->type === 'waste' ? 'waste' : 'check_out',
                'quantity'   => $requestedQty,
                'notes'      => $request->notes,
            ]);
        });

        return redirect()->route('dashboard')->with('success', 'Transaction complete.');
    }

    public function history()
    {
        $transactions = Transaction::with(['product', 'user'])->latest()->paginate(20);
        return view('transactions.history', compact('transactions'));
    }
}