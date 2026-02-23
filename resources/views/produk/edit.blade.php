@extends('master')

@section('title', 'Edit Produk')

@section('content')
<div class="flex p-5">
    @include('dashboard.components.sidebar')
    
    <div class="ml-[280px] flex-1">
        <div class="bg-white rounded-xl shadow-sm p-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Edit Produk</h1>
            </div>

            <form action="{{ route('produk.update', $produk->id_produk) }}" method="POST" enctype="multipart/form-data"
                x-data="{ 
                    previewUrl: '{{ $produk->foto_url }}',
                    isDragging: false,
                    handleFileDrop(e) {
                        e.preventDefault();
                        this.isDragging = false;
                        const file = e.dataTransfer.files[0];
                        if (file && file.type.startsWith('image/')) {
                            this.$refs.fileInput.files = e.dataTransfer.files;
                            this.previewUrl = URL.createObjectURL(file);
                        }
                    }
                }"
                @submit.prevent="
                    const formData = new FormData($el);
                    fetch($el.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        },
                        body: formData
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
                                window.location.href = '{{ route('produk.produk') }}';
                            });
                        } else {
                            throw new Error(result.message || 'Terjadi kesalahan');
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: error.message || 'Terjadi kesalahan saat memperbarui produk'
                        });
                    });
                "
            >
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Kolom Kiri: Form Input -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                            <input type="text" name="nama_produk" value="{{ $produk->nama_produk }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select name="kategori" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                <option value="HP" {{ $produk->kategori == 'HP' ? 'selected' : '' }}>HP</option>
                                <option value="Laptop" {{ $produk->kategori == 'Laptop' ? 'selected' : '' }}>Laptop</option>
                                <option value="Tablet" {{ $produk->kategori == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                                <option value="Aksesoris" {{ $produk->kategori == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                            <select name="id_supplier" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id_supplier }}" {{ $produk->id_supplier == $supplier->id_supplier ? 'selected' : '' }}>
                                        {{ $supplier->nama_supplier }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                            <input type="number" name="harga" value="{{ $produk->harga }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                            <input type="number" name="stok" value="{{ $produk->stok }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Spesifikasi</label>
                            <textarea name="spesifikasi" rows="4" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">{{ $produk->spesifikasi }}</textarea>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Upload Foto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-4">Foto Produk</label>
                        
                        <!-- Area Drag n Drop -->
                        <div class="mb-4"
                             @dragover.prevent="isDragging = true"
                             @dragleave.prevent="isDragging = false"
                             @drop="handleFileDrop"
                        >
                            <div class="relative border-2 border-dashed rounded-xl p-4 text-center"
                                 :class="{'border-blue-500 bg-blue-50': isDragging, 'border-gray-300': !isDragging}">
                                
                                <!-- Preview Foto -->
                                <div class="mb-4">
                                    <img :src="previewUrl" 
                                         alt="Preview" 
                                         class="mx-auto w-48 h-48 object-cover rounded-lg shadow-sm">
                                </div>
                                
                                <!-- Teks Panduan -->
                                <div class="space-y-2">
                                    <div class="text-sm text-gray-600">
                                        Drag & drop foto di sini atau
                                    </div>
                                    <label class="inline-block px-4 py-2 bg-blue-50 text-blue-700 rounded-lg cursor-pointer hover:bg-blue-100 transition-colors">
                                        Pilih File
                                        <input type="file" 
                                               name="foto_produk" 
                                               x-ref="fileInput"
                                               class="hidden"
                                               @change="const file = $event.target.files[0]; if(file) previewUrl = URL.createObjectURL(file)">
                                    </label>
                                    <div class="text-xs text-gray-500">
                                        PNG, JPG atau JPEG (Maks. 2MB)
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-end gap-4 mt-8 pt-6 border-t">
                    <a href="{{ route('produk.produk') }}" 
                       class="px-6 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection