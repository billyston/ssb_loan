<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('v1/loan')
    ->as('v1.loan:')
    ->group(base_path('routes/v1/routes.php'));
