<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\FosfinController;
use App\Http\Controllers\api\DhtController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\RelayController;
use App\Http\Controllers\api\GetSensorController;

Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/user/profile', [UserController::class, 'profile']);
    Route::post('/user/profile/edit/{id}', [UserController::class, 'editprofile']);
    Route::post('/user/profile/edit/password/{id}', [UserController::class, 'editpassword']);
    Route::post('/sensor/data/{id_lokasi}', [GetSensorController::class, 'getNilaiGudang']);
    Route::get('/sensor/data/Suhu/{id_lokasi}', [GetSensorController::class, 'getAvgSuhu']);
    Route::get('/sensor/data/Kelembaban/{id_lokasi}', [GetSensorController::class, 'getAvgKelembapan']);
    Route::get('/sensor/data/Fosfina/{id_lokasi}', [GetSensorController::class, 'getAvgFosfina']);
    Route::post('/sensor/data/Suhu/detail', [GetSensorController::class, 'getJamSuhu']);
    Route::post('/sensor/data/Kelembaban/detail', [GetSensorController::class, 'getJamKelembaban']);
    Route::post('/sensor/data/Fosfin/detail', [GetSensorController::class, 'getJamFosfin']);
    Route::post('/logout', [UserController::class, 'logout']);
});

Route::post('/sensor/fosfin', [FosfinController::class, 'store']);
Route::post('/sensor/dht', [DhtController::class, 'store']);
Route::post('/relay', [RelayController::class, 'getRelay']);
Route::post('/relay/toogle', [RelayController::class, 'toggleRelay']);
Route::get('/relay/{kode_board}/state', [RelayController::class, 'getRelayState']);
Route::post('/relay/{kode_board}/state', [RelayController::class, 'getRelayState']);
