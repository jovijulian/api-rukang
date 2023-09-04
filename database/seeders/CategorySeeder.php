<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timeNow = \Carbon\Carbon::now();
        Category::query()->create([
            'id' =>  "1",
            'category' => "Kulit",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "admin@admin.com",
            'updated_by' => "",
            'deleted_by' => "",
        ]);

        Category::query()->create([
            'id' =>  "2",
            'category' => "Rangka",
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
            'created_by' => "admin@admin.com",
            'updated_by' => "",
            'deleted_by' => "",
        ]);
    }
}
