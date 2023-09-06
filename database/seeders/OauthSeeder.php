<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OauthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timeNow = \Carbon\Carbon::now();
        \Illuminate\Support\Facades\DB::table('oauth_clients')
            ->insert([
                'name' => 'user-bilah',
                'secret' => '$2a$12$Opqcx/yMfKsPVaaCC8d7DubtfpYa85XEMrCgnkfFsPIu2Iy8KdRV2',
                'provider' => 'users',
                'redirect' => 'http://localhost',
                'personal_access_client' => false,
                'password_client' => true,
                'revoked' => false,
                'created_at' => $timeNow,
                'updated_at' => $timeNow,
            ]);
    }
}
