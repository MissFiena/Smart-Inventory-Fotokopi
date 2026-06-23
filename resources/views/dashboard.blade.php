<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="py-5 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- ☕ Header Region -->
        <div class="mb-4">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                📊 <span>FOTOKOPI Operations Control</span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">Real-time status parameters, decentralized stock entries, and shelf lifecycle alerts.</p>
        </div>

        {{-- ─── Quick Action Cards ─── --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <a href="{{ route('checkin.form') }}" class="bg-white rounded-xl border border-gray-150 p-6 text-center hover:shadow-md transition duration-200 group relative overflow-hidden">
                <div class="w-14 h-14 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-teal-100 transition-colors">
                    <span class="text-2xl font-black">+</span>
                </div>
                <p class="font-black text-gray-900 text-sm uppercase tracking-wide">Stock Check-In</p>
                <p class="text-xs text-gray-400 mt-1">Register incoming supplies & lock batch lifetimes</p>
            </a>

            <a href="{{ route('checkout.form') }}" class="bg-white rounded-xl border border-gray-150 p-6 text-center hover:shadow-md transition duration-200 group relative overflow-hidden">
                <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-amber-100 transition-colors">
                    <span class="text-xl font-black">←</span>
                </div>
                <p class="font-black text-gray-900 text-sm uppercase tracking-wide">Stock Check-Out</p>
                <p class="text-xs text-gray-400 mt-1">Log ingredient extraction</p>
            </a>

            <a href="{{ route('products.index') }}" class="bg-white rounded-xl border border-gray-150 p-6 text-center hover:shadow-md transition duration-200 group relative overflow-hidden">
                <div class="w-14 h-14 bg-sky-50 text-sky-600 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-sky-100 transition-colors">
                    <span class="text-xl font-black">⊞</span>
                </div>
                <p class="font-black text-gray-900 text-sm uppercase tracking-wide">View Inventory</p>
                <p class="text-xs text-gray-400 mt-1">Audit active store ledgers</p>
            </a>
        </div>

        {{-- ─── Stats Metrics Row ─── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl border border-gray-150 px-5 py-4 shadow-sm flex flex-col justify-between">
                <p class="text-[10px] font-black uppercase tracking-wider text-gray-400">Total Products</p>
                <p class="text-3xl font-black text-gray-900 mt-1 tracking-tight">{{ $stats['total_products'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-150 px-5 py-4 shadow-sm flex flex-col justify-between">
                <p class="text-[10px] font-black uppercase tracking-wider text-gray-400">Low Stock Triggers</p>
                <p class="text-3xl font-black text-rose-600 mt-1 tracking-tight">{{ $stats['low_stock_count'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-150 px-5 py-4 shadow-sm flex flex-col justify-between">
                <p class="text-[10px] font-black uppercase tracking-wider text-gray-400">Today Check-In Flows</p>
                <p class="text-3xl font-black text-emerald-600 mt-1 tracking-tight">{{ $stats['today_checkins'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-150 px-5 py-4 shadow-sm flex flex-col justify-between">
                <p class="text-[10px] font-black uppercase tracking-wider text-gray-400">Expiring Soon Queue</p>
                <p class="text-3xl font-black text-amber-500 mt-1 tracking-tight">{{ $stats['expiring_soon'] }}</p>
            </div>
        </div>        

        {{-- ─── MOVED: Expiry Reminder Component Table ─── --}}
        <div class="bg-white rounded-xl border border-gray-150 shadow-sm p-6 overflow-hidden mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-5">
                <div>
                    <h3 class="font-black text-gray-900 text-sm uppercase tracking-wider">Expiry Monitor</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Tracking independent stock batch windows nearing their lifespan thresholds.</p>
                </div>
                <span class="self-start sm:self-center text-[10px] font-black text-amber-700 bg-amber-50 border border-amber-100 uppercase tracking-wider px-3 py-1 rounded-full">
                    ⚠️ 7-Day Threshold
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="text-gray-400 text-[10px] font-black uppercase tracking-wider border-b border-gray-150">
                            <th class="pb-3">Batch Reference Item</th>
                            <th class="pb-3">Category Allocation</th>
                            <th class="pb-3">Remaining Volume</th>
                            <th class="pb-3">Target Expiration</th>
                            <th class="pb-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                    @forelse($expiringItems as $item)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3.5 font-bold text-gray-900">{{ method_exists($item, 'batches') ? $item->name : ($item->product->name ?? 'Unknown Item') }}</td>
                            <td class="py-3.5"><span class="text-xs font-medium bg-gray-100 text-gray-600 px-2 py-0.5 rounded">{{ method_exists($item, 'batches') ? $item->category : ($item->product->category ?? '—') }}</span></td>
                            <td class="py-3.5 font-semibold text-gray-700">{{ method_exists($item, 'batches') ? $item->stock : ($item->remaining_quantity ?? $item->quantity ?? '0') }}<span class="text-xs text-gray-400 font-normal uppercase ml-0.5">{{ method_exists($item, 'batches') ? $item->unit : ($item->product->unit ?? '') }}</span></td>
                            <td class="py-3.5 font-mono text-xs text-gray-600">{{ \Carbon\Carbon::parse($item->expiry_date)->format('d M Y') }}</td>
                            <td class="py-3.5 text-right">
                                @php $daysLeft = (int) now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($item->expiry_date)->startOfDay(), false); @endphp
                                @if($daysLeft <= 0) <span class="inline-block text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded bg-rose-100 text-rose-700 border border-rose-200 animate-pulse">Critical (Expired)</span>
                                @elseif($daysLeft === 1) <span class="inline-block text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded bg-rose-50 text-rose-600 border border-rose-100">Tomorrow</span>
                                @elseif($daysLeft <= 3) <span class="inline-block text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded bg-amber-100 text-amber-700 border border-amber-200">{{ $daysLeft }} Days Left</span>
                                @else <span class="inline-block text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded bg-yellow-50 text-yellow-700 border border-yellow-200">{{ $daysLeft }} Days Left</span> @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-12 text-center text-gray-400 text-xs">✨ Safe Zone: No batch records are flagged for expiration inside the current 7-day tracking window.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ─── Middle Row: Transactions + Low Stock ─── --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-8">
            <div class="lg:col-span-3 bg-white rounded-xl border border-gray-150 shadow-sm p-6 overflow-hidden">
                <div class="mb-4">
                    <h3 class="font-black text-gray-900 text-sm uppercase tracking-wider">Today's Store Transactions</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Live operational throughput logs for the current shift.</p>
                </div>
                <div class="overflow-y-auto max-h-[400px] pr-2">
                    <table class="w-full text-sm text-left">
                        <thead class="sticky top-0 bg-white z-10">
                            <tr class="text-gray-400 text-[10px] font-black uppercase tracking-wider border-b border-gray-150">
                                <th class="pb-3 pt-1">Item Descriptor</th>
                                <th class="pb-3 pt-1">Action Type</th>
                                <th class="pb-3 pt-1">Adjustment</th>
                                <th class="pb-3 pt-1 text-right">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($todaysTransactions as $t)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="py-3 font-bold text-gray-900">{{ $t->product->name }}</td>
                                    <td class="py-3">
                                        @if($t->type === 'check_in') <span class="inline-block text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-100">Check-In</span>
                                        @elseif($t->type === 'check_out') <span class="inline-block text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-amber-50 text-amber-700 border border-amber-100">Check-Out</span>
                                        @else <span class="inline-block text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-rose-50 text-rose-700 border border-rose-100">{{ $t->type }}</span> @endif
                                    </td>
                                    <td class="py-3 font-black text-sm {{ $t->type === 'check_in' ? 'text-emerald-600' : 'text-rose-600' }}">{{ $t->type === 'check_in' ? '+' : '-' }}{{ $t->quantity }}</td>
                                    <td class="py-3 text-gray-400 text-xs font-medium text-right">{{ $t->created_at->format('g:i A') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-10 text-center text-gray-400 text-xs italic">No activity recorded during this transaction cycle.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white rounded-xl border border-gray-150 shadow-sm p-6">
                <div class="mb-4">
                    <h3 class="font-black text-gray-900 text-sm uppercase tracking-wider">Low Stock Alerts</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Items whose combined batch stocks fall under target thresholds.</p>
                </div>
                <div class="space-y-3 overflow-y-auto max-h-[400px] pr-1">
                @forelse($lowStockProducts as $p)
                    <div class="bg-rose-50/60 border border-rose-100 rounded-xl px-4 py-3 flex items-center justify-between shadow-inner">
                        <div>
                            <p class="text-sm font-black text-gray-900">{{ $p->name }}</p>
                            <p class="text-[10px] text-gray-400 font-medium uppercase mt-0.5">Threshold: Minimum {{ $p->min_stock }} {{ $p->unit }}</p>
                        </div>
                        <span class="text-xs font-black bg-rose-100 text-rose-700 px-2 py-1 rounded-md">{{ $p->stock }} {{ $p->unit }} left</span>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <span class="text-3xl block mb-2">🎉</span>
                        <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wide">Ledgers Clear</h4>
                        <p class="text-[11px] text-gray-400 mt-0.5">All ingredients are perfectly provisioned.</p>
                    </div>
                @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection