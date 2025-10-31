<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;

class UserController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Lista todos os usuários",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Operação bem-sucedida"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Requisição inválida"
     *     )
     * )
     */

    public function index()
    {
        Gate::authorize('admin');
    
        try {
            $dados = User::all();
    
            if ($dados->isEmpty()) {
                return response()->json(['message' => 'Nenhum usuário encontrado'], 404);
            }
    
            $user = auth()->user();
            Log::info("O usuário {$user->id} listou todos os cadastros");
    
            return response()->json($dados, 200);
        } catch (Exception $e) {
            Log::error('Erro ao listar os usuários: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erro ao listar usuários',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function create()
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Mostrar os dados de um Usuario",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="usuario encontrado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario não encontrado"
     *     )
     * )
     */
    public function show(User $user)
    {
        return response()->json($user, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Atualizar os dados de um Usuario",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário",
     *         @OA\Schema(type="integer")
     *     ),
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
     *         response=204,
     *         description="Usuario atualizado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario não encontrado"
     *     )
     * )
     */

    public function update(UpdateUserRequest $request, User $user)
    {
        Gate::authorize('admin');

        try {
            if(Auth::id() === $user->id && !$request->status){
                return response()->json(["voce não pode inativar o seu usario"],403);
            }

            $validated = $request->validated();
            $user->update($validated);
    
            //Log::info("Usuário atualizado: {$user->id} por " . auth()->user()->id);
    
            return response()->json($user, 200);
        } catch (Exception $e) {
            Log::error("Erro ao atualizar usuário: " . $e->getMessage());
    
            return response()->json([
                'message' => 'Erro ao atualizar usuário',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Remover um usuario",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Usuario removido com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario não encontrado"
     *     )
     * )
     */
    public function destroy(User $user)
    {
        Gate::authorize('admin');
    
        try {
            $user->delete();
    
            //Log::info("Usuário deletado: {$user->id} por " . auth()->user()->id);
    
            return response()->json(['message' => 'Usuário deletado com sucesso'], 200);
        } catch (Exception $e) {
            Log::error("Erro ao deletar usuário: " . $e->getMessage());
    
            return response()->json([
                'message' => 'Erro ao deletar usuário',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}