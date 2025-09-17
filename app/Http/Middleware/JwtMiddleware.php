<?php

namespace App\Http\Middleware;

use Closure;
use Log;
use PhpParser\Node\Expr\Empty_;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            // estrai o token da requisição, autentifica o usuario e se estiver certo a varriavel "user" vai ter usuario
            $user = JWTAuth::parseToken()->authenticate();

            if(empty($user)){
                abort(404);
            }
            
        } catch (JWTException $e) {

            // caso ocorra algum erro
            Log::error('JWT Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Token not valid'], 401);
        }

        //enviando o usuario
        return $next($request);
    }
}
