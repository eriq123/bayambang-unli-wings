<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'postLogin'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/{slug?}', [HomeController::class, 'index'])->name('index');
    Route::get('/sales/{slug?}', [SalesController::class, 'index'])->name('sales.index');

    Route::name('product.')->prefix('menu')->controller(ProductController::class)->group(function () {
        Route::get('/list', 'index')->name('index');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update', 'update')->name('update');
        Route::post('/destroy', 'destroy')->name('destroy');
    });

    Route::name('user.')->prefix('user')->controller(AuthController::class)->group(function () {
        Route::get('/', 'add')->name('add');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update', 'update')->name('update');
        Route::post('/destroy', 'destroy')->name('destroy');
    });

    Route::name('order.')->controller(OrderController::class)->group(function () {
        Route::get('/{shop}/{slug}', 'orderByStatus')->name('byStatus');

        Route::prefix('order')->group(function () {
            Route::post('/update/{id}', 'update')->name('update');
            Route::delete('/destroy/{id}', 'destroy')->name('destroy');
        });
    });
});
