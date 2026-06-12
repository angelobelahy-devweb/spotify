<?php

use App\Http\Controllers\AccueilController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AccueilController::class, 'index'])->name('home');
