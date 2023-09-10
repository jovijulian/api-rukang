<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timeNow = \Carbon\Carbon::now();
        Module::query()->create([
            'id' =>  "1",
            'module_number' => "M1",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "admin@admin.com",
            'updated_by' => "",
            'deleted_by' => "",
        ]);

        Module::query()->create([
            'id' =>  "2",
            'module_number' => "M2",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "admin@admin.com",
            'updated_by' => "",
            'deleted_by' => "",
        ]);

        Module::query()->create([
            'id' =>  "3",
            'module_number' => "M3",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "admin@admin.com",
            'updated_by' => "",
            'deleted_by' => "",
        ]);

        Module::query()->create([
            'id' =>  "4",
            'module_number' => "M4",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "admin@admin.com",
            'updated_by' => "",
            'deleted_by' => "",
        ]);

        Module::query()->create([
            'id' =>  "5",
            'module_number' => "M1",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "admin@admin.com",
            'updated_by' => "",
            'deleted_by' => "",
        ]);

        Module::query()->create([
            'id' =>  "6",
            'module_number' => "M2",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "admin@admin.com",
            'updated_by' => "",
            'deleted_by' => "",
        ]);

        Module::query()->create([
            'id' =>  "7",
            'module_number' => "M3",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "admin@admin.com",
            'updated_by' => "",
            'deleted_by' => "",
        ]);

        Module::query()->create([
            'id' =>  "8",
            'module_number' => "M4",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "admin@admin.com",
            'updated_by' => "",
            'deleted_by' => "",
        ]);

        Module::query()->create([
            'id' =>  "9",
            'module_number' => "M5",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "admin@admin.com",
            'updated_by' => "",
            'deleted_by' => "",
        ]);

        Module::query()->create([
            'id' =>  "10",
            'module_number' => "M6",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "admin@admin.com",
            'updated_by' => "",
            'deleted_by' => "",
        ]);
    }
}
