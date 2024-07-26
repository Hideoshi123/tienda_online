<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $carts = Cart::with(['cartProducts.product'])->get();
		if (!$request->ajax()) return view();
		return response()->json(['carts' => $carts], 200);
    }

    /*
    public function edit(Request $request, User $user)
    {
        if (!$request->ajax()) return view();
		return response()->json(['user' => $user], 200);
    }*/

}
