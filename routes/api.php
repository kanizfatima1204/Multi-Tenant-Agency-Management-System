<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/auth/token', [ApiController::class, 'token'])->middleware('throttle:10,1');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [ApiController::class, 'me']);
        Route::get('/projects', [ApiController::class, 'projects']);
        Route::get('/projects/{project}', [ApiController::class, 'project']);
    });
});
