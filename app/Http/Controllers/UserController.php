<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\UploadFile;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\UserRequest;

class UserController extends Controller
{

	use UploadFile;

	public function index(Request $request)
	{
		$users = User::with('roles')->get();
		if (!$request->ajax()) return view('users.index', compact('users'));
		return response()->json(['users' => $users], 200);
	}

	public function log()
    {
		/** @var \App\Models\User\User $user */
        $user = Auth::user();
		$user->load('cart');
        return response()->json($user);
    }

	public function create()
	{
		$roles = Role::all()->pluck('name');
		return view('users.create', compact('roles'));
	}


	public function store(UserRequest $request)
	{
	    try {
	        DB::beginTransaction();
	        $user = new User($request->all());
	        $user->save();
	        $user->assignRole($request->role);
			if ($request->role === 'buyer'){
				$cart = new Cart();
	        	$cart->user_id = $user->id;
	        	$cart->save();
			}
	        $this->uploadFile($user, $request);
	        DB::commit();
			return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
	    } catch (\Throwable) {
	        DB::rollBack();
	        return redirect()->back()->with('error', 'Ocurrió un error al crear el usuario.')->withInput();
	    }
	}

	public function edit(User $user)
	{
		$roles = Role::all()->pluck('name');
		return view('users.edit', compact('user', 'roles'));
	}


	public function update(UserRequest $request, User $user)
	{
		try {
			DB::beginTransaction();
			$user->update($request->all());
			$user->syncRoles([$request->role]);
			if ($request->role === 'buyer'){
				$existingCart = Cart::where('user_id', $user->id)->first();
				if (!$existingCart) {
					$cart = new Cart();
	        		$cart->user_id = $user->id;
	        		$cart->save();
				}
			} else {
				$existingCart = Cart::where('user_id', $user->id)->first();
				if ($existingCart) {
					$existingCart->delete();
				}
			}
			$this->uploadFile($user, $request);
			DB::commit();
			return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
		} catch (\Throwable) {
			DB::rollBack();
			return redirect()->back()->with('error', 'Ocurrió un error al crear el usuario.')->withInput();
		}
	}

	public function destroy(User $user)
	{
		$user->delete();
		$this->deleteFile($user);
		return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
	}
}
