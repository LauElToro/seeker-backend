<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParkingController;

Route::get('/parkings', [ParkingController::class, 'index']);
Route::get('/parkings/{id}', [ParkingController::class, 'show']);
Route::post('/parkings', [ParkingController::class, 'store']);
Route::post('/parking/nearest', [ParkingController::class, 'nearest']);
Route::get('/request-logs', [RequestLogController::class, 'index']);