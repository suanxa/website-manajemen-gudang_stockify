@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="p-6 bg-cream-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    
    {{-- Header Dashboard --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div class="animate-fade-in-up">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white uppercase">
                Panel <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-orange-500">Kendali Utama</span>
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1 italic">
                Selamat datang kembali, Kapten <span class="font-bold text-orange-500 dark:text-orange-500">{{ auth()->user()->name }}</span>.
            </p>
        </div>
        <div class="flex items-center space-x-3 text-sm font-medium text-gray-500 bg-white dark:bg-gray-800 px-5 py-2.5 rounded-2xl shadow-sm border border-cream-200 dark:border-gray-700">
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
            </span>
            <span class="dark:text-gray-300">Analisis Waktu Nyata</span>
        </div>
    </div>

    {{-- Baris 1: Widget Statistik --}}
    <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-3">
        <div class="group p-6 bg-white border border-cream-200 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Total Produk</p>
                    <p class="text-3xl font-black text-gray-900 dark:text-white mt-1">{{ $totalProducts }}</p>
                </div>
                <div class="p-4 text-blue-600 bg-blue-50 rounded-2xl dark:text-blue-400 dark:bg-blue-900/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </div>
        </div>

        <div class="group p-6 bg-white border border-cream-200 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Masuk (30 Hari)</p>
                    <p class="text-3xl font-black text-green-600 mt-1">+{{ $totalIncoming }}</p>
                </div>
                <div class="p-4 text-green-600 bg-green-50 rounded-2xl dark:text-green-400 dark:bg-green-900/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                </div>
            </div>
        </div>

        <div class="group p-6 bg-white border border-cream-200 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Keluar (30 Hari)</p>
                    <p class="text-3xl font-black text-orange-600 mt-1">-{{ $totalOutgoing }}</p>
                </div>
                <div class="p-4 text-orange-600 bg-orange-50 rounded-2xl dark:text-orange-400 dark:bg-orange-900/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- BARIS BARU: GRAFIK TREN --}}
    <div class="mb-8 p-8 bg-white border border-cream-200 rounded-[2.5rem] shadow-sm dark:bg-gray-800 dark:border-gray-700 border-t-8 border-t-green-600 dark:border-t-green-600">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-black text-gray-800 dark:text-white uppercase tracking-tighter">Tren Arus Barang</h3>
                <p class="text-xs text-gray-400 dark:text-gray-500">Visualisasi masuk dan keluar barang 7 hari terakhir</p>
            </div>
            <div class="flex space-x-4 text-[10px] font-bold uppercase dark:text-white">
                <div class="flex items-center"><span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span> Masuk</div>
                <div class="flex items-center"><span class="w-3 h-3 bg-orange-500 rounded-full mr-2"></span> Keluar</div>
            </div>
        </div>
        <div class="h-[300px] w-full">
            <canvas id="stockTrendChart"></canvas>
        </div>
    </div>

    {{-- Baris Terakhir: Inventory & Activity --}}
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <div class="p-8 bg-white border border-cream-200 rounded-[2.5rem] shadow-sm dark:bg-gray-800 dark:border-gray-700 border-b-4 border-b-blue-500 dark:border-b-blue-500">
            <h3 class="text-lg font-black text-gray-800 dark:text-white mb-8 uppercase tracking-tighter">Produk dengan Stok Tertinggi</h3>
            <div class="space-y-7">
                @foreach($chartData as $product)
                <div class="group">
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-200">{{ $product->name }}</span>
                        <span class="text-[10px] font-black bg-blue-50 text-blue-700 px-3 py-1 rounded-full dark:bg-blue-900/50 dark:text-blue-300">{{ $product->current_stock }} UNIT</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3.5 dark:bg-gray-700">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-400 h-3.5 rounded-full" 
                             style="width: {{ min(100, ($product->current_stock / ($totalProducts > 0 ? $totalProducts * 1.5 : 100)) * 100) }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="p-8 bg-white border border-cream-200 rounded-[2.5rem] shadow-sm dark:bg-gray-800 dark:border-gray-700 border-b-4 border-b-orange-500 dark:border-b-orange-500">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-lg font-black text-gray-800 dark:text-white uppercase tracking-tighter">Log Aktivitas Terbaru</h3>
                <a href="{{ route('admin.reports.activities') }}" class="text-[11px] font-black text-blue-600 hover:text-orange-500 uppercase tracking-widest transition-colors">Lihat Semua</a>
            </div>
            <ul class="relative space-y-6 before:absolute before:left-6 before:top-2 before:bottom-0 before:w-0.5 before:bg-blue-100 dark:before:bg-gray-700">
                @forelse($recentActivities as $activity)
                <li class="relative pl-12 group">
                    <div class="absolute left-[1.15rem] top-1 w-3.5 h-3.5 bg-white border-2 {{ $activity->type == 'Masuk' ? 'border-green-500' : 'border-orange-500' }} rounded-full z-10 group-hover:scale-150 transition-transform duration-300 dark:bg-gray-800"></div>
                    <div class="p-4 bg-gray-50 border border-cream-100 rounded-2xl dark:bg-gray-700/50 dark:border-gray-600 shadow-sm group-hover:bg-white dark:group-hover:bg-gray-700 group-hover:shadow-md group-hover:-translate-x-1 transition-all duration-300">
                        <span class="text-[9px] text-gray-400 font-bold block mb-1 uppercase tracking-tighter">
                            {{ $activity->created_at->diffForHumans() }}
                        </span>
                        <p class="text-[11px] text-gray-600 dark:text-gray-300 leading-relaxed font-medium">
                            <span class="font-black text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                {{ $activity->user->name ?? 'System' }}
                            </span> 
                            memproses 
                            <b class="text-gray-800 dark:text-gray-100">{{ $activity->quantity }} unit</b> 
                            <span class="italic">{{ $activity->product->name ?? 'Produk Dihapus' }}</span>
                        </p>
                    </div>
                </li>
                @empty
                <p class="text-center text-xs text-gray-400">Belum ada aktivitas.</p>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('stockTrendChart').getContext('2d');
    const isDarkMode = document.documentElement.classList.contains('dark');
    const textColor = isDarkMode ? '#94a3b8' : '#64748b';

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!}, 
            datasets: [
                {
                    label: 'Barang Masuk',
                    data: {!! json_encode($incomingData) !!},
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    fill: true,
                    tension: 0.4,
                },
                {
                    label: 'Barang Keluar',
                    data: {!! json_encode($outgoingData) !!},
                    borderColor: '#ea580c',
                    backgroundColor: 'rgba(234, 88, 12, 0.1)',
                    fill: true,
                    tension: 0.4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { display: false },
                    ticks: { color: textColor } 
                },
                x: { 
                    grid: { display: false },
                    ticks: { color: textColor } 
                }
            }
        }
    });
</script>

<style>
    @keyframes fade-in-up {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fade-in-up 0.8s ease-out; }
</style>
@endsection