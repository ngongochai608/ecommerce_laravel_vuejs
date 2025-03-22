<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TablesController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\ProductsController;
use App\Http\Controllers\Api\InvoicesController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\ProfitController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('categories', CategoriesController::class);
Route::apiResource('products', ProductsController::class);
Route::apiResource('tables', TablesController::class);
Route::apiResource('invoices', InvoicesController::class);
Route::apiResource('users', UsersController::class);
Route::post('history-warehouse', [ProductsController::class, 'addHistoryWarehouse']);
Route::get('history-warehouse', [ProductsController::class, 'getHistoryWarehouse']);
Route::get('statistical/{date}', [InvoicesController::class, 'statistical']);