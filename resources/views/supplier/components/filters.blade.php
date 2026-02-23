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

    <!-- Tombol Tambah -->
    <div>
        <button 
            @click="$dispatch('open-modal')"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg flex items-center gap-2 hover:bg-blue-700"
        >
            <i class="fas fa-plus"></i>
            <span>Tambah Supplier</span>
        </button>
    </div>
</div>