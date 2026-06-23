<div class="space-y-6">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        {{-- Name --}}
        <div>
            <label for="name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                🏷️ Product / Ingredient Name <span class="text-rose-500">*</span>
            </label>
            <input type="text" name="name" id="name" 
                   value="{{ old('name', $product->name ?? '') }}"
                   placeholder="e.g. Evaporated milk"
                   class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition duration-150 @error('name') border-rose-500 ring-2 ring-rose-500/10 @enderror" 
                   required>
            @error('name') <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p> @enderror
        </div>

        {{-- Category --}}
        <div>
            <label for="category" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                📁 Category Allocation <span class="text-rose-500">*</span>
            </label>
            <select name="category" id="category" 
                    class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-lg text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition duration-150 @error('category') border-rose-500 ring-2 ring-rose-500/10 @enderror"
                    required>
                <option value="" disabled {{ old('category', $product->category ?? '') == '' ? 'selected' : '' }}>-- Choose Category --</option>
                <optgroup label="☕ Barista Stock">
                    <option value="barista-packaging" {{ old('category', $product->category ?? '') == 'barista-packaging' ? 'selected' : '' }}>Packaging</option>
                    <option value="barista-syrups" {{ old('category', $product->category ?? '') == 'barista-syrups' ? 'selected' : '' }}>Syrups & Purees</option>
                    <option value="barista-coffee" {{ old('category', $product->category ?? '') == 'barista-coffee' ? 'selected' : '' }}>Coffee, Tea & Powders</option>
                    <option value="barista-dairy" {{ old('category', $product->category ?? '') == 'barista-dairy' ? 'selected' : '' }}>Dairy & Drinks</option>
                    <option value="barista-snacks" {{ old('category', $product->category ?? '') == 'barista-snacks' ? 'selected' : '' }}>Snacks & Add-ons</option>
                </optgroup>
                <optgroup label="🍳 Kitchen Stock">
                    <option value="kitchen-dry" {{ old('category', $product->category ?? '') == 'kitchen-dry' ? 'selected' : '' }}>Dry Ingredients & Seasonings</option>
                    <option value="kitchen-sauces" {{ old('category', $product->category ?? '') == 'kitchen-sauces' ? 'selected' : '' }}>Sauces & Condiments</option>
                    <option value="kitchen-carbs" {{ old('category', $product->category ?? '') == 'kitchen-carbs' ? 'selected' : '' }}>Carbs & Staples</option>
                    <option value="kitchen-protein" {{ old('category', $product->category ?? '') == 'kitchen-protein' ? 'selected' : '' }}>Frozen Protein & Processed</option>
                    <option value="kitchen-fresh" {{ old('category', $product->category ?? '') == 'kitchen-fresh' ? 'selected' : '' }}>Fresh Produce</option>
                    <option value="kitchen-packaging" {{ old('category', $product->category ?? '') == 'kitchen-packaging' ? 'selected' : '' }}>Kitchen Packaging</option>
                </optgroup>
            </select>
            @error('category') <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        {{-- Unit --}}
        <div>
            <label for="unit" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                ⚖️ Measurement Unit <span class="text-rose-500">*</span>
            </label>
            <input type="text" name="unit" id="unit" 
                   value="{{ old('unit', $product->unit ?? '') }}"
                   placeholder="e.g. tin, pcs, kg"
                   class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition duration-150 @error('unit') border-rose-500 ring-2 ring-rose-500/10 @enderror" 
                   required>
            @error('unit') <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p> @enderror
        </div>

        {{-- Min Stock --}}
        <div>
            <label for="min_stock" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                ⚠️ Min Stock Alert Threshold <span class="text-rose-500">*</span>
            </label>
            <input type="number" name="min_stock" id="min_stock" 
                   value="{{ old('min_stock', $product->min_stock ?? '5') }}"
                   min="0"
                   class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-lg text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition duration-150 @error('min_stock') border-rose-500 ring-2 ring-rose-500/10 @enderror" 
                   required>
            <p class="text-[11px] text-gray-400 mt-1.5 leading-normal">Triggers notifications when cumulative stock quantities slide below this value.</p>
            @error('min_stock') <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Description --}}
    <div>
        <label for="description" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
            📝 Product Description <span class="text-gray-400 font-normal text-[10px] lowercase">(optional)</span>
        </label>
        <textarea name="description" id="description" rows="3"
                  placeholder="Specify unique parameters, storage environments, or wholesale vendor notes..."
                  class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition duration-150 @error('description') border-rose-500 ring-2 ring-rose-500/10 @enderror"
        >{{ old('description', $product->description ?? '') }}</textarea>
        @error('description') <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p> @enderror
    </div>

</div>