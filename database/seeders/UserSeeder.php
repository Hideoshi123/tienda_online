<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{

    public function run()
    {
        User::create([
			'number_id' => '4153781',
			'name' => 'Hideoshi',
			'last_name' => 'Peralta',
			'address' => 'Barrio el Japon, calle 9, casa 22-11',
			'phone_number' => '3136409683',
			'email' => 'narazaky0307@gmail.com',
			'password' => '123456789',
		]);
    }
}
