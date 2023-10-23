<?php

namespace App\Exports;

use App\Models\Category;
use App\Models\Product;
use App\Models\Segment;
use Carbon\Carbon;
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
    protected $category;

    public function __construct($segment, $category)
    {
        $this->segment = $segment;
        $this->category = $category;
    }

    public function headings(): array
    {
        $segmentData = Segment::find($this->segment);
        $categoryData = Category::find($this->category);

        return [
            [
                'Daftar ' . $categoryData->category
            ],
            [
                'Tanggal Data',
                '',
                Carbon::now()->format('d F Y')
            ],
            [
                'Segmen',
                '',
                $segmentData->segment_name
            ],
            [
                'Warna Barcode',
                '',
                $segmentData->barcode_color
            ],
            [],
            [],
            [
                'No',
                'No. Modul',
                'No. Bilah',
                'No. Rak',
                'Barcode',
                'Tempat Segmen',
                'Tanggal Mulai Produksi',
                'Tanggal Selesai Produksi',
                'Tanggal Pengiriman',
                'Keterangan',
                'Status Terkini',
                'Ekspedisi',
                'Lokasi Terkini',
                'Catatan Status',
                'Kelompok',
                'Dibuat Oleh',
                'Diubah Oleh',
            ]
        ];
    }

    // public function columnWidths(): array
    // {
    //     return [
    //         'A' => 35,
    //         'B' => 35,
    //         'C' => 35,
    //         'D' => 35,
    //         'E' => 35,
    //         'F' => 35,
    //         'G' => 35,
    //         'H' => 35,
    //         'I' => 35,
    //         'J' => 35,
    //         'K' => 35,
    //         'L' => 35,
    //         'M' => 35,
    //         'N' => 35,
    //         'O' => 35,
    //         'P' => 35,
    //     ];
    // }

    public function styles(Worksheet $sheet)
    {
        $lastRowIndex = $sheet->getHighestDataRow();
        $lastColumnIndex = 'Q';

        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('A2:B2');
        $sheet->mergeCells('A3:B3');
        $sheet->mergeCells('A4:B4');


        return [
            'A1:A4' => [
                'font' => [
                    'bold' => true,
                    'size' => 11
                ],
            ],
            'A7:' . $lastColumnIndex . '7' => [
                'font' => [
                    'bold' => true,
                    'size' => 11
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
            'A8:' . $lastColumnIndex . $lastRowIndex => [
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
        if ($this->segment != null || $this->category != null) {
            $products = Product::where('segment_id', $this->segment)
                ->where('category_id', $this->category)
                ->select('module_number', 'bilah_number', 'shelf_name', 'barcode', 'segment_place', 'start_production_date', 'finish_production_date', 'delivery_date', 'description', 'status', 'shipping_name', 'current_location', 'note', 'group_name', 'created_by', 'updated_by')
                // ->select('category', 'segment_place', 'segment_place', 'barcode', 'module_number', 'bilah_number', 'start_production_date', 'finish_production_date', 'shelf_name', 'description', 'delivery_date', 'status', 'note', 'shipping_name', 'current_location', 'group_name')
                ->get()
                ->map(function ($item, $key) {
                    return array_merge(['no' => $key + 1], $item->toArray());
                });

            return $products;
        } else {
            $products = Product::select('module_number', 'bilah_number', 'shelf_name', 'barcode', 'segment_place', 'start_production_date', 'finish_production_date', 'delivery_date', 'description', 'status', 'shipping_name', 'current_location', 'note', 'group_name', 'created_by', 'updated_by')   
                ->get()
                ->map(function ($item, $key) {
                    $item['no'] = $key + 1; // Menambahkan nomor
                    return $item;
                });

            return $products;
        }
    }
}
