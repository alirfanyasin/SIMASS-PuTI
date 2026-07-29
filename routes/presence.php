<?php

use App\Http\Controllers\Presence\CalendarController;
use App\Http\Controllers\Presence\DashboardController;
use App\Http\Controllers\Presence\OvertimeController;
use App\Http\Controllers\Presence\PresenceController;
use App\Http\Controllers\Presence\StudentStaffController;
use Illuminate\Support\Facades\Route;

Route::prefix('presence')->name('presence.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', [PresenceController::class, 'presence'])->name('index');
    Route::get('/list', [PresenceController::class, 'presenceList'])->name('list');
    Route::get('/history', [PresenceController::class, 'presenceHistory'])->name('history');
    Route::get('/overtime', [OvertimeController::class, 'index'])->name('overtime');
    Route::get('/student-staff', [StudentStaffController::class, 'index'])->name('student-staff');
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
});
