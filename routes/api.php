<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/checkApi', [AuthController::class, 'checkApi']);
Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::prefix('product')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/', [ProductController::class, 'store']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::put('/update/{id}', [ProductController::class, 'update']);
        Route::delete('/delete/{id}', [ProductController::class, 'destroy']);
    });
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrdersController::class, 'index']);
        Route::post('/', [OrdersController::class, 'store']);
        Route::get('/{id}', [OrdersController::class, 'show']);
        Route::put('/update/{id}', [OrdersController::class, 'update']);
        Route::delete('/delete/{id}', [OrdersController::class, 'destroy']);
    });
});

