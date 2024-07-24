<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
	protected $model = Product::class;

	private function createBigWord()
	{
		$word1 = fake()->word();
		$word2 = fake()->word();

		return $word1 . $word2;
	}

    public function definition()
    {
        return [
            'category_id' => fake()->numberBetween(1, 10), // Ajusta esto según tus necesidades
            'name' => $this->createBigWord(),
			'description' => fake()->paragraph(),
			'stock' => fake()->randomDigit(),
        ];
    }
}
