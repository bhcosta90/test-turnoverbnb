<?php

declare(strict_types = 1);

use App\Http\Controllers;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    require __DIR__ . '/deposit.php';

    require __DIR__ . '/order.php';

    Route::as('api.')->group(function () {
        Route::patch('deposit/{deposit}/status', [Controllers\Customer\DepositController::class, 'status'])->name('deposit.status');
        Route::resource('deposit', Controllers\Customer\DepositController::class)->only(['store', 'update']);
        Route::resource('order', Controllers\Customer\OrderController::class)->only(['store']);
    });
});

require __DIR__ . '/auth.php';
