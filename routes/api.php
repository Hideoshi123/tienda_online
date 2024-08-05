<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;


// // Route::post('/login', [AuthUserAPIController::class, 'login']);
// // Route::post('/register', [UserController::class, 'store']);

// // Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
// //     return $request->user();
// // });

// // Route::group(['middleware' => ['auth']], function () {
// // 	Route::group(['prefix' => 'users', 'controller' => UserController::class], function () {
// // 		Route::get('/user', 'show')->name('users.show');
// // 		// 		Route::get('/', 'index');
// // 		// 		Route::get('/{user}', 'show');
// // 		// 		Route::post('/', 'store');
// // 		// 		Route::put('/{user}', 'update');
// // 		// 		Route::delete('/{user}', 'destroy');
// // 	});
// // });

// // Rutas protegidas
// Route::group(['middleware' => ['auth:sanctum']], function () {
// // 	Route::post('/logout', [AuthUserAPIController::class, 'logout']);
// // 	Route::get('/profile', [AuthUserAPIController::class, 'profile']);

// Route::group(['prefix' => 'users', 'controller' => UserController::class], function () {
// 	Route::get('/user', 'show')->name('users.show');
// // 		Route::get('/', 'index');
// // 		Route::get('/{user}', 'show');
// // 		Route::post('/', 'store');
// // 		Route::put('/{user}', 'update');
// // 		Route::delete('/{user}', 'destroy');
// // 	});
// });

// // // Rutas para productos
// // Route::group(['prefix' => 'products', 'controller' => ProductController::class], function () {
// //     Route::get('/search', 'search')->name('products.search');
// });
