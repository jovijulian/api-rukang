<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class TalentResource extends JsonResource
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
            'fullname' => $this->fullname,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'about_me' => $this->about_me,
            'address' => $this->address,
            'rating' => $this->rating,
            'category_id1' => $this->category_id1,
            'category_name1' => $this->category_name1,
            'category1' => new CategoryResource(Category::where('id', $this->category_id1)->first()),
            'category_id2' => $this->category_id2,
            'category_name2' => $this->category_name2,
            'category2' => new CategoryResource(Category::where('id', $this->category_id2)->first()),
            'category_id3' => $this->category_id3,
            'category_name3' => $this->category_name3,
            'category3' => new CategoryResource(Category::where('id', $this->category_id3)->first()),
            'category_id4' => $this->category_id4,
            'category_name4' => $this->category_name4,
            'category4' => new CategoryResource(Category::where('id', $this->category_id4)->first()),
            'image_profile' => $this->image_profile ? url(Storage::url($this->image_profile)) : null,
            'image_profile2' => $this->image_profile2 ? url(Storage::url($this->image_profile2)) : null,
            'image_profile3' => $this->image_profile3 ? url(Storage::url($this->image_profile3)) : null,
            'image_profile4' => $this->image_profile4 ? url(Storage::url($this->image_profile4)) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
