<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class UserController
{
    public function index()
    {

        Gate::authorize('admin');

        try{

            $dados= User::all();

            if (empty($dados)) {
                return response()->json('não foi encontrado dados');
            }

            $user = auth()->user();

            Log::info("O usuario ". $user->id ." listou todos os cadastros");
            return response()->json($dados,200);

        }catch(Exception $e){

            Log::error('Erro ao listar todos os usuarios: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao listar todos os usuarios', 'error' => $e->getMessage()], 500);

        }
    }

    public function show($id)
    {
        $dados = User::find($id);

        if (empty($dados)) {
            return response()->json("Sem dados para retornar");
        }

        return response()->json($dados);
    }

    public function update(Request $request, $id){
        
        $user = User::find($id);
        
        if(!$user) {
            return response()->json(["message" => "Usuário não encontrado"], 404);
        }

        return response()->json([
            "message" => "Usuário atualizado com sucesso",
            "user" =>$user
        ]);
    }

    public function destroy(){
        //lembrar de nao destruir o usuario logado
    }
}