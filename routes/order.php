<?php

declare(strict_types = 1);

use App\Http\Controllers;
use App\Models;
use Illuminate\Support\Facades\Route;

Route::get('order', [Controllers\Customer\OrderController::class, 'index'])
    ->can('viewAny', Models\Order::class)
    ->name('order.index');

Route::get('order/create', [Controllers\Customer\OrderController::class, 'create'])
    ->can('create', Models\Order::class)
    ->name('order.create');
