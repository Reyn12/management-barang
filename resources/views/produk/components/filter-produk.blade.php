<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-4 flex-1">
        <!-- Search Bar -->
        <form action="{{ route('produk.produk') }}" method="GET" class="relative flex-1 max-w-md">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="fas fa-search text-gray-400"></i>
            </span>
            <input 
                type="text" 
                name="search"
                value="{{ request('search') }}"
                class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                placeholder="Cari produk..."
            >
        </form>

        <!-- Filter Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="px-4 py-2 bg-white border border-gray-300 rounded-lg flex items-center gap-2">
                <i class="fas fa-filter"></i>
                <span>Filter</span>
                <i class="fas fa-chevron-down text-sm"></i>
            </button>
            <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg z-10">
                <form action="{{ route('produk.produk') }}" method="GET">
                    <!-- Preserve search parameter if exists -->
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <div class="p-3">
                        <label class="block text-sm text-gray-700 mb-2">Kategori</label>
                        <select name="kategori" class="w-full border border-gray-300 rounded-md p-1.5 text-sm">
                            <option value="">Semua</option>
                            <option value="hp" {{ request('kategori') == 'hp' ? 'selected' : '' }}>HP</option>
                            <option value="laptop" {{ request('kategori') == 'laptop' ? 'selected' : '' }}>Laptop</option>
                            <option value="tablet" {{ request('kategori') == 'tablet' ? 'selected' : '' }}>Tablet</option>
                            <option value="aksesoris" {{ request('kategori') == 'aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                        </select>
                    </div>

                    <div class="p-3 border-t">
                        <label class="block text-sm text-gray-700 mb-2">Supplier</label>
                        <select name="supplier" class="w-full border border-gray-300 rounded-md p-1.5 text-sm">
                            <option value="">Semua</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id_supplier }}" {{ request('supplier') == $supplier->id_supplier ? 'selected' : '' }}>
                                    {{ $supplier->nama_supplier }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="p-3 border-t">
                        <label class="block text-sm text-gray-700 mb-2">Range Harga</label>
                        <div class="space-y-2">
                            <input type="number" name="min_harga" value="{{ request('min_harga') }}" 
                                class="w-full border border-gray-300 rounded-md p-1.5 text-sm" 
                                placeholder="Harga Minimal">
                            <input type="number" name="max_harga" value="{{ request('max_harga') }}" 
                                class="w-full border border-gray-300 rounded-md p-1.5 text-sm" 
                                placeholder="Harga Maksimal">
                        </div>
                    </div>

                    <div class="p-3 border-t flex justify-between">
                        <a href="{{ route('produk.produk') }}" class="text-sm text-gray-600 hover:text-gray-800">
                            Reset
                        </a>
                        <button type="submit" class="px-4 py-1.5 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                            Terapkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tombol Download tetap sama -->
    <div x-data="{
        showDownloadModal: false,
        downloadPDF() {
            const currentUrl = new URL(window.location.href);
            const searchParams = currentUrl.searchParams;
            // Tambahkan parameter kategori jika ada
            if ('{{ request("kategori") }}') {
                searchParams.set('kategori', '{{ request("kategori") }}');
            }
            window.location.href = `/produk/download/pdf?${searchParams.toString()}`;
            this.showDownloadModal = false;
        },
        downloadExcel() {
            const currentUrl = new URL(window.location.href);
            const searchParams = currentUrl.searchParams;
            // Tambahkan parameter kategori jika ada
            if ('{{ request("kategori") }}') {
                searchParams.set('kategori', '{{ request("kategori") }}');
            }
            window.location.href = `/produk/download/excel?${searchParams.toString()}`;
            this.showDownloadModal = false;
        },
    }" class="mr-4">
        <button type="button" @click="showDownloadModal = true" 
            class="flex items-center gap-2 bg-gradient-to-r from-blue-700 to-blue-900 text-white px-6 py-2 rounded-lg hover:from-blue-800 hover:to-blue-950">
            <i class="fas fa-download"></i>
            <span>Download</span>
        </button>
        @include('produk.components.modal-download')
    </div>

    <!-- Tombol Tambah tetap sama -->
    <div x-data>
        <button 
            @click="$dispatch('open-modal')"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg flex items-center gap-2 hover:bg-blue-700"
        >
            <i class="fas fa-plus"></i>
            <span>Tambah Produk</span>
        </button>
    </div>
    @include('produk.components.tambah-produk')
</div>