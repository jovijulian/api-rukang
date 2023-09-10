<?php

namespace Database\Seeders;

use App\Models\Segment;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SegmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timeNow = \Carbon\Carbon::now();
        Segment::query()->create([
            'id' =>  "1",
            'segment_name' => "Segmen 6",
            'segment_place' => "Rak Segmen 6",
            'barcode_color' => "",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "Admin",
            'updated_by' => "",
            'deleted_by' => "",
        ]);

        Segment::query()->create([
            'id' =>  "2",
            'segment_name' => "Segmen 7",
            'segment_place' => "Rak Segmen 7",
            'barcode_color' => "Ungu",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "Admin",
            'updated_by' => "",
            'deleted_by' => "",
        ]);

        Segment::query()->create([
            'id' =>  "3",
            'segment_name' => "Segmen 8",
            'segment_place' => "Rak Segmen 8",
            'barcode_color' => "Abu-abu",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "Admin",
            'updated_by' => "",
        ]);
    }
}