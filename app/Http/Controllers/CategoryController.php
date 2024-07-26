<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\Category\CategoryRequest;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::get();
		if (!$request->ajax()) return view();
		return response()->json(['category' => $categories], 200);
    }


    public function create()
    {
        // view
    }


    public function store(CategoryRequest $request)
    {
        $category = new Category($request->all());
		$category->save();
		//if (!$request->ajax()) return back()->with('success', 'User created');
		return response()->json(['status' => 'Category created', 'category' => $category], 201);
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


    public function update(CategoryRequest $request, Category $category)
    {
		$category->update($request->all());
		//$user->syncRoles([$request->role]);
		if (!$request->ajax()) return view();
		return response()->json([], 204);
    }


    public function destroy(Request $request, Category $category)
    {
        $category->delete();
		if (!$request->ajax()) return back()->with('success', 'User deleted');
		return response()->json([], 204);
    }
}
