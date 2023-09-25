<?php

namespace App\Http\Resources;

use App\Models\StatusLog;
use App\Models\StatusToolLog;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationToolLogResource extends JsonResource
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
            'status_tool_log_id' => $this->status_tool_log_id,
            'status' => StatusToolLog::select('status_name')->where('id', $this->status_tool_log_id)->first(),
            'tool_id' => $this->tool_id,
            'current_location' => $this->current_location,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}