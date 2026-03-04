@extends('layouts.app')

@section('content')
{{-- 1. Definisikan Logika Dinamis di awal --}}
@php
    $role = auth()->user()->role;
    $isManager = str_contains(request()->path(), 'manager');
    $routePrefix = $isManager ? 'manager' : 'admin';
@endphp

<div class="p-6 bg-cream-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    
    {{-- Header Section --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-[2rem] shadow-sm border border-cream-200 dark:border-gray-700 animate-fade-in-up">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-gray-900 dark:text-white uppercase">
                Mitra <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Supplier</span>
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 italic font-medium">
                Daftar mitra pemasok barang untuk kelancaran inventaris gudang Stockify.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            {{-- Search Bar --}}
            <form action="{{ route($routePrefix . '.suppliers.index') }}" method="GET" class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full sm:w-64 p-2.5 pl-10 text-sm text-gray-900 border border-cream-200 rounded-xl bg-gray-50 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm" 
                    placeholder="Cari nama atau email...">
            </form>

            @if($role === 'admin')
                <button type="button" data-modal-target="add-supplier-modal" data-modal-toggle="add-supplier-modal" 
                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-black text-white bg-emerald-600 rounded-xl hover:bg-orange-500 shadow-lg shadow-emerald-200 dark:shadow-none transition-all hover:scale-105 active:scale-95">
                    <i class="fas fa-plus mr-2"></i>Tambah
                </button>
            @endif
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div id="alert-success" class="flex items-center p-4 mb-6 text-emerald-800 border-t-4 border-emerald-500 bg-emerald-50 dark:text-emerald-400 dark:bg-gray-800 animate-fade-in rounded-xl shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-3 text-lg"></i>
            <div class="text-sm font-bold">{{ session('success') }}</div>
            <button type="button" class="ms-auto bg-transparent text-emerald-500 rounded-lg p-1.5 hover:bg-emerald-100 inline-flex items-center justify-center h-8 w-8 dark:hover:bg-gray-700" data-dismiss-target="#alert-success">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- Table Section --}}
    <div class="overflow-hidden bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-cream-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left table-fixed">
                <thead class="text-[11px] font-black uppercase tracking-widest text-gray-400 bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-5 w-[60px] text-center">No</th>
                        <th class="px-6 py-5 w-[250px]">Informasi Supplier</th>
                        <th class="px-6 py-5 w-[200px]">Kontak Detail</th>
                        <th class="px-6 py-5 w-[250px]">Alamat Gudang</th>
                        <th class="px-6 py-5 w-[200px] text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($suppliers as $supplier)
                        <tr class="group hover:bg-emerald-50/20 dark:hover:bg-emerald-900/10 transition-all duration-200">
                            <td class="px-6 py-5 text-center font-bold text-gray-400">
                                {{ ($suppliers->currentPage() - 1) * $suppliers->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-black text-lg shadow-sm group-hover:rotate-3 transition-transform">
                                        {{ substr($supplier->name, 0, 1) }}
                                    </div>
                                    <span class="text-base font-black text-gray-800 dark:text-white group-hover:text-emerald-600 transition-colors uppercase">{{ $supplier->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col gap-1">
                                    <div class="text-[11px] font-bold text-gray-700 dark:text-gray-300 flex items-center">
                                        <i class="far fa-envelope mr-2 text-emerald-500 w-4"></i> {{ $supplier->email ?? '-' }}
                                    </div>
                                    <div class="text-[11px] font-bold text-gray-500 dark:text-gray-400 flex items-center">
                                        <i class="fas fa-phone-alt mr-2 text-emerald-500 w-4"></i> {{ $supplier->phone ?? '-' }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 leading-relaxed italic">
                                    {{ Str::limit($supplier->address, 60) }}
                                </p>
                            </td>
                            
                            <td class="px-6 py-5">
                                <div class="flex justify-center items-center gap-2">
                                    @if($role === 'admin')
                                        <button type="button" data-modal-target="edit-supplier-modal-{{ $supplier->id }}" data-modal-toggle="edit-supplier-modal-{{ $supplier->id }}" 
                                            class="inline-flex items-center px-4 py-2 text-[10px] font-black text-blue-700 bg-blue-50 rounded-xl hover:bg-blue-600 hover:text-white transition-all border border-blue-100 dark:bg-blue-900/20 dark:border-blue-800 shadow-sm">
                                            <i class="fas fa-edit mr-1.5"></i> EDIT
                                        </button>

                                        <button type="button" data-modal-target="delete-supplier-modal-{{ $supplier->id }}" data-modal-toggle="delete-supplier-modal-{{ $supplier->id }}" 
                                            class="inline-flex items-center px-4 py-2 text-[10px] font-black text-red-700 bg-red-50 rounded-xl hover:bg-red-600 hover:text-white transition-all border border-red-100 dark:bg-red-900/20 dark:border-red-800 shadow-sm">
                                            <i class="fas fa-trash-alt mr-1.5"></i> HAPUS
                                        </button>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 text-[10px] font-black text-gray-500 bg-gray-50 rounded-xl dark:bg-gray-700 dark:text-gray-400 uppercase tracking-widest border border-gray-100 dark:border-gray-600">
                                            <i class="fas fa-eye mr-1.5"></i> View Only
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- MODALS --}}
                        @if($role === 'admin')
                            @include('admin.suppliers.modals.edit')
                            @include('admin.suppliers.modals.delete')
                        @endif

                    @empty
                        <tr>
                            <td colspan="5" class="p-20 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-truck-loading text-5xl text-gray-200 mb-4 dark:text-gray-700"></i>
                                    <span class="text-sm text-gray-500 dark:text-gray-400 italic font-medium">Belum ada mitra supplier yang terdaftar.</span>
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
        {{ $suppliers->appends(['search' => request('search')])->links() }}
    </div>
</div>

{{-- MODAL TAMBAH --}}
@if($role === 'admin')
    @include('admin.suppliers.modals.create')
@endif

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