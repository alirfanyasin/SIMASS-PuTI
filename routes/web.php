<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\Presence\PresenceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::redirect('/', '/login', 301);

// ---------------------------------------------------------
// Authentication Routes (guest only)
// ---------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ---------------------------------------------------------
// Authenticated Routes
// ---------------------------------------------------------
Route::middleware('auth')->group(function () {
    // Presence Routes
    require __DIR__ . '/presence.php';

    // PDF Export
    Route::get('/export-pdf', [PresenceController::class, 'exportPdf'])->name('export-pdf');

    // Ticketing Routes
    Route::prefix('ticketing')->group(function () {
        require __DIR__ . '/ticketing.php';
    });

    // Holiday Management (super-admin + staff only)
    Route::prefix('holiday')->name('holiday.')->middleware('can:manage-holiday')->group(function () {
        Route::get('/', [HolidayController::class, 'index'])->name('index');
        Route::post('/', [HolidayController::class, 'store'])->name('store');
        Route::put('/{holiday}', [HolidayController::class, 'update'])->name('update');
        Route::delete('/{holiday}', [HolidayController::class, 'destroy'])->name('destroy');
    });

    // General Pages
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::middleware('can:manage-roles')->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });
    // Role & Permission (super-admin only)
    Route::get('/role-permission', [RolePermissionController::class, 'index'])
        ->middleware('can:manage-roles')
        ->name('role-permission');
    Route::post('/role-permission/{user}', [RolePermissionController::class, 'update'])
        ->middleware('can:manage-roles')
        ->name('role-permission.update');
});
