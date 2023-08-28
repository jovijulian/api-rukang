<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OauthKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (config('app.env') == 'local')
            $timeNow = \Carbon\Carbon::now();
        \Illuminate\Support\Facades\DB::table('oauth_clients')
            ->insert([
                'id' => '99b8eead-a1d6-4eba-abe8-208a988cbfd3',
                'name' => 'users',
                'secret' => 'nBpVIARTIeUS8iVPjVSBhRlsFymN4YX0h4225nej',
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
