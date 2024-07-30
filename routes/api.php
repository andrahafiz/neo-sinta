<?php

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\MeController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AuthController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    //     Route::post('logout', [MahasiswaAuthController::class, 'logout']);
    Route::get('/me', MeController::class);
    Route::post('logout', [AuthController::class, 'logout']);
    //     Route::get('/thesis', ThesisController::class);

    //     Route::post('sitin/checkin', [SitInController::class, 'checkIn']);
    //     Route::post('sitin/checkout/{sitIn}', [SitInController::class, 'checkOut']);

    //     Route::apiResource('sitin', SitInController::class);
    //     Route::apiResource('bimbingan', BimbinganController::class);

    //     Route::apiResource('pengajuan-judul', PengajuanJudulController::class);
    //     Route::apiResource('seminar-literatur', SeminarLiteraturController::class);
    //     Route::apiResource('seminar-praproposal', SeminarPraProposalController::class);
    //     Route::apiResource('seminar-proyek', SeminarProyekController::class);
    //     Route::apiResource('seminar-proposal', SeminarProposalController::class);
    //     Route::apiResource('seminar-hasil', SeminarHasilController::class);
    //     Route::apiResource('sidang-meja-hijau', SidangMejaHijauController::class);
});
