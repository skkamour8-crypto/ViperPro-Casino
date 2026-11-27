<?php

use App\Http\Controllers\Api\Points\PointsController;
use Illuminate\Support\Facades\Route;

Route::prefix('points')->middleware('auth.jwt')->group(function () {
    Route::get('/me', [PointsController::class, 'me']);
    Route::get('/admin/users', [PointsController::class, 'users']);
    Route::post('/admin/adjust', [PointsController::class, 'adjust']);
});
