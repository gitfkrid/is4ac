<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetailDashboardController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\UserLokasiController;

// Auth::routes();

Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::group(['middleware' => ['auth', 'cekuser:1']], function () {
    Route::post('/dashboard', [DashboardController::class, 'store'])->name('dashboard.store');
    Route::get('/dashboard/{uuid}/edit', [DetailDashboardController::class, 'edit'])->name('detail_dashboard.edit');
    Route::patch('/dashboard/{uuid}', [DetailDashboardController::class, 'update'])->name('detail_dashboard.update');
    Route::delete('/dashboard/{uuid}', [DetailDashboardController::class, 'destroy'])->name('detail_dashboard.destroy');

    Route::get('/dashboard/{id}/edit', [DashboardController::class, 'edit'])->name('dashboard.edit');
    Route::patch('/dashboard/{id}', [DashboardController::class, 'update'])->name('dashboard.update');
    Route::delete('/dashboard/{id}', [DashboardController::class, 'destroy'])->name('dashboard.destroy');

    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
    Route::get('/pengguna/data', [PenggunaController::class, 'dataPengguna'])->name('pengguna.data');
    Route::post('/pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
    Route::delete('/pengguna/{id}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');

    Route::get('/pengguna/lokasi', [UserLokasiController::class, 'index'])->name('userLokasi.index');
    Route::get('/pengguna/lokasi/user', [UserLokasiController::class, 'getUsers'])->name('userLokasi.getUsers');
    Route::get('/pengguna/lokasi/data', [UserLokasiController::class, 'dataUserLokasi'])->name('userLokasi.data');
    Route::post('/pengguna/lokasi', [UserLokasiController::class, 'store'])->name('userLokasi.store');
    Route::get('/pengguna/lokasi/{id}/edit', [UserLokasiController::class, 'edit'])->name('userLokasi.edit');
    Route::post('/pengguna/lokasi/update', [UserLokasiController::class, 'update'])->name('userLokasi.update');
    Route::delete('/pengguna/lokasi/{id}', [UserLokasiController::class, 'destroy'])->name('userLokasi.destroy');
});

Route::post('/dashboard/toggleRelay/{kode_board}', [DashboardController::class, 'toggleRelay']);
Route::get('/dashboard/alat/cards', [DashboardController::class, 'getAlatCards'])->name('dashboard.alatCards');
Route::get('/dashboard/alat/sensor', [DashboardController::class, 'getSensorNow'])->name('dashboard.getSensorNow');
Route::get('/dashboard/{uuid}', [DetailDashboardController::class, 'index'])->name('detail_dashboard.index');
Route::get('/dashboard/{uuid}/sensor-data', [DetailDashboardController::class, 'getSensorData']);
Route::get('/dashboard/{uuid}/chart', [DetailDashboardController::class, 'getSensorChartData']);
Route::get('/dashboard/{uuid}/chart/kelembaban', [DetailDashboardController::class, 'getSensorChartHumidity']);
Route::post('/dashboard/{uuid}/export-data', [DetailDashboardController::class, 'exportData'])->name('export-data');