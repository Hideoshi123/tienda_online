<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartProductController;

Auth::routes();

// Rutas accesibles para no autenticados y usuarios con rol 'buyer'
Route::middleware('check.role.or.guest:buyer')->group(function () {
    Route::get('/', [ProductController::class, 'home'])->name('products.home');
    Route::get('/products/get-all', [ProductController::class, 'getAll'])->name('products.getAll');
});

Route::group(['middleware' => ['auth']], function () {
    //Users
    Route::group(['prefix' => 'users', 'controller' => UserController::class], function () {
		Route::get('/', 'index')->name('users.index')->middleware('can:users.index');
        Route::get('/create', 'create')->name('users.create')->middleware('can:users.create');
        Route::post('/', 'store')->name('users.store')->middleware('can:users.store');
        Route::get('/{user}/edit', 'edit')->name('users.edit')->middleware('can:users.edit');
        Route::put('/{user}', 'update')->name('users.update')->middleware('can:users.update');
        Route::delete('/{user}', 'destroy')->name('users.destroy')->middleware('can:users.destroy');
		Route::get('/user', 'log')->name('users.log')->middleware('can:users.log');
	});

    //Products
	Route::group(['prefix' => 'products', 'controller' => ProductController::class], function () {
		Route::get('/', 'index')->name('products.index')->middleware('can:products.index');
		Route::get('/get-all-dt', 'getAllDt')->name('products.get-all-dt')->middleware('can:products.get-all-dt');
        Route::post('/store', 'store')->name('products.store')->middleware('can:products.store');
        Route::get('/{product}', 'show')->name('products.show')->middleware('can:products.show');
        Route::post('/update/{product}', 'update')->name('products.update')->middleware('can:products.update');
        Route::delete('/{product}', 'destroy')->name('products.destroy')->middleware('can:products.destroy');
	});

	//Carts
	Route::group(['prefix' => 'cart', 'middleware' => ['role:buyer'], 'controller' => CartController::class], function () {
		Route::get('/show', 'show')->name('cart.show');
		Route::get('/{cartId}/edit', 'edit')->name('cart.edit');
		Route::get('/{cartId}/quantity', 'getCartQuantity')->name('cart.getCartQuantity');
	});

	//CartProducts
	Route::group(['prefix' => 'cartproducts', 'middleware' => ['role:buyer'], 'controller' => CartProductController::class], function () {
		Route::post('/store', 'store')->name('cartproducts.store');
		Route::post('/{cartProduct}/update', 'update')->name('cartproducts.update');
		Route::post('/{cartProduct}/delete', 'destroy')->name('cartproducts.destroy');
	});

    //Categories
    Route::group(['prefix' => 'categories', 'controller' => CategoryController::class], function () {
		Route::get('/', 'index')->name('categories.index')->middleware('can:categories.index');
		Route::get('/get-all', 'index')->name('categories.get-all')->middleware('can:categories.get-all');
        Route::get('/get-all-dt', 'getAll')->name('categories.get-all-dt')->middleware('can:categories.get-all-dt');
        Route::post('/store', 'store')->name('categories.store')->middleware('can:categories.store');
        Route::get('/{category}', 'show')->name('categories.show')->middleware('can:categories.show');
        Route::post('/update/{category}', 'update')->name('categories.update')->middleware('can:categories.update');
        Route::delete('/{category}', 'destroy')->name('categories.destroy')->middleware('can:categories.destroy');
    });
});
