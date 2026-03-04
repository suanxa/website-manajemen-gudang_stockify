@extends('layouts.app')

@section('content')
<div class="p-6 bg-cream-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    
    {{-- Header Section --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-[2rem] shadow-sm border border-cream-200 dark:border-gray-700 animate-fade-in-up">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-gray-900 dark:text-white uppercase">
                Inventory <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-500">Alert</span>
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 italic font-medium">
                Daftar produk yang sudah mencapai atau di bawah batas minimum stok.
            </p>
        </div>
        
        <div class="flex items-center">
            <div class="px-4 py-2 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 rounded-xl">
                <span class="text-xs font-black text-red-600 uppercase tracking-widest">Status: Perlu Re-order</span>
            </div>
        </div>
    </div>

    {{-- Main Table Section --}}
    <div class="overflow-hidden bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-cream-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left table-fixed">
                <thead class="text-[11px] font-black uppercase tracking-widest text-red-500 bg-red-50/50 dark:bg-red-900/20 border-b border-red-100 dark:border-red-900/30">
                    <tr>
                        <th class="px-6 py-5 w-[250px]">Produk & Supplier</th>
                        <th class="px-6 py-5 w-[120px] text-center">Stok Saat Ini</th>
                        <th class="px-6 py-5 w-[120px] text-center">Batas Minimum</th>
                        <th class="px-6 py-5 w-[150px] text-center">Status</th>
                        <th class="px-6 py-5 w-[150px] text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($lowStockProducts as $product)
                        <tr class="group hover:bg-red-50/30 dark:hover:bg-red-900/10 transition-all duration-200">
                            <td class="px-6 py-5">
                                <div class="text-base font-black text-gray-800 dark:text-white group-hover:text-red-600 transition-colors duration-200 uppercase tracking-tighter">{{ $product->name }}</div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] font-bold text-gray-400">SKU: {{ $product->sku }}</span>
                                    <span class="text-[9px] font-black text-orange-600 bg-orange-50 px-2 py-0.5 rounded dark:bg-orange-900/30">
                                        <i class="fas fa-truck mr-1"></i> {{ $product->supplier->name ?? 'N/A' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="text-xl font-black text-red-600 animate-pulse">
                                    {{ $product->current_stock }}
                                </span>
                                <small class="block text-[8px] font-bold text-gray-400 uppercase tracking-widest">Unit Tersisa</small>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="text-sm font-black text-gray-400 dark:text-gray-500">
                                    {{ $product->minimum_stock }}
                                </span>
                                <small class="block text-[8px] font-bold text-gray-400 uppercase tracking-widest">Limit Sistem</small>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="inline-flex items-center px-3 py-1 text-[10px] font-black text-red-700 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-300 uppercase tracking-widest shadow-sm">
                                    <i class="fas fa-exclamation-circle mr-1.5"></i> Kritis
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                {{-- Tombol Restock --}}
                                <a href="{{ route('admin.stock.history', ['open_modal' => 1, 'product_id' => $product->id]) }}" 
                                    class="inline-flex items-center px-4 py-2 text-[10px] font-black text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all shadow-md shadow-blue-100 dark:shadow-none uppercase tracking-widest">
                                    <i class="fas fa-plus-circle mr-1.5"></i> Restock
                                </a>
                            </td>
                        </tr>
                    @empty 
                        <tr>
                            <td colspan="5" class="p-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-green-50 dark:bg-green-900/30 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-check-double text-2xl text-green-500"></i>
                                    </div>
                                    <span class="text-sm text-green-600 dark:text-green-400 font-black uppercase tracking-widest">Stok Aman!</span>
                                    <p class="text-xs text-gray-400 mt-1 italic font-medium">Semua produk berada di atas batas minimum stok.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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