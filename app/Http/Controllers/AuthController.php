<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuerRequest;
use Illuminate\Http\Request;

use App\Models\User;
use App\Services\BulkSmsService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;



class AuthController extends Controller
{
    protected $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%¨&*()_+';
    protected $smsService;

    public function __construct(BulkSmsService $smsService)
    {
        $this->smsService = $smsService;
    }
    
    // User registration
    public function register(StoreUsuerRequest $request)
    {
        try {
            $request->validated();

            $request["telefone"] = "+" . $request["telefone"];

            $user = User::create($request->all());
        
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

    public function esqueciaSenha(Request $request){

        try {
            $request->validate([
            'cpf' => 'exists:users,cpf',
            ],[
                'cpf.exists' => 'não foi encontrado nenhum CPF válido no sistema'
            ]);

            $user = DB::table('users')->where('cpf', '=', $request->get('cpf'))->first();

            dd($user->telefone);

            if (!empty($user)){
                $randomString = substr(str_shuffle($this->caracteres), 10, 15);
                $this->smsService->send($user->telefone, $user->name . " sua senha foi alterada para " . $randomString);

                Hash::make($randomString);
                $user->update(['password'=> $randomString]);
            }

            return response()->json(['message'=> 'usuario não encontrado'], 404);
        } catch (\Throwable $th) {
            Log::error("erro não esperado no esqueciaSenha: ".$th->getMessage());
            return response()->json(['error'=> 'erro legal'. $th], 500);
        }
        
    }
}