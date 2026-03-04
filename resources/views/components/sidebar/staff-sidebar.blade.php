{{-- 1. DASHBOARD STAFF --}}
<x-sidebar-menu-dashboard routeName="staff.dashboard" title="Dashboard">
    <x-slot name="icon">
        <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path></svg>
    </x-slot>
</x-sidebar-menu-dashboard>

{{-- 2. CEK STOK BARANG --}}
<x-sidebar-menu-dashboard routeName="staff.products.index" title="Daftar Produk">
    <x-slot name="icon">
        <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path></svg>
    </x-slot>
</x-sidebar-menu-dashboard>

{{-- 3. OPERASIONAL GUDANG (DROPDOWN) --}}
<x-sidebar-menu-dropdown-dashboard routeName="staff.stock.*" title="Operasional Gudang">
    <x-slot name="icon">
        <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path></svg>
    </x-slot>
    <x-sidebar-menu-dropdown-item-dashboard routeName="staff.stock.index" title="Konfirmasi Barang"/>
    <x-sidebar-menu-dropdown-item-dashboard routeName="staff.stock.opname" title="Bantu Stock Opname"/>
</x-sidebar-menu-dropdown-dashboard>

{{-- 4. INFO TIPS (Custom Styled to Orange) --}}
<div class="pt-4 mt-4 border-t border-cream-200">
    <div class="p-4 rounded-lg bg-orange-50 dark:bg-orange-900/20 border border-orange-100 dark:border-orange-800" role="alert">
        <div class="flex items-center mb-2">
            <span class="bg-primary-100 text-primary-800 text-xs font-bold mr-2 px-2.5 py-0.5 rounded dark:bg-primary-900 dark:text-primary-300">
                INFO STAFF
            </span>
        </div>
        <p class="text-xs text-orange-800 dark:text-orange-300 leading-relaxed">
            Pastikan fisik barang sesuai dengan jumlah yang diinput ke sistem!
        </p>
    </div>
</div>