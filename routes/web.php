<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartProductController;

Auth::routes();

Route::get('/', [ProductController::class, 'home'])->name('products.home');
Route::get('/products/index', [ProductController::class, 'index'])->name('products.index');

Route::group(['middleware' => ['auth']], function () {
    //Users
    Route::group(['prefix' => 'users', /*'middleware' => ['role:admin'],*/ 'controller' => UserController::class], function () {
		Route::get('/', 'index')->name('users.index')->middleware('can:users.index');
        Route::get('/create', 'create')->name('users.create')->middleware('can:users.create');
        Route::post('/', 'store')->name('users.store')->middleware('can:users.store');
        Route::get('/{user}/edit', 'edit')->name('users.edit')->middleware('can:users.edit');
        Route::put('/{user}', 'update')->name('users.update')->middleware('can:users.update');
        Route::delete('/{user}', 'destroy')->name('users.destroy')->middleware('can:users.destroy');
		Route::get('/user', 'log')->name('users.log');
	});

    //Products
	Route::group(['prefix' => 'products', 'controller' => ProductController::class], function () {
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
     	Route::get('/', 'index')->name('categories.index');
    });
});
