<?php

use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\TicketController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:agent'])->group(function () {

    // Ticket CRUD
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::get('/tickets/{ticket}', [TicketController::class, 'show']);
    Route::put('/tickets/{ticket}', [TicketController::class, 'update']);
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy']);

    // Message CRUD
    Route::get(
        '/tickets/{ticket}/messages',
        [MessageController::class, 'index']
    );

    Route::post(
        '/tickets/{ticket}/messages',
        [MessageController::class, 'store']
    );

    Route::get(
        '/tickets/{ticket}/messages/{message}',
        [MessageController::class, 'show']
    );

    Route::put(
        '/tickets/{ticket}/messages/{message}',
        [MessageController::class, 'update']
    );

    Route::delete(
        '/tickets/{ticket}/messages/{message}',
        [MessageController::class, 'destroy']
    );
});
