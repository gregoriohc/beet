<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'name' => 'builder',
                'display_name' => 'Builder',
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
            ],
            [
                'name' => 'customer',
                'display_name' => 'Customer',
            ],
        ]);
    }
}
