<?php

use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/index', [AgendamentoController::class, 'index']);

Route::get('/horarios-disponiveis', [AgendamentoController::class, 'horariosDisponiveis']);


// Route::get('/test-token', function() {
//     $user = App\Models\User::first();
//     try {
//         $token = $user->createToken('test-token')->plainTextToken;
//         return "Token criado com sucesso: ".$token;
//     } catch (\Exception $e) {
//         return "Erro: ".$e->getMessage();
//     }
// });

require __DIR__.'/auth.php';
