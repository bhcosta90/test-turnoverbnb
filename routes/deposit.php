<?php

declare(strict_types = 1);

use App\Http\Controllers;
use App\Models;
use Illuminate\Support\Facades\Route;

Route::get('deposit', [Controllers\Customer\DepositController::class, 'index'])
    ->can('viewAny', Models\Deposit::class)
    ->name('deposit.index');

Route::get('deposit/status', Controllers\Admin\DepositStatusController::class)
    ->can('viewAprove', Models\Deposit::class)
    ->name('deposit.status');

Route::get('deposit/create', [Controllers\Customer\DepositController::class, 'create'])
    ->can('create', Models\Deposit::class)
    ->name('deposit.create');

Route::get('deposit/{deposit}/edit', [Controllers\Customer\DepositController::class, 'edit'])
    ->can('edit', 'deposit')
    ->name('deposit.edit');
