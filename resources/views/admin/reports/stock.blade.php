@extends('layouts.app')

@section('content')
{{-- 1. Deteksi Role dan Prefix --}}
@php
    $isManager = str_contains(request()->path(), 'manager');
    $routePrefix = $isManager ? 'manager' : 'admin';
@endphp

<div class="p-6 bg-cream-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    
    {{-- Header Section --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-[2rem] shadow-sm border border-cream-200 dark:border-gray-700 animate-fade-in-up">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-gray-900 dark:text-white uppercase">
                Laporan <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">Stok Barang</span>
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 italic font-medium">
                Data ketersediaan inventaris real-time per {{ now()->format('d M Y') }}.
            </p>
        </div>

        <a href="{{ route($routePrefix . '.reports.stock.export', ['category_id' => request('category_id'), 'search' => request('search')]) }}"
           class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-black text-white bg-green-600 rounded-xl hover:bg-green-700 shadow-lg shadow-green-200 dark:shadow-none transition-all hover:scale-105 active:scale-95">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Export ke Excel
        </a>
    </div>

    {{-- Filter Card --}}
    <div class="mb-6 p-6 bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-cream-200 dark:border-gray-700">
        <form action="{{ route($routePrefix . '.reports.stock') }}" method="GET" class="flex flex-wrap items-center gap-4">
            
            {{-- Filter Kategori --}}
            <div class="w-full sm:w-64 group">
                <label class="block mb-1.5 text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kategori Produk</label>
                <select name="category_id" class="block w-full p-2.5 text-sm font-bold text-gray-700 border border-cream-200 rounded-xl bg-gray-50 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Input Pencarian --}}
            <div class="w-full sm:w-64 group">
                <label class="block mb-1.5 text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Cari Produk</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 group-focus-within:text-blue-500">
                        <i class="fas fa-search"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Nama barang atau SKU..." 
                           class="block w-full p-2.5 pl-10 text-sm font-bold border border-cream-200 rounded-xl bg-gray-50 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all">
                </div>
            </div>

            <div class="flex items-end h-full pt-6">
                <button type="submit" class="inline-flex items-center px-6 py-2.5 text-sm font-black text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all uppercase tracking-widest shadow-md shadow-blue-100 dark:shadow-none">
                    <i class="fas fa-filter mr-2"></i> Filter Laporan
                </button>

                @if(request('category_id') || request('search'))
                    <a href="{{ route($routePrefix . '.reports.stock') }}" class="ml-4 text-[10px] font-black text-gray-400 hover:text-red-500 uppercase tracking-widest transition-colors">
                        Reset <i class="fas fa-undo ml-1"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="overflow-hidden bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-cream-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[11px] font-black uppercase tracking-widest text-gray-400 bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-5">Informasi Produk</th>
                        <th class="px-6 py-5">Kategori</th>
                        <th class="px-6 py-5 text-center">Stok Sistem</th>
                        <th class="px-6 py-5 text-center">Status Keamanan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($products as $product)
                    <tr class="group hover:bg-blue-50/20 dark:hover:bg-blue-900/10 transition-all duration-200">
                        <td class="px-6 py-4">
                            <div class="text-base font-black text-gray-800 dark:text-white group-hover:text-orange-400 transition-colors">{{ $product->name }}</div>
                            <div class="text-[10px] font-bold text-gray-400 tracking-tighter uppercase mt-0.5">SKU: {{ $product->sku }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-black bg-purple-50 text-purple-500 rounded-lg dark:bg-purple-900/30 dark:text-purple-500 dark:text-gray-300 uppercase">
                                <i class="fas fa-tag mr-1.5 opacity-50"></i> {{ $product->category->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="text-lg font-black {{ $product->current_stock <= $product->minimum_stock ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">
                                {{ $product->current_stock }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center">
                                @if($product->current_stock <= 0)
                                    <span class="inline-flex items-center px-3 py-1 text-[10px] font-black text-white bg-black rounded-full uppercase tracking-widest">
                                        <i class="fas fa-exclamation-circle mr-1.5"></i> Kosong
                                    </span>
                                @elseif($product->current_stock <= $product->minimum_stock)
                                    <span class="inline-flex items-center px-3 py-1 text-[10px] font-black text-red-700 bg-red-100 border border-red-200 rounded-full dark:bg-red-900 dark:text-red-300 dark:border-red-800 uppercase tracking-widest animate-pulse">
                                        <i class="fas fa-exclamation-triangle mr-1.5"></i> Kritis
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 text-[10px] font-black text-emerald-700 bg-emerald-50 border border-emerald-100 rounded-full dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800 uppercase tracking-widest">
                                        <i class="fas fa-check-circle mr-1.5"></i> Aman
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-20 text-center text-gray-400 italic font-medium">Data laporan stok tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination Section --}}
    <div class="mt-6 px-4">
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>

<style>
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fade-in-up 0.5s ease-out; }
    .animate-fade-in { animation: fadeIn 0.3s ease-in; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>
@endsection