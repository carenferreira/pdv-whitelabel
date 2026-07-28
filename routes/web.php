<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CashRegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    Route::get('/pedidos', function () {
        return view('pedidos.index');
    });

    Route::get('/produtos', function () {
        return view('produtos.index');
    });

    // Rotas que exigem permissão de manager
    Route::middleware('manager')->group(function () {

        // Caixa 
        Route::prefix('caixa')->name('caixa.')->group(function () {
            Route::get('/', [CashRegisterController::class, 'index'])->name('index');
            Route::post('/abrir', [CashRegisterController::class, 'abrir'])->name('abrir');
            Route::get('/sangria', [CashRegisterController::class, 'criarSangria'])->name('sangria.form');
            Route::post('/sangria', [CashRegisterController::class, 'sangria'])->name('sangria');
            Route::get('/suprimento', [CashRegisterController::class, 'criarSuprimento'])->name('suprimento.form');
            Route::post('/suprimento', [CashRegisterController::class, 'suprimento'])->name('suprimento');
            Route::get('/fechar', [CashRegisterController::class, 'criarFechamento'])->name('fechar.form');
            Route::post('/fechar', [CashRegisterController::class, 'fechar'])->name('fechar');

            Route::get('/fluxo', [CashRegisterController::class, 'fluxo'])->name('fluxo');
        });

        

        Route::get('/relatorios', function () {
            return view('relatorios.index');
        });

        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
    });

    // Perfil — só exige autenticação, não manager
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';