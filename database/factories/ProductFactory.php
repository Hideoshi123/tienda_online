<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;


class ProductFactory extends Factory
{
    protected $model = Product::class;

    private function createBigWord()
	{
		$word1 = fake()->unique()->word();
		$word2 = fake()->unique()->word();

		return $word1 . $word2;
	}

    public function definition()
    {
        return[
            'category_id' => fake()->numberBetween(1, 10), // Ajusta esto según tus necesidades
            'name' => $this->createBigWord(),
			'description' => fake()->paragraph(),
			'stock' => fake()->randomDigit(),
			'price' => number_format($this->faker->randomFloat(2, 1, 99), 2, '.', ''),
        ];
    }

    public function configure()
	{
	    return $this->afterCreating(function (Product $product) {
	        // Lista de posibles archivos
	        $files = [
	            '/storage/images/products/default.webp',
	            '/storage/images/products/funda_almohada.jfif',
	            '/storage/images/products/pack_gamer.webp',
				'/storage/images/products/pack_gamer.webp',
				'/storage/images/products/pala.jpg',
				'/storage/images/products/zapato.jpg',
	        ];

	        // Seleccionar un archivo aleatorio de la lista
	        $randomFile = $files[array_rand($files)];

	        $file = new File(['route' => $randomFile]);
	        $product->file()->save($file);
	    });
	}
}
