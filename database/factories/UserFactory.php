<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'number_id' => fake()->unique()->numerify('##########'),
            'name' => fake()->name(),
            'last_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => '123456789',
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user){
            Cart::factory(1)->userId($user)->create();
            $user->assignRole('buyer');
        });
    }
}
