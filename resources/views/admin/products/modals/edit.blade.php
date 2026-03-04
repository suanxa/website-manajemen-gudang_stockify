<div id="edit-product-modal-{{ $product->id }}" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-modal w-full items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0 md:h-full">
    <div class="relative h-full w-full max-w-2xl p-4 md:h-auto">
        <div class="relative rounded-lg bg-white shadow dark:bg-gray-700">
            
            {{-- LOGIKA DINAMIS: Redirect berdasarkan role user --}}
            @php
                $routePrefix = auth()->user()->role === 'admin' ? 'admin' : 'manager';
            @endphp

            <form action="{{ route($routePrefix . '.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="flex items-start justify-between rounded-t border-b p-5 dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-edit mr-2 text-blue-600"></i>Edit Produk: {{ $product->name }}
                    </h3>
                    <button type="button" class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200" data-modal-toggle="edit-product-modal-{{ $product->id }}">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                
                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto text-left">
                    {{-- Nama Produk --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Produk</label>
                        <input type="text" name="name" value="{{ $product->name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- SKU --}}
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">SKU (Kode Unik)</label>
                            <input type="text" name="sku" value="{{ $product->sku }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:text-white" required>
                        </div>
                        {{-- Kategori --}}
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                            <select name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:text-white" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Supplier --}}
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Supplier</label>
                            <select name="supplier_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:text-white" required>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ $product->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Harga Beli --}}
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Beli (Rp)</label>
                            <input type="number" name="purchase_price" value="{{ $product->purchase_price }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:text-white" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Harga Jual --}}
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Jual (Rp)</label>
                            <input type="number" name="selling_price" value="{{ $product->selling_price }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:text-white" required>
                        </div>
                    </div>

                    {{-- Deskripsi Produk --}}
                    <div class="border-t border-gray-200 pt-4 dark:border-gray-600 text-left">
                        <label for="description-edit-{{ $product->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi Produk</label>
                        <textarea id="description-edit-{{ $product->id }}" name="description" rows="3" 
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                            placeholder="Tuliskan detail produk di sini...">{{ $product->description }}</textarea>
                    </div>

                    {{-- ATRIBUT EDIT --}}
                    <div class="border-t border-gray-200 pt-4 dark:border-gray-600 bg-gray-50 p-3 rounded-lg dark:bg-gray-800/50 text-left">
                        <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Atribut Produk</label>
                        <div id="attr-container-edit-{{ $product->id }}">
                            @forelse($product->attributes as $attr)
                                <div class="flex gap-2 mb-2">
                                    <select name="attr_names[]" class="w-1/2 p-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                        <option value="">Pilih Atribut</option>
                                        @foreach($attributes as $masterAttr)
                                            <option value="{{ $masterAttr->name }}" {{ $attr->name == $masterAttr->name ? 'selected' : '' }}>
                                                {{ $masterAttr->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="attr_values[]" value="{{ $attr->value }}" placeholder="Nilai" class="w-1/2 p-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                </div>
                            @empty
                                <div class="flex gap-2 mb-2">
                                    <select name="attr_names[]" class="w-1/2 p-2 text-sm border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white">
                                        <option value="">Pilih Atribut</option>
                                        @foreach($attributes as $masterAttr)
                                            <option value="{{ $masterAttr->name }}">{{ $masterAttr->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="attr_values[]" placeholder="Nilai" class="w-1/2 p-2 text-sm border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white">
                                </div>
                            @endforelse
                        </div>
                        <button type="button" onclick="addAttrFieldEdit('attr-container-edit-{{ $product->id }}')" class="inline-flex items-center text-xs font-medium text-blue-600 hover:text-blue-800 dark:text-blue-500">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Atribut Baru
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Minimum Stok --}}
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Minimum Stok</label>
                            <input type="number" name="minimum_stock" value="{{ $product->minimum_stock }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:text-white" required>
                        </div>
                        {{-- Foto Baru --}}
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Foto Produk (Baru)</label>
                            <input type="file" name="image" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 dark:text-gray-400 focus:outline-none dark:border-gray-600">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-300 italic">Biarkan kosong jika tidak ingin ganti foto.</p>
                        </div>
                    </div>

                    {{-- Preview Gambar Saat Ini --}}
                    @if($product->image)
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-600 flex items-center gap-4">
                        <img src="{{ asset('storage/' . $product->image) }}" class="w-16 h-16 rounded-lg object-cover border border-gray-300 shadow-sm">
                        <div>
                            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Gambar Saat Ini</p>
                            <p class="text-[10px] text-gray-500">File: {{ basename($product->image) }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Simpan Perubahan
                    </button>
                    <button type="button" data-modal-toggle="edit-product-modal-{{ $product->id }}" class="w-full text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>