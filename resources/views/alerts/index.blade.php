@extends('layouts.app')
@section('page_title', 'System Alerts')

@section('content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                🔔 <span>System Alerts</span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">📊 Today's inventory snapshot and risk assessments.</p>
        </div>

        {{-- 3-Column Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            {{-- Column 1: Critical Stock --}}
            <div class="bg-white rounded-xl shadow-sm border-t-4 border-red-500 p-5">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4">🔴 Critical Stock</h3>
                @forelse($outStocks as $product)
                    <div class="p-3 bg-red-50 border border-red-100 rounded-lg mb-3">
                        <h4 class="text-sm font-bold text-red-900">{{ $product->name }}</h4>
                        <p class="text-xs text-red-700 mt-0.5">Completely out of stock!</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 italic">No critical shortages. 🌱</p>
                @endforelse
            </div>

            {{-- Column 2: Low Stock --}}
            <div class="bg-white rounded-xl shadow-sm border-t-4 border-orange-500 p-5">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4">🟡 Low Stock</h3>
                @forelse($lowStocks as $product)
                    <div class="p-3 bg-orange-50 border border-orange-100 rounded-lg mb-3">
                        <h4 class="text-sm font-bold text-orange-900">{{ $product->name }}</h4>
                        <p class="text-xs text-orange-700 mt-0.5">Only {{ $product->stock }} left.</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 italic">No low stock items. ☕</p>
                @endforelse
            </div>

            {{-- Column 3: Expiring Soon --}}
<div class="bg-white rounded-xl shadow-sm border-t-4 border-purple-500 p-5">
    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4">📅 Expiring Soon</h3>
    
    @forelse($expiringSoon as $batch)
        <div class="p-3 bg-purple-50 border border-purple-100 rounded-lg mb-3">
            <h4 class="text-sm font-bold text-purple-900">{{ $batch->product->name }}</h4>
            <p class="text-xs text-purple-700 mt-0.5">
                {{-- If it expires today, show a special warning --}}
                @if($batch->expiry_date->isToday())
                    <span class="font-bold text-red-600">Expires Today!</span>
                @else
                    Expires: {{ $batch->expiry_date->format('d M Y') }}
                @endif
            </p>
        </div>
    @empty
        <p class="text-sm text-gray-400 italic">No items expiring in the next 7 days. ✅</p>
    @endforelse
</div>

        </div>
    </div>
</div>
@endsection