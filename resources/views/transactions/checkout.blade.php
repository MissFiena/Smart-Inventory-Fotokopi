<!-- resources/views/checkout/create.blade.php -->
@extends('layouts.app')
@section('title', 'Stock Check-Out')

{{-- 🛠️ CONNECTS DIRECTLY TO THE LAYOUT TITLE RULES --}}
@section('page_title', 'Stock Check-Out')

@section('content')
<div class="py-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Page Header -->
 
        <div class="mb-6">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-2">
            📥 <span>Stock Check-Out</span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">Track and deduct café inventory usage or log damages with high accuracy.</p>
        </div>

        <!-- System Validation Status Alerts -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-orange-50 border-l-4 border-orange-500 rounded-r-xl text-orange-800 shadow-sm flex items-center gap-2">
                <span>🟢</span> <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- 📤 Master Form Container --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-150 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-orange-50 to-purple-50/30">
                <h3 class="font-bold text-gray-800 text-base flex items-center gap-2">
                    🌱 <span>Deduct Active Inventory</span>
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">Please review your selected product batch counts before confirming deductions.</p>
            </div>

            <form method="POST" action="{{ route('checkout.store') }}" class="p-6 space-y-6">
                @csrf

                <!-- Dropdown Selector -->
                <div>
                    <label for="product_id" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2 flex items-center gap-1.5">
                        🏷️ Select Product <span class="text-rose-500">*</span>
                    </label>
                    <select name="product_id" id="product_id" required
                        class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-lg text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition duration-150 @error('product_id') border-rose-500 ring-2 ring-rose-500/10 @enderror">
                        <option value="">-- Choose product --</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} ({{ $p->stock }} {{ $p->unit ?? 'available' }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id') 
                        <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Matrix Form Field Split Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Quantity Input Field -->
                    <div>
                        <label for="quantity" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2 flex items-center gap-1.5">
                            ⚖️ Quantity <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="quantity" id="quantity" min="1" required placeholder="e.g. 5"
                               value="{{ old('quantity') }}"
                               class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition duration-150 @error('quantity') border-rose-500 ring-2 ring-rose-500/10 @enderror">
                        @error('quantity') 
                            <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Checkout Type Dropdown Selector -->
                    <div>
                        <label for="type" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2 flex items-center gap-1.5">
                            ⚡ Allocation Type <span class="text-rose-500">*</span>
                        </label>
                        <select name="type" id="type" required 
                                class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-lg text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition duration-150 @error('type') border-rose-500 ring-2 ring-rose-500/10 @enderror">
                            <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>Used</option>
                            <option value="waste" {{ old('type') == 'waste' ? 'selected' : '' }}>Waste/Damage</option>
                        </select>
                        @error('type') 
                            <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>

                <!-- Descriptive Notes Area -->
                <div>
                    <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2 flex items-center gap-1.5">
                        📝 Log Assignment Notes <span class="text-gray-400 font-normal text-[10px] lowercase">(optional)</span>
                    </label>
                    <textarea name="notes" id="notes" rows="3" placeholder="Specify order manifest IDs, customer table batches, or reason notes for broken stock seals..."
                              class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition duration-150 @error('notes') border-rose-500 ring-2 ring-rose-500/10 @enderror">{{ old('notes') }}</textarea>
                    @error('notes') 
                        <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Form Action Options Control Bar -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('products.index') }}" class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-gray-500 hover:text-gray-700 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-orange-500 to-purple-600 text-white rounded-lg font-bold text-xs uppercase tracking-wider shadow-md hover:from-orange-600 hover:to-purple-700 focus:ring-2 focus:ring-orange-500/20 transition duration-150">
                        Confirm Check-Out
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection