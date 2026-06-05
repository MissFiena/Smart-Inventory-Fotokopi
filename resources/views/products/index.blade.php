<!-- resources/views/products/index.blade.php -->
@extends('layouts.app')
@section('title', 'Inventory Ledger')

{{-- 🛠️ CONNECTS DIRECTLY TO THE NEW LAYOUT TITLE RULES --}}
@section('page_title', 'Inventory Ledger')

@section('content')
<div class="py-4 bg-gray-50 min-h-screen" x-data="inventoryFilter()">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                    📥 <span>Inventory</span>
                </h1>
                <p class="text-sm text-gray-500 mt-1">Live overview of product stock volumes, low threshold alerts, and batch distributions.</p>
            </div>

            @if(auth()->user()->isAdmin())
                <div class="shrink-0">
                    <a href="{{ route('products.create') }}"
                       class="inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-teal-600 to-emerald-600 text-white text-xs font-black uppercase tracking-wider rounded-lg shadow-sm hover:from-teal-700 hover:to-emerald-700 transition duration-150">
                        ➕ Add New Product
                    </a>
                </div>
            @endif
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl text-emerald-800 shadow-sm flex items-center gap-2 text-sm">
                <span>🟢</span> <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- 🔍 REAL-TIME CONTROL UTILITIES BAR --}}
        <div class="mb-6 space-y-4">
            <!-- Search Input Field -->
            <div class="relative max-w-md shadow-sm rounded-lg">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 text-sm">
                    🔍
                </div>
                <input type="text" id="inventorySearch" oninput="filterInventoryTable()" placeholder="     Search items by name or SKU"
                       class="w-full pl-9 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition duration-150">
            </div>

            <!-- Café Category Filters Bar -->
            <div class="flex flex-wrap items-center gap-1.5 text-xs font-bold uppercase tracking-wider">
                <span class="text-gray-400 mr-1.5 normal-case text-xs font-medium">Quick Categorization:</span>
                <button onclick="filterCategory('all')" class="cat-btn px-3 py-1.5 rounded-md bg-teal-600 text-white transition shadow-sm" data-cat="all">All Items</button>
                <button onclick="filterCategory('beans')" class="cat-btn px-3 py-1.5 rounded-md bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 transition" data-cat="beans">☕ Beans</button>
                <button onclick="filterCategory('syrups')" class="cat-btn px-3 py-1.5 rounded-md bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 transition" data-cat="syrups">🍯 Syrups</button>
                <button onclick="filterCategory('powder')" class="cat-btn px-3 py-1.5 rounded-md bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 transition" data-cat="powder">🍫 Powder</button>
                <button onclick="filterCategory('dairy')" class="cat-btn px-3 py-1.5 rounded-md bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 transition" data-cat="dairy">🥛 Dairy</button>
                <button onclick="filterCategory('packaging')" class="cat-btn px-3 py-1.5 rounded-md bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 transition" data-cat="packaging">📦 Packaging</button>
            </div>
        </div>

        {{-- 📊 Main Ledger Table Component Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-150 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left" id="inventoryTable">
                    <thead class="bg-gray-50/70 border-b border-gray-150">
                        <tr class="text-gray-500 text-[11px] font-bold uppercase tracking-wider">
                            <th class="px-6 py-4">Product Details</th>
                            <th class="px-6 py-4">Category</th>
                            <th class="px-6 py-4">Current Stock</th>
                            <th class="px-6 py-4">Min Stock Alert</th>
                            <th class="px-6 py-4">Earliest Batch Expiry</th>
                            <th class="px-6 py-4">System Status</th>
                            @if(auth()->user()->isAdmin())
                                <th class="px-6 py-4 text-right">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    {{-- 🔤 ALPHABETICALLY SORTED VIA PHP COLLECTION SORT --}}
                    @forelse($products->sortBy('name', SORT_NATURAL|SORT_FLAG_CASE) as $product)
                        <tr class="hover:bg-teal-50/10 transition-colors inventory-row" 
                            data-name="{{ strtolower($product->name) }}" 
                            data-sku="{{ strtolower($product->sku ?? '') }}"
                            data-category="{{ strtolower($product->category ?? '') }}">
                            
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-900 block text-base row-item-name">{{ $product->name }}</span>
                                @if($product->sku)
                                    <span class="inline-block mt-0.5 text-[10px] font-mono tracking-wider text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">{{ $product->sku }}</span>
                                @else
                                    <span class="text-[10px] italic text-gray-400">No SKU registered</span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-gray-100 text-gray-700 uppercase tracking-wider">
                                    {{ $product->category ?? 'General' }}
                                </span>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-baseline gap-1">
                                    <span class="text-lg font-black {{ $product->isLowStock() ? 'text-rose-600' : 'text-slate-800' }}">
                                        {{ $product->stock }}
                                    </span>
                                    <span class="text-gray-400 text-xs font-medium uppercase">{{ $product->unit }}</span>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600 font-semibold">
                                {{ $product->min_stock }} <span class="text-[11px] text-gray-400 font-normal uppercase">{{ $product->unit }}</span>
                            </td>
                            
                            <td class="px-6 py-4">
                                @php
                                    $nearestBatch = $product->batches()->where('remaining_quantity', '>', 0)->orderBy('expiry_date', 'asc')->first();
                                    $activeBatchesCount = $product->batches()->where('remaining_quantity', '>', 0)->count();
                                @endphp

                                @if($nearestBatch)
                                    <div class="space-y-0.5">
                                        <span class="text-xs font-bold text-gray-700 block">
                                            📅 {{ \Carbon\Carbon::parse($nearestBatch->expiry_date)->format('d M Y') }}
                                        </span>
                                        <span class="text-[10px] text-teal-600 font-medium block">
                                            📦 Across {{ $activeBatchesCount }} active {{ Str::plural('batch', $activeBatchesCount) }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-xs font-medium text-gray-400 italic">No batches active</span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $daysToExpiry = null;
                                    if ($nearestBatch) {
                                        $daysToExpiry = now()->diffInDays(\Carbon\Carbon::parse($nearestBatch->expiry_date), false);
                                    }
                                @endphp

                                @if($nearestBatch && $daysToExpiry < 0)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-black uppercase tracking-wider px-2.5 py-1 rounded-md bg-rose-100 text-rose-700 border border-rose-200">
                                        🚨 Expired
                                    </span>
                                @elseif($nearestBatch && $daysToExpiry >= 0 && $daysToExpiry <= 30)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-black uppercase tracking-wider px-2.5 py-1 rounded-md bg-amber-100 text-amber-700 border border-amber-200">
                                        ⏳ Near Expiry
                                    </span>
                                @elseif($product->stock <= 0)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-black uppercase tracking-wider px-2.5 py-1 rounded-md bg-gray-100 text-gray-500 border border-gray-200">
                                        📦 Out of Stock
                                    </span>
                                @elseif($product->isLowStock())
                                    <span class="inline-flex items-center gap-1 text-[11px] font-black uppercase tracking-wider px-2.5 py-1 rounded-md bg-red-50 text-red-700 border border-red-100">
                                        ⚠️ Low Stock
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] font-black uppercase tracking-wider px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        ✅ Stable
                                    </span>
                                @endif
                            </td>
                            
                            @if(auth()->user()->isAdmin())
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('products.edit', $product) }}"
                                           class="text-xs font-bold text-teal-600 hover:text-teal-800 bg-teal-50 px-2.5 py-1.5 rounded transition">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('products.destroy', $product) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this master product? This wipes out its full batch history!')"
                                              class="inline">
                                            @csrf 
                                            @method('DELETE')
                                            <button class="text-xs font-bold text-rose-600 hover:text-rose-800 bg-rose-50 px-2.5 py-1.5 rounded transition">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="{{ auth()->user()->isAdmin() ? 7 : 6 }}" class="px-6 py-16 text-center">
                                <div class="text-3xl mb-2">📦</div>
                                <h3 class="text-sm font-bold text-gray-700">The product ledger repository is empty</h3>
                                <p class="text-xs text-gray-400 mt-0.5 max-w-xs mx-auto">No raw ingredients or items are registered in FOTOKOPI yet.</p>
                            </td>
                        </tr>
                    @endforelse
                    
                    <!-- Search Not Found Row Fallback -->
                    <tr id="noResultsRow" class="hidden">
                        <td colspan="{{ auth()->user()->isAdmin() ? 7 : 6 }}" class="px-6 py-12 text-center text-gray-400 italic text-sm">
                            No matching products found in this category...
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @if($products->hasPages())
            <div class="mt-6 bg-white px-4 py-3 rounded-lg border border-gray-150 shadow-sm">
                {{ $products->links() }}
            </div>
        @endif

    </div>
