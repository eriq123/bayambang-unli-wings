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
    Route::post('/register', 'register');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::prefix('user')->group(function () {
            Route::get('/', 'getUser');
            Route::get('/address', 'getAddress');
            Route::post('/address', 'updateAddress');
        });
        Route::post('/logout', 'logout');
    });

    Route::controller(OrderController::class)->group(function () {
        Route::prefix('cart')->group(function () {
            Route::post('/add', 'addToCart');
            Route::post('/remove', 'removeFromCart');
            Route::get('/view', 'viewCart');
            Route::post('/checkout', 'checkout');
        });

        Route::prefix('order')->group(function () {
            Route::post('/view', 'viewOrders');
        });
    });
});
