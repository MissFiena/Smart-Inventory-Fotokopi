@extends('layouts.app')

@section('content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                🔔 <span>System Alerts</span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">📊 Here’s today’s café inventory snapshot and risk assessments.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="space-y-4">
                <div class="bg-white rounded-xl shadow-sm border-t-4 border-red-500 p-5">
                    <h3 class="text-md font-bold text-gray-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        🔴 Critical Stock Alerts
                    </h3>
                    
                    @if($outStocks->isEmpty())
                        <p class="text-sm text-gray-400 italic">No items are currently out of stock. Great job! 🌱</p>
                    @else
                        <div class="space-y-3">
                            @foreach($outStocks as $product)
                                <div class="p-3 bg-red-50 border border-red-100 rounded-lg flex items-start gap-3">
                                    <span class="text-xl">❌</span>
                                    <div>
                                        <h4 class="text-sm font-bold text-red-900">{{ $product->name }}</h4>
                                        <p class="text-xs text-red-700 font-semibold mt-0.5">Completely out of stock!</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-white rounded-xl shadow-sm border-t-4 border-orange-500 p-5">
                    <h3 class="text-md font-bold text-gray-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        🟡 Low Stock & Expiry Warnings
                    </h3>
                    
                    @if($lowStocks->isEmpty() && (!isset($expiringSoon) || $expiringSoon->isEmpty()))
                        <p class="text-sm text-gray-400 italic">All ingredients are well-stocked and fresh! ☕</p>
                    @else
                        <div class="space-y-3">
                            @foreach($lowStocks as $product)
                                <div class="p-3 bg-orange-50 border border-orange-100 rounded-lg flex items-start gap-3">
                                    <span class="text-xl">⚠️</span>
                                    <div>
                                        <h4 class="text-sm font-bold text-orange-900">{{ $product->name }}</h4>
                                        <p class="text-xs text-orange-700 mt-0.5">
                                            Running low: Only <span class="font-bold">{{ $product->stock }}</span> units left.
                                        </p>
                                    </div>
                                </div>
                            @endforeach

                            @if(isset($expiringSoon))
                                @foreach($expiringSoon as $product)
                                    <div class="p-3 bg-yellow-50 border border-yellow-100 rounded-lg flex items-start gap-3">
                                        <span class="text-xl">⏳</span>
                                        <div>
                                            <h4 class="text-sm font-bold text-yellow-900">{{ $product->name }}</h4>
                                            <p class="text-xs text-yellow-700 mt-0.5">
                                                Expires on: <span class="font-bold">{{ \Carbon\Carbon::parse($product->expiry_date)->format('d M Y') }}</span>
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-white rounded-xl shadow-sm border-t-4 border-purple-500 p-5">
                    <h3 class="text-md font-bold text-gray-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        🔮 Smart Velocity Insights
                    </h3>
                    
                    @if($lowStocks->isEmpty() && $outStocks->isEmpty())
                        <div class="p-4 bg-green-50 border border-green-100 rounded-lg text-center">
                            <p class="text-xs font-bold text-green-800">🟢 Inventory Health Score is Stable</p>
                        </div>
                    @endif

                    <div class="space-y-3">
                        @foreach($lowStocks as $product)
                            @if(method_exists($product, 'getPredictedDaysRemainingAttribute') && $product->predicted_days_remaining <= 7)
                                <div class="p-3 bg-purple-50 border border-purple-100 rounded-lg flex items-start gap-3">
                                    <span class="text-xl">☕</span>
                                    <div>
                                        <h4 class="text-sm font-bold text-purple-900">{{ $product->name }}</h4>
                                        <p class="text-xs text-purple-700 mt-0.5">
                                            Predicted to run out in <span class="font-bold text-red-600">{{ $product->predicted_days_remaining }} days</span> based on recent brewing trends.
                                        </p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection