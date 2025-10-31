<?php

namespace App\Http\Controllers;

use App\Models\localAtendimento;
use App\Http\Requests\StorelocalAtendimentoRequest;
use App\Http\Requests\UpdatelocalAtendimentoRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Log;

class LocalAtendimentoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/localAtendimentos",
     *     summary="Listar todos os locais de atendimento",
     *     tags={"Local de Atendimentos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=204,
     *         description="Lista de locais de atendimento retornada com sucesso",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nenhum local de atendimento encontrado",
     *     )
     * )
     */
    public function index()
    {

        Gate::authorize('admin');

        $dados = localAtendimento::all();

        if ($dados->isEmpty()) {
            return response()->json(['message' => 'não foi encontrado nenhum local'], 404);
        }

        return response()->json($dados, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/api/localAtendimentos",
     *     summary="Criar um novo local de atendimento",
     *     tags={"Local de Atendimentos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome","endereco","telefone"},
     *             @OA\Property(property="nome", type="string", example="Clinica Saúde"),
     *             @OA\Property(property="endereco", type="string", example="Rua das Flores, 123"),
     *             @OA\Property(property="telefone", type="string", example="(11) 98765-4321"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Local de atendimento criado com sucesso",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Erro ao criar local de atendimento",
     *     )
     * )
     */
    public function store(StorelocalAtendimentoRequest $request)
    {

        Gate::authorize('admin');

        try {

            $localAtendimento = localAtendimento::create($request->validated());

            //pegar o id do usuario autenticado
            $user = auth()->user();

            Log::info('o usuario ' . $user->id . ' Local de Atendimento created successfully: ' . $localAtendimento->id);
            return response()->json($localAtendimento, 201);
        } catch (\Exception $e) {

            // Log the error message
            Log::error('Error storing Local Atendimento: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function toggleStatus($id)
    {
        Gate::authorize('admin');

        $local = localAtendimento::find($id);

        if (!$local) {
            return response()->json(['error' => 'Local não encontrado'], 404);
        }

        $local->status = !$local->status;
        $local->save();

        $user = auth()->user();
        Log::info("Usuário {$user->id} alterou status do Local Atendimento {$local->id} para " . ($local->status ? 'Ativo' : 'Inativo'));

        return response()->json($local, 200);
    }


    /**
     * Display the specified resource.
     */
    public function show(localAtendimento $localAtendimento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(localAtendimento $localAtendimento)
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/api/localAtendimentos/{id}",
     *     summary="Atualizar os dados de um Local de Atendimento",
     *     tags={"Local de Atendimentos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Id do local de atendimento a ser atualizado",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome","endereco","telefone"},
     *             @OA\Property(property="nome", type="string", example="Clinica Saúde"),
     *             @OA\Property(property="endereco", type="string", example="Rua das Flores, 123"),
     *             @OA\Property(property="telefone", type="string", example="(11) 98765-4321"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Local de atendimento atualizado com sucesso",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Local de atendimento não encontrado",
     *     )
     * )
     */
    public function update(UpdatelocalAtendimentoRequest $request, localAtendimento $localAtendimento)
    {

        Gate::authorize('admin');

        try {

            $localAtendimento->update($request->validated());

            $user = auth()->user();

            Log::info('usuraio: ' . $user->id . ' Local Atendimento updated successfully: ' . $localAtendimento->id);
            return response()->json($localAtendimento, 200);
        } catch (\Exception $e) {

            // Log the error message
            Log::error('Error updating Local Atendimento: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/localAtendimentos/{id}",
     *     summary="Remover um local de atendimento",
     *     tags={"Local de Atendimentos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do local de atendimento a ser removido",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Local de atendimento removido com sucesso",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Local de atendimento não encontrado",
     *     )
     * )
     */
    public function destroy(localAtendimento $localAtendimento)
    {
        try {

            $localAtendimento->delete();

            $user = auth()->user();

            Log::info('usuraio: ' . $user->id . ' Local Atendimento deleted successfully: ' . $localAtendimento->id);
            return response()->json(['message' => 'Local Atendimento deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting Local Atendimento: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
