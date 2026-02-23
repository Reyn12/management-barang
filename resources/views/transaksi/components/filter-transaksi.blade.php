@push('styles')
<style>
    @keyframes modalShow {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(-10px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .animate-modal-show {
        animation: modalShow 0.3s ease-out;
    }
</style>
@endpush
<div class="flex items-center justify-between mb-6">
    <div x-data="{
        showDownloadModal: false,
        downloadPDF() {
            const currentUrl = new URL(window.location.href);
            const searchParams = currentUrl.searchParams;
            window.location.href = `/transaksi/download/pdf?${searchParams.toString()}`;
            this.showDownloadModal = false;
        },
        downloadExcel() {
            const currentUrl = new URL(window.location.href);
            const searchParams = currentUrl.searchParams;
            window.location.href = `/transaksi/download/excel?${searchParams.toString()}`;
            this.showDownloadModal = false;
        }
    }" class="flex items-center gap-4 flex-1">
        <!-- Search Bar -->
        <form action="{{ route('transaksi.index') }}" method="GET">
            <div class="relative">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       class="w-full pl-10 pr-4 py-2 border rounded-lg focus:border-blue-300 focus:ring focus:ring-blue-200" 
                       placeholder="Cari transaksi...">
                <button type="submit" class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <i class="fas fa-search text-gray-400"></i>
                </button>
            </div>
        </form>

        <!-- Filter Dropdown -->
        <div class="relative" 
        x-data="{
            show: false,
            selectedDate: 'all',
            selectedStatus: 'all',
            applyFilter() {
                const url = new URL(window.location.href);
                // Reset search params dulu
                url.searchParams.delete('date');
                url.searchParams.delete('status');
                url.searchParams.delete('page');
                
                // Tambah params baru sesuai filter
                if (this.selectedDate !== 'all') url.searchParams.set('date', this.selectedDate);
                if (this.selectedStatus !== 'Semua Status') url.searchParams.set('status', this.selectedStatus);
                
                window.location.href = url.toString();
            }
        }">
        <button @click="show = !show" 
                class="px-4 py-2 border border-gray-300 rounded-lg flex items-center gap-2 hover:bg-gray-50">
            <i class="fas fa-filter text-gray-400"></i>
            <span class="text-gray-700">Filters</span>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="show" 
            @click.away="show = false"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute left-0 mt-2 w-64 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 p-4 space-y-4 z-50">
            
            <!-- Filter by Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Date</label>
                <select x-model="selectedDate" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    <option value="all">Semua</option>
                    <option value="today">Hari Ini</option>
                    <option value="week">Minggu Ini</option>
                    <option value="month">Bulan Ini</option>
                </select>
            </div>
 
            <!-- Filter by Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Status</label>
                <select x-model="selectedStatus" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    <option value="all">Semua Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Sukses">Sukses</option>
                    <option value="Gagal">Gagal</option>
                </select>
            </div>

            <!-- Apply Filter Button -->
            <div>
                <button @click="applyFilter()" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Terapkan Filter
                </button>
            </div>
        </div>
        </div>

        <!-- Download Button -->
        <div class="" >
            <button type="button" @click="showDownloadModal = true" 
                class="flex items-center gap-2 bg-gradient-to-r from-blue-700 to-blue-900 text-white px-6 py-2 rounded-lg hover:from-blue-800 hover:to-blue-950">
                <i class="fas fa-download"></i>
                <span>Download</span>
            </button>
            @include('transaksi.components.modal-download')
        </div>
    </div>
 
    <!-- Tombol Tambah -->
    <div>
        <button 
            type="button"
            @click="$dispatch('open-tambah-modal')"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg flex items-center gap-2 hover:bg-blue-700"
        >
            <i class="fas fa-plus"></i>
            <span>Tambah Transaksi</span>
        </button>
    </div>
</div>

