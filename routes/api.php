<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RumahController;
use App\Http\Controllers\PenghuniController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\LaporanController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rumah routes
Route::prefix('rumah')->group(function () {
    Route::get('/', [RumahController::class, 'index']);
    Route::post('/', [RumahController::class, 'store']);
    Route::get('/{rumah}', [RumahController::class, 'show']);
    Route::put('/{rumah}', [RumahController::class, 'update']);
    Route::post('/{rumah}/tambah-penghuni', [RumahController::class, 'tambahPenghuni']);
    Route::post('/{rumah}/hapus-penghuni/{penghuni}', [RumahController::class, 'hapusPenghuni']);
    Route::get('/{rumah}/riwayat-pembayaran', [RumahController::class, 'riwayatPembayaran']);
});

// Penghuni routes
Route::prefix('penghuni')->group(function () {
    Route::get('/', [PenghuniController::class, 'index']);
    Route::post('/', [PenghuniController::class, 'store']);
    Route::get('/{penghuni}', [PenghuniController::class, 'show']);
    Route::put('/{penghuni}', [PenghuniController::class, 'update']);
});

// Pembayaran routes
Route::prefix('pembayaran')->group(function () {
    Route::get('/', [PembayaranController::class, 'index']);
    Route::post('/', [PembayaranController::class, 'store']);
    Route::get('/{pembayaran}', [PembayaranController::class, 'show']);
    Route::put('/{pembayaran}', [PembayaranController::class, 'update']);
});

// Pengeluaran routes
Route::prefix('pengeluaran')->group(function () {
    Route::get('/', [PengeluaranController::class, 'index']);
    Route::post('/', [PengeluaranController::class, 'store']);
    Route::get('/{pengeluaran}', [PengeluaranController::class, 'show']);
    Route::put('/{pengeluaran}', [PengeluaranController::class, 'update']);
});

// Laporan routes
Route::prefix('laporan')->group(function () {
    Route::get('ringkasan-bulanan', [LaporanController::class, 'ringkasanBulanan']);
    Route::get('detail-pembayaran', [LaporanController::class, 'detailPembayaran']);
    Route::get('detail-pengeluaran', [LaporanController::class, 'detailPengeluaran']);
    Route::get('tunggakan-pembayaran', [LaporanController::class, 'tunggakanPembayaran']);
    Route::get('occupancy-rate', [LaporanController::class, 'occupancyRate']);
    Route::get('grafik-tahunan', [LaporanController::class, 'grafikTahunan']);
    Route::get('detail-bulanan', [LaporanController::class, 'detailBulanan']);
});