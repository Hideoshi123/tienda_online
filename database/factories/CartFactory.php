<?php

namespace Database\Factories;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
	protected $model = Cart::class;

	public function userId($user)
	{
		return $this->state([
			'user_id' => $user->id
		]);
	}

    public function definition()
    {
        return [
        ];
    }
}
