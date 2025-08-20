<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicoController;
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

// Route::post('medicos', function (Request $request) {
//     return 'aaaaaaaaaaaaa';
// });

Route::middleware([JwtMiddleware::class])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('medicos', MedicoController::class);
});