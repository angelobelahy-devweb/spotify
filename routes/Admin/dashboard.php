<?php
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth','admin'])
    ->prefix('admin')
    ->name('admin.')
    ->controller(DashboardController::class)
    ->group(function () {
        Route::get('/dashboard', 'index');
});
