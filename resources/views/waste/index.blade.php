@extends('layouts.app')
@section('title', 'Waste & Loss Tracking')
@section('content')

<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="mb-8">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                🗑️ <span>Waste & Loss Tracking</span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">📊 Here’s today’s cafe inventory snapshot — keep spills low and data precise!</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl text-green-800 shadow-sm flex items-center gap-2">
                <span>🟢</span> <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl text-red-800 shadow-sm">
                <p class="font-bold mb-1">☕ Hold up, Barista! Please fix these errors:</p>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">

            {{-- 📥 Record Waste / Loss Form --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-red-50 to-orange-50/30">
                    <h3 class="font-bold text-gray-800 text-base flex items-center gap-2">
                        🌱 <span>Record Cafe Loss</span>
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Accurately account for discrepancies to maintain high inventory health.</p>
                </div>

                <form method="POST" action="{{ route('waste.store') }}" class="p-6 space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Select Ingredient / Product *</label>
                        <select name="product_id" required class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-orange-500 shadow-sm transition">
                            <option value="">-- Choose Item --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }} (Available: {{ $p->stock }} left)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Reason Code *</label>
                        <select name="reason" required class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-orange-500 shadow-sm transition">
                            <option value="expired" {{ old('reason') == 'expired' ? 'selected' : '' }}>Expired / Outdated</option>
                            <option value="damaged" {{ old('reason') == 'damaged' ? 'selected' : '' }}>Damaged / Broken / Spilled</option>
                            <option value="lost" {{ old('reason') == 'lost' ? 'selected' : '' }}>Lost / Missing</option>
                            <option value="other" {{ old('reason') == 'other' ? 'selected' : '' }}>Other Reasons</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Quantity Deducted *</label>
                        <input type="number" name="quantity" min="1" value="{{ old('quantity') }}" required
                            placeholder="e.g. 5"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-orange-500 shadow-sm transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Operational Notes</label>
                        <textarea name="notes" rows="3" placeholder="Provide extra details (e.g. Fridge temperature failure during morning shift)" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm resize-none focus:border-orange-500 focus:ring-orange-500 shadow-sm transition">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-orange-600 text-white py-3 rounded-lg font-bold text-sm shadow-sm hover:from-red-700 hover:to-orange-700 active:scale-[0.99] transition duration-150">
                        Log Waste Transaction
                    </button>
                </form>
            </div>

            {{-- 📑 Waste History Log Table --}}
            <div class="lg:col-span-3 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-800 text-base">Waste Log Ledger</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Historical verification records for quality score analysis.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-xs font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left">Product</th>
                                <th class="px-6 py-4 text-left">Reason</th>
                                <th class="px-6 py-4 text-center">Qty</th>
                                <th class="px-6 py-4 text-left">Logged By</th>
                                <th class="px-6 py-4 text-right">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($wasteLogs as $w)
                            <tr class="hover:bg-orange-50/20 odd:bg-gray-50/50 transition duration-150">
                                <td class="px-6 py-4 font-semibold text-gray-900">{{ $w->product->name }}</td>
                                <td class="px-6 py-4">
                                    @switch($w->reason)
                                        @case('expired')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">⏳ Expired</span>
                                            @break
                                        @case('damaged')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">💥 Damaged</span>
                                            @break
                                        @case('lost')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">🔍 Lost</span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">📝 Other</span>
                                    @endswitch
                                    @if($w->notes)
                                        <p class="text-xs text-gray-400 mt-1 italic max-w-xs truncate" title="{{ $w->notes }}">"{{ $w->notes }}"</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-red-600 bg-red-50/20">-{{ $w->quantity }}</td>
                                <td class="px-6 py-4 text-gray-600 font-medium">
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-teal-400"></span>
                                        {{ $w->user->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-gray-400 text-xs font-medium">
                                    {{ $w->created_at->format('d M Y') }}
                                    <span class="block text-[10px] text-gray-300">{{ $w->created_at->format('h:i A') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                    <div class="text-3xl mb-2">☕</div>
                                    <p class="font-medium text-sm">Perfect streak! No waste logs registered yet.</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($wasteLogs->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $wasteLogs->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection