@extends('layouts.app')

@section('content')
{{-- 1. Tentukan Prefix secara Dinamis berdasarkan Path URL --}}
@php
    $path = request()->path();
    if (str_contains($path, 'admin')) {
        $routePrefix = 'admin';
    } elseif (str_contains($path, 'manager')) {
        $routePrefix = 'manager';
    } elseif (str_contains($path, 'staff')) {
        $routePrefix = 'staff';
    } else {
        $routePrefix = 'admin'; 
    }
@endphp

<div class="p-6 bg-cream-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    
    {{-- Header Section --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-[2rem] shadow-sm border border-cream-200 dark:border-gray-700 animate-fade-in-up">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-gray-900 dark:text-white uppercase">
                Stock <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">Opname</span>
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 italic font-medium">
                Sinkronisasi data sistem dengan stok fisik barang di gudang secara akurat.
            </p>
        </div>
    </div>

    {{-- Alert Section --}}
    @if(session('success'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-2xl bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 flex items-center animate-fade-in">
            <i class="fas fa-check-circle mr-3 text-lg"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 mb-6 text-sm text-red-800 rounded-2xl bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 flex items-center animate-fade-in">
            <i class="fas fa-exclamation-triangle mr-3 text-lg"></i> {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                {{-- Card Wrapper --}}
                <div class="overflow-hidden bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-cream-200 dark:border-gray-700">
                    
                    {{-- Filter Section --}}
                    <div class="p-6 bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-600">
                        <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-center gap-4">
                            <div class="relative group w-full sm:w-64">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-search text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    class="block w-full p-2.5 pl-10 text-sm text-gray-900 border border-cream-200 rounded-xl bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" 
                                    placeholder="Cari SKU atau Nama...">
                            </div>

                            <div class="w-full sm:w-64">
                                <select name="category_id" class="block w-full p-2.5 text-sm font-bold text-gray-700 border border-cream-200 rounded-xl bg-white focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white uppercase tracking-tighter">
                                    <option value="">-- Semua Kategori --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="inline-flex items-center px-6 py-2.5 text-sm font-black text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all uppercase tracking-widest shadow-md shadow-blue-100 dark:shadow-none">
                                <i class="fas fa-filter mr-2"></i> Cari Barang
                            </button>

                            @if(request('category_id') || request('search'))
                                <a href="{{ url()->current() }}" class="text-[10px] font-black text-gray-400 hover:text-red-500 uppercase tracking-widest transition-colors">
                                    Reset Filter <i class="fas fa-times-circle ml-1"></i>
                                </a>
                            @endif
                        </form>
                    </div>

                    {{-- Table --}}
                    <table class="w-full text-sm text-left table-fixed">
                        <thead class="text-[11px] font-black uppercase tracking-widest text-gray-400 bg-gray-50/30 dark:bg-gray-700/30 border-b border-gray-100 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-5 w-[250px]">Produk & SKU</th>
                                <th class="px-6 py-5 w-[150px]">Kategori</th>
                                <th class="px-6 py-5 w-[120px] text-center">Stok Sistem</th>
                                <th class="px-6 py-5 w-[200px] text-center">Aksi Penyesuaian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($products as $product)
                                <tr class="group hover:bg-orange-50/20 dark:hover:bg-orange-900/10 transition-all duration-200">
                                    <td class="px-6 py-5">
                                        <div class="text-base font-black text-gray-800 dark:text-white group-hover:text-orange-400 transition-colors">{{ $product->name }}</div>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mt-0.5">SKU: {{ $product->sku }}</div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-black bg-purple-50 text-purple-500 rounded-lg dark:bg-purple-900/30 dark:text-purple-500 uppercase">
                                            {{ $product->category->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="inline-block px-4 py-1.5 text-sm font-black text-blue-700 bg-blue-50 rounded-xl dark:bg-blue-900/40 dark:text-blue-300 border border-blue-100 dark:border-blue-800">
                                            {{ $product->current_stock }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex justify-center">
                                            <button type="button" data-modal-target="opname-modal-{{ $product->id }}" data-modal-toggle="opname-modal-{{ $product->id }}" 
                                                class="inline-flex items-center px-4 py-2 text-[10px] font-black text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-all shadow-md shadow-indigo-100 dark:shadow-none uppercase tracking-widest">
                                                <i class="fas fa-sync-alt mr-2"></i> Sesuaikan Stok
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                {{-- MODAL OPNAME --}}
                                <div id="opname-modal-{{ $product->id }}" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-modal w-full items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0 md:h-full">
                                    <div class="relative h-full w-full max-w-md p-4 md:h-auto">
                                        <div class="relative rounded-[2.5rem] bg-white shadow-2xl dark:bg-gray-800 border border-cream-100 dark:border-gray-700 overflow-hidden">
                                            <form action="{{ route($routePrefix . '.stock.opname.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                
                                                <div class="p-6 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-700/50">
                                                    <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tighter">Opname: {{ $product->name }}</h3>
                                                    <button type="button" class="text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors" data-modal-toggle="opname-modal-{{ $product->id }}">
                                                        <i class="fas fa-times text-xl"></i>
                                                    </button>
                                                </div>
                                                
                                                <div class="p-8 space-y-6">
                                                    <div class="p-4 bg-blue-50 dark:bg-blue-900/30 rounded-2xl border border-blue-100 dark:border-blue-800">
                                                        <div class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-1">Data Sistem Saat Ini</div>
                                                        <div class="text-2xl font-black text-blue-700 dark:text-blue-300">{{ $product->current_stock }} <small class="text-sm">UNIT</small></div>
                                                    </div>
                                                    
                                                    <div>
                                                        <label class="block mb-2 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Jumlah Stok Fisik <span class="text-red-500">*</span></label>
                                                        <input type="number" name="physical_stock" class="block w-full p-4 text-sm font-black text-gray-900 border border-cream-200 rounded-2xl bg-gray-50 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-inner" placeholder="0" required>
                                                    </div>
                                                    
                                                    <div>
                                                        <label class="block mb-2 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Alasan Penyesuaian <span class="text-red-500">*</span></label>
                                                        <textarea name="notes" rows="3" class="block w-full p-4 text-xs font-medium text-gray-900 border border-cream-200 rounded-2xl bg-gray-50 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Contoh: Barang ditemukan rusak, selisih hitung gudang, dll" required></textarea>
                                                    </div>
                                                </div>
                                                
                                                <div class="p-6 bg-gray-50 dark:bg-gray-700/50 border-t dark:border-gray-700 flex gap-3">
                                                    <button type="submit" class="w-full py-4 text-sm font-black text-white bg-blue-600 rounded-2xl hover:bg-blue-700 shadow-lg shadow-blue-100 dark:shadow-none transition-all uppercase tracking-widest">Simpan Data</button>
                                                    <button type="button" data-modal-toggle="opname-modal-{{ $product->id }}" class="w-full py-4 text-sm font-black text-gray-500 bg-white border border-gray-200 rounded-2xl hover:bg-gray-100 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-200 transition-all uppercase tracking-widest">Batal</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-20 text-center text-gray-400 italic font-medium">Data barang tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="p-6 border-t border-gray-100 dark:border-gray-700">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
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