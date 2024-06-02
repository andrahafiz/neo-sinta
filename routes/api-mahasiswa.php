<?php

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mahasiswa\UserController;
use App\Http\Controllers\Mahasiswa\AuthController as MahasiswaAuthController;
use App\Http\Controllers\Mahasiswa\MeController;
use App\Http\Controllers\Mahasiswa\PengajuanJudulController;
use App\Http\Controllers\Mahasiswa\SitInController;

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

Route::post('/register', [MahasiswaAuthController::class, 'register']);
Route::post('/login', [MahasiswaAuthController::class, 'login']);

Route::middleware(['auth:mahasiswa-api', 'role:mahasiswa'])->group(function () {
    Route::post('logout', [MahasiswaAuthController::class, 'logout']);
    Route::get('/me', MeController::class);

    Route::post('sitin/checkin', [SitInController::class, 'checkIn']);
    Route::post('sitin/checkout/{sitIn}', [SitInController::class, 'checkOut']);

    Route::apiResource('sitin', SitInController::class);

    Route::apiResource('pengajuan-judul', PengajuanJudulController::class);
});
