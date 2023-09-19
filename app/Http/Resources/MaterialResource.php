<?php

namespace App\Http\Resources;

use App\Models\StatusMaterialLog;
use App\Models\LocationMaterialLog;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\StatusMaterialLogResource;
use App\Http\Resources\LocationMaterialLogResource;

class MaterialResource extends JsonResource
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
            'material_name' => $this->material_name,
            'material_note' => $this->material_note,
            'status_id' => $this->status_id,
            'status' => $this->status,
            'status_photo' => $this->status_photo,
            'status_note' => $this->status_note,
            'status_mmaterial_logs' => StatusMaterialLogResource::collection(StatusMaterialLog::query()->where('material_id', $this->id)->get()),
            'location_mmaterial_logs' => LocationMaterialLogResource::collection(LocationMaterialLog::query()->where('material_id', $this->id)->get()),
            'shipping_id' => $this->shipping_id,
            'shipping_name' => $this->shipping_name,
            'current_location' => $this->current_location,
            'group_id' => $this->group_id,
            'group_name' => $this->group_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}