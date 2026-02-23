<div
    x-data="{ open: false }"
    @open-modal.window="open = true"
    @close-modal.window="open = false"
    class="relative z-50"
    x-cloak
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true"
>
    <!-- Background backdrop -->
    <div
        x-show="open"
        x-cloak
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
        @click="open = false"
    ></div>

    <div
        x-show="open"
        class="fixed inset-0 z-10 overflow-y-auto"
    >
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <!-- Modal panel -->
            <div
                x-show="open"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                @click.outside="open = false"
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl"
            >
                <div class="grid grid-cols-2">
                    <!-- Kolom Kiri - Gambar -->
                    <div class="bg-blue-50 p-6 flex items-center justify-center">
                        <img src="{{ asset('/images/bgTambah.png') }}" 
                             alt="Supplier Illustration" 
                             class="w-full max-w-sm">
                    </div>

                    <!-- Kolom Kanan - Form -->
                    <div class="bg-white" >
                        <!-- Header -->
                        <div class="flex items-center justify-between px-6 py-4 border-b">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-user-plus"></i>
                                Tambah Supplier
                            </h3>
                            <button 
                                @click="open = false"
                                class="text-gray-400 hover:text-gray-500 transition-colors duration-200"
                            >
                                <i class="fas fa-times"></i>
                            </button>
                        </div> 

                        <!-- Body -->
                        <div class="px-6 py-4">
                            <form id="formTambahSupplier" action="{{ route('supplier.store') }}" method="POST" @submit.prevent="
                                fetch($el.action, {
                                    method: 'POST',
                                    body: new FormData($el)
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (!data.success) {
                                        let errorMessage = data.message;
                                        if (data.errors) {
                                            errorMessage = Object.values(data.errors)[0][0];
                                        }
                                        throw new Error(errorMessage);
                                    }
                                    
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: 'Supplier berhasil ditambahkan',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    open = false;
                                    $el.reset();
                                })
                                .catch(error => {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: error.message || 'Gagal menambahkan supplier'
                                    });
                                })
                            ">
                                @csrf
                                <!-- Nama Supplier -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama Supplier
                                    </label>
                                    <input type="text" 
                                        name="nama_supplier"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Masukkan nama supplier"
                                        required
                                    >
                                </div>

                                <!-- Alamat -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Alamat
                                    </label>
                                    <textarea 
                                        name="alamat"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        rows="3"
                                        placeholder="Masukkan alamat lengkap"
                                        required
                                    ></textarea>
                                </div>

                                <!-- No Telp -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        No Telp
                                    </label>
                                    <input type="number" 
                                        name="no_telp"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Masukkan nomor telepon"
                                        required
                                    >
                                </div>

                                <!-- Email -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Email
                                    </label>
                                    <input type="email" 
                                        name="email"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Masukkan email"
                                        required
                                    >
                                </div>
                            </form>
                        </div>

                        <!-- Footer -->
                        <div class="bg-gray-50 px-6 py-4 flex justify-center gap-6 w-full ">
                            <button 
                                type="button"
                                @click="open = false"
                                class="px-8 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                form="formTambahSupplier"
                                class="px-8 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200"
                            >
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>