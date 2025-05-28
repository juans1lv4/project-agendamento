<?php

use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::get('/horarios-disponiveis', [AgendamentoController::class, 'horariosDisponiveis']);
Route::post('/agendamentos', [AgendamentoController::class, 'store']);
Route::get('/index', [AgendamentoController::class, 'index']);
Route::delete('/agendamentos/{id}', [AgendamentoController::class, 'destroy']);