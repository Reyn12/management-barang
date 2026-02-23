<!DOCTYPE html>
<html>
<head>
    <title>Invoice {{ $transaksi->kode_transaksi }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <h2>Invoice #{{ $transaksi->kode_transaksi }}</h2>
        <p>Tanggal: {{ date('d/m/Y', strtotime($transaksi->tgl_jual)) }}</p>
        
        <table class="table">
            <tr>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
            </tr>
            <tr>
                <td>{{ $transaksi->produk->nama_produk }}</td>
                <td>{{ $transaksi->jumlah }}</td>
                <td>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
            </tr>
        </table>
        
        <p><strong>Status Pembayaran:</strong> {{ $transaksi->status_bayar }}</p>
    </div>
</body>
</html>