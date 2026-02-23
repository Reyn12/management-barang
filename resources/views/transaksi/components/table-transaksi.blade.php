<div class="bg-white rounded-lg shadow-md">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-blue-100 text-gray-600">
                <tr>
                    <th class="px-4 py-3">Kode Transaksi</th>
                    <th class="px-4 py-3">Tanggal Jual</th>
                    <th class="px-4 py-3">Produk</th>
                    <th class="px-4 py-3">Jumlah</th>
                    <th class="px-4 py-3">Total Harga</th>
                    <th class="px-4 py-3">Status Bayar</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($transaksis as $transaksi)
                    <tr class="hover:bg-gray-50 even:bg-gray-50">
                        <td class="px-4 py-3">{{ $transaksi->kode_transaksi }}</td>
                        <td class="px-4 py-3">{{ date('d/m/Y', strtotime($transaksi->tgl_jual)) }}</td>
                        <td class="px-4 py-3">{{ $transaksi->produk->nama_produk }}</td>
                        <td class="px-4 py-3">{{ $transaksi->jumlah }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs 
                                {{ $transaksi->status_bayar === 'Sukses' ? 'bg-green-100 text-green-600' : 
                                   ($transaksi->status_bayar === 'Pending' ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600') }}">
                                {{ $transaksi->status_bayar }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                            {{-- Download Invoice Button --}}
                            <a href="{{ route('transaksi.download-invoice', $transaksi->id_transaksi) }}"
                                class="p-1.5 bg-green-50 text-green-600 hover:bg-green-100 rounded-lg transition-colors duration-200">
                                <i class="fas fa-file-download text-lg"></i>
                            </a>
                            {{-- Edit Button --}}
                            <button type="button"
                                    @click="$dispatch('open-modal-{{ $transaksi->id_transaksi }}')"
                                    class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors duration-200">
                                <i class="fas fa-edit text-lg"></i>
                            </button>
                        
                            {{-- Delete Button --}}
                            <button onclick="confirmDelete({{ $transaksi->id_transaksi }})" 
                                    type="button" 
                                    class="p-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors duration-200 mr-6">
                                <i class="fas fa-trash text-lg"></i>
                            </button>

                            {{-- Form Delete --}}
                            <form id="delete-form-{{ $transaksi->id_transaksi }}" 
                                action="{{ route('transaksi.destroy', $transaksi->id_transaksi) }}" 
                                method="POST" 
                                style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        
                            {{-- Include Modal --}}
                            @include('transaksi.components.edit-modal', ['transaksi' => $transaksi, 'produks' => $produks])
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-3 text-center">Belum ada data transaksi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3">
        @if ($transaksis->hasPages())
            <nav class="flex items-center justify-between">
                {{-- Previous Page Link --}}
                <div class="flex-1 flex justify-start">
                    @if ($transaksis->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md">
                            Previous
                        </span>
                    @else
                        <a href="{{ $transaksis->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:text-gray-500">
                            Previous
                        </a>
                    @endif
                </div>

                {{-- Next Page Link --}}
                <div class="flex-1 flex justify-end">
                    @if ($transaksis->hasMorePages())
                        <a href="{{ $transaksis->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:text-gray-500">
                            Next
                        </a>
                    @else
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md">
                            Next
                        </span>
                    @endif
                </div>
            </nav>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush