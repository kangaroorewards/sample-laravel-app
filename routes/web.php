<?php
// routes/web.php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\KangarooAuth;

Route::get('/', function () {
    return view('welcome');
});


// OAuth routes
Route::get('/login', [AuthController::class, 'passwordProvider']);
// Route::get('/login', [AuthController::class, 'redirectToProvider']);
Route::get('/callback', [AuthController::class, 'handleProviderCallback']);

// Group routes that require a valid Kangaroo token
Route::middleware(KangarooAuth::class)->group(function () {
    // Customers endpoints example
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Similarly, add routes for transactions, offers, rewards, etc.
});
