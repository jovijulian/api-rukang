<?php

namespace App\Exports;

use App\Models\StatusProduct;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StatusProductExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        // return Tool::all();
        return StatusProduct::select('status', 'need_expedition', 'created_by', 'created_at', 'updated_by', 'updated_at')
            ->get()
            ->map(function ($item, $key) {
                if ($item->need_expedition == 1) {
                    $msg = 'Ya';
                } else {
                    $msg = 'Tidak';
                }
                return array_merge(['no' => $key + 1], $item->toArray(), [
                    'created_at' => optional($item->created_at)->format('d-m-Y'),
                    'updated_at' => optional($item->updated_at)->format('d-m-Y'),
                    'need_expedition' => $msg
                ]);
            });
    }

    public function headings(): array
    {
        return [
            [
                'Daftar Status Produk'
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
                'Nama Status',
                'Butuh Ekspedisi',
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
        $lastColumnIndex = 'G';

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
