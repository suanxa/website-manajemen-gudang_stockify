@props(['icon' => null, 'routeName' => null, 'title' => null])

@php
    $isActive = request()->routeIs($routeName);
@endphp

<li>
    {{-- 1. Tambahkan class 'relative' agar titik terkunci di dalam menu ini --}}
    <a href="{{ route($routeName) }}"
        class="relative text-base rounded-lg flex items-center p-2 group transition duration-200 pl-11
        {{ $isActive 
            ? 'text-primary-600 bg-orange-100/50 dark:bg-primary-900/10 dark:text-primary-400 font-semibold shadow-sm shadow-orange-100/50' 
            : 'text-gray-700 hover:text-primary-600 hover:bg-cream-50 dark:text-gray-300 dark:hover:bg-gray-700' 
        }}">
        
        {{-- Indikator titik kecil saat aktif --}}
        @if($isActive)
            {{-- 2. Sekarang span ini akan mengikuti scroll karena parentnya 'relative' --}}
            <span class="absolute left-6 w-1.5 h-1.5 bg-primary-500 rounded-full shadow-[0_0_8px_rgba(249,115,22,0.8)]"></span>
        @endif

        {{ $title }}
    </a>
</li>