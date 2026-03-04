@extends('layouts.app')

@section('content')
<div class="p-6 bg-cream-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    
    {{-- Header Section --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-[2rem] shadow-sm border border-cream-200 dark:border-gray-700 animate-fade-in-up">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-gray-900 dark:text-white uppercase">
                Recycle <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-500">Bin</span>
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 italic font-medium">
                Daftar produk yang telah dinonaktifkan. Anda bisa memulihkan atau menghapus permanen.
            </p>
        </div>
        
        <div>
            <a href="{{ route('admin.products.index') }}" class="group inline-flex items-center px-5 py-2.5 text-sm font-black text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 dark:shadow-none transition-all hover:scale-105 active:scale-95">
                <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar Produk
            </a>
        </div>
    </div>

    {{-- Alert Notifikasi --}}
    @if(session('success'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-2xl bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 flex items-center animate-fade-in">
            <i class="fas fa-check-circle mr-3 text-lg"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Table Section --}}
    <div class="overflow-hidden bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-cream-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left table-fixed">
                <thead class="text-[11px] font-black uppercase tracking-widest text-gray-400 bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-5 w-[250px]">Nama & SKU</th>
                        <th class="px-6 py-5 w-[150px]">Kategori</th>
                        <th class="px-6 py-5 w-[200px]">Riwayat Penghapusan</th>
                        <th class="px-6 py-5 w-[220px] text-center">Tindakan Pemulihan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($products as $product)
                        <tr class="group hover:bg-red-50/20 dark:hover:bg-red-900/10 transition-all duration-200">
                            <td class="px-6 py-5">
                                <div class="text-base font-black text-gray-800 dark:text-white group-hover:text-red-500 transition-colors duration-200">{{ $product->name }}</div>
                                <div class="text-[10px] font-bold text-gray-400 mt-0.5 uppercase tracking-tighter">SKU: {{ $product->sku }}</div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-black bg-gray-100 text-gray-700 rounded-lg dark:bg-gray-700 dark:text-gray-300 w-fit uppercase">
                                    <i class="fas fa-tag mr-1.5 text-[8px]"></i> {{ $product->category->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-red-500 uppercase tracking-tight">Dihapus Pada</span>
                                    <span class="text-xs font-bold text-gray-600 dark:text-gray-400">
                                        <i class="far fa-calendar-alt mr-1"></i> {{ $product->deleted_at->format('d M Y') }}
                                    </span>
                                    <span class="text-[10px] font-medium text-gray-400">
                                        <i class="far fa-clock mr-1 text-[9px]"></i> Pukul {{ $product->deleted_at->format('H:i') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex justify-center items-center gap-2">
                                    {{-- Tombol Restore --}}
                                    <form action="{{ route('admin.products.restore', $product->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 text-[10px] font-black text-emerald-700 bg-emerald-50 rounded-xl hover:bg-emerald-600 hover:text-white transition-all border border-emerald-100 dark:bg-emerald-900/20 dark:border-emerald-800 shadow-sm">
                                            <i class="fas fa-undo mr-1.5"></i> PULIHKAN
                                        </button>
                                    </form>

                                    {{-- Tombol Force Delete --}}
                                    <form action="{{ route('admin.products.force-delete', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus permanen? Data ini tidak bisa dikembalikan lagi!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-4 py-2 text-[10px] font-black text-red-700 bg-red-50 rounded-xl hover:bg-red-600 hover:text-white transition-all border border-red-100 dark:bg-red-900/20 dark:border-red-800 shadow-sm">
                                            <i class="fas fa-trash-alt mr-1.5"></i> HAPUS PERMANEN
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-trash text-2xl text-gray-300"></i>
                                    </div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400 italic font-medium">Recycle Bin kosong. Tidak ada sampah di sini.</span>
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