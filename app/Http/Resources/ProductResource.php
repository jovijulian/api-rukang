<?php

namespace App\Http\Resources;

use App\Models\StatusLog;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'category' => $this->category,
            'segment_id' => $this->segment_id,
            'segment_name' => $this->segment_name,
            'barcode' => $this->barcode,
            'module_number' => $this->module_number,
            'bilah_number' => $this->bilah_number,
            'production_date' => $this->production_date,
            'shelf_number' => $this->shelf_number,
            '1/0' => $this->{'"1/0"'},
            'nut_bolt' => $this->nut_bolt,
            'description_id' => $this->description_id,
            'description' => $this->description,
            'delivery_date' => $this->delivery_date,
            'process_photo' => $this->process_photo,
            'status_log' => new StatusLogResource(StatusLog::where('product_id', $this->id)->first()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,

        ];
    }
}
