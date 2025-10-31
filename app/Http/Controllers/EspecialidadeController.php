<?php

namespace App\Http\Controllers;

use App\Models\Especialidade;
use App\Http\Requests\StoreEspecialidadeRequest;
use App\Http\Requests\UpdateEspecialidadeRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;


class EspecialidadeController
{
    /**
     * @OA\Get(
     *     path="/api/especialidades",
     *     summary="Listar todas as especialidades",
     *     tags={"Especialidades"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=204,
     *         description="Lista de especialidades retornada com sucesso",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nenhuma especialidade encontrada",
     *     )
     * )
     */
    public function index()
    {
        Gate::authorize('admin');

        try{
            $dados =  Especialidade::all();

            if($dados->isEmpty()) {
                return response()->json(['message' => 'Nenhuma especialidade encontrada.'], 404);
            }

            $user = auth()->user();

            Log::info('O usuario '. $user->id .' buscou as especialidades' );
            return response()->json($dados, 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar especialidades: ' . $e->getMessage()], 500);
        }
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
     *     path="/api/especialidades",
     *     summary="Criar uma nova especialidade",
     *     tags={"Especialidades"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome", "descricao", "area", "status"},
     *             @OA\Property(property="nome", type="string", example="Cardiologia"),
     *             @OA\Property(property="descricao", type="string", example="Consulta Geral"),
     *             @OA\Property(property="area", type="string", example="Saúde"),
     *             @OA\Property(property="status", type="boolean", example=true),
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Especialidade criada com sucesso",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Erro ao criar especialidade",
     *     )
     * )
     */
    public function store(StoreEspecialidadeRequest $request)
    {

        Gate::authorize('admin');
        try{
            $especialidade = Especialidade::create($request->validated());

            $user = Auth()->user();


            Log::info('Usuario'. $user->id .'criou a especialidade'. $especialidade->id);

            return response()->json($especialidade, 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar especialidade: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Especialidade $especialidade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Especialidade $especialidade)
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/api/especialidades/{id}",
     *     summary="Atualizar os dados de uma especialidade",
     *     tags={"Especialidades"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da especialidade a ser atualizada",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome", "descricao", "area", "status"},
     *             @OA\Property(property="nome", type="string", example="Cardiologia"),
     *             @OA\Property(property="descricao", type="string", example="Consulta Geral"),
     *             @OA\Property(property="area", type="string", example="Medica"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Especialidade atualizada com sucesso",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Especialidade não encontrada",
     *     )
     * )
     */
    public function update(UpdateEspecialidadeRequest $request, Especialidade $especialidade)
    {
        Gate::authorize('admin');
        try {
            $especialidade->update($request->validated());
            $user = Auth()->user();

            Log::info('Usuario'. $user->id .'atualizou a especialidade'. $especialidade->id);

            return response()->json($especialidade, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar especialidade: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/especialidades/{id}",
     *     summary="Remover uma especialidade",
     *     tags={"Especialidades"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da especialidade a ser removida",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Especialidade removida com sucesso",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Especialidade não encontrada",
     *     )
     * )
     */
    public function destroy(Especialidade $especialidade)
    {
        Gate::authorize('admin');
        try {
            $especialidade->delete();
            $user = Auth()->user();

            Log::info('Usuario'. $user->id .'deletou a especialidade'. $especialidade->id);

            return response()->json(['message' => 'Especialidade deletada com sucesso.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao deletar especialidade: ' . $e->getMessage()], 500);
        }
    }
}
