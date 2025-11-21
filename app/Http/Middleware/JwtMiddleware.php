<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Log;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['error' => 'Usuário não encontrado'], 404);
            }

        } catch (JWTException $e) {
            Log::error('JWT ERROR: '.$e->getMessage());
            return response()->json(['error' => 'Token inválido'], 401);
        }

        return $next($request);
    }
}
