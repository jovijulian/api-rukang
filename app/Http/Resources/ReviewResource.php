<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'customer_id' => $this->customer_id,
            'customer_name' => $this->customer_name,
            'talent_id' => $this->talent_id,
            'talent_name' => $this->talent_name,
            'transaction_id' => $this->transaction_id,
            'transaction' => new TransactionResource(Transaction::where('id', $this->transaction_id)->first()),
            'rating' => $this->rating,
            'comment' => $this->comment,
            'review_date' => $this->review_date,
            'review_photo' => $this->review_photo ? url(Storage::url($this->review_photo)) : null,
            'review_photo2' => $this->review_photo2 ? url(Storage::url($this->review_photo2)) : null,
            'review_photo3' => $this->review_photo3 ? url(Storage::url($this->review_photo3)) : null,
            'review_photo4' => $this->review_photo4 ? url(Storage::url($this->review_photo4)) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}