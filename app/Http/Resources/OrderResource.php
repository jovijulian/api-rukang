<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $whatsappMessage = "Halo " . $this->talent_name . "!\n\nSaya " . $this->customer_name . " adalah pelanggan dari Aplikasi Rukang. Saya ingin memesan jasa " . $this->category_name . " untuk melakukan perbaikan di rumah saya.\n\nBerikut detail pesanannya:\nTanggal dan Waktu yang diinginkan: " . date('d/m/Y', strtotime($this->order_date)) . " - " . $this->repair_time . " \nDeskripsi Pekerjaan: " . $this->detail_requirement . "\nAlamat Pengerjaan: " . $this->repair_address . "\n\nApakah Anda tersedia pada waktu tersebut? Mohon konfirmasi ketersediaan Anda dan perkiraan biaya untuk pekerjaan ini.\n\nTerima kasih!";
        $encodedMessage = rawurlencode($whatsappMessage);
        $link = 'https://api.whatsapp.com/send/?phone=' . $this->talent_phone_number . '&text=' . $encodedMessage;
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'customer_name' => $this->customer_name,
            'talent_id' => $this->talent_id,
            'talent_name' => $this->talent_name,
            'talent_phone_number' => $this->talent_phone_number,
            'order_date' => $this->order_date,
            'repair_time' => $this->repair_time,
            'repair_address' => $this->repair_address,
            'category_id' => $this->category_id,
            'category_name' => $this->category_name,
            'detail_requirement' => $this->detail_requirement,
            'phone_number' => $this->phone_number,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'link_wa' => $link,
        ];
    }
}
