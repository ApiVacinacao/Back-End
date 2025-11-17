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

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Cria um novo usuario",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "cpf", "password", "password_confirmation", "telefone", "email" },
     *             @OA\Property(property="name", type="string", example="rodrigo lindo"),
     *             @OA\Property(property="cpf", type="string", example="14785236945"),
     *             @OA\Property(property="password", type="string", example="@saudell123"),
     *             @OA\Property(property="password_confirmation", type="string", example="@saudell123"),
     *             @OA\Property(property="telefone", type="string", example="5544978947894"),
     *             @OA\Property(property="email", type="string", example="rodrigolindo@gmail.com"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dados inválidos"
     *     )
     * )
     */
    // User registration
    public function register(StoreUsuerRequest $request)
    {
        try {

            $data = $request->validated();
            
            $data['telefone'] = '+' . $data['telefone'];
            $data['role'] = $data['role'] ?? 'user';
            $data['status'] = $data['status'] ?? true;

            $user = User::create($data);

            $token = JWTAuth::fromUser($user);

            Log::info("usuario cirado com sucesso". $user->id);
            return response()->json(compact('user','token'), 201);
        } catch (\Throwable $th) {
            Log::error('Erro ao registrar usuario: '. $th->getMessage());
            return response()->json(['error' => 'Erro ao registrar usuario'], 500);
        }
        
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"cpf", "password"},
     *             @OA\Property(property="cpf", type="string", example="11122233347"),
     *             @OA\Property(property="password", type="string", example="password"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Login realizado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dados inválidos"
     *     )
     * )
     */
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


    /**
     * @OA\Post(
     *     path="/api/esquecisenha",
     *     summary="Esqueci minha senha",
     *     tags={"Auth"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"cpf"},
     *             @OA\Property(property="cpf", type="string", example="11122233347"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Senha alterada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="CPF não encontrado"
     *     )
     * )
     */
    
    public function esqueciaSenha(Request $request)
{
    try {
        $request->validate([
            'cpf' => 'required|exists:users,cpf',
        ], [
            'cpf.required' => 'O campo CPF é obrigatório.',
            'cpf.exists' => 'CPF não encontrado no sistema.'
        ]);

        $cpf = preg_replace('/\D/', '', $request->cpf);
        $user = User::where('cpf', $cpf)->first();

        if ($user) {
            $novaSenha = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);

            $this->smsService->send($user->telefone, "Sua nova senha é: {$novaSenha}");
            $user->update(['password' => Hash::make($novaSenha)]);

            return response()->json([
                'success' => true,
                'message' => 'Senha redefinida e enviada por SMS!'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'CPF não encontrado no sistema.'
        ], 404);

    } catch (\Throwable $th) {
        return response()->json([
            'success' => false,
            'message' => 'Erro interno no servidor.'
        ], 500);
    }
}

}