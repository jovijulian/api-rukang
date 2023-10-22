<?php

namespace App\Exports;

use App\Appraisal;

use Carbon\Carbon;
use App\Application;
use App\Models\Product;
use App\Libraries\ResponseStd;
use App\Models\StatusProductLog;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
// use Maatwebsite\Excel\Concerns\WithColumnWidths;

class TravelDocumentExport implements FromView, WithEvents
{
    protected $selected_product;
    protected $travel_document_id;
    protected $receiver;
    protected $from;
    protected $checked_by_gudang;
    protected $checked_by_keamanan;
    protected $checked_by_produksi;
    protected $checked_by_project_manager;
    protected $driver;
    protected $received_by_site_manager;
    protected $nomor_travel;
    protected $travel_date;
    protected $shipping_name;
    protected $number_plate;
    protected $driver_name;
    protected $driver_telp;
    public $result = [];

    public function __construct($selected_product, $travel_document_id, $receiver, $from, $checked_by_gudang, $checked_by_keamanan, $checked_by_produksi, $checked_by_project_manager, $driver, $received_by_site_manager, $nomor_travel, $travel_date, $shipping_name, $number_plate, $driver_name, $driver_telp)
    {
        $this->selected_product = $selected_product;
        $this->travel_document_id = $travel_document_id;
        $this->receiver = $receiver;
        $this->from = $from;
        $this->checked_by_gudang = $checked_by_gudang;
        $this->checked_by_keamanan = $checked_by_keamanan;
        $this->checked_by_produksi = $checked_by_produksi;
        $this->checked_by_project_manager = $checked_by_project_manager;
        $this->driver = $driver;
        $this->received_by_site_manager = $received_by_site_manager;
        $this->nomor_travel = $nomor_travel;
        $this->travel_date = $travel_date;
        $this->shipping_name = $shipping_name;
        $this->number_plate = $number_plate;
        $this->driver_name = $driver_name;
        $this->driver_telp = $driver_telp;
    }

    public function view(): View
    {
        $products = Product::whereIn('id', $this->selected_product)->get();

        $result = [];

        $groupedProducts = $products->groupBy('segment');

        foreach ($groupedProducts as $segment => $segmentProducts) {
            foreach ($segmentProducts as $sp) {
                $getStatusLog = StatusProductLog::where('product_id', $sp->id)->where('status_id', 32)->first();
                $getStatusLog->travel_document_id = $this->travel_document_id;
                $getStatusLog->save();
            }

            $segmentName = $segmentProducts->first()->segment_name;

            $moduleMap = $segmentProducts->groupBy('module_number')
                ->map(function ($moduleProducts) {
                    return $moduleProducts->first()->module_number . '(' . $moduleProducts->pluck('bilah_number')->implode(',') . ')';
                });
            $description = $moduleMap->implode(', ');
            $totalQty = $segmentProducts->sum('qty');

            $result[] = [
                'segment' => $segmentName,
                'description' => $description,
                'qty' => $totalQty,
            ];
        }
        $this->result = $result;
        $receiver = $this->receiver;
        $from = $this->from;
        $checked_by_gudang = $this->checked_by_gudang;
        $checked_by_keamanan = $this->checked_by_keamanan;
        $checked_by_produksi = $this->checked_by_produksi;
        $checked_by_project_manager = $this->checked_by_project_manager;
        $driver = $this->driver;
        $received_by_site_manager = $this->received_by_site_manager;
        $nomor_travel = $this->nomor_travel;
        $travel_date = $this->travel_date;
        $shipping_name = $this->shipping_name;
        $number_plate = $this->number_plate;
        $driver_name = $this->driver_name;
        $driver_telp = $this->driver_telp;
        // $url_image = 'https://nuart.sgp1.cdn.digitaloceanspaces.com/asset/logo.png';
        return view('export.travel-document', [
            'products' => $result,
            'receiver' => $receiver,
            'from' => $from,
            'checked_by_gudang' => $checked_by_gudang,
            'checked_by_keamanan' => $checked_by_keamanan,
            'checked_by_produksi' => $checked_by_produksi,
            'checked_by_project_manager' => $checked_by_project_manager,
            'driver' => $driver,
            'received_by_site_manager' => $received_by_site_manager,
            'nomor_travel' => $nomor_travel,
            'travel_date' => $travel_date,
            'shipping_name' => $shipping_name,
            'number_plate' => $number_plate,
            'driver_name' => $driver_name,
            'driver_telp' => $driver_telp,
        ]);
    }
    public function registerEvents(): array
    {
        return [
            //     // Handle by a closure.
            BeforeExport::class => function (BeforeExport $event) {
                $event->writer->getProperties()->setCreator('Dcrops');
            },

            AfterSheet::class    => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                // $url_image = 'https://nuart.sgp1.cdn.digitaloceanspaces.com/asset/logo.png';
                // $sheet->getDrawingCollection()->offsetSet('A1', new Drawing());
                // $sheet->getDrawingCollection()->get('A1')->setPath($url_image);
                $baseRowNumber = 16;
                $dataCount = count($this->result);
                foreach ($this->result as $index => $data) {
                    $rowNumber = $baseRowNumber + $index;
                    $dynamicRange = 'A' . $rowNumber . ':' . 'O' . $rowNumber;
                    $firstColumnRange = 'A' . $rowNumber;
                    $jumlahRange = 'G' . $rowNumber;
                    //custom border
                    $borderStyle = \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN;
                    $sheet
                        ->getStyle($dynamicRange)
                        ->getBorders()
                        ->getAllBorders()
                        ->setBorderStyle($borderStyle);

                    $sheet->getStyle($firstColumnRange)->getAlignment()->setWrapText(true)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle($jumlahRange)->getAlignment()->setWrapText(true)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    if ($index === $dataCount - 1) {
                        $footerRowNumber = $rowNumber + 3;
                        $lastFooterRowNumber = $footerRowNumber + 7;
                        $dynamicDataFooter = $lastFooterRowNumber - 1;

                        $footerRange = 'A' . $footerRowNumber . ':O' . $lastFooterRowNumber;
                        $dynamicDataFooterRange = 'A' . $dynamicDataFooter . ':O' . $dynamicDataFooter;
                        $lastRowRange = 'A' . $lastFooterRowNumber . ':O' . $lastFooterRowNumber;

                        // Custom border untuk baris footer

                        // dd($dynamicDataFooterRange);

                        $sheet->getStyle($dynamicDataFooterRange)->getFont()->setSize(14)->setName('Times New Roman')->setBold(true)->setUnderline(true)->setItalic(true);
                        $sheet->getStyle($lastRowRange)->getFont()->setSize(10)->setName('Calibri');
                        $sheet->getStyle($footerRange)->getAlignment()->setWrapText(true)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $footerCustomStyle = $sheet->getStyle($dynamicDataFooterRange);
                        $footerCustomStyle->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE);
                        $footerCustomStyle->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE);

                        $sheet
                            ->getStyle($footerRange)
                            ->getBorders()
                            ->getAllBorders()
                            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    }
                }
                // $event->getDefaultStyle()->getAlignment()->setWrapText(true);

                //header Kepada
                $sheet->getStyle('C6:F10')->getAlignment()->setWrapText(true)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                //header surat jalan
                $sheet->getStyle('H2:O3')->getAlignment()->setWrapText(true)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $headerSuratJalanRange = 'H3:M4';
                $sheet->getStyle($headerSuratJalanRange)->getFont()->setSize(24)->setName('Palatino Linotype');

                //header table
                $sheet->getStyle('A13:A15')->getAlignment()->setWrapText(true)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B13:F15')->getAlignment()->setWrapText(true)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('G13:G15')->getAlignment()->setWrapText(true)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('H13:H15')->getAlignment()->setWrapText(true)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('I13:J15')->getAlignment()->setWrapText(true)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('K13:O15')->getAlignment()->setWrapText(true)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


                //customfont
                $kepadaRange = 'B6';
                $isiKepadaRange = 'C6:F10';
                $namaPerusahaanRange = 'C1:E1';
                $alamatRange = 'C2:E2';
                $emailRange = 'C3:E3';
                $sheet->getStyle($kepadaRange)->getFont()->setSize(14)->setName('Times New Roman');
                $sheet->getStyle($isiKepadaRange)->getFont()->setSize(14)->setName('Calibri');
                $sheet->getStyle($namaPerusahaanRange)->getFont()->setSize(20)->setName('Calibri')->setBold(true)->getColor()->setRGB('833C0C');
                $sheet->getStyle($alamatRange)->getFont()->setSize(11)->setName('Calibri')->getColor()->setRGB('833C0C');
                $sheet->getStyle($emailRange)->getFont()->setSize(11)->setName('Calibri')->getColor()->setRGB('833C0C');

