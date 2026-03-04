{{-- 1. DASHBOARD --}}
<x-sidebar-menu-dashboard routeName="manager.dashboard" title="Dashboard">
    <x-slot name="icon">
        <svg fill="currentColor" viewBox="0 0 20 20"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path></svg>
    </x-slot>
</x-sidebar-menu-dashboard>

{{-- 2. MANAJEMEN PRODUK --}}
<x-sidebar-menu-dashboard routeName="manager.products.index" title="Manajemen Produk">
    <x-slot name="icon">
        <svg fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path></svg>
    </x-slot>
</x-sidebar-menu-dashboard>

{{-- 3. MANAJEMEN STOK --}}
<x-sidebar-menu-dropdown-dashboard routeName="manager.stock.*" title="Manajemen Stok">
    <x-slot name="icon">
        <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path></svg>
    </x-slot>
    <x-sidebar-menu-dropdown-item-dashboard routeName="manager.stock.index" title="Riwayat & Transaksi"/>
    <x-sidebar-menu-dropdown-item-dashboard routeName="manager.stock.opname" title="Penyesuaian Stok"/>
</x-sidebar-menu-dropdown-dashboard>

{{-- 4. DAFTAR SUPPLIER --}}
<x-sidebar-menu-dashboard routeName="manager.suppliers.index" title="Daftar Supplier">
    <x-slot name="icon">
        <svg fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 015.25-2.906z"></path></svg>
    </x-slot>
</x-sidebar-menu-dashboard>

{{-- 5. LAPORAN --}}
<x-sidebar-menu-dropdown-dashboard routeName="manager.reports.*" title="Laporan">
    <x-slot name="icon">
        <svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>
    </x-slot>
    <x-sidebar-menu-dropdown-item-dashboard routeName="manager.reports.stock" title="Laporan Stok"/>
    <x-sidebar-menu-dropdown-item-dashboard routeName="manager.reports.transactions" title="Barang Masuk/Keluar"/>
</x-sidebar-menu-dropdown-dashboard>