<!-- Modal Tambah Transaksi -->
<div x-data="{ open: false }"
     @open-tambah-modal.window="open = true"
     @close-tambah-modal.window="open = false"
     :class="{ 'hidden': !open }"
     class="fixed inset-0 z-50 overflow-y-auto"
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true"
     x-cloak>
    
    <!-- Background backdrop -->
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="relative w-full max-w-3xl bg-white rounded-lg shadow-xl transform transition-all">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Tambah Transaksi
                </h3>
                <button @click="open = false" 
                        type="button" 
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 flex justify-center items-center">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal body -->
            <form action="{{ route('transaksi.store') }}" method="POST" id="formTambahTransaksi" x-data="formData">
                @csrf
                <div class="p-6">
                    <div class="flex gap-8">
                        <!-- Kolom Kiri - Gambar -->
                        <div class="w-1/3">
                            <img src="{{ asset('images/bgTambah2.png') }}" alt="Transaction Illustration" class="w-full rounded-lg">
                        </div>
                        
                        <!-- Kolom Kanan - Form -->
                        <div class="w-2/3 space-y-4">
                            <!-- Produk -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Produk</label>
                                <select name="id_produk" 
                                       x-model="idProduk"
                                       @change="hitungTotal()"
                                       required 
                                       class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    <option value="">Pilih Produk</option>
                                    @foreach($produks as $produk)
                                        <option value="{{ $produk->id_produk }}">{{ $produk->nama_produk }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Jumlah -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                                    <input type="number" 
                                           name="jumlah" 
                                           x-model="jumlah"
                                           @input="hitungTotal()"
                                           required 
                                           min="1"
                                           :max="stokProduk[idProduk] || 1"
                                           class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    <p x-show="errorStok" x-text="errorStok" class="mt-1 text-sm text-red-600"></p>
                                </div>

                                <!-- Tanggal Jual -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Jual</label>
                                    <input type="date" 
                                           name="tgl_jual" 
                                           required
                                           class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                                </div>
                            </div>

                            <!-- Total Harga -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Total Harga</label>
                                <input type="number" 
                                       name="total_harga" 
                                       x-model="totalHarga"
                                       required
                                       readonly
                                       class="w-full px-3 py-2 rounded-lg border border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring focus:ring-blue-200">
                            </div>

                            <!-- Status Bayar -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status Bayar</label>
                                <select name="status_bayar" 
                                        required 
                                        class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    <option value="">Pilih Status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Sukses">Sukses</option>
                                    <option value="Gagal">Gagal</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="flex items-center justify-end p-4 space-x-2 border-t">
                    <button @click="open = false" 
                            type="button"
                            class="px-4 py-2 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
 
@push('scripts')
<script>
    // Alert untuk success
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 1500
        });
    @endif

    // Alert untuk error
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ session('error') }}"
        });
    @endif

    // Form submission dengan SweetAlert2
    document.getElementById('formTambahTransaksi').addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah anda yakin ingin menyimpan data ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });

    // Data harga produk
    const hargaProduk = {
        @foreach($produks as $produk)
            {{ $produk->id_produk }}: {{ $produk->harga }},
        @endforeach
    };

    // Data stok produk
    const stokProduk = {
        @foreach($produks as $produk)
            {{ $produk->id_produk }}: {{ $produk->stok }},
        @endforeach
    };

    // Alpine.js data untuk handle total harga
    document.addEventListener('alpine:init', () => {
        Alpine.data('formData', () => ({
            idProduk: '',
            jumlah: '',
            totalHarga: '',
            errorStok: '',
            
            hitungTotal() {
                if(this.idProduk && this.jumlah) {
                    // Validasi stok
                    const stok = stokProduk[this.idProduk];
                    if (this.jumlah > stok) {
                        this.errorStok = `Stok tidak mencukupi! Stok tersedia: ${stok}`;
                        this.jumlah = stok; // Reset ke stok maksimal
                    } else {
                        this.errorStok = '';
                    }
                    
                    // Hitung total harga
                    const harga = hargaProduk[this.idProduk];
                    this.totalHarga = harga * this.jumlah;
                } else {
                    this.totalHarga = '';
                    this.errorStok = '';
                }
            }
        }));
    });
</script>
@endpush