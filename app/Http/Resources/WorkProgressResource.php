<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkProgressResource extends JsonResource
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
            'process_name' => $this->process_name,
            'photo_01' => $this->photo_01,
            'photo_02' => $this->photo_02,
            'photo_03' => $this->photo_03,
            'photo_04' => $this->photo_04,
            'photo_05' => $this->photo_05,
            'photo_06' => $this->photo_06,
            'photo_07' => $this->photo_07,
            'photo_08' => $this->photo_08,
            'photo_09' => $this->photo_09,
            'photo_10' => $this->photo_10,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
