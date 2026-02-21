<?php

use App\Http\Controllers\Api\V1\Auth\AuthenticateController;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\ReservController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->prefix('authenticate')->group(function () {
    Route::post('/', [AuthenticateController::class, 'login']);
    Route::post('/verify', [AuthenticateController::class, 'verify']);
});

Route::middleware('auth:sanctum')->group(function () {


    //active event List
    Route::get('/events', EventController::class);
    //reserv routes
    Route::apiResource('/reservs', ReservController::class)->only(['store', 'index']);
    Route::prefix('reservs')->group(function () {
        //confirm reservation
        Route::put('/{reserv}/confirm', [ReservController::class, 'confirm']);
    });
    //Admin Routes
    include __DIR__ . '/panel-api.php';
});
