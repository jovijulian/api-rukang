<?php

namespace App\Http\Resources;

use App\Models\StatusLog;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationLogResource extends JsonResource
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
            'status_log_id' => $this->status_log_id,
            'status' => StatusLog::select('status_name')->where('id', $this->status_log_id)->first(),
            'product_id' => $this->product_id,
            'current_location' => $this->current_location,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
