<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityParticipantController;
use App\Http\Controllers\ActivityReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
Route::get('/activities/{id}', [ActivityController::class, 'show'])->name('activities.show');

// Authenticated Routes (Only for logged-in users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/activities-create', [ActivityController::class, 'create'])->name('activities.create');
    Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');
    Route::post('/activities/{id}/join', [ActivityParticipantController::class, 'join'])->name('activities.join');
    Route::delete('/activities/{id}/leave', [ActivityParticipantController::class, 'leave'])->name('activities.leave');
    Route::post('/activities/{id}/report', [ActivityReportController::class, 'store'])->name('activities.report');
});