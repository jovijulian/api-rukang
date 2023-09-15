<?php

namespace App\Http\Resources;

use App\Models\LocationLog;
use App\Models\Status;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusLogResource extends JsonResource
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
            'note' => $this->note,
            'status_detail' => Status::select('status', 'need_expedition')->where('id', $this->status_id)->first(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
