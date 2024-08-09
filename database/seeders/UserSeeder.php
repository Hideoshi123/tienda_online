<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    public function run()
    {
        $user = new User([
            'number_id' => '4153781',
            'name' => 'Gabriel',
            'last_name' => 'Nolasco',
            'email' => 'gabriel.govinda.GG@gmail.com',
            'password' => '123456789',
        ]);
        $user->save();
        $user->assignRole('admin');
		$file = new File(['route' => '/storage/images/users/default.jpg']);
        $user->file()->save($file);
    }
}
