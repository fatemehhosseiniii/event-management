<?php

use App\Http\Controllers\Api\V1\Auth\AuthenticateController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->prefix('authenticate')->group(function () {
    Route::post('/', [AuthenticateController::class, 'login']);
    Route::post('/verify', [AuthenticateController::class, 'verify']);
});

Route::middleware('auth:sanctum')->group(function () {
});