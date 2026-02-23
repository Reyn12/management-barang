<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SupplierExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return Supplier::all();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Supplier',
            'Alamat',
            'No Telp',
            'Email'
        ];
    }

    public function map($supplier): array
    {
        static $no = 0;
        $no++;
        
        return [
            $no,
            $supplier->nama_supplier,
            $supplier->alamat,
            $supplier->no_telp,
            $supplier->email
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Style untuk header
            1 => [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],
            // Style untuk header background
            'A1:E1' => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4A90E2']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
            // Style untuk seluruh data
            'A2:E'.$sheet->getHighestRow() => [
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
            // Style khusus untuk kolom nomor
            'A2:A'.$sheet->getHighestRow() => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            // Style khusus untuk kolom no telp
            'D2:D'.$sheet->getHighestRow() => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}