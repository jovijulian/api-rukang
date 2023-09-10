<?php

namespace Database\Seeders;

use App\Models\Description;
use Illuminate\Database\Seeder;

class DescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timeNow = \Carbon\Carbon::now();
        Description::query()->create([
            'id' =>  "1",
            'description' => "PANJANG : 2,12 M",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "admin@admin.com",
            'updated_by' => "",
            'deleted_by' => "",
        ]);

        Description::query()->create([
            'id' =>  "2",
            'description' => "PANJANG : 3,42 M",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "admin@admin.com",
            'updated_by' => "",
            'deleted_by' => "",
        ]);

        Description::query()->create([
            'id' =>  "3",
            'description' => "PANJANG : 4,44 M",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "Admin",
            'updated_by' => "",
            'deleted_by' => "",
        ]);

        Description::query()->create([
            'id' =>  "4",
            'description' => "PANJANG : 3,12 M",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "Admin",
            'updated_by' => "",
            'deleted_by' => "",
        ]);
    }
}
