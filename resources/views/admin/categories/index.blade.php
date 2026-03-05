@extends('layouts.app')

@section('content')
<div class="p-6 bg-cream-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    
    {{-- Header Section --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-[2rem] shadow-sm border border-cream-200 dark:border-gray-700 animate-fade-in-up">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-gray-900 dark:text-white uppercase">
                Master <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-600">Kategori</span>
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 italic font-medium">
                Kelola pengelompokan produk untuk mempermudah manajemen inventaris Stockify.
            </p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3">
            {{-- Search Bar terintegrasi di Header --}}
            <form action="{{ route('admin.products.categories.index') }}" method="GET" class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search text-gray-400 group-focus-within:text-purple-500 transition-colors"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full sm:w-64 p-2.5 pl-10 text-sm text-gray-900 border border-cream-200 rounded-xl bg-gray-50 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm" 
                    placeholder="Cari kategori...">
            </form>

            <button type="button" data-modal-target="add-category-modal" data-modal-toggle="add-category-modal" 
                class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-black text-white bg-purple-500 rounded-xl hover:bg-orange-500 shadow-lg shadow-purple-200 dark:shadow-none transition-all hover:scale-105 active:scale-95">
                <i class="fas fa-plus mr-2"></i>Tambah
            </button>
        </div>
    </div>

   {{-- Alert Notifikasi --}}
    @if(session('success'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-2xl bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 flex items-center animate-fade-in">
            <i class="fas fa-check-circle mr-3 text-lg"></i> {{ session('success') }}
        </div>
    @endif

    {{-- GACOR: Tambahkan Blok Validation Errors di Sini --}}
    @if($errors->any())
        <div class="p-4 mb-6 text-sm text-red-800 rounded-2xl bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 animate-fade-in">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-circle mr-3 text-lg"></i>
                <span class="font-black uppercase tracking-tight">Gagal Simpan Data:</span>
            </div>
            <ul class="list-disc pl-8 font-bold">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 mb-6 text-sm text-red-800 rounded-2xl bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 flex items-center animate-fade-in">
            <i class="fas fa-exclamation-triangle mr-3 text-lg"></i> {{ session('error') }}
        </div>
    @endif
    {{-- Table Section --}}
    <div class="overflow-hidden bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-cream-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left table-fixed">
                <thead class="text-[11px] font-black uppercase tracking-widest text-gray-400 bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-5 w-[60px] text-center">No</th>
                        <th class="px-6 py-5">Nama Kategori</th>
                        <th class="px-6 py-5 w-[200px]">Tanggal Pembuatan</th>
                        <th class="px-6 py-5 w-[200px] text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($categories as $category)
                        <tr class="group hover:bg-purple-50/20 dark:hover:bg-purple-900/10 transition-all duration-200">
                            <td class="px-6 py-5 text-center font-bold text-gray-400">{{ $loop->iteration }}</td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center text-purple-400 dark:text-purple-400 font-black text-lg shadow-sm">
                                        {{ substr($category->name, 0, 1) }}
                                    </div>
                                    <span class="text-base font-black text-gray-800 dark:text-white group-hover:text-purple-400 transition-colors duration-200 uppercase">{{ $category->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-gray-600 dark:text-gray-400 italic">
                                        <i class="far fa-calendar-alt mr-1.5"></i>{{ $category->created_at->format('d M Y') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex justify-center items-center gap-2">
                                    <button type="button" data-modal-target="edit-category-modal-{{ $category->id }}" data-modal-toggle="edit-category-modal-{{ $category->id }}" 
                                        class="inline-flex items-center px-4 py-2 text-[10px] font-black text-blue-700 bg-blue-50 rounded-xl hover:bg-blue-600 hover:text-white transition-all border border-blue-100 dark:bg-blue-900/20 dark:border-blue-800 shadow-sm">
                                        <i class="fas fa-edit mr-1.5"></i> EDIT
                                    </button>

                                    <button type="button" data-modal-target="delete-category-modal-{{ $category->id }}" data-modal-toggle="delete-category-modal-{{ $category->id }}" 
                                        class="inline-flex items-center px-4 py-2 text-[10px] font-black text-red-700 bg-red-50 rounded-xl hover:bg-red-600 hover:text-white transition-all border border-red-100 dark:bg-red-900/20 dark:border-red-800 shadow-sm">
                                        <i class="fas fa-trash-alt mr-1.5"></i> HAPUS
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- MODAL EDIT --}}
                        <div id="edit-category-modal-{{ $category->id }}" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-modal w-full items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0 md:h-full">
                            <div class="relative h-full w-full max-w-md p-4 md:h-auto">
                                <div class="relative rounded-[2rem] bg-white shadow-2xl dark:bg-gray-800 border border-cream-100 dark:border-gray-700">
                                    <form action="{{ route('admin.products.categories.update', $category->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="flex items-center justify-between p-6 border-b dark:border-gray-700">
                                            <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Edit Kategori</h3>
                                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="edit-category-modal-{{ $category->id }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="p-8">
                                            <label class="block mb-3 text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Nama Kategori Baru</label>
                                            <input type="text" name="name" value="{{ $category->name }}" class="block w-full p-4 text-gray-900 border border-cream-200 rounded-2xl bg-gray-50 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white font-bold" required>
                                        </div>
                                        <div class="flex items-center p-6 space-x-3 border-t dark:border-gray-700">
                                            <button type="submit" class="w-full py-4 text-sm font-black text-white bg-purple-600 rounded-2xl hover:bg-purple-700 shadow-lg shadow-purple-100 dark:shadow-none transition-all uppercase">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- MODAL HAPUS --}}
                        <div id="delete-category-modal-{{ $category->id }}" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-modal w-full items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0 md:h-full">
                            <div class="relative h-full w-full max-w-md p-4 md:h-auto">
                                <div class="relative rounded-[2rem] bg-white shadow-2xl dark:bg-gray-800 p-8 text-center border border-cream-100 dark:border-gray-700">
                                    <div class="w-20 h-20 bg-red-50 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <i class="fas fa-exclamation-triangle text-3xl text-red-500"></i>
                                    </div>
                                    <h3 class="mb-2 text-xl font-black text-gray-900 dark:text-white uppercase">Hapus Kategori?</h3>
                                    <p class="mb-8 text-sm text-gray-500 dark:text-gray-400 font-medium">Kategori <span class="text-red-500 font-black">"{{ $category->name }}"</span> akan dihapus. Pastikan tidak ada produk yang menggunakan kategori ini.</p>
                                    <div class="flex gap-3">
                                        <form action="{{ route('admin.products.categories.destroy', $category->id) }}" method="POST" class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full py-3 text-xs font-black text-white bg-red-600 rounded-xl hover:bg-red-700 transition-all uppercase shadow-lg shadow-red-100 dark:shadow-none">Ya, Hapus</button>
                                        </form>
                                        <button data-modal-toggle="delete-category-modal-{{ $category->id }}" type="button" class="w-full py-3 text-xs font-black text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 transition-all uppercase">Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="4" class="p-20 text-center text-gray-400 italic font-medium">Data kategori tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-6 px-4">
        {{ $categories->appends(['search' => request('search')])->links() }}
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div id="add-category-modal" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-modal w-full items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0 md:h-full">
    <div class="relative h-full w-full max-w-md p-4 md:h-auto">
        <div class="relative rounded-[2rem] bg-white shadow-2xl dark:bg-gray-800 border border-cream-100 dark:border-gray-700">
            <form action="{{ route('admin.products.categories.store') }}" method="POST">
                @csrf
                <div class="flex items-center justify-between p-6 border-b dark:border-gray-700">
                    <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Kategori Baru</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="add-category-modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-8">
                    <label class="block mb-3 text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Nama Kategori</label>
                    <input type="text" name="name" class="block w-full p-4 text-gray-900 border border-cream-200 rounded-2xl bg-gray-50 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white font-bold" placeholder="Contoh: ELEKTRONIK" required>
                </div>
                <div class="flex items-center p-6 space-x-3 border-t dark:border-gray-700">
                    <button type="submit" class="w-full py-4 text-sm font-black text-white bg-purple-600 rounded-2xl hover:bg-purple-700 shadow-lg shadow-purple-100 dark:shadow-none transition-all uppercase">Simpan Kategori</button>
                </div>
            </form>
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