<?php

use App\Http\Controllers\AuthUserAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartProductController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthUserAPIController::class, 'login']);
Route::post('/register', [UserController::class, 'store']);

//Rutas protegidas
Route::group(['middleware' =>['auth:sanctum']], function () {
	Route::post('/logout', [AuthUserAPIController::class, 'logout']);

	Route::group(['prefix' => 'users', 'controller' => UserController::class], function () {
		Route::get('/', 'index');
		Route::post('/store', 'store');
		Route::put('/update/{user}', 'update');
		Route::delete('/delete/{user}', 'destroy');
	});

	Route::group(['prefix' => 'categories', 'controller' => CategoryController::class], function () {
		Route::get('/', 'index');
		Route::get('/{category}', 'edit');
		Route::post('/store', 'store');
		Route::put('/update/{category}', 'update');
		Route::delete('/delete/{category}', 'destroy');
	});

	Route::group(['prefix' => 'products', 'controller' => ProductController::class], function () {
		Route::get('/', 'index');
		Route::post('/store', 'store');
		Route::put('/update/{product}', 'update');
		Route::delete('/delete/{product}', 'destroy');
	});

	Route::group(['prefix' => 'carts', 'controller' => CartController::class], function () {
		Route::get('/', 'index');
	});

	Route::group(['prefix' => 'cartproducts', 'controller' => CartProductController::class], function () {
		Route::post('/store', 'store');
		Route::put('/update/{cartProduct}', 'update');
		Route::delete('/delete/{cartProduct}', 'destroy');
	});
});
