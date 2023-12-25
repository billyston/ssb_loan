<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// Routes without {customer} parameter
Route::prefix('v1/loan')
    ->as('v1:loan.')
    ->group(base_path('routes/v1/common.php'));

// Routes with {customer} parameter
Route::prefix('v1/loan/customers/{customer}')
    ->as('v1:loan.customers.')
    ->group(base_path('routes/v1/main.php'))
    ->whereUuid(
        parameters: ['customer'],
    );
