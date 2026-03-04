@extends('layouts.app')

@section('content')
<div class="p-6 bg-cream-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    
    {{-- Header Section --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-[2rem] shadow-sm border border-cream-200 dark:border-gray-700 animate-fade-in-up">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-gray-900 dark:text-white uppercase">
                Otomasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">Data Produk</span>
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 italic font-medium">
                Kelola ribuan data produk sekaligus menggunakan integrasi file Excel.
            </p>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-2xl bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 flex items-center animate-fade-in">
            <i class="fas fa-check-circle mr-3 text-lg"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 mb-6 text-sm text-red-800 rounded-2xl bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 animate-fade-in">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-circle mr-3 text-lg"></i>
                <span class="font-black uppercase tracking-tight">Terjadi Kesalahan:</span>
            </div>
            <ul class="list-disc pl-8 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-8 md:grid-cols-2 mb-8">
        {{-- BAGIAN EXPORT --}}
        <div class="group p-8 bg-white border border-cream-200 rounded-[2.5rem] shadow-sm hover:shadow-xl transition-all duration-300 dark:bg-gray-800 dark:border-gray-700 border-b-8 border-b-green-500 dark:border-b-green-500">
            <div class="flex items-center mb-6">
                <div class="p-4 bg-green-50 rounded-2xl dark:bg-green-900/30 mr-4 text-green-600 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </div>
                <h2 class="text-xl font-black text-gray-800 dark:text-white uppercase tracking-tighter">Export Data</h2>
            </div>
            <p class="mb-8 text-sm text-gray-500 dark:text-gray-400 leading-relaxed">Unduh seluruh inventaris Stockify ke dalam format <b>.xlsx (Excel)</b> untuk laporan fisik atau backup data.</p>
            
            <a href="{{ route('admin.products.export') }}" class="inline-flex items-center justify-center w-full py-4 text-sm font-black text-white bg-green-600 rounded-2xl hover:bg-green-700 shadow-lg shadow-green-100 dark:shadow-none transition-all uppercase tracking-widest">
                <i class="fas fa-file-excel mr-2 text-lg"></i> Download Excel
            </a>
        </div>

        {{-- BAGIAN IMPORT --}}
        <div class="group p-8 bg-white border border-cream-200 rounded-[2.5rem] shadow-sm hover:shadow-xl transition-all duration-300 dark:bg-gray-800 dark:border-gray-700 border-b-8 border-b-blue-500 dark:border-b-blue-500">
            <div class="flex items-center mb-6">
                <div class="p-4 bg-blue-50 rounded-2xl dark:bg-blue-900/30 mr-4 text-blue-600 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                </div>
                <h2 class="text-xl font-black text-gray-800 dark:text-white uppercase tracking-tighter">Import Data</h2>
            </div>
            
            <div class="flex items-center justify-between mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl border border-dashed border-gray-300 dark:border-gray-600">
                <span class="text-xs font-bold text-gray-500">Gunakan format standar:</span>
                <a href="{{ route('admin.products.template') }}" class="text-[10px] font-black text-blue-600 hover:text-orange-500 uppercase tracking-widest transition-colors">
                    Download Template <i class="fas fa-download ml-1"></i>
                </a>
            </div>

            <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <input class="block w-full text-xs text-gray-900 border border-cream-200 rounded-xl cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 p-2" type="file" name="file" required>
                    <p class="mt-2 text-[10px] text-gray-400 font-medium italic">*Pastikan ekstensi file adalah .xlsx atau .xls</p>
                </div>
                <button type="submit" class="w-full py-4 text-sm font-black text-white bg-blue-600 rounded-2xl hover:bg-blue-700 shadow-lg shadow-blue-100 dark:shadow-none transition-all uppercase tracking-widest">
                    <i class="fas fa-upload mr-2"></i> Proses Import
                </button>
            </form>
        </div>
    </div>

    {{-- INFORMASI TEMPLATE --}}
<div class="p-8 bg-orange-50 border-2 border-dashed border-orange-200 rounded-[2rem] dark:bg-gray-800 dark:border-orange-900/50 animate-pulse-slow">
        
        <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center text-white mr-4">
                <i class="fas fa-search-dollar"></i>
            </div>
            <h3 class="text-lg font-black text-orange-700 dark:text-orange-400 uppercase tracking-tight">Cari Referensi ID</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
            {{-- Search Kategori --}}
            <div class="bg-white/60 dark:bg-gray-900/40 p-5 rounded-[1.5rem] border border-orange-100 dark:border-gray-700">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-[10px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-widest">Master Kategori</h4>
                    <input type="text" id="searchCat" onkeyup="filterID('searchCat', 'listCat')" placeholder="Cari kategori..." class="text-[10px] p-1 px-3 border border-orange-200 rounded-full bg-white focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800">
                </div>
                <div id="listCat" class="space-y-1.5 max-h-40 overflow-y-auto pr-2 custom-scrollbar">
                    @foreach(\App\Models\Category::latest()->take(5)->get() as $cat)
                        <div class="flex justify-between items-center text-[11px] p-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-orange-50/50 searchable-item">
                            <span class="font-bold text-gray-700 dark:text-gray-300 item-name">{{ $cat->name }}</span>
                            <span class="font-black text-blue-600 bg-blue-50 dark:bg-blue-900/30 px-2 py-0.5 rounded-lg">ID: {{ $cat->id }}</span>
                        </div>
                    @endforeach
                    {{-- Item ini disembunyikan, muncul via JS kalau ada semua data --}}
                </div>
            </div>

            {{-- Search Supplier --}}
            <div class="bg-white/60 dark:bg-gray-900/40 p-5 rounded-[1.5rem] border border-orange-100 dark:border-gray-700">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">Master Supplier</h4>
                    <input type="text" id="searchSup" onkeyup="filterID('searchSup', 'listSup')" placeholder="Cari supplier..." class="text-[10px] p-1 px-3 border border-orange-200 rounded-full bg-white focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800">
                </div>
                <div id="listSup" class="space-y-1.5 max-h-40 overflow-y-auto pr-2 custom-scrollbar">
                    @foreach(\App\Models\Supplier::latest()->take(5)->get() as $sup)
                        <div class="flex justify-between items-center text-[11px] p-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-orange-50/50 searchable-item">
                            <span class="font-bold text-gray-700 dark:text-gray-300 item-name">{{ $sup->name }}</span>
                            <span class="font-black text-blue-600 bg-blue-50 dark:bg-blue-900/30 px-2 py-0.5 rounded-lg">ID: {{ $sup->id }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center text-white mr-4 ">
                <i class="fas fa-lightbulb"></i>
            </div>
            <h3 class="text-lg font-black text-orange-700 dark:text-orange-400 uppercase tracking-tight">Panduan Kolom Wajib</h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <div class="flex items-center text-xs font-bold text-orange-800 dark:text-orange-300 bg-white/50 dark:bg-gray-700 p-3 rounded-xl border border-orange-100 dark:border-orange-900/30">
                <i class="fas fa-tag mr-2 opacity-50"></i> name
            </div>
            <div class="flex items-center text-xs font-bold text-orange-800 dark:text-orange-300 bg-white/50 dark:bg-gray-700 p-3 rounded-xl border border-orange-100 dark:border-orange-900/30">
                <i class="fas fa-list mr-2 opacity-50"></i> category_id
            </div>
            <div class="flex items-center text-xs font-bold text-orange-800 dark:text-orange-300 bg-white/50 dark:bg-gray-700 p-3 rounded-xl border border-orange-100 dark:border-orange-900/30">
                <i class="fas fa-truck mr-2 opacity-50"></i> supplier_id
            </div>
            <div class="flex items-center text-xs font-bold text-orange-800 dark:text-orange-300 bg-white/50 dark:bg-gray-700 p-3 rounded-xl border border-orange-100 dark:border-orange-900/30">
                <i class="fas fa-barcode mr-2 opacity-50"></i> sku
            </div>
            <div class="flex items-center text-xs font-bold text-orange-800 dark:text-orange-300 bg-white/50 dark:bg-gray-700 p-3 rounded-xl border border-orange-100 dark:border-orange-900/30">
                <i class="fas fa-wallet mr-2 opacity-50"></i> purchase_price
            </div>
            <div class="flex items-center text-xs font-bold text-orange-800 dark:text-orange-300 bg-white/50 dark:bg-gray-700 p-3 rounded-xl border border-orange-100 dark:border-orange-900/30">
                <i class="fas fa-hand-holding-usd mr-2 opacity-50"></i> selling_price
            </div>
        </div>
        <p class="mt-6 text-[10px] font-black text-orange-600 dark:text-orange-500 uppercase tracking-widest text-center italic">
            *Pastikan ID Kategori dan ID Supplier sesuai dengan data yang ada di sistem
        </p>
    </div>
</div>

<script>
    function filterID(inputId, containerId) {
        let input = document.getElementById(inputId);
        let filter = input.value.toLowerCase();
        let container = document.getElementById(containerId);
        let items = container.getElementsByClassName('searchable-item');

        for (let i = 0; i < items.length; i++) {
            let name = items[i].querySelector(".item-name").innerText;
            if (name.toLowerCase().indexOf(filter) > -1) {
                items[i].style.display = "";
            } else {
                items[i].style.display = "none";
            }
        }
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
    @keyframes pulse-slow {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }
    .animate-pulse-slow { animation: pulse-slow 4s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
</style>
@endsection