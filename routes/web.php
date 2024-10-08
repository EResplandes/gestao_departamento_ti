<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChamadoController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('chamados')->group(function () {
    Route::controller(ChamadoController::class)->group(function () {
        Route::get('/associar-tecnico-chamado/{id}', 'associarChamadoTecnico')->name('chamados.associar-tecnico');
        Route::post('/finalizar-chamado', 'finalizarChamado')->name('chamados.finalizar-chamado');
    });
});

Route::prefix('users')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/alterar-status-usuario/{id}', 'alterarStatusUsuario')->name('users.alterar-status');
    });
});
