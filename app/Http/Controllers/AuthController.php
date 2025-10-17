<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuerRequest;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;



class AuthController extends Controller
{
    // User registration
    public function register(StoreUsuerRequest $request)
    {
        try {
            $user = User::create($request->validated());
        
            $token = JWTAuth::fromUser($user);

            Log::info("usuario cirado com sucesso". $user->id);
            return response()->json(compact('user','token'), 201);
        } catch (\Throwable $th) {
            Log::error('Erro ao registrar usuario: '. $th->getMessage());
            return response()->json(['error' => 'Erro ao registrar usuario'], 500);
        }
        
    }

    public function login(Request $request)
    {
        $credentials = $request->only('cpf', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            $user = User::where('cpf', $request->get('cpf'))->first();

            log::info('usuario logado: '. $user->id);
            return response()->json(compact('token'));
        } catch (JWTException $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }


        // User logout
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out']);
    }
}