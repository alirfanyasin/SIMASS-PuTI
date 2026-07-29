<?php

use App\Http\Controllers\Ticketing\TicketingController;
use Illuminate\Support\Facades\Route;

Route::prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', [TicketingController::class, 'index'])->name('index');
    Route::get('/create', [TicketingController::class, 'create'])->name('create');
    Route::get('/my-tickets', [TicketingController::class, 'myTickets'])->name('my-tickets');
    Route::get('/task', [TicketingController::class, 'tasks'])->name('tasks');
    Route::get('/history', [TicketingController::class, 'history'])->name('history');
    Route::get('/luna', [TicketingController::class, 'luna'])->name('luna');
});
