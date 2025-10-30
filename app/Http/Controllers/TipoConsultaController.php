<?php

namespace App\Http\Controllers;

use App\Models\tipoConsulta;
use App\Http\Requests\StoretipoConsultaRequest;
use App\Http\Requests\UpdatetipoConsultaRequest;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TipoConsultaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        /**
     * @OA\Get(
     *     path="/api/tipoConsultas",
     *     summary="Lista todos os Tipos de consulta",
     *     tags={"Tipo de Consultas"},
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
            $dados = tipoConsulta::all();

            if ($dados->isEmpty()) {
                return response()->json(['message' => 'não foi encontrado nenhum tipo de consulta'], 404);
            }

            return response()->json($dados, 200);
        } catch (\Exception $e) {
            Log::error('Erro ao listar tipos de consulta: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao listar tipos de consulta', 'error' => $e->getMessage()], 500);
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
     *     path="/api/tipoConsultas",
     *     summary="Cria um novo Tipo de Consulta",
     *     tags={"Tipo de Consultas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"descricao"},
     *             @OA\Property(property="descricao", type="string", example="Consulta Geral"),
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
    public function store(StoretipoConsultaRequest $request)
    {
        Gate::authorize('admin');

        try {

            $tipoConsulta = tipoConsulta::create($request->validated());

            $idUser = Auth::id();

            Log::info('o usuario ' . $idUser . ' Tipo de Consulta criado com :' . $tipoConsulta->id);

            return response()->json($tipoConsulta, 201);
        } catch (\Exception $e) {
            Log::error('Erro ao criar tipo de consulta: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao criar tipo de consulta', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Alterar status de ativo/inativo
     */
    public function toggleStatus(tipoConsulta $tipoConsulta)
    {
        Gate::authorize('admin');

        try {
            $tipoConsulta->status = !$tipoConsulta->status;
            $tipoConsulta->save();

            $idUser = Auth::id();
            Log::info('O usuário ' . $idUser . ' alterou status do Tipo de Consulta: ' . $tipoConsulta->id . ' para ' . ($tipoConsulta->status ? 'Ativo' : 'Inativo'));

            return response()->json($tipoConsulta, 200);
        } catch (\Throwable $th) {
            Log::error('Erro ao alterar status do tipo de consulta: ' . $th->getMessage());
            return response()->json(['message' => 'Erro ao alterar status', 'error' => $th->getMessage()], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(tipoConsulta $tipoConsulta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(tipoConsulta $tipoConsulta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatetipoConsultaRequest $request, tipoConsulta $tipoConsulta)
    {
        Gate::authorize('admin');

        try {

            $tipoConsulta->update($request->validated());

            //pegar o id do usuario autenticado
            $idUser = Auth::id();

            Log::info('o usuario ' . $idUser . ' Tipo de Consulta updated successfully: ' . $tipoConsulta->id);
            return response()->json($tipoConsulta, 200);
        } catch (\Throwable $th) {
            Log::error('Erro ao atualizar tipo de consulta: ' . $th->getMessage());
            return response()->json(['message' => 'Erro ao atualizar tipo de consulta', 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/tipoConsultas/{id}",
     *     summary="Remove um usuário pelo ID",
     *     tags={"Tipo de Consultas"},
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
     *         description="Tipo de consutla removido com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tipo de consutla não encontrado"
     *     )
     * )
     */
    public function destroy(tipoConsulta $tipoConsulta)
    {
        Gate::authorize('admin');
        try {
            $tipoConsulta->delete();

            //pegar o id do usuario autenticado
            $idUser = Auth::id();

            Log::info('o usuario ' . $idUser . ' Tipo de Consulta deleted successfully: ' . $tipoConsulta->id);
            return response()->json(['message' => 'Tipo de consulta deletado com sucesso'], 200);
        } catch (\Throwable $th) {
            Log::error('Erro ao deletar tipo de consulta: ' . $th->getMessage());
            return response()->json(['message' => 'Erro ao deletar tipo de consulta', 'error' => $th->getMessage()], 500);
        }
    }
}
