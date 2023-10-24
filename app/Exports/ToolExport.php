<?php

namespace App\Exports;

use App\Models\Tool;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ToolExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        // return Tool::all();
        return Tool::select('tool_name', 'type', 'serial_number' , 'amount', 'note', 'status', 'shipping_name', 'current_location', 'status_note', 'group_name', 'created_by', 'updated_by')
            ->get()
            ->map(function ($item, $key) {
                return array_merge(['no' => $key + 1], $item->toArray());
            });
    }

    public function headings(): array
    {
        return [
            [
                'Daftar Alat'
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
                'Nama Alat',
                'Tipe Alat',
                'Nomor Seri',
                'Jumlah',
                'Catatan',
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
