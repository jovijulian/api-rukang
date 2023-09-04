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
            'description' => "SIAP DIKIRIM (CAT DI IKN)",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "admin@admin.com",
            'updated_by' => "",
            'deleted_by' => "",
        ]);
    }
}
