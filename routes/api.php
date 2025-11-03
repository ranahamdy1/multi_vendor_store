<?php

use App\Http\Controllers\Api\AccessTokenController;
use App\Http\Controllers\Api\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return Auth::guard('sanctum')->user();
});


Route::apiResource('products', ProductsController::class);

Route::post('auth/access-tokens', [AccessTokenController::class,'store'])->middleware('guest:sanctum');

Route::delete('auth/access-tokens/{token?}', [AccessTokenController::class,'destroy'])->middleware('guest:sanctum');
