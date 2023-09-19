<?php

namespace App\Http\Resources;

use App\Models\StatusMaterialLog;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationMaterialLogResource extends JsonResource
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
            'status_material_log_id' => $this->status_material_log_id,
            'status' => StatusMaterialLog::select('status_name')->where('id', $this->status_material_log_id)->first(),
            'material_id' => $this->material_id,
            'current_location' => $this->current_location,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
