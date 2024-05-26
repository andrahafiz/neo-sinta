<?php

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\User\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    $category = Category::all();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', \App\Http\Controllers\Api\User\MeController::class);
});

Route::middleware(['auth:api', 'checkRole:KARYAWAN,ADMIN'])->group(function () {
    Route::apiResource('category', \App\Http\Controllers\Api\CategoryController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::apiResource('product', \App\Http\Controllers\Api\ProductController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::apiResource('cart', \App\Http\Controllers\Api\CartController::class)->only(['index', 'store', 'destroy']);

    Route::post('checkout', \App\Http\Controllers\Api\CheckoutController::class);
});

Route::middleware(['auth:api', 'checkRole:ADMIN'])->group(function () {
    Route::controller(UserController::class)->group(
        function () {
            Route::get('/user', 'index')->name('user.index');
            Route::get('/user/{user}', 'show')->name('user.show');
            Route::post('/user', 'store')->name('user.store');
            Route::put('/user/{user}', 'update')->name('user.update');
            Route::delete('/user/{user}', 'destroy')->name('user.destroy');
        }
    );
});
