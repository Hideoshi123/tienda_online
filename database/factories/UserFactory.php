<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
	protected $model = User::class;

    public function definition()
    {
        return [
			'number_id' => fake()->unique()->numerify('##########'),
			'name' => fake()->name(),
			'last_name' => fake()->lastName(),
			'address' => fake()->address(),
			'phone_number' => fake()->unique()->numerify('##########'),
			'email' => fake()->unique()->email(),
            'password' => bcrypt(123456789),
            'remember_token' => Str::random(10),
        ];
    }

	public function configure()
	{
		return $this->afterCreating(function (User $user) {
			Cart::factory(1)->userId($user)->create();
		});
	}
}
