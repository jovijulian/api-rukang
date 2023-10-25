<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductPerStatusExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $status_id;

    public function __construct($status_id)
    {
        $this->status_id = $status_id;
    }
    public function collection()
    {
        // return Tool::all();

        return  Product::select('products.module_number',  'products.bilah_number', 'products.shelf_name', 'products.barcode', 'products.segment_place', 'products.status', 'products.shipping_name', 'status_product_logs.number_plate', 'products.current_location', 'products.note', 'products.group_name', 'products.created_by', 'products.created_at', 'products.updated_by', 'products.updated_at')
            ->join('status_product_logs', function ($join) {
                $join->on('products.id', '=', 'status_product_logs.product_id')
                    ->where('status_product_logs.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_product_logs WHERE product_id = products.id)'))
                    ->whereNull('products.deleted_at');
            })
            ->where('products.status_id', $this->status_id)
            ->get()
            ->map(function ($item, $key) {
                return array_merge(['no' => $key + 1], $item->toArray(), [
                    'created_at' => optional($item->created_at)->format('d-m-Y'),
                    'updated_at' => optional($item->updated_at)->format('d-m-Y'),
                ]);
            });
    }

    public function headings(): array
    {
        return [
            [
                'Daftar Aset Berdasarkan Status'
            ],
            [
                'Tanggal Data',
                '',
                Carbon::now()->format('d F Y')
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
                'Status Terkini',
                'Ekspedisi',
                'Plat Nomor',
                'Lokasi Terkini',
                'Catatan Status',
                'Kelompok',
                'Dibuat Oleh',
                'Dibuat Pada',
                'Diubah Oleh',
                'Diubah Pada',
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
    //     ];
    // }

    public function styles(Worksheet $sheet)
    {
        $lastRowIndex = $sheet->getHighestDataRow();
        $lastColumnIndex = 'M';

        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('A2:B2');


        return [
            'A1:A2' => [
                'font' => [
                    'bold' => true,
                    'size' => 11
                ],
            ],
            'A5:' . $lastColumnIndex . '5' => [
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
            'A6:' . $lastColumnIndex . $lastRowIndex => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
        ];
    }
}