</div>

{{-- ⚡ COMPACT CLIENT-SIDE SEARCH ENGINE & FILTER CONTROLLER --}}
<script>
    let activeCategory = 'all';

    function filterCategory(cat) {
        activeCategory = cat.toLowerCase();
        
        // Update Filter Pill Buttons Styling
        document.querySelectorAll('.cat-btn').forEach(btn => {
            if(btn.getAttribute('data-cat') === cat) {
                btn.className = "cat-btn px-3 py-1.5 rounded-md bg-teal-600 text-white transition shadow-sm";
            } else {
                btn.className = "cat-btn px-3 py-1.5 rounded-md bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 transition";
            }
        });

        filterInventoryTable();
    }

    function filterInventoryTable() {
        const searchInput = document.getElementById('inventorySearch').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.inventory-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            const sku = row.getAttribute('data-sku');
            const category = row.getAttribute('data-category');

            // Category matching rule
            const matchesCategory = (activeCategory === 'all' || category.includes(activeCategory));
            
            // Search string matching rule
            const matchesSearch = (!searchInput || name.includes(searchInput) || sku.includes(searchInput));

            if (matchesCategory && matchesSearch) {
                row.classList.remove('hidden');
                visibleCount++;
            } else {
                row.classList.add('hidden');
            }
        });

        // Toggle No Results feedback warning line items
        const noResults = document.getElementById('noResultsRow');
        if (noResults) {
            if (visibleCount === 0 && rows.length > 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }
    }
</script>
@endsection