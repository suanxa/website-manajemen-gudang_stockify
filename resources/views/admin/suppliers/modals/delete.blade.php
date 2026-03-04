<div id="delete-supplier-modal-{{ $supplier->id }}" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-modal w-full items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0 md:h-full">
    <div class="relative h-full w-full max-w-md p-4 md:h-auto">
        <div class="relative rounded-lg bg-white shadow dark:bg-gray-700">
            <div class="p-6 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mb-2 text-lg font-normal text-gray-500 dark:text-gray-400">Apakah kamu yakin ingin menghapus supplier <strong>{{ $supplier->name }}</strong>?</h3>
                <p class="mb-5 text-sm text-red-500">Catatan: Supplier tidak dapat dihapus jika masih memiliki produk yang terdaftar.</p>

                <div class="flex justify-center gap-3">
                    <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                            Ya, Hapus
                        </button>
                    </form>
                    <button data-modal-toggle="delete-supplier-modal-{{ $supplier->id }}" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>