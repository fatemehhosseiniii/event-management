<?php

use App\Http\Controllers\Api\V1\Panel\EventController;
use App\Http\Controllers\Api\V1\Panel\ReservController;
use App\Http\Middleware\AdminAccess;
use Illuminate\Support\Facades\Route;

Route::middleware(AdminAccess::class)->prefix('panel')->group(function () {

    Route::apiResource('/events', EventController::class);

    Route::apiResource('/reservs', ReservController::class)->only(['index','update','destroy']);
});