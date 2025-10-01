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

class UserController extends Controller
{

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


    public function show(User $user)
    {
        //return response()->json($user, 200);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        Gate::authorize('admin');

        try {
            if(Auth::id() === $user->id && !$request->status){
                return response()->json(["voce não pode inativar o seu usario"],403);
            }

            $validated = $request->validated();
            $user->update($validated);
    
            Log::info("Usuário atualizado: {$user->id} por " . auth()->user()->id);
    
            return response()->json($user, 200);
        } catch (Exception $e) {
            Log::error("Erro ao atualizar usuário: " . $e->getMessage());
    
            return response()->json([
                'message' => 'Erro ao atualizar usuário',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function destroy(User $user)
    {
        Gate::authorize('admin');
    
        try {
            $user->delete();
    
            Log::info("Usuário deletado: {$user->id} por " . auth()->user()->id);
    
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