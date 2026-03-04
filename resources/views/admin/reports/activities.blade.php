@extends('layouts.app')

@section('content')
<div class="p-6 bg-cream-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    
    {{-- Header Section --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-[2rem] shadow-sm border border-cream-200 dark:border-gray-700 animate-fade-in-up">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-gray-900 dark:text-white uppercase">
                Log <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-600 to-orange-500">Aktivitas Tim</span>
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 italic font-medium">
                Rekaman jejak digital seluruh aktivitas pengelolaan stok barang Stockify.
            </p>
        </div>
        
        <div class="flex items-center gap-2">
            <div class="px-4 py-2 bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800 rounded-xl">
                <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest"><i class="fas fa-history mr-1.5"></i> Mode Audit Aktif</span>
            </div>
        </div>
    </div>

    {{-- FILTER BARU: Cari berdasarkan Aktor & Produk --}}
    <div class="mb-6 p-6 bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-cream-200 dark:border-gray-700 animate-fade-in">
        <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-end gap-4">
            
            {{-- Filter Nama Aktor (Dropdown) --}}
            <div class="w-full sm:w-64">
                <label class="block mb-1.5 text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Pilih Aktor (User)</label>
                <select name="user_id" class="block w-full p-2.5 text-sm font-bold border border-cream-200 rounded-xl bg-gray-50 focus:ring-2 focus:ring-amber-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Semua Anggota Tim</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ strtoupper($user->role) }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Cari Produk --}}
            <div class="w-full sm:w-64">
                <label class="block mb-1.5 text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Cari Nama Produk</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 group-focus-within:text-amber-500">
                        <i class="fas fa-search"></i>
                    </div>
                    <input type="text" name="search_product" value="{{ request('search_product') }}" 
                           placeholder="Nama produk..." 
                           class="block w-full p-2.5 pl-10 text-sm font-bold border border-cream-200 rounded-xl bg-gray-50 focus:ring-2 focus:ring-amber-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all">
                </div>
            </div>

            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-2.5 text-sm font-black text-white bg-amber-600 rounded-xl hover:bg-amber-700 transition-all uppercase tracking-widest shadow-md shadow-amber-100 dark:shadow-none active:scale-95">
                    <i class="fas fa-filter mr-2 text-xs"></i> Filter Log
                </button>
                
                @if(request('user_id') || request('search_product'))
                    <a href="{{ url()->current() }}" 
                       class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-2.5 text-[11px] font-black text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-red-50 hover:text-red-600 transition-all dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 uppercase tracking-widest active:scale-95">
                        <i class="fas fa-undo-alt mr-2"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="overflow-hidden bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-cream-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left table-fixed">
                <thead class="text-[11px] font-black uppercase tracking-widest text-gray-400 bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-5 w-[150px]">Stempel Waktu</th>
                        <th class="px-6 py-5 w-[180px]">Nama Aktor</th>
                        <th class="px-6 py-5 w-[130px] text-center">Jenis Aksi</th>
                        <th class="px-6 py-5 w-[300px]">Detail Perubahan Inventaris</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($activities as $log)
                        <tr class="group hover:bg-amber-50/20 dark:hover:bg-amber-900/10 transition-all duration-200">
                            {{-- Waktu --}}
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="text-gray-900 dark:text-white font-black text-xs uppercase">{{ $log->created_at->format('d M Y') }}</span>
                                    <span class="text-[10px] text-amber-600 font-bold tracking-tighter">{{ $log->created_at->format('H:i') }} WIB</span>
                                </div>
                            </td>

                            {{-- Nama Pengguna --}}
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-600 dark:text-slate-300 font-black text-[10px] shadow-sm group-hover:rotate-12 transition-transform">
                                        {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-black text-gray-800 dark:text-white truncate group-hover:text-blue-500 transition-colors">{{ $log->user->name }}</span>
                                </div>
                            </td>

                            {{-- Jenis Aksi (Type) --}}
                            <td class="px-6 py-5 text-center">
                                <span class="inline-flex items-center px-3 py-1 text-[9px] font-black rounded-full uppercase border {{ $log->type == 'Masuk' ? 'bg-green-50 text-green-700 border-green-200 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-50 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-400' }}">
                                    {{ $log->type }}
                                </span>
                            </td>

                            {{-- Detail Perubahan --}}
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <p class="text-xs text-gray-700 dark:text-gray-300 leading-relaxed">
                                        Mencatat <span class="font-black text-gray-900 dark:text-white underline decoration-amber-500 decoration-2">{{ $log->quantity }} Unit</span> 
                                        pada <span class="font-bold text-amber-600 uppercase">{{ $log->product->name ?? 'Produk Dihapus' }}</span>
                                    </p>
                                    @if($log->notes)
                                        <div class="mt-2 p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg border-l-2 border-amber-400">
                                            <span class="text-[10px] italic text-gray-500 dark:text-gray-400">"<i class="fas fa-quote-left mr-1 opacity-50"></i>{{ $log->notes }}"</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-20 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-fingerprint text-5xl text-gray-200 mb-4 dark:text-gray-700"></i>
                                    <span class="text-sm text-gray-500 dark:text-gray-400 italic font-medium">Log aktivitas tidak ditemukan untuk filter ini.</span>
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
        {{ $activities->appends(request()->query())->links() }}
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