<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuerRequest;
use App\Models\User;
use App\Services\BulkSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    protected $smsService;

    public function __construct(BulkSmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function register(StoreUsuerRequest $request)
    {
        try {
            $data = $request->validated();
            $data['telefone'] = '+' . $data['telefone'];
            $data['role'] = $data['role'] ?? 'user';
            $data['status'] = $data['status'] ?? true;

            $user = User::create($data);

            $token = JWTAuth::fromUser($user);

            Log::info("Usuário criado ID: {$user->id}");

            return response()->json([
                'user'  => $user,
                'token' => $token
            ], 201);

        } catch (\Throwable $e) {
            Log::error('Erro ao registrar: '.$e->getMessage());
            return response()->json(['error' => 'Erro ao registrar'], 500);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('cpf', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Credenciais inválidas'], 401);
            }

            $user = auth()->user();

            Log::info("Login ID: {$user->id}");

            return response()->json([
                'token' => $token,
                'user'  => $user
            ], 200);

        } catch (JWTException $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Erro ao criar token'], 500);
        }
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Logout efetuado']);
    }

    public function esqueciaSenha(Request $request)
    {
        try {
            $request->validate([
                'cpf' => 'required|exists:users,cpf',
            ]);

            $cpf = preg_replace('/\D/', '', $request->cpf);
            $user = User::where('cpf', $cpf)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'CPF não encontrado'
                ], 404);
            }

            $novaSenha = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);

            $this->smsService->send($user->telefone, "Nova senha: {$novaSenha}");

            $user->update(['password' => Hash::make($novaSenha)]);

            return response()->json([
                'success' => true,
                'message' => 'Senha redefinida e enviada por SMS!'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno'
            ], 500);
        }
    }
}
