@extends('layouts.app')

@section('content')
<div class="p-4 bg-cream-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    
    {{-- Header Dashboard: Lebih Modern dengan Gradient Text --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                Pusat <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-orange-500">Kendali Staff</span>
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Selamat bekerja, <span class="font-bold text-orange-500 dark:text-orange-500">{{ auth()->user()->name }}</span>. Ada tugas baru menunggu?
            </p>
        </div>
        <div class="flex items-center space-x-2 text-sm text-gray-400 bg-white dark:bg-gray-800 px-4 py-2 rounded-2xl shadow-sm border border-cream-200 dark:border-gray-700">
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
            </span>
            <span>Sistem Online</span>
        </div>
    </div>

    {{-- Notifikasi Flash: Dibuat lebih Floating & Clean --}}
    @if(session('success'))
        <div id="alert-success" class="flex items-center p-4 mb-6 text-green-800 border-l-4 border-green-500 rounded-r-xl bg-white shadow-md dark:bg-gray-800 dark:text-green-400 animate-fade-in-down" role="alert">
            <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            </div>
            <div class="ml-4 text-sm font-bold">{{ session('success') }}</div>
        </div>
    @endif

    {{-- Widget Statistik: Lebih Stylish dengan Hover Effect --}}
    <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-3">
        <div class="group relative p-6 bg-white border border-cream-200 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Total Produk</p>
                    <p class="text-3xl font-black text-gray-800 dark:text-white mt-1">{{ $totalProducts }}</p>
                </div>
                <div class="p-4 text-primary-600 bg-primary-50 rounded-2xl group-hover:scale-110 transition-transform dark:text-primary-400 dark:bg-primary-900/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-gray-400">
                <span class="text-green-500 font-bold mr-1">Active</span> di gudang utama
            </div>
        </div>

        <div class="group relative p-6 bg-white border border-cream-200 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Stok Kritis</p>
                    <p class="text-3xl font-black text-red-600 dark:text-red-500 mt-1">{{ $lowStockCount }}</p>
                </div>
                <div class="p-4 text-red-600 bg-red-50 rounded-2xl group-hover:animate-pulse transition-transform dark:text-red-400 dark:bg-red-900/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-red-500 font-bold">
                Butuh perhatian segera
            </div>
        </div>
    </div>

    {{-- Daftar Tugas: Menggunakan Layout Card yang lebih Terpisah & Bersih --}}
    <div class="grid grid-cols-1 gap-8 xl:grid-cols-2">
        
        {{-- Barang Masuk Section --}}
        <div class="flex flex-col">
            <div class="flex items-center justify-between mb-4 px-2">
                <h3 class="text-lg font-black text-gray-800 dark:text-white flex items-center uppercase tracking-tighter">
                    <span class="w-8 h-8 bg-green-500 text-white rounded-lg flex items-center justify-center mr-3 shadow-lg shadow-green-200 dark:shadow-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    </span>
                    Antrean Barang Masuk
                </h3>
                <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full dark:bg-green-900/30 dark:text-green-400">
                    {{ count($pendingIncoming) }} Tugas
                </span>
            </div>

            <div class="space-y-4">
                @forelse($pendingIncoming as $item)
                    <div class="group p-5 bg-white border border-cream-200 rounded-3xl shadow-sm hover:shadow-md transition-all dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center">
                                <div class="relative">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($item->product->name) }}&background=E8F5E9&color=2E7D32" class="w-12 h-12 rounded-2xl border-2 border-white dark:border-gray-700 shadow-sm" alt="">
                                    <div class="absolute -bottom-1 -right-1 bg-green-500 border-2 border-white dark:border-gray-800 w-5 h-5 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"></path></svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-primary-600 transition-colors">{{ $item->product->name }}</h4>
                                    <p class="text-[11px] text-gray-400 flex items-center mt-0.5">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $item->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xl font-black text-gray-800 dark:text-white">{{ $item->quantity }}</span>
                                <span class="text-xs font-bold text-gray-400 block uppercase">Unit</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            {{-- Tombol Tolak --}}
                            <form action="{{ route('staff.stock.confirm', $item->id) }}" method="POST" onsubmit="return confirm('Tolak barang masuk ini?')">
                                @csrf
                                <input type="hidden" name="status" value="Ditolak">
                                <button type="submit" class="inline-flex items-center px-4 py-2.5 text-xs font-bold text-red-600 bg-white border border-red-200 rounded-xl hover:bg-red-50 transition-all">
                                    Tolak
                                </button>
                            </form>

                            {{-- Tombol Terima --}}
                            <form action="{{ route('staff.stock.confirm', $item->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="Diterima">
                                <button type="submit" class="inline-flex items-center px-6 py-2.5 text-xs font-black text-white bg-green-600 rounded-xl hover:bg-green-700 shadow-lg shadow-green-100 dark:shadow-none hover:scale-105 transition-all">
                                    Terima Barang
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center p-12 bg-white/50 border-2 border-dashed border-cream-200 rounded-3xl dark:bg-gray-800/50 dark:border-gray-700">
                        <div class="p-4 bg-cream-100 dark:bg-gray-700 rounded-full mb-4">
                            <svg class="w-8 h-8 text-cream-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="text-sm text-gray-500 font-medium italic text-center">Kerja bagus! Antrean barang masuk kosong.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Barang Keluar Section --}}
        <div class="flex flex-col">
            <div class="flex items-center justify-between mb-4 px-2">
                <h3 class="text-lg font-black text-gray-800 dark:text-white flex items-center uppercase tracking-tighter">
                    <span class="w-8 h-8 bg-blue-500 text-white rounded-lg flex items-center justify-center mr-3 shadow-lg shadow-blue-200 dark:shadow-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    </span>
                    Tugas Barang Keluar
                </h3>
                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full dark:bg-blue-900/30 dark:text-blue-400">
                    {{ count($pendingOutgoing) }} Tugas
                </span>
            </div>

            <div class="space-y-4">
                @forelse($pendingOutgoing as $item)
                    <div class="group p-5 bg-white border border-cream-200 rounded-3xl shadow-sm hover:shadow-md transition-all dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center">
                                <div class="relative">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($item->product->name) }}&background=E3F2FD&color=1565C0" class="w-12 h-12 rounded-2xl border-2 border-white dark:border-gray-700 shadow-sm" alt="">
                                    <div class="absolute -bottom-1 -right-1 bg-blue-500 border-2 border-white dark:border-gray-800 w-5 h-5 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"></path></svg>
                                    </div>
                                </div>
                                <div class="ml-4 text-left">
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors">{{ $item->product->name }}</h4>
                                    <p class="text-[11px] text-gray-400 line-clamp-1 mt-0.5">Catatan: {{ $item->notes ?? 'Tanpa catatan' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xl font-black text-gray-800 dark:text-white">{{ $item->quantity }}</span>
                                <span class="text-xs font-bold text-gray-400 block uppercase">Unit</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-cream-100 dark:border-gray-700">
                             <span class="text-[10px] font-bold px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-500 rounded-lg uppercase">Siap Kirim</span>
                            <form action="{{ route('staff.stock.confirm', $item->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="Dikeluarkan">
                                <button type="submit" class="inline-flex items-center px-6 py-2.5 text-xs font-black text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-100 dark:shadow-none hover:scale-105 transition-all">
                                    Konfirmasi Kirim
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center p-12 bg-white/50 border-2 border-dashed border-cream-200 rounded-3xl dark:bg-gray-800/50 dark:border-gray-700">
                        <div class="p-4 bg-cream-100 dark:bg-gray-700 rounded-full mb-4">
                            <svg class="w-8 h-8 text-cream-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                        </div>
                        <p class="text-sm text-gray-500 font-medium italic text-center">Semua barang keluar sudah diproses.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<style>
    @keyframes fade-in-down {
        0% { opacity: 0; transform: translateY(-10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down { animation: fade-in-down 0.5s ease-out; }
</style>
@endsection