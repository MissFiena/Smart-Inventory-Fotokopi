<!-- resources/views/checkin/create.blade.php -->
@extends('layouts.app')
@section('title', 'Stock Check-In')

{{-- 🛠️ CONNECTS DIRECTLY TO THE LAYOUT TITLE RULES --}}
@section('page_title', 'Stock Check-In')

@section('content')
<div class="py-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                📥 <span>Stock Check-In</span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">Register incoming batches below to update active quantities and tracking timelines.</p>
        </div>

        <!-- System Validation Status Alerts -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl text-emerald-800 shadow-sm flex items-center gap-2">
                <span>🟢</span> <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- 📦 Log New Supply Form Card (Stretched to Full Width) --}}
        <div class="w-full bg-white rounded-xl shadow-sm border border-gray-150 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-teal-50 to-emerald-50/30">
                <h3 class="font-bold text-gray-800 text-base flex items-center gap-2">
                    📦 <span>Log New Batch Supply</span>
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">Always verify quantities and validate expiry fields before saving changes.</p>
            </div>

            <form method="POST" action="{{ route('checkin.store') }}" class="p-6 space-y-6">
                @csrf

                <!-- Dropdown Selector -->
                <div>
                    <label for="product_id" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2 flex items-center gap-1.5">
                        🏷️ Select Product / Ingredient <span class="text-rose-500">*</span>
                    </label>
                    <select name="product_id" id="product_id" required
                        class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-lg text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition duration-150 @error('product_id') border-rose-500 ring-2 ring-rose-500/10 @enderror">
                        <option value="">-- Choose product --</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} (Current Stock: {{ $p->stock }} {{ $p->unit ?? 'units' }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id') 
                        <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Split Matrix Inputs row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Quantity Input Field -->
                    <div>
                        <label for="quantity" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2 flex items-center gap-1.5">
                            ⚖️ Quantity to Add <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="quantity" id="quantity" min="1" required
                               placeholder="e.g. 24"
                               value="{{ old('quantity') }}"
                               class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition duration-150 @error('quantity') border-rose-500 ring-2 ring-rose-500/10 @enderror">
                        @error('quantity') 
                            <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- 📅 Expiry Date Selector -->
                    <div>
                        <label for="expiry_date" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2 flex items-center gap-1.5">
                            📅 Expiry Date <span class="text-gray-400 font-normal text-[10px]">(Optional for packaging)</span>
                        </label>
                        <input type="date" name="expiry_date" id="expiry_date" 
                            value="{{ old('expiry_date') }}"
                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-lg text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition duration-150 @error('expiry_date') border-rose-500 ring-2 ring-rose-500/10 @enderror">
                        @error('expiry_date') 
                            <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>

                <!-- Descriptive Notes Area -->
                <div>
                    <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2 flex items-center gap-1.5">
                        📝 Supply Notes <span class="text-gray-400 font-normal text-[10px] lowercase">(optional)</span>
                    </label>
                    <textarea name="notes" id="notes" rows="3" placeholder="Supplier information, shipping manifest codes, or warehouse tracking IDs..."
                              class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition duration-150 @error('notes') border-rose-500 ring-2 ring-rose-500/10 @enderror">{{ old('notes') }}</textarea>
                    @error('notes') 
                        <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Form Action Options Bar -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-gray-500 hover:text-gray-700 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg font-bold text-xs uppercase tracking-wider shadow-md hover:from-teal-700 hover:to-emerald-700 focus:ring-2 focus:ring-teal-500/20 transition duration-150">
                        Confirm Check-In
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection