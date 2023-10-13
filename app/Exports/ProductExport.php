<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $segment;

    public function __construct($segment)
    {
        $this->segment = $segment;
    }

    public function headings(): array
    {
        return [
            'Kategori',
            'Segmen',
            'Tempat Segmen',
            'Barcode',
            'Nomor Modul',
            'Nomor Bilah',
            'Tanggal Mulai Produksi',
            'Tanggal Selesai Produksi',
            'Rak',
            'Keterangan',
            'Tanggal Pengiriman',
            'status',
            'note',
            'Nama Ekspedisi',
            'Lokasi Terkini',
            'Grup',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 35,
            'B' => 35,
            'C' => 35,
            'D' => 35,
            'E' => 35,
            'F' => 35,
            'G' => 35,
            'H' => 35,
            'I' => 35,
            'J' => 35,
            'K' => 35,
            'L' => 35,
            'M' => 35,
            'N' => 35,
            'O' => 35,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRowIndex = $sheet->getHighestDataRow();

        $lastColumnIndex = 'O';


        return [
            'A1:' . $lastColumnIndex . '1' => [
                'font' => [
                    'bold' => true,
                    'size' => 12
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
            'A2:' . $lastColumnIndex . $lastRowIndex => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
        ];
    }

    public function collection()
    {
        if ($this->segment != null) {
            $products = Product::where('segment_id', $this->segment)
                ->select('category', 'segment_name', 'segment_place', 'barcode', 'module_number', 'bilah_number', 'start_production_date', 'finish_production_date', 'shelf_name', 'description', 'delivery_date', 'status', 'note', 'shipping_name', 'current_location', 'group_name')
                ->get();

            return $products;
        } else {
            $products = Product::select('category', 'segment_name', 'segment_place', 'barcode', 'module_number', 'bilah_number', 'start_production_date', 'finish_production_date', 'shelf_name', 'description', 'delivery_date', 'status', 'note', 'shipping_name', 'current_location', 'group_name')
                ->get();

            return $products;
        }
    }
}
