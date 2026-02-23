<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($produk as $item)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200 flex flex-col h-full">
        <!-- Foto Produk -->
        <a href="{{ route('produk.show', $item->id_produk) }}" class="block relative aspect-[4/3] bg-gray-100 shrink-0 group">
            <img
                src="{{ $item->foto_url ?? asset('images/no-image.jpg') }}"
                alt="{{ $item->nama_produk }}"
                class="absolute w-full h-full object-cover"
            >
            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                <span class="px-4 py-2 bg-white text-gray-800 text-sm font-medium rounded-lg shadow-lg">
                    Lihat Produk
                </span>
            </div>
        </a>

        <!-- Info Produk -->
        <div class="p-4 flex flex-col flex-1 min-h-0">
            <!-- Konten atas -->
            <div class="flex-1 min-h-0 space-y-3">
                <div class="flex items-start justify-between gap-2">
                    <h3 class="font-semibold text-gray-800 line-clamp-2">{{ $item->nama_produk }}</h3>
                    <span class="shrink-0 px-2 py-1 text-xs font-medium rounded-lg {{ $item->stok > 10 ? 'bg-green-50 text-green-700' : ($item->stok > 0 ? 'bg-yellow-50 text-yellow-700' : 'bg-red-50 text-red-700') }}">
                        {{ $item->stok }} unit
                    </span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-lg font-medium text-xs">{{ $item->kategori }}</span>
                    <span class="text-gray-400">•</span>
                    <span class="text-gray-600 truncate">{{ $item->supplier->nama_supplier }}</span>
                </div>
                <div class="text-lg font-semibold text-gray-900">
                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                </div>
                <p class="text-sm text-gray-600 line-clamp-2">{{ $item->spesifikasi }}</p>
            </div>

            <!-- Tombol selalu di bawah card: Edit & Hapus sebaris, Detail full width di bawah -->
            <div class="pt-4 mt-auto space-y-2">
                <div class="flex items-center gap-2">
                    <a href="{{ route('produk.edit', $item->id_produk) }}"
                        class="flex-1 px-3 py-2 text-center text-sm font-medium bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
                        Edit
                    </a>
                    <button onclick="confirmDelete({{ $item->id_produk }})"
                        class="flex-1 px-3 py-2 text-sm font-medium bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">
                        Hapus
                    </button>
                </div>
                <a href="{{ route('produk.show', $item->id_produk) }}"
                    class="block w-full px-3 py-2 text-center text-sm font-medium bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Detail
                </a>
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
            fetch(`{{ url('produk') }}/${id}`, {
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
