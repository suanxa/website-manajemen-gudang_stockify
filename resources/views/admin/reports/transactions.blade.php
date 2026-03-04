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
                Rekapitulasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">Transaksi</span>
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 italic font-medium">
                Pantau detail arus masuk dan keluar barang berdasarkan periode dan supplier.
            </p>
        </div>

        <a href="{{ route($routePrefix . '.reports.transactions.export', request()->query()) }}" 
           class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-black text-white bg-green-600 rounded-xl hover:bg-green-700 shadow-lg shadow-green-200 dark:shadow-none transition-all hover:scale-105 active:scale-95">
            <i class="fas fa-file-excel mr-2"></i> Export Excel
        </a>
    </div>

    {{-- Filter Card --}}
    <div class="mb-6 p-6 bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-cream-200 dark:border-gray-700">
        <form action="{{ route($routePrefix . '.reports.transactions') }}" method="GET" class="space-y-4">
            {{-- Baris Input --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block mb-1.5 text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" 
                        class="block w-full p-2.5 text-sm font-bold border border-cream-200 rounded-xl bg-gray-50 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div>
                    <label class="block mb-1.5 text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" 
                        class="block w-full p-2.5 text-sm font-bold border border-cream-200 rounded-xl bg-gray-50 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div>
                    <label class="block mb-1.5 text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Supplier</label>
                    <select name="supplier_id" class="block w-full p-2.5 text-sm font-bold border border-cream-200 rounded-xl bg-gray-50 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white uppercase">
                        <option value="">Semua Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-1.5 text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tipe Arus</label>
                    <select name="type" class="block w-full p-2.5 text-sm font-bold border border-cream-200 rounded-xl bg-gray-50 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Semua Tipe</option>
                        <option value="Masuk" {{ request('type') == 'Masuk' ? 'selected' : '' }}>Masuk (+)</option>
                        <option value="Keluar" {{ request('type') == 'Keluar' ? 'selected' : '' }}>Keluar (-)</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-gray-50 dark:border-gray-700">
                <div class="flex gap-2 w-full sm:w-auto">
                    <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-2.5 text-sm font-black text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all uppercase tracking-widest shadow-md shadow-blue-100 dark:shadow-none active:scale-95">
                        <i class="fas fa-filter mr-2 text-xs"></i> Tampilkan
                    </button>
                    
                    <a href="{{ route($routePrefix . '.reports.transactions') }}" 
                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-2.5 text-[11px] font-black text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-red-50 hover:text-red-600 hover:border-red-100 transition-all dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-red-900/30 uppercase tracking-widest active:scale-95">
                        <i class="fas fa-undo-alt mr-2"></i> Reset
                    </a>
                </div>
                <span class="text-[10px] font-bold text-gray-400 italic hidden md:block">*Menampilkan riwayat transaksi lengkap.</span>
            </div>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="overflow-hidden bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-cream-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left table-fixed">
                <thead class="text-[11px] font-black uppercase tracking-widest text-gray-400 bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-5 w-[140px]">Waktu</th>
                        <th class="px-6 py-5 w-[200px]">Produk & SKU</th>
                        <th class="px-6 py-5 w-[130px] text-center">Tipe Arus</th>
                        <th class="px-6 py-5 w-[100px] text-center">Jumlah</th>
                        <th class="px-6 py-5 w-[150px] text-center">Status Transaksi</th> {{-- KOLOM STATUS BARU --}}
                        <th class="px-6 py-5 w-[150px]">Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($transactions as $trx)
                    <tr class="group hover:bg-blue-50/20 dark:hover:bg-blue-900/10 transition-all duration-200">
                        <td class="px-6 py-4">
                            <div class="text-sm font-black text-gray-900 dark:text-white uppercase">{{ $trx->date->format('d M Y') }}</div>
                            <div class="text-[10px] text-gray-400 font-bold tracking-tighter uppercase">{{ $trx->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-base font-black text-gray-800 dark:text-white group-hover:text-orange-400 transition-colors truncate" title="{{ $trx->product->name ?? '' }}">
                                {{ $trx->product->name ?? 'Produk Dihapus' }}
                            </div>
                            @if($trx->product)
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">SKU: {{ $trx->product->sku }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="{{ $trx->type == 'Masuk' ? 'bg-green-100 text-green-700 border-green-200 dark:bg-green-900/40 dark:text-green-300' : 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/40 dark:text-red-300' }} text-[9px] font-black px-2.5 py-1 rounded-full uppercase border">
                                {{ $trx->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center font-black">
                            <div class="text-base {{ $trx->type == 'Masuk' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $trx->type == 'Masuk' ? '+' : '-' }}{{ number_format($trx->quantity) }}
                            </div>
                        </td>
                        {{-- STATUS COLUMN GACOR --}}
                        <td class="px-6 py-4 text-center">
                            @if($trx->status == 'Pending')
                                <span class="bg-amber-100 text-amber-700 border border-amber-200 text-[10px] font-black px-3 py-1.5 rounded-xl uppercase tracking-widest animate-pulse">
                                    <i class="fas fa-clock mr-1"></i> Pending
                                </span>
                            @elseif($trx->status == 'Diterima' || $trx->status == 'Dikeluarkan')
                                <span class="bg-emerald-100 text-emerald-700 border border-emerald-200 text-[10px] font-black px-3 py-1.5 rounded-xl uppercase tracking-widest">
                                    <i class="fas fa-check-circle mr-1"></i> Selesai
                                </span>
                            @elseif($trx->status == 'Ditolak')
                                <span class="bg-rose-100 text-rose-700 border border-rose-200 text-[10px] font-black px-3 py-1.5 rounded-xl uppercase tracking-widest">
                                    <i class="fas fa-times-circle mr-1"></i> Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-[11px] font-bold text-gray-500 dark:text-gray-400">
                             <div class="flex items-center gap-2">
                                <i class="fas fa-user-circle text-gray-300 text-sm"></i>
                                <span>{{ $trx->user->name ?? 'System' }}</span>
                             </div>
                             <span class="text-[9px] uppercase tracking-widest text-gray-400 ml-5">{{ $trx->user->role ?? '' }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-20 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-history text-5xl text-gray-200 mb-4 dark:text-gray-700"></i>
                                <span class="text-sm text-gray-500 dark:text-gray-400 italic font-medium">Tidak ada aktivitas transaksi pada periode ini.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination Section --}}
    <div class="mt-6 px-4">
        {{ $transactions->appends(request()->query())->links() }}
    </div>
</div>

<style>
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fade-in-up 0.5s ease-out; }
</style>
@endsection