<?php

namespace App\Http\Controllers\Auth;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\UserRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

	public function register(UserRequest $request)
	{
	    try {
	        DB::beginTransaction();
	        $user = new User($request->all());
	        $user->save();
	        $user->assignRole($request->role);
	        $cart = new Cart();
	        $cart->user_id = $user->id;
	        $cart->save();
	        $this->uploadFile($user, $request);
	        DB::commit();Auth::login($user);
            session(['cartId' => $cart]);
            return redirect($this->redirectPath());
	    } catch (\Throwable) {
	        DB::rollBack();
	        return redirect()->back()->with('error', 'Ocurrió un error al crear el usuario.')->withInput();
	    }
	}
}
