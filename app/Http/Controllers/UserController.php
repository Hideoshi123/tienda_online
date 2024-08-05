<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\UserRequest;

class UserController extends Controller
{
	public function index(Request $request)
	{
		$users = User::with('roles')->get();
		//dd($users[0]->toArray());
		if (!$request->ajax()) return view('users.index', compact('users'));
		return response()->json(['users' => $users], 200);
	}

	public function log()
    {
        // Obtiene el usuario autenticado
		/** @var \App\Models\User\User $user */
        $user = Auth::user();
		$user->load('cart');
        // Devuelve los datos del usuario
        return response()->json($user);
    }

	// public function create()
	// {
	// 	$roles = Role::all()->pluck('name');
	// 	return view('users.create', compact('roles'));
	// }


	// public function store(UserRequest $request)
	// {
	// 	$user = new User($request->all());
	// 	$user->save();
	// 	$user->assignRole($request->role);
    //     $cart = new Cart();
	// 	$cart->user_id = $user->id;
	// 	$cart->save();
	// 	if (!$request->ajax()) return back()->with('success', 'User created');
	// 	return response()->json(['status' => 'User created', 'user' => $user, 'user' => $user], 201);
	// }


	// public function edit(User $user)
	// {
	// 	$roles = Role::all()->pluck('name');
	// 	return view('users.edit', compact('user', 'roles'));
	// }


	// public function update(UserRequest $request, User $user)
	// {
	// 	$user->update($request->all());
	// 	$user->syncRoles([$request->role]);
	// 	if (!$request->ajax()) return back()->with('success', 'User updated');
	// 	return response()->json([], 204);
	// }


	// public function destroy(Request $request, User $user)
	// {
	// 	$user->delete();
	// 	if (!$request->ajax()) return back()->with('success', 'User deleted');
	// 	return response()->json([], 204);
	// }
}
