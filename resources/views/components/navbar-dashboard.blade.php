<nav class="fixed z-50 w-full bg-white border-b border-cream-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start">
                {{-- Hamburger Mobile --}}
                <button 
                    data-drawer-target="sidebar"
                    data-drawer-toggle="sidebar"
                    data-drawer-backdrop="true"
                    aria-controls="sidebar"
                    type="button"
                    class="relative !z-[60] p-2 text-gray-700 rounded-lg cursor-pointer lg:hidden hover:text-primary-600 hover:bg-cream-100 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </button>

                <a href="{{ url('/') }}" class="flex ml-2 md:mr-24">
                    @php
                        $settingName = \App\Models\Setting::where('key', 'app_name')->first();
                        $settingLogo = \App\Models\Setting::where('key', 'app_logo')->first();
                        $appName = ($settingName && $settingName->value) ? $settingName->value : 'Stockify';
                        $appLogo = ($settingLogo && $settingLogo->value) ? $settingLogo->value : null;
                        $routePrefix = Auth::user()->role == 'manager' ? 'manager' : (Auth::user()->role == 'staff' ? 'staff' : 'admin');
                    @endphp

                    @if($appLogo)
                        <img src="{{ asset('storage/' . $appLogo) }}" class="h-8 mr-3" alt="Logo" />
                    @else
                        <img src="{{ asset('static/images/logo.svg')}}" class="h-8 mr-3" alt="Default Logo" />
                    @endif
                    <span class="self-center text-xl font-bold sm:text-2xl whitespace-nowrap text-primary-600 dark:text-white uppercase tracking-wider">{{ $appName }}</span>
                </a>
            </div>

            <div class="flex items-center gap-2">
                {{-- Apps Dropdown Toggle --}}
                <button type="button" data-dropdown-toggle="apps-dropdown" class="p-2 text-gray-500 rounded-lg hover:text-primary-600 hover:bg-cream-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                </button>
                
                <div class="z-50 hidden max-w-sm my-4 overflow-hidden text-base list-none bg-white divide-y divide-cream-100 rounded shadow-lg dark:bg-gray-700 dark:divide-gray-600" id="apps-dropdown">
                    <div class="block px-4 py-2 text-base font-medium text-center text-gray-700 bg-cream-50 dark:bg-gray-700 dark:text-gray-400">
                        Menu Cepat
                    </div>
                    <div class="grid grid-cols-3 gap-4 p-4">
                        <a href="{{ route($routePrefix . '.products.gallery') }}" class="block p-4 text-center rounded-lg hover:bg-cream-50 dark:hover:bg-gray-600 group">
                            <svg class="mx-auto mb-1 text-primary-500 w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"></path><path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                            <div class="text-xs font-medium text-gray-900 dark:text-white">Produk</div>
                        </a>

                        @if(Auth::user()->role == 'admin')
                            <a href="{{ route('admin.stock.history') }}" class="block p-4 text-center rounded-lg hover:bg-cream-50 dark:hover:bg-gray-600">
                                <svg class="mx-auto mb-1 text-gray-500 w-7 h-7 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                                <div class="text-xs font-medium text-gray-900 dark:text-white">History</div>
                            </a>

                            <a href="{{ route('admin.suppliers.index') }}" class="block p-4 text-center rounded-lg hover:bg-cream-50 dark:hover:bg-gray-600">
                                <svg class="mx-auto mb-1 text-gray-500 w-7 h-7 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <div class="text-xs font-medium text-gray-900 dark:text-white">Suppliers</div>
                            </a>

                            <a href="{{ route('admin.reports.transactions') }}" class="block p-4 text-center rounded-lg hover:bg-cream-50 dark:hover:bg-gray-600">
                                <svg class="mx-auto mb-1 text-gray-500 w-7 h-7 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                                <div class="text-xs font-medium text-gray-900 dark:text-white">Reports</div>
                            </a>

                            <a href="{{ route('admin.users.index') }}" class="block p-4 text-center rounded-lg hover:bg-cream-50 dark:hover:bg-gray-600">
                                <svg class="mx-auto mb-1 text-gray-500 w-7 h-7 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
                                <div class="text-xs font-medium text-gray-900 dark:text-white">Users</div>
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="block p-4 text-center rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20">
                            @csrf
                            <button type="submit" class="w-full h-full">
                                <svg class="mx-auto mb-1 text-red-500 w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                                <div class="text-xs font-medium text-red-600">Sign Out</div>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Dark Mode Toggle --}}
                <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-cream-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-primary-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                </button>

                {{-- Notifications --}}
                @php
                    $lowStockCount = \App\Models\Product::whereColumn('current_stock', '<=', 'minimum_stock')->count();
                @endphp
                <button type="button" data-dropdown-toggle="notification-dropdown" class="relative p-2 text-gray-500 rounded-lg hover:text-primary-600 hover:bg-cream-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>
                    @if($lowStockCount > 0)
                        <div class="absolute inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-primary-500 border-2 border-white rounded-full -top-0 -right-0 dark:border-gray-900">{{ $lowStockCount }}</div>
                    @endif
                </button>
                <div class="z-50 hidden max-w-sm my-4 overflow-hidden text-base list-none bg-white divide-y divide-cream-100 rounded shadow-lg dark:divide-gray-600 dark:bg-gray-700" id="notification-dropdown">
                    <div class="block px-4 py-2 font-medium text-center text-gray-700 bg-cream-50 dark:bg-gray-700 dark:text-gray-400">Peringatan Stok</div>
                    <div class="py-2">
                        @if($lowStockCount > 0)
                            <a href="{{ route('admin.stock.settings') }}" class="flex px-4 py-3 hover:bg-cream-50 dark:hover:bg-gray-600">
                                <div class="text-gray-500 text-sm dark:text-gray-400">Ada <span class="font-semibold text-primary-600">{{ $lowStockCount }} produk</span> kritis!</div>
                            </a>
                        @else
                            <div class="px-4 py-3 text-sm text-gray-500 text-center italic">Semua stok aman.</div>
                        @endif
                    </div>
                </div>

                {{-- Profile Dropdown --}}
                <div class="flex items-center ml-3">
                    <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-primary-300 dark:focus:ring-gray-600" id="user-menu-button-2" data-dropdown-toggle="dropdown-2">
                        <img class="w-8 h-8 rounded-full border border-cream-200 dark:border-gray-500" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=FF7A00&color=fff" alt="user photo">
                    </button>
                    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-cream-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-2">
                        <div class="px-4 py-3">
                            <p class="text-sm text-gray-900 dark:text-white font-semibold">{{ Auth::user()->name }}</p>
                            <p class="text-xs font-bold text-primary-600 truncate uppercase">{{ Auth::user()->role }}</p>
                        </div>
                        <ul class="py-1">
                            <li><a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-cream-50 dark:text-gray-300 dark:hover:bg-gray-600">Dashboard</a></li>
                            @if(Auth::user()->role == 'admin')
                                <li><a href="{{ route('admin.settings') }}" class="block px-4 py-2 text-sm text-primary-600 hover:bg-cream-50 dark:text-primary-400 dark:hover:bg-gray-600 font-bold">App Settings</a></li>
                            @endif
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-gray-600">Sign out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('miniStockChart');
        if(ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Produk', 'Kritis'],
                    datasets: [{
                        data: [{{ \App\Models\Product::count() }}, {{ $lowStockCount }}],
                        backgroundColor: ['#ff7a00', '#ef4444'], // Warna Primary Orange & Red
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, display: false },
                        x: { grid: { display: false }, ticks: { color: '#9ca3af', font: { size: 10 } } }
                    }
                }
            });
        }
    });
</script>