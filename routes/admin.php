<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/dashboard', function () {
        return view('pages.admin.dashboard');
    })->name('dashboard');

    // User Management
    Route::post('/users/import-csv', [UserController::class, 'importCsv'])->name('users.import-csv');
    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);

    // Master Data & Locations
    Route::get('/master-data', [MasterDataController::class, 'index'])->name('master-data.index');
    Route::post('/units', [MasterDataController::class, 'storeUnit'])->name('units.store');
    Route::delete('/units/{unit}', [MasterDataController::class, 'destroyUnit'])->name('units.destroy');
    Route::post('/locations', [MasterDataController::class, 'storeLocation'])->name('locations.store');
    Route::delete('/locations/{location}', [MasterDataController::class, 'destroyLocation'])->name('locations.destroy');
    Route::post('/services', [MasterDataController::class, 'storeService'])->name('services.store');
    Route::delete('/services/{service}', [MasterDataController::class, 'destroyService'])->name('services.destroy');

    // Audit Logs
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
});
