<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::get('/', [UserController::class, 'index'])->name('home');
Route::post('/login', [UserController::class, 'index'])->name('login');