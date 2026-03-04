@extends('layouts.app')

@section('content')
<div class="p-4 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Galeri Visual Produk</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400">Analisis stok visual dan katalog produk.</p>
</div>

<div class="p-4 space-y-6">
    {{-- BAGIAN GRAFIK --}}
    <div class="p-6 bg-white border rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <h3 class="mb-4 text-lg font-bold dark:text-white">Sebaran Produk per Kategori</h3>
        <div class="h-64">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>

    {{-- BAGIAN GALERI --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        @foreach($products as $product)
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 overflow-hidden group">
            <div class="relative h-48 bg-gray-100 dark:bg-gray-700">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-110" alt="{{ $product->name }}">
                @else
                    <div class="flex items-center justify-center h-full text-gray-400 italic text-xs">No Image Available</div>
                @endif
                <div class="absolute top-2 right-2">
                    <span class="{{ $product->current_stock <= $product->minimum_stock ? 'bg-red-600' : 'bg-green-500' }} text-white text-[10px] font-bold px-2 py-1 rounded shadow-lg">
                        Stok: {{ $product->current_stock }}
                    </span>
                </div>
            </div>
            <div class="p-4">
                <div class="flex justify-between items-start mb-1">
                    <span class="text-[10px] font-medium text-blue-600 uppercase">{{ $product->category->name ?? 'Uncategorized' }}</span>
                    <span class="bg-purple-100 text-purple-800 text-[9px] font-semibold px-1.5 py-0.5 rounded dark:bg-purple-900 dark:text-purple-300">
                        <i class="fas fa-truck mr-1"></i> {{ $product->supplier->name ?? 'N/A' }}
                    </span>
                </div>
                
                <h5 class="text-sm font-bold text-gray-900 dark:text-white truncate" title="{{ $product->name }}">{{ $product->name }}</h5>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 line-clamp-2">{{ $product->description ?? 'Tidak ada deskripsi.' }}</p>
                
                <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <span class="text-sm font-bold text-gray-900 dark:text-white">Rp {{ number_format($product->selling_price) }}</span>
                    <span class="text-[10px] text-gray-400">SKU: {{ $product->sku }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('categoryChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($categories->pluck('name')) !!},
            datasets: [{
                label: 'Jumlah Produk',
                data: {!! json_encode($categories->pluck('products_count')) !!},
                backgroundColor: '#3b82f6',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { color: 'rgba(156, 163, 175, 0.1)' },
                    ticks: { stepSize: 1 } 
                },
                x: { grid: { display: false } }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endsection