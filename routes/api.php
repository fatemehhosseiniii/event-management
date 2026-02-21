<?php

use App\Http\Controllers\Api\V1\Auth\AuthenticateController;
use App\Http\Controllers\Api\V1\Panel\EventController;
use App\Http\Middleware\AdminAccess;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->prefix('authenticate')->group(function () {
    Route::post('/', [AuthenticateController::class, 'login']);
    Route::post('/verify', [AuthenticateController::class, 'verify']);
});

Route::middleware('auth:sanctum')->group(function () {



    //Admin Routes
    Route::middleware(AdminAccess::class)->prefix('panel')->group(function () {

        Route::apiResource('/events', EventController::class);
    });

});