<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-4 flex-1 w-full mr-12">
        <!-- Search Bar -->
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="fas fa-search text-gray-400"></i>
            </span>
            <input 
                type="text" 
                name="search" 
                placeholder="Cari supplier (nama, alamat, email, no telp)..."
                x-model="search"
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
            >
        </div> 
    </div>

    <!-- Tombol Download -->
    <div x-data="{
        showDownloadModal: false,
        downloadPDF() {
            window.location.href = '/supplier/download/pdf';
            this.showDownloadModal = false;
        },
        downloadExcel() {
            window.location.href = '/supplier/download/excel';
            this.showDownloadModal = false;
        }
    }" class="mr-4">
        <button type="button" @click="showDownloadModal = true" 
            class="flex items-center gap-2 bg-gradient-to-r from-blue-700 to-blue-900 text-white px-6 py-2 rounded-lg hover:from-blue-800 hover:to-blue-950">
            <i class="fas fa-download"></i>
            <span>Download</span>
        </button>
        @include('supplier.components.modal-download')
    </div>

    <!-- Tombol Tambah -->
    <div x-data>
        <button 
            @click="$dispatch('open-modal')"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg flex items-center gap-2 hover:bg-blue-700"
        >
            <i class="fas fa-plus"></i>
            <span>Tambah Supplier</span>
        </button>
    </div>
</div>