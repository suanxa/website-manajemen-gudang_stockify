@extends('layouts.app')

@section('content')
{{-- 1. Definisikan Logika Dinamis di Atas --}}
@php
    $role = auth()->user()->role;
    $isStaff = ($role === 'staff'); 
    $isManager = str_contains(request()->path(), 'manager');
    $routePrefix = $role; 
@endphp

<div class="p-6 bg-cream-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    
    {{-- Header Section --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-[2rem] shadow-sm border border-cream-200 dark:border-gray-700 animate-fade-in-up">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-gray-900 dark:text-white uppercase">
                {{ $isStaff ? 'Konfirmasi' : 'Arus' }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">Transaksi Stok</span>
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 italic font-medium">
                {{ $isStaff 
                    ? 'Daftar transaksi masuk dan keluar yang perlu dikonfirmasi oleh tim gudang.' 
                    : 'Pantau pergerakan barang dan konfirmasi status transaksi secara real-time.' 
                }}
            </p>
        </div>
        
        <div class="flex items-center gap-3">
            @if(in_array($role, ['admin', 'manager']))
            <button type="button" data-modal-target="add-transaction-modal" data-modal-toggle="add-transaction-modal" class="inline-flex items-center px-5 py-2.5 text-sm font-black text-white bg-blue-500 rounded-xl hover:bg-orange-500 shadow-lg shadow-blue-200 dark:shadow-none transition-all hover:scale-105 active:scale-95">
                <i class="fas fa-exchange-alt mr-2"></i> Input Transaksi
            </button>
            @endif
        </div>
    </div>

    {{-- Alert Section --}}
    @if(session('success'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-2xl bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 flex items-center animate-fade-in">
            <i class="fas fa-check-circle mr-3 text-lg"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Main Table --}}
    <div class="overflow-hidden bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-cream-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-5 text-[11px] font-black uppercase tracking-widest text-gray-400">Info Waktu</th>
                    <th class="px-6 py-5 text-[11px] font-black uppercase tracking-widest text-gray-400">Detail Produk</th>
                    <th class="px-6 py-5 text-[11px] font-black uppercase tracking-widest text-gray-400">Tipe & Jumlah</th>
                    <th class="px-6 py-5 text-[11px] font-black uppercase tracking-widest text-gray-400">Status</th>
                    <th class="px-6 py-5 text-[11px] font-black uppercase tracking-widest text-gray-400">Oleh</th>
                    <th class="px-6 py-5 text-[11px] font-black uppercase tracking-widest text-gray-400">Catatan</th> 
                    <th class="px-6 py-5 text-[11px] font-black uppercase tracking-widest text-gray-400 text-center">Tindakan</th>
                </tr>
            </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($transactions as $trx)
                        <tr class="group hover:bg-orange-50/30 dark:hover:bg-orange-900/10 transition-all duration-200">
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="text-gray-900 dark:text-white font-black text-sm uppercase">{{ \Carbon\Carbon::parse($trx->date)->format('d M Y') }}</span>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $trx->created_at->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="text-base font-black text-gray-800 dark:text-white group-hover:text-orange-400 transition-colors">{{ $trx->product->name ?? 'Produk Dihapus' }}</span>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">SKU: {{ $trx->product->sku ?? '-' }}</span>
                                        <span class="text-[9px] font-black bg-green-50 text-green-700 px-2 py-0.5 rounded-md dark:bg-green-900/30 dark:text-green-400 uppercase">
                                            <i class="fas fa-truck mr-1"></i> {{ $trx->product->supplier->name ?? 'Tanpa Supplier' }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col gap-1">
                                    @if($trx->type === 'Masuk')
                                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-black bg-green-50 text-green-700 rounded-lg dark:bg-green-900/30 dark:text-green-400 w-fit uppercase">Barang Masuk</span>
                                        <span class="text-lg font-black text-green-600">+{{ $trx->quantity }} <small class="text-[10px] text-gray-400">UNIT</small></span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-black bg-red-50 text-red-700 rounded-lg dark:bg-red-900/30 dark:text-red-400 w-fit uppercase">Barang Keluar</span>
                                        <span class="text-lg font-black text-red-600">-{{ $trx->quantity }} <small class="text-[10px] text-gray-400">UNIT</small></span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                @if($trx->status === 'Pending')
                                    <span class="inline-flex items-center px-3 py-1 text-[10px] font-black text-orange-600 bg-orange-50 border border-orange-200 rounded-full dark:bg-orange-900/30 dark:text-orange-400 uppercase animate-pulse">
                                        <i class="fas fa-clock mr-1.5"></i> Pending
                                    </span>
                                @elseif($trx->status === 'Diterima' || $trx->status === 'Dikeluarkan')
                                    <span class="inline-flex items-center px-3 py-1 text-[10px] font-black text-emerald-600 bg-emerald-50 border border-emerald-200 rounded-full dark:bg-emerald-900/30 dark:text-emerald-400 uppercase">
                                        <i class="fas fa-check-circle mr-1.5"></i> Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 text-[10px] font-black text-red-600 bg-red-50 border border-red-200 rounded-full uppercase">
                                        {{ $trx->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-300 font-black text-xs">
                                        {{ substr($trx->user->name ?? 'S', 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-black text-gray-800 dark:text-white leading-none">{{ $trx->user->name ?? 'System' }}</span>
                                        <span class="text-[9px] font-bold text-gray-400 uppercase">{{ $trx->user->role ?? '' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="max-w-[150px] break-words text-[11px] text-gray-500 dark:text-gray-400 italic leading-tight" title="{{ $trx->notes }}">
                                    {{ $trx->notes ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex justify-center">
                                    @if($trx->status === 'Pending' && in_array($role, ['admin', 'staff']))
                                        <div class="flex gap-2">
                                            <form action="{{ route($routePrefix . '.stock.confirm', $trx->id) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="status" value="{{ $trx->type === 'Masuk' ? 'Diterima' : 'Dikeluarkan' }}">
                                                <button type="submit" class="inline-flex items-center px-4 py-2 text-[10px] font-black text-white bg-green-600 rounded-xl hover:bg-green-700 transition-all shadow-md shadow-green-100 dark:shadow-none uppercase tracking-widest">
                                                    <i class="fas fa-check mr-1.5"></i> Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route($routePrefix . '.stock.confirm', $trx->id) }}" method="POST" class="inline" onsubmit="return confirm('Tolak transaksi ini?')">
                                                @csrf
                                                <input type="hidden" name="status" value="Ditolak">
                                                <button type="submit" class="inline-flex items-center px-4 py-2 text-[10px] font-black text-red-600 bg-red-50 rounded-xl hover:bg-red-600 hover:text-white transition-all border border-red-100 dark:bg-red-900/20 dark:border-red-800 uppercase tracking-widest">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($trx->status === 'Pending')
                                        <span class="text-[10px] font-black text-orange-500 uppercase italic opacity-70">Menunggu Staff</span>
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gray-50 dark:bg-gray-700 flex items-center justify-center text-gray-300" title="Transaksi Terkunci">
                                            <i class="fas fa-lock text-xs"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-20 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-exchange-alt text-5xl text-gray-200 mb-4 dark:text-gray-700"></i>
                                    <span class="text-sm text-gray-500 dark:text-gray-400 italic">Belum ada riwayat transaksi ditemukan.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-6 px-4">
        {{ $transactions->links() }}
    </div>
</div>

{{-- MODAL TAMBAH TRANSAKSI --}}
@if(in_array($role, ['admin', 'manager']))
<div id="add-transaction-modal" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-modal w-full items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0 md:h-full transition-all duration-300">
    <div class="relative h-full w-full max-w-md p-4 md:h-auto">
        <div class="relative rounded-[2.5rem] bg-white shadow-2xl dark:bg-gray-800 border border-cream-100 dark:border-gray-700">
            <form action="{{ route($routePrefix . '.stock.store') }}" method="POST">
                @csrf
                <div class="flex items-center justify-between p-6 border-b dark:border-gray-700">
                    <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Input <span class="text-orange-400">Transaksi</span></h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="add-transaction-modal">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                
                <div class="p-8 space-y-5">
                    <div>
                        <label class="block mb-2 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Pilih Produk</label>
                        <select id="product_id_select" name="product_id" class="block w-full p-3.5 text-sm font-bold text-gray-900 border border-cream-200 rounded-2xl bg-gray-50 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">
                                    [{{ $product->supplier->name ?? 'N/A' }}] {{ $product->name }} (Sisa: {{ $product->current_stock }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Tipe</label>
                            <select id="type_select" name="type" class="block w-full p-3.5 text-sm font-bold text-gray-900 border border-cream-200 rounded-2xl bg-gray-50 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                <option value="Masuk">Masuk (+)</option>
                                <option value="Keluar">Keluar (-)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Jumlah</label>
                            <input type="number" name="quantity" min="1" class="block w-full p-3.5 text-sm font-black text-gray-900 border border-cream-200 rounded-2xl bg-gray-50 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="0" required>
                        </div>
                    </div>
                    <div>
                        <label class="block mb-2 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Tanggal</label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" class="block w-full p-3.5 text-sm font-bold text-gray-900 border border-cream-200 rounded-2xl bg-gray-50 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    </div>
                    <div>
                        <label class="block mb-2 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Catatan</label>
                        <textarea name="notes" rows="2" class="block w-full p-3.5 text-xs font-medium text-gray-900 border border-cream-200 rounded-2xl bg-gray-50 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Keterangan transaksi..."></textarea>
                    </div>
                </div>

                <div class="flex items-center p-6 space-x-3 border-t dark:border-gray-700">
                    <button type="submit" class="w-full py-4 text-sm font-black text-white bg-blue-600 rounded-2xl hover:bg-blue-700 shadow-lg shadow-blue-100 dark:shadow-none transition-all uppercase tracking-widest">Proses</button>
                    <button type="button" data-modal-toggle="add-transaction-modal" class="w-full py-4 text-sm font-black text-gray-500 bg-gray-100 rounded-2xl hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 transition-all uppercase tracking-widest">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
    {{-- Script untuk auto-open modal tetap dipertahankan --}}
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const openModal = urlParams.get('open_modal');
        const productId = urlParams.get('product_id');

        if (openModal === '1') {
            if (productId) {
                const selectProduct = document.getElementById('product_id_select');
                if (selectProduct) selectProduct.value = productId;
                const selectType = document.getElementById('type_select');
                if (selectType) selectType.value = 'Masuk';
            }
            const triggerBtn = document.querySelector('[data-modal-toggle="add-transaction-modal"]');
            if (triggerBtn) {
                setTimeout(() => {
                    triggerBtn.click();
                    const newUrl = window.location.pathname;
                    window.history.replaceState({}, document.title, newUrl);
                }, 300);
            }
        }
    });
</script>

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