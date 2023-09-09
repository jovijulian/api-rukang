<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->create([
            'id' => 'dafe28f2-ab02-48cd-89c9-83dbc6c23125',
            'email' => "admin@admin.com",
            'password' => Hash::make("test1234"),
            'fullname' => "Admin",
            'phone_number' => "081313716261",
            'address' => "Bandung",
            'birthdate' => "2002-07-31",
            'isActive' => "1",
            'isAdmin' => 1,
        ]);
    }
}
