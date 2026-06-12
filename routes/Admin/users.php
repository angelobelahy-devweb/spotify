<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->controller(UserController::class)->group(function () {
    Route::get('/users', 'index')->name('admin.users.index');
    Route::delete('/users/{user}', 'destroy')->name('admin.users.destroy');
});
