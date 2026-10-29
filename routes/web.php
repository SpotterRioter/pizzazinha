<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PizzaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('login.store');

Route::middleware(['auth', 'sessao.ativa'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::resource('clientes', ClienteController::class)->except('show');
    Route::resource('pizzas', PizzaController::class)->except('show');
    Route::patch('/pizzas/{pizza}/disponibilidade', [PizzaController::class, 'alternarDisponibilidade'])
        ->name('pizzas.disponibilidade');
    Route::resource('pedidos', PedidoController::class)->except('show');
});
