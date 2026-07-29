<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login', 301);

// Authentication Route
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Presence Routes
require __DIR__.'/presence.php';

// Ticketing Routes
Route::prefix('ticketing')->group(function () {
    require __DIR__.'/ticketing.php';
});

Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
Route::get('/role-permission', [RolePermissionController::class, 'index'])->name('role-permission');
