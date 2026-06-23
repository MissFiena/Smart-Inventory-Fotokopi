<!-- resources/views/reports/index.blade.php -->
@extends('layouts.app')
@section('title', 'Café Analytics')
@section('content')

<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- ☕ FOTOKOPI Branded Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                    📊 <span>Analytics</span>
                </h1>
                <p class="text-sm text-gray-500 mt-1">Deep dive into transaction history and brewing velocity.</p>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-bold text-gray-600 hover:bg-gray-50 transition shadow-sm">
                    🖨️ Print Report
                </button>
            </div>
        </div>

        <!-- 📅 Modern Date Filter -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-8">
            <form method="GET" class="flex flex-wrap items-center gap-6">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-black uppercase tracking-widest text-gray-400">Date Range</span>
                    <div class="flex items-center bg-gray-50 border border-gray-200 rounded-lg px-2 shadow-inner">
                        <input type="date" name="from" value="{{ $from }}"
                               class="bg-transparent border-0 text-sm focus:ring-0 py-2">
                        <span class="text-gray-300 mx-1">→</span>
                        <input type="date" name="to" value="{{ $to }}"
                               class="bg-transparent border-0 text-sm focus:ring-0 py-2">
                    </div>
                </div>
                <button type="submit"
                        class="bg-teal-600 text-white px-6 py-2 rounded-lg text-sm font-bold hover:bg-teal-700 transition shadow-md active:scale-95">
                    Apply Filter
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            {{-- 🔥 Top Velocity Products --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-50 bg-orange-50/30">
                    <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                        🔥 Top Used Ingredients
                    </h3>
                </div>
                <div class="p-5">
                    @forelse($topProducts as $p)
                    <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0 hover:bg-gray-50 transition px-2 rounded-md">
                        <span class="text-sm font-medium text-gray-700">{{ $p->name }}</span>
                        <span class="text-xs font-black bg-orange-100 text-orange-700 px-2 py-1 rounded-full">{{ $p->total_out ?? 0 }} units</span>
                    </div>
                    @empty
                    <div class="text-center py-6">
                        <p class="text-gray-400 text-sm italic">No consumption data found.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- 🗑️ Waste Analysis Card --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-50 bg-red-50/30">
                    <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                        🗑️ Waste & Loss Summary
                    </h3>
                </div>
                <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
    @forelse($wasteSummary as $w)
    {{-- Flex container ensures the quantity stays aligned with the product info --}}
    <div class="flex items-center justify-between p-4 bg-white border border-gray-100 rounded-xl shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-2 h-10 bg-rose-500 rounded-full"></div>
            <div>
                <p class="text-[10px] font-black text-rose-500 uppercase tracking-widest">{{ $w->reason }}</p>
                <p class="text-sm font-black text-gray-900">{{ $w->product->name }}</p>
            </div>
        </div>
        
        <div class="text-right">
            <p class="text-lg font-black text-rose-600">-{{ $w->quantity }}</p>
            <p class="text-[9px] font-bold text-gray-400 uppercase">Units Lost</p>
        </div>
    </div>
    @empty
    <div class="col-span-2 py-10 text-center border border-dashed border-gray-200 rounded-xl">
        <p class="text-gray-400 text-xs font-bold uppercase">Perfect efficiency—no waste.</p>
    </div>
    @endforelse
</div>
        </div>
    </div>

        {{-- 📑 Transaction Ledger --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-black text-gray-800 text-base">Full Transaction Ledger</h3>
                <span class="text-[10px] font-bold bg-teal-50 text-teal-600 px-2 py-1 rounded border border-teal-100">AUDIT READY</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left">Timestamp</th>
                            <th class="px-6 py-4 text-left">Product</th>
                            <th class="px-6 py-4 text-center">Operation</th>
                            <th class="px-6 py-4 text-center">Quantity</th>
                            <th class="px-6 py-4 text-left">Executor</th>
                            <th class="px-6 py-4 text-left">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($transactions as $t)
                    <tr class="hover:bg-teal-50/30 transition duration-150 odd:bg-gray-50/30">
                        <td class="px-6 py-4 text-gray-400 text-[11px] font-bold">
                            {{ $t->created_at->format('d M Y') }}
                            <span class="block text-gray-300 font-medium uppercase">{{ $t->created_at->format('g:i A') }}</span>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-800">{{ $t->product->name }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($t->type === 'check_in')
                                <span class="px-2 py-1 rounded text-[10px] font-black bg-green-100 text-green-700 uppercase border border-green-200">INBOUND</span>
                            @else
                                <span class="px-2 py-1 rounded text-[10px] font-black bg-orange-100 text-orange-700 uppercase border border-orange-200">OUTBOUND</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center font-black {{ $t->type === 'check_in' ? 'text-green-600' : 'text-red-500' }}">
                            {{ $t->type === 'check_in' ? '↑' : '↓' }} {{ $t->quantity }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-teal-500 text-white text-[10px] flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($t->user->name, 0, 1)) }}
                                </div>
                                <span class="text-xs font-semibold text-gray-600">{{ $t->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-xs italic truncate max-w-[150px]" title="{{ $t->notes }}">
                            {{ $t->notes ?? '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center text-gray-400">
                            <p class="text-sm font-medium">No activity recorded for this timeline.</p>
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $transactions->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection