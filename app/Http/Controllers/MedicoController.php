<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\Especialidade;
use App\Http\Requests\StoreMedicoRequest;
use App\Http\Requests\UpdateMedicoRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller;

class MedicoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/medicos",
     *     summary="Listar todos os médicos",
     *     tags={"Medicos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=204,
     *         description="Lista de médicos retornada com sucesso",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nenhum médico encontrado",
     *     )
     * )
     */
    public function index()
    {
        // Retorna todos os médicos com a especialidade relacionada
        $medicos = Medico::with('especialidade')->get();

        return response()->json($medicos, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/medicos/{id}",
     *     summary="Criar um novo Medico",
     *     tags={"Medicos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome","cpf","crm","especialidade_id","status"},
     *             @OA\Property(property="nome", type="string", example="Dr. Rodrigo"),
     *             @OA\Property(property="cpf", type="string", example="123.456.789-00"),
     *             @OA\Property(property="crm", type="string", example="CRM123456"),
     *             @OA\Property(property="especialidade_id", type="integer", example=1),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Medico criado com sucesso",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Erro ao criar Medico",
     *     )
     * )
     */
    public function store(StoreMedicoRequest $request)
    {
        Gate::authorize('admin');

        try {
            $medico = Medico::create($request->validated());

            $user = auth()->user();

            Log::info('Médico criado: ' . $medico->id . "POR:" . $user->id);

            return response()->json($medico, 201);
        } catch (\Exception $e) {
            Log::error('Erro ao criar Médico: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function toggleStatus(Medico $medico)
    {
        Gate::authorize('admin');

        try {
            // Alterna o status
            $medico->status = !$medico->status;
            $medico->save();

            // Retorna o médico atualizado
            return response()->json($medico, 200);
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar status do Médico: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    /**
     * Exibir um médico específico
     */
    public function show(Medico $medico)
    {
        //return response()->json($medico, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/medicos/{id}",
     *     summary="Atualizar um médico",
     *     tags={"Medicos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do médico a ser atualizado",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome","cpf","crm","especialidade_id","status"},
     *             @OA\Property(property="nome", type="string", example="Dr. Rodrigo"),
     *             @OA\Property(property="cpf", type="string", example="123.456.789-00"),
     *             @OA\Property(property="crm", type="string", example="CRM123456"),
     *             @OA\Property(property="especialidade_id", type="integer", example=1),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Médico atualizado com sucesso",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Médico não encontrado",
     *     )
     * )
     */
    public function update(UpdateMedicoRequest $request, Medico $medico)
    {
        Gate::authorize('admin');

        try {
            $validated = $request->validated();

            $medico->update($validated);

            Log::info('Médico atualizado: ' . $medico->id);
            return response()->json($medico, 200);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar Médico: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/medicos/{id}",
     *     summary="Remover um médico",
     *     tags={"Medicos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do médico a ser removido",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Médico removido com sucesso",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Médico não encontrado",
     *     )
     * )
     */
    public function destroy(Medico $medico)
    {
        Gate::authorize('admin');

        try {
            $medico->delete();
            Log::info('Médico deletado: ' . $medico->id);
            return response()->json(['message' => 'Médico deletado com sucesso'], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao deletar Médico: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
