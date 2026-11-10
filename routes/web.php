<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityParticipantController;
use App\Http\Controllers\ActivityReportController;

// HOMEPAGE
Route::get('/', [UserController::class, 'index'])
    ->name('home');

// GUEST ROUTES
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.store');
});

// PUBLIC ACTIVITY ROUTES
Route::get('/activities', [ActivityController::class, 'index'])
    ->name('activities.index');

Route::get('/activities/{id}', [ActivityController::class, 'show'])
    ->name('activities.show');

// AUTHENTICATED ROUTES
Route::middleware('auth')->group(function () {

    // PROFILE
    Route::get('/profile', [UserController::class, 'showProfile'])
        ->name('profile');

    Route::put('/profile/update', [UserController::class, 'updateProfile'])
        ->name('profile.update');

    Route::put('/profile/password', [UserController::class, 'updatePassword'])
        ->name('profile.password');

    Route::post('/profile/picture', [UserController::class, 'updateProfilePicture'])
        ->name('profile.picture');

    // QUESTS
    Route::get('/quests/progress', [UserController::class, 'questProgress'])
        ->name('quests.progress');

    Route::post('/quests/apply', [UserController::class, 'applyQuest'])
        ->name('quests.apply');

    Route::post('/quests/complete', [UserController::class, 'completeQuest'])
        ->name('quests.complete');

    // ACTIVITY MANAGEMENT
    Route::get('/activities-create', [ActivityController::class, 'create'])
        ->name('activities.create');

    Route::post('/activities', [ActivityController::class, 'store'])
        ->name('activities.store');

    Route::post('/activities/{id}/join', [ActivityParticipantController::class, 'join'])
        ->name('activities.join');

    Route::delete('/activities/{id}/leave', [ActivityParticipantController::class, 'leave'])
        ->name('activities.leave');

    Route::post('/activities/{id}/report', [ActivityReportController::class, 'store'])
        ->name('activities.report');

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});