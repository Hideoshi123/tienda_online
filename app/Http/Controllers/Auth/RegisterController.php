<?php

namespace App\Http\Controllers\Auth;

use App\Models\Cart;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Requests\User\UserRegisterRequest;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function register(UserRegisterRequest $request)
    {
        $user = new User($request->all());
        $user->save();
        $user->assignRole('buyer');
		$cart = new Cart();
		$cart->user_id = $user->id;
		$cart->save();
        Auth::login($user);
		session(['cartId' => $cart]);
        return redirect($this->redirectPath());
    }
}
