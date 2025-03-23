<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiProductController;


Route::prefix('api/products')->group(function () {
    Route::get('/', [ApiProductController::class, 'index']); // список продуктов для API
});