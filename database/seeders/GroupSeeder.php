<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timeNow = \Carbon\Carbon::now();
        Group::query()->create([
            'id' =>  "1",
            'group_name' => "Kelompok 1",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "admin@admin.com",
            'updated_by' => "",
            'deleted_by' => "",
        ]);

        Group::query()->create([
            'id' =>  "2",
            'group_name' => "Kelompok 2",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "admin@admin.com",
            'updated_by' => "admin@admin.com",
        ]);

        Group::query()->create([
            'id' =>  "3",
            'group_name' => "Kelompok 3",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "admin@admin.com",
            'updated_by' => "admin@admin.com",
        ]);

        Group::query()->create([
            'id' =>  "4",
            'group_name' => "Kelompok 4",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "admin@admin.com",
            'updated_by' => "admin@admin.com",
        ]);

        Group::query()->create([
            'id' =>  "5",
            'group_name' => "Kelompok 5",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "admin@admin.com",
            'updated_by' => "admin@admin.com",
        ]);
    }
}
