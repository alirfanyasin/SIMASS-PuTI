<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StudentStaffController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login', 301);


// Authentication Route
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// Dashboard & Application Routes
Route::prefix('app')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('app.dashboard');

    Route::get('/presence', [PresenceController::class, 'presence'])->name('app.presence');
    Route::get('/presence/list', [PresenceController::class, 'presenceList'])->name('app.presence-list');
    Route::get('/presence/history', [PresenceController::class, 'presenceHistory'])->name('app.presence-history');
    Route::get('/overtime', [OvertimeController::class, 'index'])->name('app.overtime');

    Route::get('/student-staff', [StudentStaffController::class, 'index'])->name('app.student-staff');

    Route::get('/profile', [ProfileController::class, 'index'])->name('app.profile');
    Route::get('/settings', [SettingsController::class, 'index'])->name('app.settings');
});


