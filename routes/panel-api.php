<?php

use App\Http\Controllers\Api\V1\Panel\EventController;
use App\Http\Middleware\AdminAccess;
use Illuminate\Support\Facades\Route;

Route::middleware(AdminAccess::class)->prefix('panel')->group(function () {

    Route::apiResource('/events', EventController::class);
});