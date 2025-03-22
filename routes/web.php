<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\TableController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\InvoiceController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\WarehouseController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::redirect('/', '/login');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('categories', CategoryController::class);
    Route::resource('tables', TableController::class);
    Route::resource('products', ProductController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::get('/warehouse', function () {
        return Inertia::render('Warehouse/Index');
    })->name('warehouse');
    Route::get('/users', function () {
        return Inertia::render('Users/Index');
    })->name('users');
    Route::get('/create-invoices', function () {
        return Inertia::render('CreateInvoice/Index');
    })->name('create-invoice');

    Route::get('/test-auth', function () {
        return auth()->user()->id;
    });
});

require __DIR__.'/auth.php';
