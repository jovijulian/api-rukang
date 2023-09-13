<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timeNow = \Carbon\Carbon::now();
        Status::query()->create([
            'id' =>  "1",
            'status' => "Selesai Produksi",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "Admin",
            'updated_by' => "",
            'deleted_by' => "",
        ]);

        Status::query()->create([
            'id' =>  "2",
            'status' => "Siap dikirim",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "Admin",
            'updated_by' => "",
            'deleted_by' => "",
        ]);

        Status::query()->create([
            'id' =>  "3",
            'status' => "Siap dikirim (cat di IKN)",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "Admin",
            'updated_by' => "",
            'deleted_by' => "",
        ]);

        Status::query()->create([
            'id' =>  "4",
            'status' => "Sedang dikirim",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "Admin",
            'updated_by' => "",
            'deleted_by' => "",
        ]);

        Status::query()->create([
            'id' =>  "5",
            'status' => "Diterima",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "Admin",
            'updated_by' => "",
            'deleted_by' => "",
        ]);

        Status::query()->create([
            'id' =>  "6",
            'status' => "Disimpan",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "Admin",
            'updated_by' => "",
            'deleted_by' => "",
        ]);

        Status::query()->create([
            'id' =>  "7",
            'status' => "Perakitan",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "Admin",
            'updated_by' => "",
            'deleted_by' => "",
        ]);

        Status::query()->create([
            'id' =>  "8",
            'status' => "Instalasi",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "Admin",
            'updated_by' => "",
            'deleted_by' => "",
        ]);
    }
}
