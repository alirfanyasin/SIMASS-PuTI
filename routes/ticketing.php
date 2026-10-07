<?php

use App\Http\Controllers\Ticketing\TicketingController;
use Illuminate\Support\Facades\Route;

Route::prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', [TicketingController::class, 'index'])->name('index');
    Route::get('/create', [TicketingController::class, 'create'])->name('create');
    Route::post('/', [TicketingController::class, 'store'])->name('store');
    Route::get('/my-tickets', [TicketingController::class, 'myTickets'])->name('my-tickets');
    Route::get('/tasks', [TicketingController::class, 'tasks'])->name('tasks');
    Route::get('/tasks/poll', [TicketingController::class, 'tasksPoll'])->name('tasks-poll');
    Route::get('/task', [TicketingController::class, 'tasks']); // Alias for backward compatibility
    Route::get('/history', [TicketingController::class, 'history'])->name('history');
    Route::get('/luna', [TicketingController::class, 'luna'])->name('luna');
    Route::post('/luna/suggest', [TicketingController::class, 'lunaSuggest'])->name('luna-suggest');
    Route::post('/luna/chat', [TicketingController::class, 'lunaChat'])->name('luna-chat');

    Route::get('/{ticket}', [TicketingController::class, 'show'])->name('show');
    Route::post('/{ticket}/reply', [TicketingController::class, 'reply'])->name('reply');
    Route::patch('/{ticket}/status', [TicketingController::class, 'updateStatus'])->name('update-status');
    Route::patch('/{ticket}/override-scope', [TicketingController::class, 'overrideScope'])->name('override-scope');
});
