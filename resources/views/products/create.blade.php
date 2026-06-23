@extends('layouts.app')
@section('title', 'Add New Product')

@section('page_title', 'Add New Product')

@section('content')
<div class="py-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-6">
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-teal-600 mb-1">
                <a href="{{ route('products.index') }}" class="hover:underline">Products Registry</a>
                <span>/</span>
                <span class="text-gray-400">Add New Item</span>
            </div>
            <p class="text-sm text-gray-500">Add a new ingredient or supply stock item.</p>
        </div>

        {{-- 📝 Master Record Creation Card (Updated to full-width clean layout) --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-150 overflow-hidden">
            

            <form method="POST" action="{{ route('products.store') }}" class="p-6 space-y-6">
                @csrf 

                {{-- Injecting your cleaned full-width form inputs template --}}
                @include('products._form')

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('products.index') }}" 
                       class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-gray-500 hover:text-gray-700 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg font-bold text-xs uppercase tracking-wider shadow-md hover:from-teal-700 hover:to-emerald-700 focus:ring-2 focus:ring-teal-500/20 transition duration-150">
                        Add Product
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection