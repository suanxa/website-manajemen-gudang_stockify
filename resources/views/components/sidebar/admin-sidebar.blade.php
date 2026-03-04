{{-- 1. DASHBOARD --}}
<x-sidebar-menu-dashboard routeName="admin.dashboard" title="Dashboard">
    <x-slot name="icon">
        <svg class="w-6 h-6 transition duration-75" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path></svg>
    </x-slot>
</x-sidebar-menu-dashboard>

{{-- 2. PRODUK --}}
<x-sidebar-menu-dropdown-dashboard routeName="admin.products.*" title="Produk">
    <x-slot name="icon">
        <svg class="w-6 h-6 transition duration-75" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path></svg>
    </x-slot>
    <x-sidebar-menu-dropdown-item-dashboard routeName="admin.products.index" title="Manajemen Produk"/>
    <x-sidebar-menu-dropdown-item-dashboard routeName="admin.products.categories.index" title="Kategori Produk"/>
    <x-sidebar-menu-dropdown-item-dashboard routeName="admin.products.attributes.index" title="Atribut Produk"/>
    <x-sidebar-menu-dropdown-item-dashboard routeName="admin.products.import-export" title="Import/Export Data"/>
</x-sidebar-menu-dropdown-dashboard>

{{-- 3. STOK --}}
<x-sidebar-menu-dropdown-dashboard routeName="admin.stock.*" title="Stok">
    <x-slot name="icon">
        <svg class="w-6 h-6 transition duration-75" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path></svg>
    </x-slot>
    <x-sidebar-menu-dropdown-item-dashboard routeName="admin.stock.history" title="Transaksi"/>
    <x-sidebar-menu-dropdown-item-dashboard routeName="admin.stock.opname" title="Stock Opname"/>
    <x-sidebar-menu-dropdown-item-dashboard routeName="admin.stock.settings" title="Pengaturan Stok Min."/>
</x-sidebar-menu-dropdown-dashboard>

{{-- 4. SUPPLIER --}}
<x-sidebar-menu-dashboard routeName="admin.suppliers.index" title="Supplier">
    <x-slot name="icon">
        <svg class="w-6 h-6 transition duration-75" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 015.25-2.906z"></path></svg>
    </x-slot>
</x-sidebar-menu-dashboard>

{{-- 5. PENGGUNA --}}
<x-sidebar-menu-dashboard routeName="admin.users.index" title="Pengguna">
    <x-slot name="icon">
        <svg class="w-6 h-6 transition duration-75" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
    </x-slot>
</x-sidebar-menu-dashboard>

{{-- 6. LAPORAN --}}
<x-sidebar-menu-dropdown-dashboard routeName="admin.reports.*" title="Laporan">
    <x-slot name="icon">
        <svg class="w-6 h-6 transition duration-75" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 11a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4zM10 11a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4zM16 5a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 01-2 2h-2a2 2 0 01-2-2V5z"></path></svg>
    </x-slot>
    <x-sidebar-menu-dropdown-item-dashboard routeName="admin.reports.stock" title="Laporan Stok"/>
    <x-sidebar-menu-dropdown-item-dashboard routeName="admin.reports.transactions" title="Laporan Transaksi"/>
    <x-sidebar-menu-dropdown-item-dashboard routeName="admin.reports.activities" title="Aktivitas Pengguna"/>
</x-sidebar-menu-dropdown-dashboard>

{{-- 7. PENGATURAN --}}
<x-sidebar-menu-dashboard routeName="admin.settings" title="Pengaturan">
    <x-slot name="icon">
        <svg class="w-6 h-6 transition duration-75" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg>
    </x-slot>
</x-sidebar-menu-dashboard>