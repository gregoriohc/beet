<?php

use Illuminate\Database\Seeder;

class OauthClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clientId = str_random(40);
        $clientSecret = str_random(40);

        DB::table('oauth_clients')->insert([
            'id' => $clientId,
            'secret' => $clientSecret,
            'name' => config('api.name'),
        ]);

        echo 'Oauth Client ID: ' . $clientId . PHP_EOL;
        echo 'Oauth Client Secret: ' . $clientSecret . PHP_EOL;
    }
}
