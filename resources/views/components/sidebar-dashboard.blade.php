@php
    $page_slug = request()->segment(2);
@endphp

<aside id="sidebar"
    {{-- Ganti border-gray-200 menjadi border-cream-200 --}}
    class="fixed top-0 left-0 z-20 w-64 h-full pt-16 transition-transform duration-300 -translate-x-full bg-white border-r border-cream-200 dark:bg-gray-800 dark:border-gray-700 lg:translate-x-0"
    aria-label="Sidebar">

    <div class="h-full px-3 pb-4 overflow-y-auto">
        <ul class="space-y-2 font-medium">
            @auth
                @if(auth()->user()->role === 'admin')
                    @include('components.sidebar.admin-sidebar')
                @elseif(auth()->user()->role === 'manager')
                    @include('components.sidebar.manager-sidebar')
                @elseif(auth()->user()->role === 'staff')
                    @include('components.sidebar.staff-sidebar')
                @endif
            @endauth
        </ul>
    </div>
</aside>

{{-- Backdrop saat mobile --}}
{{-- <div class="fixed inset-0 z-10 hidden bg-orange-900/20 backdrop-blur-sm" id="sidebarBackdrop"></div> --}}