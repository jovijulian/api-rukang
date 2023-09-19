<?php

namespace App\Http\Resources;

use App\Models\LocationLog;
use App\Models\Status;
use App\Models\StatusProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusProductLogResource extends JsonResource
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
            'product_id' => $this->product_id,
            'status_id' => $this->status_id,
            'status_name' => $this->status_name,
            'status_photo' => $this->status_photo,
            'status_photo2' => $this->status_photo2,
            'status_photo3' => $this->status_photo3,
            'status_photo4' => $this->status_photo4,
            'status_photo5' => $this->status_photo5,
            'status_photo6' => $this->status_photo6,
            'status_photo7' => $this->status_photo7,
            'status_photo8' => $this->status_photo8,
            'status_photo9' => $this->status_photo9,
            'status_photo10' => $this->status_photo10,
            'shipping_id' => $this->shipping_id,
            'shipping_name' => $this->shipping_name,
            'number_plate' => $this->number_plate,
            'note' => $this->note,
            'status_detail' => StatusProduct::select('status', 'need_expedition')->where('id', $this->status_id)->first(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
