<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\DashboardController;

// Auth::routes();

Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::group(['middleware' => ['auth', 'cekuser:1']], function () {
    // Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
    Route::get('/pengguna/data', [PenggunaController::class, 'dataPengguna'])->name('pengguna.data');
    Route::post('/pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
    Route::delete('/pengguna/{id}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');
});
