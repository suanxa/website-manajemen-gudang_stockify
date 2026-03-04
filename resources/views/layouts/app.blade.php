<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        @php
            $settings = \App\Models\Setting::where('key', 'app_name')->first();
            $webName = $settings && $settings->value ? $settings->value : 'Stockify';
        @endphp
        <title>{{ $webName }}</title>
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            /* 1. LAYER MANAGEMENT (Z-INDEX) */
            nav.fixed {
                z-index: 60 !important;
            }
            [data-drawer-toggle="sidebar"], 
            [data-drawer-target="sidebar"] {
                z-index: 70 !important;
                position: relative !important;
            }
            #sidebar {
                z-index: 50 !important;
            }
            .bg-gray-900\/50, 
            .bg-gray-900\/80,
            [drawer-backdrop],
            [fixed-backdrop] {
                z-index: 40 !important;
            }

            /* 2. MODAL & DIALOG CUSTOMIZATION */
            [role="dialog"] {
                z-index: 80 !important; 
                padding-top: 80px !important;
                display: flex !important;
                align-items: flex-start !important;
                justify-content: center !important;
            }
            [role="dialog"] > div {
                max-height: calc(100vh - 100px) !important;
                overflow-y: auto !important;
                margin-bottom: 20px !important;
            }
            [role="dialog"]::-webkit-scrollbar,
            [role="dialog"] > div::-webkit-scrollbar {
                display: none !important;
            }
            [role="dialog"], [role="dialog"] > div {
                -ms-overflow-style: none !important;
                scrollbar-width: none !important;
            }
        </style>

        <script>
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body class="bg-cream-50 dark:bg-gray-900 font-sans antialiased">
        @include('components.navbar-dashboard')
        <div class="flex overflow-hidden bg-cream-50 dark:bg-gray-900">
            <x-sidebar-dashboard />
            <div id="main-content" class="relative w-full h-full overflow-y-auto bg-cream-50 lg:ml-64 dark:bg-gray-900 min-h-screen pt-20">
                <main class="p-4">
                    <div class="p-4 md:p-6 min-h-[calc(100vh-10rem)] border border-cream-200 rounded-3xl bg-white shadow-sm shadow-cream-200/50 dark:bg-gray-800 dark:border-gray-700 transition-all duration-300">
                        @yield('content')
                    </div>
                </main>
                @include('components.footer-dashboard')
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    </body>
</html>