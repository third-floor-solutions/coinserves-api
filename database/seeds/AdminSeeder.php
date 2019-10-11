<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('blockchains')->insert([
            'wallet_address'=>'1234',
            'id'=>'1234'
        ]);

        DB::table('users')->insert([
            'id' => 'a651af52-cca5-477e-8d0b-6e176f24beca',
            'email' => 'thirdfloor.solutions@gmail.com',
            'display_name' => 'administrator',
            'user_type' => 'admin',
            'wallet_address' => '1234',
            'password' => Hash::make('T005h0rt123!')
        ]);
    }
}
