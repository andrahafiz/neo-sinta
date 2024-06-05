<?php

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dosen\UserController;
use App\Http\Controllers\Dosen\AuthController as DosenAuthController;
use App\Http\Controllers\Dosen\MeController;
use App\Http\Controllers\Dosen\SitInController;

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

Route::post('/register', [DosenAuthController::class, 'register']);
Route::post('/login', [DosenAuthController::class, 'login']);

Route::middleware(['auth:dosen-guard', 'role:dosen|kaprodi'])->group(function () {
    Route::post('logout', [DosenAuthController::class, 'logout']);
    Route::get('/me', MeController::class);

    Route::put('sitin/{sitIn}/confirm', [SitInController::class, 'confirm']);
    Route::apiResource('sitin', SitInController::class);
});
