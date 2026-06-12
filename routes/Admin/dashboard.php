<?php
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->controller(DashboardController::class)->group(function () {
    Route::get('/dashboard', 'index')->name('admin.index');
});