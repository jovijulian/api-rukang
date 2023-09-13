<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\Module;
use App\Models\Segment;
use App\Models\StatusLog;
use App\Models\Description;
use App\Http\Resources\ModuleResource;
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
            'segment' => new SegmentResource(Segment::where('id', $this->segment_id)->first()),
            'barcode' => $this->barcode,
            'module_id' => $this->module_id,
            'module' => new ModuleResource(Module::where('id', $this->module_id)->first()),
            'bilah_number' => $this->bilah_number,
            'production_date' => $this->production_date,
            'shelf_number' => $this->shelf_number,
            'quantity' => $this->quantity,
            'nut_bolt' => $this->nut_bolt,
            'description_id' => $this->description_id,
            'description' => new DescriptionResource(Description::where('id', $this->description_id)->first()),
            'delivery_date' => $this->delivery_date,
            'status_id' => $this->status_id,
            'status' => $this->status,
            'status_date' => $this->status_date,
            'status_photo' => $this->status_photo,
            'note' => $this->note,
            'status_logs' => StatusLogResource::collection(StatusLog::query()->where('product_id', $this->id)->get()),
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
