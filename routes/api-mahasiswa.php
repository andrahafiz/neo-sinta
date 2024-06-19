<?php

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mahasiswa\MeController;
use App\Http\Controllers\Mahasiswa\SitInController;
use App\Http\Controllers\Mahasiswa\SeminarProyekController;
use App\Http\Controllers\Mahasiswa\PengajuanJudulController;
use App\Http\Controllers\Mahasiswa\SeminarLiteraturController;
use App\Http\Controllers\Mahasiswa\SeminarPraProposalController;
use App\Http\Controllers\Mahasiswa\AuthController as MahasiswaAuthController;
use App\Http\Controllers\Mahasiswa\SeminarHasilController;
use App\Http\Controllers\Mahasiswa\SeminarProposalController;
use App\Http\Controllers\Mahasiswa\SidangMejaHijauController;

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


Route::post('/register', [MahasiswaAuthController::class, 'register']);
Route::post('/login', [MahasiswaAuthController::class, 'login']);

Route::middleware(['auth:mahasiswa-guard', 'role:mahasiswa'])->group(function () {
    Route::post('logout', [MahasiswaAuthController::class, 'logout']);
    Route::get('/me', MeController::class);

    Route::post('sitin/checkin', [SitInController::class, 'checkIn']);
    Route::post('sitin/checkout/{sitIn}', [SitInController::class, 'checkOut']);

    Route::apiResource('sitin', SitInController::class);

    Route::apiResource('pengajuan-judul', PengajuanJudulController::class);
    Route::apiResource('seminar-literatur', SeminarLiteraturController::class);
    Route::apiResource('seminar-praproposal', SeminarPraProposalController::class);
    Route::apiResource('seminar-proyek', SeminarProyekController::class);
    Route::apiResource('seminar-proposal', SeminarProposalController::class);
    Route::apiResource('seminar-hasil', SeminarHasilController::class);
    Route::apiResource('sidang-meja-hijau', SidangMejaHijauController::class);
});
