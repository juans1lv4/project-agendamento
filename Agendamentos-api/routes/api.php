<?php
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']); //Authenticaçao 
Route::get('/horarios-disponiveis', [AgendamentoController::class, 'horariosDisponiveis']); // Rota Publica


Route::post('/agendamentos', [AgendamentoController::class, 'store']); // Cria agendamentos
Route::delete('/agendamentos/{id}', [AgendamentoController::class, 'destroy']); // Apaga os agendamentos

// acoes de adm
Route::middleware(['auth:sanctum', IsAdmin::class])->prefix('admin')->group(function () {
    Route::get('/agendamentos', [AdminController::class, 'index']);
    Route::delete('/agendamentos/{id}/cancelar', [AdminController::class, 'cancelar']);
});