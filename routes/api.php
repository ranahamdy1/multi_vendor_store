<?php

use App\Http\Controllers\Api\ProductsController;
use Illuminate\Support\Facades\Route;

Route::apiResource('products', ProductsController::class);
