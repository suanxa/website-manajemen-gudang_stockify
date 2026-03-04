@extends('layouts.app')

@section('content')
<div class="p-6 bg-cream-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    
    {{-- Header Dashboard: Elegant & Focus --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div class="animate-fade-in-up">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white uppercase">
                Dashboard <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-orange-500">Manajer Gudang</span>
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1 italic">
                Selamat datang kembali, <span class="font-bold text-orange-500 dark:text-orange-500">{{ auth()->user()->name }}</span>! Pantau pergerakan stok hari ini.
            </p>
        </div>
        <div class="flex items-center space-x-3 text-sm font-medium text-gray-500 bg-white dark:bg-gray-800 px-5 py-2.5 rounded-2xl shadow-sm border border-cream-200 dark:border-gray-700">
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
            </span>
            <span>Update Gudang Terkini</span>
        </div>
    </div>

    {{-- Widget Ringkasan Utama: High Impact --}}
    <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-3">
        {{-- Stok Menipis (Urgency Card) --}}
        <div class="group p-6 bg-white border border-cream-200 rounded-[2rem] shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 dark:bg-gray-800 dark:border-gray-700 border-l-8 border-l-red-500 dark:border-l-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Stok Menipis</p>
                    <p class="text-4xl font-black text-red-600 dark:text-red-500 mt-1">{{ $lowStockCount }}</p>
                </div>
                <div class="p-4 text-red-600 bg-red-50 rounded-2xl group-hover:animate-pulse transition-transform dark:text-red-400 dark:bg-red-900/20 shadow-lg shadow-red-100 dark:shadow-none">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
            <p class="mt-4 text-[10px] font-black text-red-500 uppercase tracking-tighter">
                <i class="fas fa-exclamation-triangle mr-1"></i> Segera Lakukan Restock
            </p>
        </div>

        {{-- Barang Masuk Hari Ini --}}
        <div class="group p-6 bg-white border border-cream-200 rounded-[2rem] shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 dark:bg-gray-800 dark:border-gray-700 border-l-8 border-l-green-500 dark:border-l-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Inflow Hari Ini</p>
                    <p class="text-4xl font-black text-green-600 dark:text-green-400 mt-1">+{{ $incomingToday }}</p>
                </div>
                <div class="p-4 text-green-600 bg-green-50 rounded-2xl group-hover:scale-110 transition-transform dark:text-green-400 dark:bg-green-900/20 shadow-lg shadow-green-100 dark:shadow-none">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                </div>
            </div>
            <p class="mt-4 text-[10px] font-black text-green-600 uppercase tracking-tighter">
                <i class="fas fa-check-circle mr-1"></i> Unit Berhasil Diterima
            </p>
        </div>

        {{-- Barang Keluar Hari Ini --}}
        <div class="group p-6 bg-white border border-cream-200 rounded-[2rem] shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 dark:bg-gray-800 dark:border-gray-700 border-l-8 border-l-blue-500 dark:border-l-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Outflow Hari Ini</p>
                    <p class="text-4xl font-black text-blue-600 dark:text-blue-400 mt-1">-{{ $outgoingToday }}</p>
                </div>
                <div class="p-4 text-blue-600 bg-blue-50 rounded-2xl group-hover:rotate-12 transition-transform dark:text-blue-400 dark:bg-blue-900/20 shadow-lg shadow-blue-100 dark:shadow-none">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </div>
            </div>
            <p class="mt-4 text-[10px] font-black text-blue-600 uppercase tracking-tighter">
                <i class="fas fa-shipping-fast mr-1"></i> Unit Berhasil Terkirim
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- List Barang Stok Menipis (Modern Table) --}}
        <div class="p-8 bg-white border border-cream-200 rounded-[2.5rem] shadow-sm dark:bg-gray-800 dark:border-gray-700 border-b-4 border-b-red-500 dark:border-b-red-500">
            <div class="flex items-center justify-between mb-8 px-2">
                <h3 class="text-lg font-black text-gray-800 dark:text-white uppercase tracking-tighter flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3"></i> Daftar Stok Kritis
                </h3>
                <a href="{{ route('manager.reports.stock') }}" class="text-[11px] font-black text-blue-600 hover:text-red-500 uppercase tracking-widest transition-colors">
                    Audit Stok <i class="fas fa-chevron-right ml-1"></i>
                </a>
            </div>
            <div class="overflow-hidden rounded-2xl border border-gray-100 dark:border-gray-700">
                <table class="w-full text-sm text-left">
                    <thead class="text-[10px] font-black text-gray-400 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-6 py-4">Nama Produk</th>
                            <th class="px-6 py-4 text-center">Sisa Stok</th>
                            <th class="px-6 py-4 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($lowStockProducts as $product)
                        <tr class="bg-white dark:bg-gray-800 hover:bg-red-50/30 dark:hover:bg-red-900/10 transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-700 dark:text-gray-200">{{ $product->name }}</td>
                            <td class="px-6 py-4 text-center font-black text-red-600">{{ $product->current_stock }}</td>
                            <td class="px-6 py-4 text-right">
                                <span class="px-3 py-1 bg-red-100 text-red-700 text-[10px] font-black rounded-full dark:bg-red-900/40 dark:text-red-400 uppercase">Kritis</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-400 italic text-xs">Semua stok di gudang aman terkendali.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Aktivitas Stok Terbaru (Timeline Style) --}}
        <div class="p-8 bg-white border border-cream-200 rounded-[2.5rem] shadow-sm dark:bg-gray-800 dark:border-gray-700 border-b-4 border-b-blue-500 dark:border-b-blue-500">
            <div class="flex items-center justify-between mb-8 px-2">
                <h3 class="text-lg font-black text-gray-800 dark:text-white uppercase tracking-tighter flex items-center">
                    <i class="fas fa-history text-blue-500 mr-3"></i> Aktivitas Transaksi
                </h3>
                {{-- LINK LIHAT SEMUA --}}
                <a href="{{ url('/manager/reports/transactions') }}" class="text-[11px] font-black text-blue-600 hover:text-red-500 uppercase tracking-widest transition-colors">
                    Lihat Semua <i class="fas fa-chevron-right ml-2 text-[8px]"></i>
                </a>
            </div>
            
            <div class="flow-root">
                <ul class="relative space-y-6 before:absolute before:left-6 before:top-2 before:bottom-0 before:w-0.5 before:bg-gradient-to-b before:from-blue-200 before:to-transparent dark:before:from-gray-700">
                    @forelse($recentTransactions as $transaction)
                    {{-- DOT TIMELINE & CONTENT --}}
                    <li class="relative pl-12 group">
                        {{-- Dot Timeline --}}
                        <div class="absolute left-[1.15rem] top-1 w-3.5 h-3.5 bg-white border-2 {{ $transaction->type == 'Masuk' ? 'border-green-500' : 'border-blue-500' }} rounded-full z-10 group-hover:scale-150 transition-transform duration-300 dark:bg-gray-800 shadow-sm"></div>
                        
                        <div class="p-4 bg-gray-50 border border-cream-100 rounded-2xl group-hover:bg-white dark:bg-gray-700/30 dark:border-gray-600 group-hover:shadow-lg dark:group-hover:bg-gray-700 group-hover:-translate-x-1 transition-all duration-300">
                            <div class="flex justify-between items-start mb-2">
                                {{-- Nama Produk: dark:text-white --}}
                                <span class="text-xs font-black text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                    {{ $transaction->product->name ?? 'Produk Dihapus' }}
                                </span>
                                <span class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">
                                    {{ $transaction->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-relaxed font-medium">
                                <span class="px-2 py-0.5 rounded-md {{ $transaction->type == 'Masuk' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400' }} font-black mr-2 uppercase text-[9px] tracking-tighter">
                                    {{ $transaction->type }}
                                </span>
                                <span class="font-black text-gray-800 dark:text-gray-200">
                                    {{ $transaction->quantity }} unit
                                </span> 
                                oleh 
                                <span class="font-bold text-gray-700 dark:text-gray-300">
                                    {{ $transaction->user->name }}
                                </span>
                            </p>
                        </div>
                    </li>
                    @empty
                    <div class="text-center py-10 opacity-60">
                        <i class="fas fa-clipboard-list text-3xl text-gray-300 mb-3 block"></i>
                        <p class="text-xs text-gray-400 font-bold uppercase italic">Belum ada transaksi hari ini.</p>
                    </div>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in-up {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fade-in-up 0.8s cubic-bezier(0.16, 1, 0.3, 1); }

    /* Custom Scrollbar */
    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .dark ::-webkit-scrollbar-thumb { background: #475569; }
</style>
@endsection