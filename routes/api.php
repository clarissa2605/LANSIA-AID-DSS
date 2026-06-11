<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LansiaController;
use App\Http\Controllers\Api\KriteriaController;
use App\Http\Controllers\Api\PerbandinganKriteriaController;
use App\Http\Controllers\Api\PenilaianController;
use App\Http\Controllers\Api\AhpController;
use App\Http\Controllers\Api\RankingController;
use App\Http\Controllers\Api\PenerimaBantuanController;
use App\Http\Controllers\Api\PengajuanBantuanController;
use App\Http\Controllers\Api\PenyaluranController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\PetugasDashboardController;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/test-kriteria', function () {
    return response()->json([
        'status' => 'working'
    ]);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/users', [UserController::class, 'index']);

    Route::apiResource('lansia', LansiaController::class);

    Route::apiResource('kriteria', KriteriaController::class);

Route::apiResource(
    'perbandingan-kriteria',
    PerbandinganKriteriaController::class
);

Route::apiResource(
    'penilaian',
    PenilaianController::class
);

Route::post(
    '/ahp/hitung',
    [AhpController::class, 'hitung']
);

Route::get(
    '/ahp/bobot',
    [AhpController::class, 'bobot']
);

Route::get(
    '/ahp/konsistensi',
    [AhpController::class, 'konsistensi']
);

Route::post(
    '/ranking/hitung',
    [RankingController::class, 'hitung']
);

Route::get(
    '/ranking',
    [RankingController::class, 'index']
);

Route::get(
    '/penerima-bantuan',
    [PenerimaBantuanController::class, 'index']
);

Route::post(
    '/penerima-bantuan',
    [PenerimaBantuanController::class, 'store']
);

Route::patch(
    '/penerima-bantuan/{id}',
    [PenerimaBantuanController::class, 'update']
);

Route::get(
    '/penyaluran',
    [PenyaluranController::class, 'index']
);

Route::apiResource(
    'pengajuan-bantuan',
    PengajuanBantuanController::class
)->only(['index', 'store', 'show', 'update', 'destroy']);

Route::get(
    '/dashboard',
    [DashboardController::class, 'index']
);

Route::get(
    '/dashboard/statistik',
    [DashboardController::class, 'statistik']
);

Route::get(
    '/petugas/dashboard',
    [PetugasDashboardController::class, 'index']
);

Route::get(
    '/petugas/tugas',
    [PetugasDashboardController::class, 'tugas']
);

Route::get(
    '/petugas/lansia-belum-dinilai',
    [PetugasDashboardController::class, 'lansiaBelumDinilai']
);

Route::get(
    '/petugas/prioritas-bantuan',
    [PetugasDashboardController::class, 'prioritasBantuan']
);

Route::get(
    '/petugas/monitoring',
    [PetugasDashboardController::class, 'monitoring']
);

Route::get(
    '/petugas/riwayat',
    [PetugasDashboardController::class, 'riwayat']
);

Route::patch(
    '/pengajuan-bantuan/{id}/verifikasi',
    [PengajuanBantuanController::class, 'verifikasi']
);

Route::patch(
    '/pengajuan-bantuan/{id}/tolak',
    [PengajuanBantuanController::class, 'tolak']
);

Route::patch(
    '/pengajuan-bantuan/{id}/salurkan',
    [PengajuanBantuanController::class, 'salurkan']
);

Route::post(
    '/users',
    [UserController::class, 'store']
);

Route::put(
    '/users/{id}',
    [UserController::class, 'update']
);

Route::delete(
    '/users/{id}',
    [UserController::class, 'destroy']
);
});
