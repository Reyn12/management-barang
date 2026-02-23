<!DOCTYPE html>
<html>
<head>
    <title>Laporan Produk</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15px;
        }
        body {
            margin: 0;
            padding: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .spesifikasi {
            max-width: 200px;
            word-wrap: break-word;
        }
        img {
            max-width: 50px;
            height: auto;
            display: block;
        }
    </style>
</head>
<body>
    <h2>Laporan Produk</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th class="spesifikasi">Spesifikasi</th>
                <th>Supplier</th>
                <th>Foto Produk</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produks as $index => $produk)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $produk->nama_produk }}</td>
                <td>{{ $produk->kategori }}</td>
                <td>Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                <td>{{ $produk->stok }}</td>
                <td class="spesifikasi">{{ $produk->spesifikasi }}</td>
                <td>{{ $produk->supplier->nama_supplier }}</td>
                <td>
                    @php
                        $storage_path = public_path('storage/'.$produk->foto_produk);
                        $public_path = public_path('images/products/'.$produk->foto_produk);
                    @endphp
                    
                    @if(file_exists($storage_path))
                        <img src="{{ public_path('storage/'.$produk->foto_produk) }}" 
                             alt="Foto {{ $produk->nama_produk }}"
                             style="width: 50px; height: 50px; object-fit: cover;">
                    @elseif(file_exists($public_path))
                        <img src="{{ public_path('/images/products/'.$produk->foto_produk) }}" 
                             alt="Foto {{ $produk->nama_produk }}"
                             style="width: 50px; height: 50px; object-fit: cover;">
                    @else
                        <span>Foto tidak tersedia</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>