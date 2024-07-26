<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\CategorySeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
		$this->call([
			CategorySeeder::class,
			UserSeeder::class
		]);
		User::factory(20)->create();
		Category::factory(10)->create();
		Product::factory(10)->create();
    }
}
