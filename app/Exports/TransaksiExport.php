<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;  
 
class TransaksiExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $request;  // <-- tambah ini

    public function __construct($request)  // <-- tambah ini
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Transaksi::with('produk');
    
        // Apply filters
        if ($this->request->filled('tanggal_awal')) {
            $query->whereDate('created_at', '>=', $this->request->tanggal_awal);
        }
        if ($this->request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $this->request->tanggal_akhir);
        }
        if ($this->request->filled('jenis')) {
            $query->where('jenis', $this->request->jenis);
        }
        // Tambah ini
        if ($this->request->filled('status')) {
            $query->where('status_bayar', $this->request->status);
        }
    
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Kode Transaksi',
            'ID Produk',
            'Nama Produk',
            'Nama Supplier',
            'Tanggal Jual',
            'Jumlah',
            'Total Harga',
            'Status Bayar',
            'Created At',
            'Updated At'
        ];
    }

    public function map($transaksi): array
    {
        return [
            $transaksi->id_transaksi,
            $transaksi->kode_transaksi,
            $transaksi->id_produk,
            $transaksi->produk->nama_produk,
            $transaksi->produk->supplier->nama_supplier,
            $transaksi->tgl_jual,
            $transaksi->jumlah,
            'Rp ' . number_format($transaksi->total_harga, 0, ',', '.'),
            $transaksi->status_bayar,
            $transaksi->created_at->format('d/m/Y H:i'),
            $transaksi->updated_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A1:H1' => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4A90E2']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ]
        ];
    }
}