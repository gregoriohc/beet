<?php

use App\Model\Role;
use App\Model\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Builder',
                'email' => 'builder@example.com',
                'password' => bcrypt('secret'),
                'remember_token' => str_random(32),
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('secret'),
                'remember_token' => str_random(32),
            ],
            [
                'name' => 'Customer',
                'email' => 'customer@example.com',
                'password' => bcrypt('secret'),
                'remember_token' => str_random(32),
            ],
        ]);

        $roleBuilder = Role::where('name','=','builder')->first();
        $roleAdmin = Role::where('name','=','admin')->first();
        $roleCustomer = Role::where('name','=','customer')->first();

        $user = User::where('email','=','builder@example.com')->first();
        $user->attachRole($roleBuilder);

        $user = User::where('email','=','admin@example.com')->first();
        $user->attachRole($roleAdmin);

        $user = User::where('email','=','customer@example.com')->first();
        $user->attachRole($roleCustomer);
    }
}
