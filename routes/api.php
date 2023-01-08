<?php

use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\api\AuthController;
use Illuminate\Http\Request;
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


Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/user', 'getUser');
        Route::post('/logout', 'logout');
    });

    Route::controller(OrderController::class)->group(function () {
        Route::post('/addToCart', 'addToCart');
        Route::post('/view/orders', 'viewOrders');
        Route::post('/checkout', 'checkout');
    });
});
