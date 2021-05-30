<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix'=> '/v1/cart'], function () {
    Route::post('/create', [CartController::class, 'create']);
    Route::post('/add-item', [CartController::class, 'addItem']);
    Route::delete('/remove-item', [CartController::class, 'removeItem']);
    Route::post('/change-quantity', [CartController::class, 'changeQuantity']);
});

Route::group(['prefix'=> '/v1/product'], function () {
    Route::post('/', [ProductController::class, 'create']);
    Route::put('/', [ProductController::class, 'update']);
    Route::delete('/', [ProductController::class, 'delete']);
});
