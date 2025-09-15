<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;



class AuthController extends Controller
{
    // User registration
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'email' => 'required|string|email|max:255|unique:users',
            'status' => 'in:ativo,inativo',
            'role' => 'in:user,admin',
        ]);


        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'cpf' => $request->get('cpf'),
            'password' => Hash::make($request->get('password')),
            'email' => $request->get('email'),
            'status' => $request->get('status', 'ativo') ,
            'role' => $request->get('role', 'user'),
        ]);

        $token = JWTAuth::fromUser($user);

        Log::info("usuario cirado com sucesso". $user->id);
        return response()->json(compact('user','token'), 201);
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
