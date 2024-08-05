<?php

namespace App\Http\Controllers\Auth;

use App\Models\Cart;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\AuthRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(AuthRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Verifica si el usuario ya tiene un carrito, si no, crea uno nuevo
            if (!$user->cart) {
                $cart = Cart::create(['user_id' => $user->id]);
                session(['cartId' => $cart->id]);
            } else {
                session(['cartId' => $user->cart->id]);
            }

            return redirect()->intended($this->redirectPath());
        }

        return back()->withErrors([
            'email' => 'Las credenciales ingresadas no coinciden con ninguna cuenta.',
        ]);
    }
}
