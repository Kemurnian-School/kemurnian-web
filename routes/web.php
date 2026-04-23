<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HeroController;

Route::prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
});

Route::prefix('admin')->group(function () {
    Route::get('/hero', [HeroController::class, 'index']);
    Route::get('/hero/create', [HeroController::class, 'create']);
    Route::post('/hero', [HeroController::class, 'store']);
    Route::delete('/hero/{id}', [HeroController::class, 'destroy']);
    Route::post('/hero/reorder', [HeroController::class, 'reorder']);
});
