<?php

use App\Http\Controllers\CaixaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    Route::get('/pedidos', function(){
        return view('pedidos.index');
    });

    Route::get('/produtos', function(){
        return view('produtos.index');
    });

    Route::middleware('manager')->group(function () {
        Route::get('/caixa', function () {
            return view('caixa.index');
        });
        Route::get('/relatorios', function(){
            return view('relatorios.index');
        });
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
