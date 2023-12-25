<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Customer\CustomerCreatedController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'customers', 'as' => 'customers.'], function (): void {
    Route::post(
        uri: '',
        action: CustomerCreatedController::class
    )->name(
        name: 'store',
    );
});
