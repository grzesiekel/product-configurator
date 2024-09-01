<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('order/{order}', [OrderController::class, 'edit'])->name('order.edit');
    Route::post('/order/{order}', [OrderController::class, 'update'])->name('order.update');
    Route::get('order/{order}/print', [OrderController::class, 'print'])->name('order.print');
});


Route::get('/produkt/{product:slug}', [ProductController::class, 'index'])->name('product.index');
// Route::get('/produkt/moskitiera', [ProductController::class, 'show'])->name('products.mos');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
