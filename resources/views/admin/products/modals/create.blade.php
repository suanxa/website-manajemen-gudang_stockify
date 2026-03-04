<div id="add-product-modal" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-modal w-full items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0 md:h-full">
    <div class="relative h-full w-full max-w-2xl p-4 md:h-auto">
        <div class="relative rounded-lg bg-white shadow dark:bg-gray-700">

            @php
                $isManager = str_contains(request()->path(), 'manager');
                $actionUrl = $isManager ? route('manager.products.store') : route('admin.products.store');
            @endphp

            <form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                {{-- Modal Header --}}
                <div class="flex items-start justify-between rounded-t border-b p-5 dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-plus-circle mr-2 text-blue-600"></i>Tambah Produk Baru
                    </h3>
                    <button type="button" class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200" data-modal-toggle="add-product-modal">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>

                {{-- CEK PESAN ERROR --}}
                @if ($errors->any())
                <div class="p-4 mx-6 mt-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                    <div class="flex items-center mb-2">
                        <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <span class="font-bold">Terjadi kesalahan input:</span>
                    </div>
                    <ul class="pl-5 mt-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                {{-- Modal Body --}}
                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                    
                    {{-- Grid 1: Nama & SKU --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Produk <span class="text-red-500">*</span></label>
                            <input type="text" name="name" placeholder="Contoh: Laptop Asus ROG" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">SKU (Kode Unik) <span class="text-red-500">*</span></label>
                            <input type="text" name="sku" placeholder="Contoh: ELK-001" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                        </div>
                    </div>

                    {{-- Grid 2: Kategori & Supplier --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori <span class="text-red-500">*</span></label>
                            <select name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:text-white" required>
                                <option value="" disabled selected>Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Supplier <span class="text-red-500">*</span></label>
                            <select name="supplier_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:text-white" required>
                                <option value="" disabled selected>Pilih Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Grid 3: Harga --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Beli (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 text-sm">Rp</div>
                                <input type="number" name="purchase_price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:text-white" placeholder="0" required>
                            </div>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 text-sm">Rp</div>
                                <input type="number" name="selling_price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:text-white" placeholder="0" required>
                            </div>
                        </div>
                    </div>

                    {{-- Grid 4: Stok --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stok Awal <span class="text-red-500">*</span></label>
                            <input type="number" name="current_stock" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:text-white" placeholder="Jumlah stok masuk" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Minimum Stok <span class="text-red-500">*</span></label>
                            <input type="number" name="minimum_stock" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:text-white" placeholder="Peringatan jika stok rendah" required>
                        </div>
                    </div>

                    {{-- Deskripsi Produk --}}
                    <div class="border-t border-gray-200 pt-4 dark:border-gray-600">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi Produk</label>
                        <textarea id="description" name="description" rows="3" 
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                            placeholder="Tuliskan detail produk, keunggulan, atau spesifikasi khusus..."></textarea>
                    </div>

                    {{-- ATRIBUT DINAMIS --}}
                    <div class="border-t border-gray-200 pt-4 dark:border-gray-600 bg-gray-50 p-3 rounded-lg dark:bg-gray-800/50">
                        <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Atribut Tambahan (Opsional)</label>
                        <div id="attr-container-add">
                            <div class="flex gap-2 mb-2">
                                <select name="attr_names[]" class="w-1/2 p-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">Pilih Atribut</option>
                                    @foreach($attributes as $attr)
                                        <option value="{{ $attr->name }}">{{ $attr->name }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="attr_values[]" placeholder="Nilai (Contoh: XL, Merah, 2kg)" class="w-1/2 p-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                        </div>
                        <button type="button" onclick="addAttrField('attr-container-add')" class="inline-flex items-center text-xs font-medium text-blue-600 hover:text-blue-800 dark:text-blue-500">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Atribut Baru
                        </button>
                    </div>

                    {{-- Foto Produk --}}
                    <div class="border-t border-gray-200 pt-4 dark:border-gray-600">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Foto Produk</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="dropzone-file-add" class="group relative flex flex-col items-center justify-center w-full h-44 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600 overflow-hidden">
                                
                                {{-- Layer 1: Placeholder (Tampil saat kosong) --}}
                                <div id="placeholder-add" class="flex flex-col items-center justify-center pt-5 pb-6 transition-opacity duration-300">
                                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2l2 2"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, JPEG (Maks. 2MB)</p>
                                </div>

                                {{-- Layer 2: Preview (Muncul setelah pilih file) --}}
                                <div id="preview-container-add" class="hidden absolute inset-0 w-full h-full bg-white dark:bg-gray-800 z-10 flex items-center justify-center p-2">
                                    <img id="image-display-add" src="#" alt="Preview" class="max-w-full max-h-full object-contain rounded-lg">
                                    <button type="button" onclick="resetPreviewAdd(event)" class="absolute top-2 right-2 p-1.5 bg-red-600 text-white rounded-full hover:bg-red-700 shadow-lg z-20">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>

                                <input id="dropzone-file-add" type="file" name="image" class="hidden" accept="image/png, image/jpeg, image/jpg" onchange="handlePreviewAdd(this)" />
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Simpan Produk Baru
                    </button>
                    <button type="button" data-modal-toggle="add-product-modal" class="w-full text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
/**
 * Menampilkan preview gambar saat file dipilih
 */
function handlePreviewAdd(input) {
    const file = input.files[0];
    const reader = new FileReader();
    const previewContainer = document.getElementById('preview-container-add');
    const imageDisplay = document.getElementById('image-display-add');
    const placeholder = document.getElementById('placeholder-add');

    if (file) {
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 2MB.');
            input.value = "";
            return;
        }

        reader.onload = function(e) {
            imageDisplay.src = e.target.result;
            previewContainer.classList.remove('hidden');
            placeholder.classList.add('opacity-0');
        }
        reader.readAsDataURL(file);
    }
}

/**
 * Mereset input file dan menyembunyikan preview
 */
function resetPreviewAdd(event) {
    event.preventDefault(); 
    const input = document.getElementById('dropzone-file-add');
    const previewContainer = document.getElementById('preview-container-add');
    const imageDisplay = document.getElementById('image-display-add');
    const placeholder = document.getElementById('placeholder-add');

    input.value = "";
    imageDisplay.src = "#";
    previewContainer.classList.add('hidden');
    placeholder.classList.remove('opacity-0');
}
</script>