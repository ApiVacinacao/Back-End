<?php

namespace App\Http\Middleware;

use Closure;
use Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            

        } catch (JWTException $e) {
            Log::error('JWT Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Token not valid'], 401);
        }



        return $next($request);
    }
}
