<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\TicketController as CustomerTicketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return auth()->user()->hasRole('customer')
        ? redirect()->route('customer.dashboard')
        : redirect()->route('dashboard');
});
Route::middleware(['auth', 'role:customer'])->group(function () {

    Route::get('/customer/dashboard', [
        DashboardController::class,
        'index'
    ])->name('customer.dashboard');

    Route::get('/customer/tickets/create', [
        CustomerTicketController::class,
        'create'
    ])->name('customer.tickets.create');

    Route::post('/customer/tickets', [
        CustomerTicketController::class,
        'store'
    ])->name('customer.tickets.store');

    Route::get('/customer/tickets/{ticket}', [
        CustomerTicketController::class,
        'show'
    ])->name('customer.tickets.show');

    Route::post('/customer/tickets/{ticket}/messages', [
        CustomerTicketController::class,
        'messageStore'
    ])->name('customer.tickets.messages.store');

});
Route::middleware(['auth', 'role:agent'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Ticket CRUD

    Route::get('/tickets', [TicketController::class, 'index'])
        ->name('tickets.index');

    Route::get('/tickets/create', [TicketController::class, 'create'])
        ->name('tickets.create');

    Route::post('/tickets', [TicketController::class, 'store'])
        ->name('tickets.store');

    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])
        ->name('tickets.show');

    Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])
        ->name('tickets.edit');

    Route::put('/tickets/{ticket}', [TicketController::class, 'update'])
        ->name('tickets.update');

    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])
        ->name('tickets.destroy');

    Route::post('/tickets/{ticket}/messages', [
        MessageController::class,
        'store'
    ])->name('messages.store');

    Route::get('/tickets/{ticket}/messages/{message}', [
        MessageController::class,
        'show'
    ])->name('messages.show');

    Route::put('/tickets/{ticket}/messages/{message}', [
        MessageController::class,
        'update'
    ])->name('messages.update');

    Route::delete('/tickets/{ticket}/messages/{message}', [
        MessageController::class,
        'destroy'
    ])->name('messages.destroy');
});
Route::middleware('auth')->group(function () {

    Route::get('/profile', [
        ProfileController::class,
        'edit'
    ])->name('profile.edit');

    Route::patch('/profile', [
        ProfileController::class,
        'update'
    ])->name('profile.update');

    Route::delete('/profile', [
        ProfileController::class,
        'destroy'
    ])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
