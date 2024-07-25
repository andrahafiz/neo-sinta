<?php

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\SidangMejaHijau;
use App\Models\SeminarLiteratur;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dosen\MeController;
use App\Http\Controllers\Dosen\UserController;
use App\Http\Controllers\Dosen\SitInController;
use App\Http\Controllers\Dosen\BimbinganController;
use App\Http\Controllers\Dosen\ThesisController;
use App\Http\Controllers\Dosen\SeminarHasilController;
use App\Http\Controllers\Dosen\SeminarProyekController;
use App\Http\Controllers\Dosen\PengajuanJudulController;
use App\Http\Controllers\Dosen\SeminarProposalController;
use App\Http\Controllers\Dosen\SidangMejaHijauController;
use App\Http\Controllers\Dosen\SeminarLiteraturController;
use App\Http\Controllers\Dosen\SeminarPraProposalController;
use App\Http\Controllers\Dosen\AuthController as DosenAuthController;
use App\Http\Controllers\Dosen\MahasiswaController;

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

Route::post('/register', [DosenAuthController::class, 'register']);
Route::post('/login', [DosenAuthController::class, 'login']);

Route::middleware(['auth:dosen-guard', 'role:dosen|kaprodi'])->group(function () {
    Route::post('logout', [DosenAuthController::class, 'logout']);
    Route::get('/me', MeController::class);

    Route::apiResource('mahasiswa', MahasiswaController::class)->only(['index', 'update', 'confirm', 'show']);

    Route::put('sitin/confirm', [SitInController::class, 'confirm']);
    Route::apiResource('sitin', SitInController::class)->only(['index', 'update', 'confirm', 'show']);

    Route::apiResource('pengajuan-judul', PengajuanJudulController::class)->only(['index', 'update', 'show']);

    Route::put('seminar-literatur/{seminar_literatur}/confirm', [SeminarLiteraturController::class, 'confirm']);
    Route::apiResource('seminar-literatur', SeminarLiteraturController::class)->only(['index', 'show']);

    Route::put('seminar-praproposal/{seminar_praproposal}/confirm', [SeminarPraProposalController::class, 'confirm']);
    Route::apiResource('seminar-praproposal', SeminarPraProposalController::class)->only(['index', 'show']);

    Route::put('seminar-proyek/{seminar_proyek}/confirm', [SeminarProyekController::class, 'confirm']);
    Route::apiResource('seminar-proyek', SeminarProyekController::class)->only(['index', 'show']);

    Route::put('seminar-proposal/{seminar_proposal}/confirm', [SeminarProposalController::class, 'confirm']);
    Route::apiResource('seminar-proposal', SeminarProposalController::class)->only(['index', 'show']);

    Route::put('seminar-hasil/{seminar_hasil}/confirm', [SeminarHasilController::class, 'confirm']);
    Route::apiResource('seminar-hasil', SeminarHasilController::class)->only(['index', 'show']);

    Route::put('sidang-meja-hijau/{sidang_meja_hijau}/confirm', [SidangMejaHijauController::class, 'confirm']);
    Route::apiResource('sidang-meja-hijau', SidangMejaHijauController::class)->only(['index', 'show']);

    Route::put('bimbingan/{bimbingan}/approve', [BimbinganController::class, 'approve']);
    Route::apiResource('bimbingan', BimbinganController::class);
});

Route::middleware(['auth:dosen-guard', 'role:kaprodi'])->group(function () {
    Route::get('/thesis', [ThesisController::class, 'index']);
    Route::post('/pembimbing-assign', [ThesisController::class, 'assign_pembimbing']);
});
