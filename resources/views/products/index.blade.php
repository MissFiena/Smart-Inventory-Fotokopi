@extends('layouts.app')
@section('title', 'Inventory Ledger')
@section('page_title', 'Inventory Ledger')

@section('content')
<div class="py-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                    📥 <span>Inventory</span>
                </h1>
                <p class="text-sm text-gray-500 mt-1">Live overview of product stock volumes and batch distributions.</p>
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

        {{-- 🔍 SERVER-SIDE CONTROL UTILITIES --}}
        <div class="mb-6 space-y-4">
            <form action="{{ route('products.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                
                <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 Search items by name..."
                       class="w-full sm:max-w-md px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition duration-150">
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-xs font-bold uppercase">Search</button>
            </form>

            @php $main = explode('-', request('category', ''))[0]; @endphp

            <div class="flex flex-wrap items-center gap-1.5 text-xs font-bold uppercase tracking-wider">
                <span class="text-gray-400 mr-2 normal-case text-xs font-medium">Category:</span>
                <a href="{{ route('products.index') }}" class="px-3 py-1.5 rounded-md {{ !request('category') ? 'bg-teal-600 text-white' : 'bg-white text-gray-600 border border-gray-200' }}">All</a>
                <a href="{{ route('products.index', ['category' => 'barista']) }}" class="px-3 py-1.5 rounded-md {{ $main == 'barista' ? 'bg-teal-600 text-white' : 'bg-white text-gray-600 border border-gray-200' }}">☕ Barista</a>
                <a href="{{ route('products.index', ['category' => 'kitchen']) }}" class="px-3 py-1.5 rounded-md {{ $main == 'kitchen' ? 'bg-teal-600 text-white' : 'bg-white text-gray-600 border border-gray-200' }}">🍳 Kitchen</a>
            </div>

            @if($main == 'barista' || $main == 'kitchen')
                <div class="flex flex-wrap items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider pl-8 border-l-2 border-gray-200 mt-2">
                    @php 
                        $subs = ($main == 'barista') 
                            ? ['packaging' => 'Packaging', 'syrups' => 'Syrups & Purees', 'coffee' => 'Coffee, Tea & Powders', 'dairy' => 'Dairy & Drinks', 'snacks' => 'Snacks & Add-ons'] 
                            : ['dry' => 'Dry Ingredients & Seasonings', 'sauces' => 'Sauces & Condiments', 'carbs' => 'Carbs & Staples', 'protein' => 'Frozen Protein & Processed', 'fresh' => 'Fresh Produce', 'packaging' => 'Kitchen Packaging'];
                    @endphp
                    @foreach($subs as $slug => $label)
                        <a href="{{ route('products.index', ['category' => $main.'-'.$slug]) }}" 
                           class="px-2 py-1 rounded {{ request('category') == $main.'-'.$slug ? 'bg-teal-100 text-teal-800' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                           {{ $label }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- 📊 Main Ledger Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-150 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50/70 border-b border-gray-150">
                        <tr class="text-gray-500 text-[11px] font-bold uppercase tracking-wider">
                            <th class="px-6 py-4">Product Name</th> <th class="px-6 py-4">Category</th>
                            <th class="px-6 py-4">Current Stock</th>
                            <th class="px-6 py-4">Min Stock Alert</th>
                            <th class="px-6 py-4">Earliest Batch Expiry</th>
                            <th class="px-6 py-4">System Status</th>
                            @if(auth()->user()->isAdmin()) <th class="px-6 py-4 text-right">Actions</th> @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-teal-50/10 transition-colors">
                            <td class="px-6 py-4"><span class="font-bold text-gray-900 text-base">{{ $product->name }}</span></td>
                            <td class="px-6 py-4">
                                <span class="text-[9px] font-black text-teal-600 uppercase tracking-widest block">{{ explode('-', $product->category)[0] }}</span>
                                <span class="text-xs font-bold text-gray-800">{{ ucwords(str_replace('-', ' ', $product->category)) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-lg font-black {{ $product->isLowStock() ? 'text-rose-600' : 'text-slate-800' }}">{{ $product->stock }}</span>
                                <span class="text-gray-400 text-xs font-medium uppercase">{{ $product->unit }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-semibold">{{ $product->min_stock }} {{ $product->unit }}</td>
                            <td class="px-6 py-4">
                                @php 
                                    $nearest = $product->batches()
                                        ->where('remaining_quantity', '>', 0)
                                        ->orderByRaw('expiry_date IS NULL ASC')
                                        ->orderBy('expiry_date', 'asc')
                                        ->first(); 
                                @endphp

                                @if($nearest && $nearest->expiry_date)
                                    <span class="text-xs font-bold text-gray-700">📅 {{ \Carbon\Carbon::parse($nearest->expiry_date)->format('d M Y') }}</span>
                                @elseif($nearest)
                                    <span class="text-xs font-medium text-gray-400 italic">No expiry</span>
                                @else
                                    <span class="text-xs font-medium text-gray-400 italic">No batches active</span>
                                @endif
                            </td>
                            
                            {{-- UPDATED SYSTEM STATUS COLUMN --}}
                            <td class="px-6 py-4">
                                @php
                                    $isExpired = $nearest && $nearest->expiry_date && \Carbon\Carbon::parse($nearest->expiry_date)->isPast();
                                    $isLowStock = $product->stock <= $product->min_stock && $product->stock > 0;
                                    $isOutOfStock = $product->stock <= 0;
                                @endphp

                                <span class="whitespace-nowrap text-[11px] font-black uppercase px-2.5 py-1 rounded-md 
                                    {{ $isExpired ? 'bg-red-100 text-red-800' : 
                                       ($isOutOfStock ? 'bg-gray-100 text-gray-500' : 
                                       ($isLowStock ? 'bg-orange-100 text-orange-700' : 'bg-emerald-50 text-emerald-700')) }}">
                                    
                                    {{ $isExpired ? 'Expired' : 
                                       ($isOutOfStock ? 'Out of Stock' : 
                                       ($isLowStock ? 'Low Stock' : 'Stable')) }}
                                </span>
                            </td>

                            @if(auth()->user()->isAdmin())
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('products.edit', $product) }}" class="text-xs font-bold text-teal-600 hover:text-teal-800 bg-teal-50 px-2.5 py-1.5 rounded transition">Edit</a>
                                        <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Are you sure you want to delete this product?')" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-800 bg-rose-50 px-2.5 py-1.5 rounded transition">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-16 text-center text-gray-400">No products found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection