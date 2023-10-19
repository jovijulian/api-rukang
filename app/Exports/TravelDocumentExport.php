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
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class TravelDocumentExport implements FromView, WithEvents, WithColumnWidths
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
    protected $status_date;
    protected $shipping_name;
    protected $number_plate;
    protected $driver_name;
    protected $driver_telp;

    public function __construct($selected_product, $travel_document_id, $receiver, $from, $checked_by_gudang, $checked_by_keamanan, $checked_by_produksi, $checked_by_project_manager, $driver, $received_by_site_manager, $nomor_travel, $status_date, $shipping_name, $number_plate, $driver_name, $driver_telp)
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
        $this->status_date = $status_date;
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
        $receiver = $this->receiver;
        $from = $this->from;
        $checked_by_gudang = $this->checked_by_gudang;
        $checked_by_keamanan = $this->checked_by_keamanan;
        $checked_by_produksi = $this->checked_by_produksi;
        $checked_by_project_manager = $this->checked_by_project_manager;
        $driver = $this->driver;
        $received_by_site_manager = $this->received_by_site_manager;
        $nomor_travel = $this->nomor_travel;
        $status_date = $this->status_date;
        $shipping_name = $this->shipping_name;
        $number_plate = $this->number_plate;
        $driver_name = $this->driver_name;
        $driver_telp = $this->driver_telp;
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
            'status_date' => $status_date,
            'shipping_name' => $shipping_name,
            'number_plate' => $number_plate,
            'driver_name' => $driver_name,
            'driver_telp' => $driver_telp,
        ]);
    }
    public function columnWidths(): array
    {
        return [
            'A' => 2.86,
            'B' => 13.43,
            'C' => 27.86,
            'D' => 7.86,
            'E' => 7.86,
            'F' => 9.57,
            'G' => 9.57,
            'H' => 13.14,
            'I' => 6.57,
            'J' => 10.29,
            'K' => 9,
            'L' => 9,
            'M' => 18,
            'N' => 9,
            'O' => 9,
        ];
    }
    public function registerEvents(): array
    {
        return [
            // Handle by a closure.
            BeforeExport::class => function (BeforeExport $event) {
                $event->writer->getProperties()->setCreator('Dcrops');
            },
            AfterSheet::class    => function (AfterSheet $event) {
                try {
                } catch (\Exception $e) {
                    Log::error(__CLASS__ . ":" . __FUNCTION__ . '' . $e->getMessage());
                }
            }

        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
}
