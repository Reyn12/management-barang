<div x-data="{ show: false }"
    x-show="show"
    x-cloak
    @open-modal.window="show = true"
    @close-modal.window="show = false"
    @keydown.escape.window="show = false" 
    class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- Modal -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <!-- Header -->
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Tambah Produk Baru
                    </h3>
                    <button @click="$dispatch('close-modal')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <span class="sr-only">Close</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
    
            <!-- Body -->
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <form id="form-tambah" enctype="multipart/form-data" action="{{ route('produk.store') }}" method="POST"
                    @submit.prevent="
                        fetch($el.action, {
                            method: 'POST',
                            body: new FormData($el)
                        })
                        .then(response => response.json())
                        .then(result => {
                            if(result.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: result.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    $dispatch('close-modal');
                                    window.location.reload();
                                });
                            }
                        })
                    "
                >
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kolom Kiri -->
                        <div class="space-y-6">
                            <!-- Nama Produk -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                                <input 
                                    type="text" 
                                    name="nama_produk"
                                    class="mt-1 block w-full px-4 py-3 rounded-md border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 sm:text-base"
                                    required
                                >
                            </div>

                            <!-- Kategori -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select 
                                    name="kategori"
                                    class="mt-1 block w-full px-4 py-3 rounded-md border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 sm:text-base"
                                    required
                                >
                                    <option value="">Pilih Kategori</option>
                                    <option value="hp">HP</option>
                                    <option value="laptop">Laptop</option>
                                    <option value="tablet">Tablet</option>
                                    <option value="aksesoris">Aksesoris</option>
                                </select>
                            </div>

                            <!-- Supplier -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Supplier</label>
                                <select 
                                    name="id_supplier"
                                    class="mt-1 block w-full px-4 py-3 rounded-md border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 sm:text-base"
                                    required
                                >
                                    <option value="">Pilih Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id_supplier }}">{{ $supplier->nama_supplier }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Harga -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Harga</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input 
                                        type="number" 
                                        name="harga"
                                        class="pl-12 block w-full px-4 py-3 rounded-md border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 sm:text-base"

                                        required
                                        min="0"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="space-y-6">
                            <!-- Spesifikasi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Spesifikasi</label>
                                <textarea 
                                    name="spesifikasi"
                                    rows="4"
                                    class="mt-1 block w-full px-4 py-3 rounded-md border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 sm:text-base"

                                ></textarea>
                            </div>

                            <!-- Foto Produk -->
                            <div x-data="{ fileName: '', filePreview: null }">
                                <label class="block text-sm font-medium text-gray-700">Foto Produk</label>
                                <div 
                                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md"
                                    x-on:dragover.prevent="$el.classList.add('border-blue-500')"
                                    x-on:dragleave.prevent="$el.classList.remove('border-blue-500')"
                                    x-on:drop.prevent="
                                        $el.classList.remove('border-blue-500');
                                        const file = $event.dataTransfer.files[0];
                                        if (file && file.type.startsWith('image/')) {
                                            fileName = file.name;
                                            const reader = new FileReader();
                                            reader.onload = (e) => {
                                                filePreview = e.target.result;
                                            };
                                            reader.readAsDataURL(file);
                                            $refs.fileInput.files = $event.dataTransfer.files;
                                        }
                                    "
                                >
                                    <div class="space-y-1 text-center">
                                        <!-- Preview Image -->
                                        <template x-if="filePreview">
                                            <div class="mb-3">
                                                <img :src="filePreview" class="mx-auto h-32 w-auto">
                                                <p class="mt-2 text-sm text-gray-600" x-text="fileName"></p>
                                            </div>
                                        </template>
                                        
                                        <!-- Upload Icon -->
                                        <template x-if="!filePreview">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </template>

                                        <div class="flex text-sm text-gray-600">
                                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                <span>Upload a file</span>
                                                <input 
                                                    id="foto_produk" 
                                                    name="foto_produk" 
                                                    type="file" 
                                                    class="sr-only"
                                                    x-ref="fileInput" 
                                                    accept="image/*"
                                                    @change="
                                                        const file = $event.target.files[0];
                                                        fileName = file.name;
                                                        const reader = new FileReader();
                                                        reader.onload = (e) => {
                                                            filePreview = e.target.result;
                                                        };
                                                        reader.readAsDataURL(file);
                                                    "
                                                >
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Stok -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Stok</label>
                                <input 
                                    type="number" 
                                    name="stok"
                                    class="mt-1 block w-full px-4 py-3 rounded-md border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 sm:text-base"

                                    required
                                    min="0"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2 border-t mt-6 justify-center align-middle">
                        <button
                            type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-12 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Simpan
                        </button>
                        <button
                            @click="$dispatch('close-modal')"
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-12 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm"
                        >
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>