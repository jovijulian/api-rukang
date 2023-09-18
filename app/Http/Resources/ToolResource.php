<?php

namespace App\Http\Resources;

use App\Models\StatusToolLog;
use App\Models\LocationToolLog;
use App\Http\Resources\StatusToolLogResource;
use App\Http\Resources\LocationToolLogResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ToolResource extends JsonResource
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
            'type' => $this->type,
            'tool_name' => $this->tool_name,
            'serial_number' => $this->serial_number,
            'amount' => $this->amount,
            'note' => $this->note,
            'status_id' => $this->status_id,
            'status' => $this->status,
            'status_photo' => $this->status_photo,
            'status_note' => $this->status_note,
            'status_tool_logs' => StatusToolLogResource::collection(StatusToolLog::query()->where('tool_id', $this->id)->get()),
            'location_tool_logs' => LocationToolLogResource::collection(LocationToolLog::query()->where('tool_id', $this->id)->get()),
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