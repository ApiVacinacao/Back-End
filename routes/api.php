<?php

use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EspecialidadeController;
use App\Http\Controllers\LocalAtendimentoController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\test\testeEmail;
use App\Http\Controllers\TipoConsultaController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::get('/test', [AuthController::class, 'user']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/esquecisenha', [AuthController::class,'esqueciaSenha']);

//Route::get('/test', [testeEmail::class, 'senEmail']);

Route::middleware([JwtMiddleware::class])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('medicos', MedicoController::class);
    Route::apiResource('localAtendimentos', LocalAtendimentoController::class);
    Route::apiResource('tipoConsultas', TipoConsultaController::class);
    Route::apiResource('especialidades', EspecialidadeController::class);

    Route::get('/users', [UserController::class, 'index']); 
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']); 
    Route::delete('/users/{user}', [UserController::class, 'destroy']); 

    // rotas para alteração
    Route::patch('/medicos/{medico}/status', [MedicoController::class, 'toggleStatus']);
    Route::patch('localAtendimentos/{id}/toggle-status', [LocalAtendimentoController::class, 'toggleStatus']);
    Route::patch('tipoConsultas/{tipoConsulta}/toggle-status', [TipoConsultaController::class, 'toggleStatus']);
    Route::patch('agendamentos/{id}/toggle-status', [AgendamentoController::class, 'toggleStatus']);

    Route::apiResource('agendamentos', AgendamentoController::class)->except(['show']);
    Route::get('meus_agendamentos', [AgendamentoController::class, 'show']);
    
    Route::post('relatorios/agendamentos', [RelatorioController::class, 'relatorioAgendamento']);
});