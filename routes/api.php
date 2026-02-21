<?php

use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
});
Route::middleware('auth:sanctum')->group(function () {
});