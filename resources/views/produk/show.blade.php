@extends('master')

@section('title', 'Detail Produk - ' . $produk->nama_produk)

@section('content')
<div class="flex p-5">
    @include('dashboard.components.sidebar')

    <div class="ml-[280px] flex-1 py-4 px-8 bg-white rounded-xl mb-4">
        {{-- Header + Back --}}
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('produk.produk') }}"
                    class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Detail Produk</h1>
                    <p class="text-sm text-gray-500">{{ $produk->nama_produk }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2 bg-gray-50 px-3 py-2 rounded-lg shadow-sm border border-gray-100">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                    <span class="text-white font-medium text-sm">{{ strtoupper(mb_substr(Auth::user()->name ?? 'U', 0, 1)) }}</span>
                </div>
                <span class="text-sm font-medium text-gray-800 truncate max-w-[120px]" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</span>
            </div>
        </div>
        <hr class="border-gray-200 mb-8">

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="flex flex-col md:flex-row">
                <!-- Foto Produk -->
                <div class="md:w-96 shrink-0 aspect-[4/3] md:aspect-auto md:h-[420px] bg-gray-100">
                    <img
                        src="{{ $produk->foto_url ?? asset('images/no-image.jpg') }}"
                        alt="{{ $produk->nama_produk }}"
                        class="w-full h-full object-cover"
                    >
                </div>

                <!-- Info Produk -->
                <div class="flex-1 p-6 md:p-8 flex flex-col">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">{{ $produk->nama_produk }}</h2>
                        <div class="flex flex-wrap items-center gap-2 mt-2">
                            <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-medium">{{ $produk->kategori }}</span>
                            <span class="px-2 py-1 rounded-lg text-sm font-medium {{ $produk->stok > 10 ? 'bg-green-50 text-green-700' : ($produk->stok > 0 ? 'bg-yellow-50 text-yellow-700' : 'bg-red-50 text-red-700') }}">
                                Stok: {{ $produk->stok }} unit
                            </span>
                        </div>
                    </div>

                    <dl class="space-y-4 flex-1">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Supplier</dt>
                            <dd class="mt-1 text-gray-900">{{ $produk->supplier->nama_supplier }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Harga</dt>
                            <dd class="mt-1 text-xl font-semibold text-gray-900">Rp {{ number_format($produk->harga, 0, ',', '.') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Spesifikasi</dt>
                            <dd class="mt-1 text-gray-700">{{ $produk->spesifikasi ?: '-' }}</dd>
                        </div>
                    </dl>

                    <!-- Tombol Edit & Hapus -->
                    <div class="flex flex-wrap items-center gap-3 pt-6 mt-6 border-t border-gray-200">
                        <a href="{{ route('produk.edit', $produk->id_produk) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                        <button type="button" onclick="confirmDelete({{ $produk->id_produk }})"
                            class="inline-flex items-center px-4 py-2 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors">
                            <i class="fas fa-trash mr-2"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message, timer: 1500, showConfirmButton: false })
                        .then(() => { window.location.href = '{{ route('produk.produk') }}'; });
                } else {
                    Swal.fire({ icon: 'error', title: 'Oops...', text: data.message });
                }
            })
            .catch(() => Swal.fire({ icon: 'error', title: 'Oops...', text: 'Terjadi kesalahan.' }));
        }
    });
}
</script>
@endpush
