<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CategoriesController;

//Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});

//Products routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/products', [ProductsController::class, 'index']);
    Route::get('/product/{id}', [ProductsController::class, 'show']);
    Route::post('/bag-products', [ProductsController::class, 'getBagProductsByIds']);
});

//Categories
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/categories', [CategoriesController::class, 'index']);
});
