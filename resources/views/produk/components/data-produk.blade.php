<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($produk as $item)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
        <!-- Foto Produk -->
        <div class="relative aspect-[4/3] bg-gray-100">
            <img 
                src="{{ $item->foto_url ?? asset('images/no-image.jpg') }}"
                alt="{{ $item->nama_produk }}"
                class="absolute w-full h-full object-cover"
            >
        </div>
        
        <!-- Info Produk -->
        <div class="p-4 space-y-3">
            <!-- Header: Nama & Stok -->
            <div class="flex items-start justify-between gap-2">
                <h3 class="font-semibold text-gray-800 line-clamp-2">{{ $item->nama_produk }}</h3>
                <span class="shrink-0 px-2 py-1 text-xs font-medium rounded-lg {{ $item->stok > 10 ? 'bg-green-50 text-green-700' : ($item->stok > 0 ? 'bg-yellow-50 text-yellow-700' : 'bg-red-50 text-red-700') }}">
                    {{ $item->stok }} unit
                </span>
            </div>

            <!-- Kategori & Supplier -->
            <div class="flex items-center gap-2 text-sm">
                <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-lg font-medium text-xs">{{ $item->kategori }}</span>
                <span class="text-gray-400">•</span>
                <span class="text-gray-600 truncate">{{ $item->supplier->nama_supplier }}</span>
            </div>

            <!-- Harga -->
            <div class="text-lg font-semibold text-gray-900">
                Rp {{ number_format($item->harga, 0, ',', '.') }}
            </div>

            <!-- Spesifikasi -->
            <p class="text-sm text-gray-600 line-clamp-2">{{ $item->spesifikasi }}</p>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 pt-2">
                <a href="{{ route('produk.edit', $item->id_produk) }}" 
                    class="flex-1 px-3 py-2 text-center text-sm font-medium bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
                    Edit
                </a>
                <button onclick="confirmDelete({{ $item->id_produk }})" 
                    class="flex-1 px-3 py-2 text-sm font-medium bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">
                    Hapus
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-6">
    {{ $produk->links() }}
</div>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Apakah anda yakin?',
        text: 'Produk ini akan dihapus secara permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ route('produk.destroy', '') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
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
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: result.message
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan! Silakan coba lagi.'
                });
            });
        }
    });
}
</script>