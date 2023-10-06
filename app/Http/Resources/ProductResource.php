<?php

namespace App\Http\Resources;

use App\Models\Shelf;
use App\Models\Module;
use App\Models\Segment;
use App\Models\StatusProductLog;
use App\Models\LocationProductLog;
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
            'segment_place' => $this->segment_place,
            'barcode' => $this->barcode,
            'module_id' => $this->module_id,
            'module' => new ModuleResource(Module::where('id', $this->module_id)->first()),
            'bilah_number' => $this->bilah_number,
            'start_production_date' => $this->start_production_date,
            'finish_production_date' => $this->finish_production_date,
            'shelf_id' => $this->shelf_id,
            'shelf' => new ShelfResource(Shelf::where('id', $this->shelf_id)->first()),
            'description' => $this->description,
            'delivery_date' => $this->delivery_date,
            'status_id' => $this->status_id,
            'status' => $this->status,
            'status_photo' => $this->status_photo,
            'note' => $this->note,
            'status_logs' => StatusProductLogResource::collection(StatusProductLog::query()->where('product_id', $this->id)->orderBy('created_at', 'ASC')->get()),
            'location_logs' => LocationProductLogResource::collection(LocationProductLog::query()->where('product_id', $this->id)->orderBy('created_at', 'ASC')->get()),
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
