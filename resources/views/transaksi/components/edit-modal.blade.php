<div x-data="{ open: false }"
    @open-modal-{{ $transaksi->id_transaksi }}.window="open = true"
    @close-modal-{{ $transaksi->id_transaksi }}.window="open = false"
    id="editTransaksiModal-{{ $transaksi->id_transaksi }}" 
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
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-5xl mx-auto">
            <!-- Modal content -->
            <div class="w-full">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Edit Transaksi
                    </h3>
                    <button type="button" 
                            @click="open = false"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="p-6">
                    <div class="flex gap-6">
                        <!-- Kolom Kiri - Gambar -->
                        <div class="w-1/3">
                            <img src="{{ asset('images/bgEdit.png') }}" alt="Product Image" class="w-full rounded-lg shadow-lg">
                        </div>
                        
                        <!-- Kolom Kanan - Form -->
                        <div class="w-2/3">
                            <form id="formEditTransaksi-{{ $transaksi->id_transaksi }}" 
                                  onsubmit="updateTransaksi(event, {{ $transaksi->id_transaksi }})">
                                @csrf
                                @method('PUT')
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Kolom Kanan Bagian 1 -->
                                    <div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2 text-left" for="id_produk">
                                                Produk
                                            </label>
                                            <select name="id_produk" id="id_produk" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                @foreach($produks as $produk)
                                                    <option value="{{ $produk->id_produk }}" {{ $transaksi->id_produk == $produk->id_produk ? 'selected' : '' }}>
                                                        {{ $produk->nama_produk }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2 text-left" for="jumlah">
                                                Jumlah
                                            </label>
                                            <input type="number" name="jumlah" id="jumlah" value="{{ $transaksi->jumlah }}" 
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        </div>
                                    </div>

                                    <!-- Kolom Kanan Bagian 2 -->
                                    <div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2 text-left" for="tgl_jual">
                                                Tanggal Jual
                                            </label>
                                            <input type="date" name="tgl_jual" id="tgl_jual" value="{{ $transaksi->tgl_jual }}" 
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2 text-left" for="status_bayar">
                                                Status Bayar
                                            </label>
                                            <select name="status_bayar" id="status_bayar" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                <option value="Sukses" {{ $transaksi->status_bayar == 'Sukses' ? 'selected' : '' }}>Sukses</option>
                                                <option value="Pending" {{ $transaksi->status_bayar == 'Pending' ? 'selected' : '' }}>Pending</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal footer -->
                                <div class="flex items-center justify-end pt-4 space-x-2 border-t mt-4">
                                    <button @click="open = false" 
                                            type="button" 
                                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                        Batal
                                    </button>
                                    <button type="submit" 
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                        Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateTransaksi(e, id) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);

    // Debug: cek isi FormData
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }

    // Tambahkan method PUT ke FormData
    formData.append('_method', 'PUT');

    // Tambahkan header CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`/transaksi/${id}`, {
        method: 'POST', // Tetap POST karena Laravel form spoofing
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.text())  // Ubah jadi text() dulu
    .then(text => {
        console.log('Raw response:', text);  // Debug: liat response mentahnya
        return JSON.parse(text);  // Convert ke JSON
    })
    .then(data => {
        // Debug: cek data
        console.log('Success data:', data);
        
        if (data.success) {
            // Close modal dengan Alpine.js dispatch
            window.dispatchEvent(new CustomEvent(`close-modal-${id}`));

            // Show success alert
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.reload();
            });
        } else {
            throw new Error(data.message || 'Terjadi kesalahan saat mengupdate data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: error.message || 'Terjadi kesalahan! Silakan coba lagi.'
        });
    });
}
</script>