                //custom fill color
                $suratJalanRangeColor = 'H2:O3';
                $noRangeColor = 'A13:A15';
                $namaBarangRangeColor = 'B13:F15';
                $jumlahRangeColor = 'G13:G15';
                $satuanRangeColor = 'H13:H15';
                $packingRangeColor = 'I13:J15';
                $keteranganRangeColor = 'K13:O15';

                $sheet->getStyle($suratJalanRangeColor)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('D9E1F2');
                $sheet->getStyle($noRangeColor)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('D9E1F2');
                $sheet->getStyle($namaBarangRangeColor)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('D9E1F2');
                $sheet->getStyle($jumlahRangeColor)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('D9E1F2');
                $sheet->getStyle($satuanRangeColor)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('D9E1F2');
                $sheet->getStyle($packingRangeColor)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('D9E1F2');
                $sheet->getStyle($keteranganRangeColor)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('D9E1F2');

                //custom border
                $borderStyle = \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN;
                $sheet
                    ->getStyle('A5:F5')
                    ->getBorders()
                    ->getTop()
                    ->setBorderStyle($borderStyle);
                $sheet
                    ->getStyle('A5:A11')
                    ->getBorders()
                    ->getLeft()
                    ->setBorderStyle($borderStyle);
                $sheet
                    ->getStyle('F5:F11')
                    ->getBorders()
                    ->getRight()
                    ->setBorderStyle($borderStyle);
                $sheet
                    ->getStyle('A11:F11')
                    ->getBorders()
                    ->getBottom()
                    ->setBorderStyle($borderStyle);
                $sheet
                    ->getStyle('A13:O15')
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle($borderStyle);

                $cellRange = 'A1:O35';
                $sheet->getStyle($cellRange)->getFont()->setName('Palatino Linotype');
                $sheet->getStyle($cellRange)->getFont()->setSize(14)->setName('Palatino Linotype');
                $event->sheet->getColumnDimension('A')->setWidth(11);
                $event->sheet->getColumnDimension('B')->setWidth(11);
                $event->sheet->getColumnDimension('C')->setWidth(11);
                $event->sheet->getColumnDimension('D')->setWidth(25);
                $event->sheet->getColumnDimension('E')->setWidth(25);
                $event->sheet->getColumnDimension('F')->setWidth(14);
                $event->sheet->getColumnDimension('G')->setWidth(14);
                $event->sheet->getColumnDimension('H')->setWidth(17);
                $event->sheet->getColumnDimension('I')->setWidth(16);
                $event->sheet->getColumnDimension('J')->setWidth(1);
                $event->sheet->getColumnDimension('K')->setWidth(1);
                $event->sheet->getColumnDimension('L')->setWidth(18);
                $event->sheet->getColumnDimension('M')->setWidth(18);
                $event->sheet->getColumnDimension('N')->setWidth(18);
                $event->sheet->getColumnDimension('O')->setWidth(18);

                $to = $sheet->getHighestRow();
                // Apply array of styles to B2:G8 cell range
                $styleArray = [
                    'borders' => [
                        // 'allBorders' => [
                        //     'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        //     'color' => ['argb' => '000000'],
                        // ],
                    ]
                ];



                $sheet->getStyle('A1:AB' . $to)->applyFromArray($styleArray);
                // $event->sheet->styleCells(
                //     'A1:AB12',
                //     [
                //         'borders' => [
                //             'outline' => [
                //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                //                 'color' => ['argb' => '000000'],
                //             ],
                //         ],
                //         'alignment' => [
                //             'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                //         ]
                //     ]
                // );

                // $event->sheet->styleCells(
                //     'A14:AB' . ($to - 3),
                //     [
                //         'borders' => [
                //             'outline' => [
                //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                //                 'color' => ['argb' => '000000'],
                //             ],
                //         ]
                //     ]
                // );
            }

        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
}