@extends('layouts.app')

@section('content')
<div class="p-6 bg-cream-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    
    {{-- Header Section --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-[2rem] shadow-sm border border-cream-200 dark:border-gray-700 animate-fade-in-up">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-gray-900 dark:text-white uppercase">
                Master <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-600">Atribut</span>
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 italic font-medium">
                Kelola variabel variasi produk (Contoh: Warna, Ukuran, Bahan) untuk detail inventaris.
            </p>
        </div>
        
        <div>
            <button type="button" data-modal-target="add-attribute-modal" data-modal-toggle="add-attribute-modal" 
                class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-black text-white bg-blue-500 rounded-xl hover:bg-orange-600 shadow-lg shadow-orange-200 dark:shadow-none transition-all hover:scale-105 active:scale-95">
                <i class="fas fa-plus mr-2"></i> Tambah Master Atribut
            </button>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div id="alert-success" class="flex items-center p-4 mb-6 text-orange-800 border-t-4 border-orange-500 bg-orange-50 dark:text-orange-400 dark:bg-gray-800 animate-fade-in rounded-xl shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-3 text-lg"></i>
            <div class="text-sm font-bold">{{ session('success') }}</div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-orange-50 text-orange-500 rounded-lg p-1.5 hover:bg-orange-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-orange-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-success">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div id="alert-error" class="flex items-center p-4 mb-6 text-red-800 border-t-4 border-red-500 bg-red-50 dark:text-red-400 dark:bg-gray-800 animate-fade-in rounded-xl shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle mr-3 text-lg"></i>
            <div class="text-sm font-bold">{{ session('error') }}</div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-error">
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
                        <th class="px-6 py-5">Nama Atribut</th>
                        <th class="px-6 py-5 w-[200px]">Tanggal Dibuat</th>
                        <th class="px-6 py-5 w-[200px] text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($attributes as $attribute)
                        <tr class="group hover:bg-orange-50/20 dark:hover:bg-orange-900/10 transition-all duration-200">
                            <td class="px-6 py-5 text-center font-bold text-gray-400">{{ $loop->iteration }}</td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-orange-100 dark:bg-orange-900/40 flex items-center justify-center text-orange-600 dark:text-orange-400 font-black text-lg shadow-sm">
                                        {{ substr($attribute->name, 0, 1) }}
                                    </div>
                                    <span class="text-base font-black text-gray-800 dark:text-white group-hover:text-orange-500 transition-colors duration-200 uppercase">{{ $attribute->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-xs font-bold text-gray-600 dark:text-gray-400 italic">
                                    <i class="far fa-calendar-alt mr-1.5 text-orange-400"></i>{{ $attribute->created_at->format('d M Y') }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex justify-center items-center">
                                    <button type="button" data-modal-target="delete-attribute-modal-{{ $attribute->id }}" data-modal-toggle="delete-attribute-modal-{{ $attribute->id }}" 
                                        class="inline-flex items-center px-4 py-2 text-[10px] font-black text-red-700 bg-red-50 rounded-xl hover:bg-red-600 hover:text-white transition-all border border-red-100 dark:bg-red-900/20 dark:border-red-800 shadow-sm">
                                        <i class="fas fa-trash-alt mr-1.5"></i> HAPUS
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- MODAL HAPUS --}}
                        <div id="delete-attribute-modal-{{ $attribute->id }}" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-modal w-full items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0 md:h-full">
                            <div class="relative h-full w-full max-w-md p-4 md:h-auto">
                                <div class="relative rounded-[2rem] bg-white shadow-2xl dark:bg-gray-800 p-8 text-center border border-cream-100 dark:border-gray-700">
                                    <div class="w-20 h-20 bg-red-50 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <i class="fas fa-exclamation-triangle text-3xl text-red-500 animate-pulse"></i>
                                    </div>
                                    <h3 class="mb-2 text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Hapus Atribut?</h3>
                                    <p class="mb-8 text-sm text-gray-500 dark:text-gray-400 font-medium">Atribut <span class="text-orange-500 font-black">"{{ $attribute->name }}"</span> akan dihapus permanen dari sistem.</p>
                                    <div class="flex gap-3">
                                        <form action="{{ route('admin.products.attributes.destroy', $attribute->id) }}" method="POST" class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full py-3 text-xs font-black text-white bg-red-600 rounded-xl hover:bg-red-700 transition-all uppercase shadow-lg shadow-red-100 dark:shadow-none">Ya, Hapus</button>
                                        </form>
                                        <button data-modal-toggle="delete-attribute-modal-{{ $attribute->id }}" type="button" class="w-full py-3 text-xs font-black text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 transition-all uppercase">Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="4" class="p-20 text-center text-gray-400 italic font-medium">Belum ada master atribut yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div id="add-attribute-modal" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-modal w-full items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0 md:h-full">
    <div class="relative h-full w-full max-w-md p-4 md:h-auto">
        <div class="relative rounded-[2rem] bg-white shadow-2xl dark:bg-gray-800 border border-cream-100 dark:border-gray-700">
            <form action="{{ route('admin.products.attributes.store') }}" method="POST">
                @csrf
                <div class="flex items-center justify-between p-6 border-b dark:border-gray-700">
                    <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Tambah <span class="text-orange-500">Atribut</span></h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="add-attribute-modal">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                <div class="p-8">
                    <label class="block mb-3 text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Nama Atribut</label>
                    <input type="text" name="name" class="block w-full p-4 text-gray-900 border border-cream-200 rounded-2xl bg-gray-50 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white font-bold placeholder-gray-400 shadow-inner" placeholder="Misal: WARNA, UKURAN, MATERIAL" required>
                </div>
                <div class="flex items-center p-6 space-x-3 border-t dark:border-gray-700">
                    <button type="submit" class="w-full py-4 text-sm font-black text-white bg-orange-500 rounded-2xl hover:bg-orange-600 shadow-lg shadow-orange-100 dark:shadow-none transition-all uppercase tracking-widest">Simpan Atribut</button>
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