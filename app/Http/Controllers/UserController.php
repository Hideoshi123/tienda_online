<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\User\UserRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::get();
		if (!$request->ajax()) return view();
		return response()->json(['user' => $users], 200);
    }


    public function create()
    {
        // view
    }


    public function store(UserRequest $request)
    {
        $user = new User($request->all());
		$user->save();
		$cart = new Cart();
		$cart->user_id = $user->id;
		$cart->save();
		if (!$request->ajax()) return back()->with('success', 'User created');
		return response()->json(['status' => 'User created', 'user' => $user, 'user' => $user], 201);
    }


    /*public function show(Request $request, User $user)
    {
        if (!$request->ajax()) return view();
		return response()->json([], 204);
    }


    public function edit(Request $request, User $user)
    {
        if (!$request->ajax()) return view();
		return response()->json(['user' => $user], 200);
    }*/


    public function update(UserRequest $request, User $user)
    {
		$user->update($request->all());
		//$user->syncRoles([$request->role]);
		if (!$request->ajax()) return view();
		return response()->json([], 204);
    }


    public function destroy(Request $request, User $user)
    {
        $user->delete();
		if (!$request->ajax()) return back()->with('success', 'User deleted');
		return response()->json([], 204);
    }
}
