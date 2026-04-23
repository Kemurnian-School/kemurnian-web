<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HeroController;

Route::prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/hero', [HeroController::class, 'index'])->name('hero');
    Route::get('/hero/create', [HeroController::class, 'create'])->name('hero.create');
    Route::post('/hero', [HeroController::class, 'store'])->name('hero.store');
    Route::delete('/hero/{id}', [HeroController::class, 'destroy'])->name('hero.destroy');
    Route::post('/hero/reorder', [HeroController::class, 'reorder'])->name('hero.reorder');
});

Route::get('/debug-hero', function () {
    $hero = \App\Models\Hero::first();
    return [
        'raw' => $hero->getRawOriginal('desktop_image'),
        'accessor' => $hero->desktop_image,
    ];
});
