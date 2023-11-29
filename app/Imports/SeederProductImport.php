<?php

namespace App\Imports;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Module;
use App\Models\Product;
use App\Models\StatusProductLog;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SeederProductImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $segmentCode = substr($row['segment_name'], -2);
        $moduleCode = $row['module_number'];
        $bd = $row['bilah_number'];
        $cd = substr($row['category'], 0, 1);

        if ($row['start_production_date'] != null) {
            $unixTimestamp = ($row['start_production_date'] - 25569) * 86400;
            $start_production_date = date("Y-m-d", $unixTimestamp);
        } else {
            $start_production_date = null;
        }
        if ($row['finish_production_date'] != null) {
            $unixTimestamp = ($row['finish_production_date'] - 25569) * 86400;
            $finish_production_date = date("Y-m-d", $unixTimestamp);
        } else {
            $finish_production_date = null;
        }
        if ($row['delivery_date'] != null) {
            $unixTimestamp = ($row['delivery_date'] - 25569) * 86400;
            $delivery_date = date("Y-m-d", $unixTimestamp) ?? null;
        } else {
            $delivery_date = null;
        }
        $product = new Product([
            'id' =>  Uuid::uuid4()->toString(),
            'category_id' => $row['category_id'],
            'category' => $row['category'],
            'segment_id' => $row['segment_id'],
            'segment_name' => $row['segment_name'],
            'segment_place' => null,
            'barcode' => 'S' . $segmentCode . $moduleCode . $bd . $cd,
            'module_id' => $row['module_id'],
            'module_number' => $row['module_number'],
            'bilah_number' => $row['bilah_number'],
            'start_production_date' => $start_production_date ?? null,
            'finish_production_date' => $finish_production_date ?? null,
            'shelf_id' => $row['shelf_id'],
            'shelf_name' => $row['shelf_name'],
            'description' => $row['description'],
            'delivery_date' => $delivery_date ?? null,
            'qty' => $row['qty'],
            'status_id' => $row['status_id'],
            'status' => $row['status'],
            'status_photo' => null,
            'note' => $row['note'],
            'shipping_id' => $row['shipping_id'],
            'shipping_name' => $row['shipping_name'],
            'current_location' => $row['current_location'],
            'group_id' => $row['group_id'],
            'group_name' => $row['group_name'],
            'created_at' => Carbon::now(),
            'updated_at' => null,
            'created_by' => 'Admin',
            'updated_by' => null,
        ]);
        $product->save();
        //save to log
        $checkStatus = $product->status_id;
        if ($checkStatus == 17) {
            $statusDate = $product->finish_production_date ?? '2023-03-30';
        } else if ($checkStatus == 20 || $checkStatus == 21) {
            $statusDate = $product->delivery_date ?? '2023-03-30';
        } else {
            $statusDate = '2023-03-30';
        }
        $statusLog = new StatusProductLog([
            'id' => Uuid::uuid4()->toString(),
            'product_id' => $product->id,
            'status_id' => $product->status_id,
            'status_name' => $product->status,
            'status_date' => $statusDate,
            'status_photo' => $product->status_photo,
            'note' => $product->note,
            'shipping_id' => $product->shipping_id,
            'shipping_name' => $product->shipping_name,
            'number_plate' => null,
            'created_at' => Carbon::now(),
            'updated_at' => null,
            'created_by' => 'Admin',
            'updated_by' => null,
        ]);
        $statusLog->save();
    }
}
