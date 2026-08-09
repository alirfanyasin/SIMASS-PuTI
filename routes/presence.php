<?php

use App\Http\Controllers\Presence\CalendarController;
use App\Http\Controllers\Presence\DashboardController;
use App\Http\Controllers\Presence\OvertimeController;
use App\Http\Controllers\Presence\PresenceController;
use App\Http\Controllers\Presence\StudentStaffController;
use Illuminate\Support\Facades\Route;

Route::prefix('presence')->name('presence.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Presence — check-in / check-out (Student staff only)
    Route::middleware('can:create-presence')->group(function () {
        Route::get('/', [PresenceController::class, 'presence'])->name('index');
        Route::post('/check-in', [PresenceController::class, 'checkIn'])->name('check-in');
        Route::post('/check-out', [PresenceController::class, 'checkOut'])->name('check-out');
        Route::post('/register-face', [PresenceController::class, 'registerFace'])->name('register-face');
        Route::post('/remove-face', [PresenceController::class, 'removeFace'])->name('remove-face');
    });

    // Presence list & history
    Route::get('/list', [PresenceController::class, 'presenceList'])->name('list');
    Route::get('/history', [PresenceController::class, 'presenceHistory'])->name('history');

    // Presence update & delete
    Route::put('/{presence}', [PresenceController::class, 'update'])->name('update');
    Route::delete('/{presence}', [PresenceController::class, 'destroy'])->name('destroy');

    // Overtime
    Route::get('/overtime', [OvertimeController::class, 'index'])->name('overtime');
    Route::post('/overtime/transfer', [OvertimeController::class, 'transfer'])
        ->middleware('can:manage-overtime')
        ->name('overtime.transfer');

    // Student staff list
    Route::get('/student-staff', [StudentStaffController::class, 'index'])->name('student-staff');

    // Calendar
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
});
