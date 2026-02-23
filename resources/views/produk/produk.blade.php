@extends('master')

@section('title', 'Produk')

@section('content')
    <div class="flex p-5">
        @include('dashboard.components.sidebar')
        
        <div class="ml-[280px] flex-1 py-4 px-8 bg-white rounded-xl mb-4">
            @include('produk.components.header')
            @include('produk.components.filter-produk')
            @include('produk.components.data-produk')
        </div>
    </div>
@endsection

@push('styles')
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endpush

@push('scripts')
<script>
    // Handle Modal
    const modal = document.getElementById('modal-tambah');
    const btnTambah = document.getElementById('btn-tambah');
    const btnClose = document.querySelectorAll('.btn-close');

    btnTambah.addEventListener('click', () => {
        modal.classList.remove('hidden');
    });

    btnClose.forEach(btn => {
        btn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });
    });

    // Handle Form Submit
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form-tambah');
        
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            try {
                const formData = new FormData(form);
                
                const response = await fetch('/produk', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    alert('Produk berhasil ditambahkan!');
                    window.location.reload();
                } else {
                    alert('Gagal menambahkan produk: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan: ' + error.message);
            }
        });
    });
</script>
@endpush