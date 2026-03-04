@extends('layouts.app')

@section('content')
{{-- 1. Deteksi Role & Prefix secara Dinamis --}}
@php
    $role = auth()->user()->role;
    $isStaff = ($role === 'staff');
    $routePrefix = $role; 
@endphp

<div class="p-6 bg-cream-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    
    {{-- Header Section --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-[2rem] shadow-sm border border-cream-200 dark:border-gray-700 animate-fade-in-up">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-gray-900 dark:text-white uppercase">
                {{ $isStaff ? 'Katalog' : 'Manajemen' }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">Produk</span>
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 italic font-medium">
                {{ $isStaff ? 'Pantau ketersediaan stok produk secara real-time.' : 'Kelola aset inventaris, variasi, dan harga Stockify.' }}
            </p>
        </div>
        
        <div class="flex items-center gap-3">
            @if($role === 'admin')
                <a href="{{ route('admin.products.trash') }}" class="group inline-flex items-center px-4 py-2.5 text-sm font-bold text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-all dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-red-900/30">
                    <i class="fas fa-trash-restore mr-2 group-hover:animate-bounce"></i> Recycle Bin
                </a>
            @endif

            @if(!$isStaff)
                <button type="button" data-modal-target="add-product-modal" data-modal-toggle="add-product-modal" class="inline-flex items-center px-5 py-2.5 text-sm font-black text-white bg-blue-500 rounded-xl hover:bg-orange-500 shadow-lg shadow-blue-200 dark:shadow-none transition-all hover:scale-105 active:scale-95">
                    <i class="fas fa-plus mr-2"></i>Tambah
                </button>
            @endif
        </div>
    </div>

{{-- Notifikasi --}}
@if(session('success'))
    <div class="p-4 mb-6 text-sm text-green-800 rounded-2xl bg-green-50 border border-green-200 flex items-center animate-fade-in">
        <i class="fas fa-check-circle mr-3 text-lg"></i> {{ session('success') }}
    </div>
@endif

{{-- GABUNGKAN SESSION ERROR DAN VALIDATION ERROR --}}
@if(session('error') || $errors->any())
    <div class="p-4 mb-6 text-sm text-red-800 rounded-2xl bg-red-50 border border-red-200 animate-fade-in">
        <div class="flex items-center mb-2">
            <i class="fas fa-exclamation-triangle mr-3 text-lg"></i>
            <span class="font-black uppercase tracking-tight">Opps! Ada Masalah:</span>
        </div>
        <ul class="list-disc pl-8 font-bold">
            {{-- Tampilkan error dari Session (Cek manual Recycle Bin) --}}
            @if(session('error'))
                <li>{{ session('error') }}</li>
            @endif
            
            {{-- Tampilkan error dari Validasi Laravel --}}
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    {{-- Search Section --}}
    <div class="mb-6">
        <form action="{{ route($routePrefix . '.products.index') }}" method="GET">
            <div class="relative group max-w-xl">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                    <i class="fas fa-search text-gray-400 group-focus-within:text-orange-500 transition-colors"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full p-3.5 pl-11 text-sm text-gray-900 border border-cream-200 rounded-2xl bg-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white shadow-sm" 
                    placeholder="Cari Nama Produk...">
            </div>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="overflow-hidden bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-cream-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left table-fixed"> {{-- Gunakan table-fixed agar width kolom ditaati --}}
                <thead class="text-[11px] font-black uppercase tracking-widest text-gray-400 bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-4 py-5 w-[50px] text-center">No</th> {{-- Kolom No diperkecil --}}
                        <th class="px-4 py-5 w-[80px] text-center">Visual</th>
                        <th class="px-6 py-5 w-[250px]">Nama & SKU</th> {{-- Kolom Nama diperbesar --}}
                        <th class="px-6 py-5 w-[150px]">Deskripsi</th>
                        <th class="px-6 py-5 w-[130px]">Kat / Sup</th>
                        <th class="px-6 py-5 w-[140px]">Harga Jual</th>
                        <th class="px-6 py-5 w-[80px] text-center">Stok</th>
                        @if(!$isStaff) 
                            <th class="px-4 py-5 w-[160px] text-center">Aksi</th> {{-- Kolom Aksi diperkecil --}}
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($products as $product)
                        <tr class="group hover:bg-orange-50/20 dark:hover:bg-orange-900/10 transition-all duration-200">
                            <td class="px-4 py-4 font-bold text-gray-400 text-center">{{ $loop->iteration }}</td>
                            <td class="px-4 py-4">
                                <div class="flex justify-center">
                                    @if($product->image)
                                        <img class="w-12 h-12 rounded-xl object-cover shadow-sm group-hover:scale-110 transition-transform duration-300 ring-2 ring-white dark:ring-gray-600" src="{{ asset('storage/' . $product->image) }}" alt="">
                                    @else
                                        <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 border-2 border-dashed border-gray-200 dark:border-gray-600">
                                            <i class="fas fa-image text-lg"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                {{-- Nama Produk: Hover Oranye --}}
                                <div class="text-base font-black text-gray-800 dark:text-white group-hover:text-orange-500 transition-colors duration-200 break-words">{{ $product->name }}</div>
                                <div class="text-[10px] font-bold text-gray-400 mt-0.5 tracking-tighter uppercase">SKU: {{ $product->sku }}</div>
                                
                                {{-- 2 Kolom Atribut Grid --}}
                                <div class="mt-2 grid grid-cols-2 gap-1 w-full">
                                    @foreach($product->attributes as $attr)
                                        <span class="px-1.5 py-0.5 text-[8px] font-black bg-orange-50 text-orange-400 rounded-md dark:bg-orange-900/30 dark:text-orange-400 border border-orange-100 dark:border-orange-800 uppercase truncate" title="{{ $attr->name }}: {{ $attr->value }}">
                                            {{ $attr->name }}: {{ $attr->value }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-gray-500 dark:text-gray-400 text-[10px] italic leading-tight" title="{{ $product->description }}">
                                    {{ Str::limit($product->description, 50) ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <span class="inline-flex items-center px-2 py-0.5 text-[9px] font-black bg-purple-50 text-purple-700 rounded-md dark:bg-purple-900/30 dark:text-purple-400 w-fit uppercase">
                                        {{ $product->category->name ?? 'N/A' }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 text-[9px] font-black bg-green-50 text-green-700 rounded-md dark:bg-green-900/30 dark:text-green-400 w-fit uppercase">
                                        {{ $product->supplier->name ?? 'N/A' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-black text-gray-900 dark:text-white text-xs">
                                Rp{{ number_format($product->selling_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center font-black">
                                <span class="text-sm {{ $product->current_stock <= $product->minimum_stock ? 'text-red-600' : 'text-gray-800 dark:text-white' }}">
                                    {{ $product->current_stock }}
                                </span>
                                @if($product->current_stock <= $product->minimum_stock)
                                    <span class="text-[7px] font-black text-red-500 block uppercase animate-pulse">Low</span>
                                @endif
                            </td>
                            @if(!$isStaff)
                                <td class="px-4 py-4">
                                    <div class="flex justify-center items-center gap-1.5">
                                        @if(in_array($role, ['admin', 'manager']))
                                            <button type="button" data-modal-target="edit-product-modal-{{ $product->id }}" data-modal-toggle="edit-product-modal-{{ $product->id }}" class="inline-flex items-center px-2.5 py-1.5 text-[9px] font-black text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-600 hover:text-white transition-all border border-blue-100 dark:bg-blue-900/20 dark:border-blue-800">
                                                <i class="fas fa-edit mr-1"></i> EDIT
                                            </button>
                                        @endif
                                        @if($role === 'admin')
                                            <button type="button" data-modal-target="delete-product-modal-{{ $product->id }}" data-modal-toggle="delete-product-modal-{{ $product->id }}" class="inline-flex items-center px-2.5 py-1.5 text-[9px] font-black text-red-700 bg-red-50 rounded-lg hover:bg-red-600 hover:text-white transition-all border border-red-100 dark:bg-red-900/20 dark:border-red-800">
                                                <i class="fas fa-trash-alt mr-1"></i> HAPUS
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                        {{-- MODALS --}}
                        @if(!$isStaff)
                            @if(in_array($role, ['admin', 'manager']))
                                @include('admin.products.modals.edit')
                            @endif
                            @if($role === 'admin')
                                @include('admin.products.modals.delete')
                            @endif
                        @endif
                    @empty
                        <tr>
                            <td colspan="8" class="p-20 text-center text-gray-400 italic">Belum ada data produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if(!$isStaff)
    @include('admin.products.modals.create')
@endif
@endsection

<script>
    {{-- Logika Tambah Field Atribut Tetap Sama (Tidak Berubah) --}}
    function addAttrField(containerId) {
        const container = document.getElementById(containerId);
        const newRow = document.createElement('div');
        newRow.className = 'flex gap-2 mb-2 animate-fade-in';

        let options = '<option value="">Pilih Atribut</option>';
        @foreach($attributes as $attr)
            options += `<option value="{{ $attr->name }}">{{ $attr->name }}</option>`;
        @endforeach

        newRow.innerHTML = `
            <select name="attr_names[]" class="w-1/2 p-2.5 text-sm border rounded-xl dark:bg-gray-700 dark:text-white dark:border-gray-600 border-gray-300">
                ${options}
            </select>
            <input type="text" name="attr_values[]" placeholder="Nilai" class="w-1/2 p-2.5 text-sm border rounded-xl dark:bg-gray-700 dark:text-white dark:border-gray-600 border-gray-300" required>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors font-bold text-xl">&times;</button>
        `;
        container.appendChild(newRow);
    }
    
    function addAttrFieldEdit(containerId) {
        const container = document.getElementById(containerId);
        const newRow = document.createElement('div');
        newRow.className = 'flex gap-2 mb-2 animate-fade-in';

        let options = '<option value="">Pilih Atribut</option>';
        @foreach($attributes as $attr)
            options += `<option value="{{ $attr->name }}">{{ $attr->name }}</option>`;
        @endforeach

        newRow.innerHTML = `
            <select name="attr_names[]" class="w-1/2 p-2.5 text-sm border rounded-xl dark:bg-gray-700 dark:text-white dark:border-gray-600 border-gray-300">
                ${options}
            </select>
            <input type="text" name="attr_values[]" placeholder="Nilai" class="w-1/2 p-2.5 text-sm border rounded-xl dark:bg-gray-700 dark:text-white dark:border-gray-600 border-gray-300" required>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors font-bold text-xl">&times;</button>
        `;
        container.appendChild(newRow);
    }
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