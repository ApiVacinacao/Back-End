<?php

namespace App\Http\Controllers;

use App\Http\Controllers\SMS\BulkSmsController;
use App\Mail\AgendamentoEmail;

use Illuminate\Support\Facades\Mail;
use App\Models\Agendamento;
use App\Http\Requests\StoreAgendamentoRequest;
use App\Http\Requests\UpdateAgendamentoRequest;
use App\Services\BulkSmsService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail as FacadesMail;

class AgendamentoController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/agendamentos",
     *     summary="Lista todos os Agendamentos",
     *     tags={"Agendamentos"},
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
            $agendamentos = Agendamento::with(['medico', 'local_atendimento', 'tipo_consulta', 'user'])->get();
            $user = Auth()->user();

            if ($agendamentos->isEmpty()) {
                return response()->json(['message' => 'Nenhum agendamento encontrado.'], 404);
            }

            Log::info("Usuário {$user->id} acessou a lista de agendamentos.");
            return response()->json($agendamentos, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar agendamentos: ' . $e->getMessage()], 500);
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
     *     path="/api/agendamentos",
     *     summary="Cria um novo Agendamento",
     *     tags={"Agendamentos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"data", "hora", "medico_id", "local_atendimento_id", "tipo_consulta_id"},
     *              @OA\Property(property="data", type="string", format="date", example="2025-12-31"),
     *              @OA\Property(property="hora", type="string", format="time", example="14:30:00"),
     *              @OA\Property(property="user_id", type="integer", example=1),
     *              @OA\Property(property="medico_id", type="integer", example=1),
     *              @OA\Property(property="local_atendimento_id", type="integer", example=1),
     *              @OA\Property(property="tipo_consulta_id", type="integer", example=1),
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
    public function store(StoreAgendamentoRequest $request)
    {
        Gate::authorize('admin');

        try {
            if (Agendamento::where('data', $request->data)->where('hora', $request->hora)->exists()) {
                return response()->json(['error' => 'Já existe um agendamento para essa data e hora.'], 409);
            }

            $agendamento = Agendamento::create($request->validated());
            $user = Auth()->user();

            //envio de mensagem
            Mail::to($user->email)->send(new AgendamentoEmail($user, $agendamento));
        
            Log::info("Usuário {$user->id} criou o agendamento {$agendamento->id}.");
            return response()->json($agendamento, 201);
        } catch (\Exception $e) {
            Log::error('Erro ao criar agendamento: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao criar agendamento: ' . $e->getMessage()], 500);
        }
    }

    // Route: PATCH /api/agendamentos/{agendamento}/toggle-status
    public function toggleStatus($id)
    {
        Gate::authorize('admin');

        try {

            $agenda = Agendamento::find($id);

            $agenda->status = !$agenda->status;
            $agenda->save();

            $user = auth()->user();
            Log::info("Usuário {$user->id} alterou status do agendamento {$agenda->id} para " . ($agenda->status ? 'ativo' : 'inativo'));

            return response()->json($agenda, 200);
        } catch (\Exception $e) {
            Log::error('Erro ao alterar status do agendamento: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao alterar status do agendamento.'], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Agendamento $agendamento)
    {
        try {
            $user = Auth()->user();

            $agendamento = Agendamento::where('user_id', $user->id)->get();

            Log::info("Usuário {$user->id} visualizou o agendamento.");
            return response()->json($agendamento, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar agendamento: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAgendamentoRequest $request, Agendamento $agendamento)
    {

        Gate::authorize('admin');

        try {

            $agendamento->update($request->validated());

            $user = auth()->user();

            Log::info('usuraio: ' . $user->id . ' Agendamento updated successfully: ' . $agendamento->id);
            return response()->json($agendamento, 200);
        } catch (\Exception $e) {

            // Log the error message
            Log::error('Error updating Agendamento: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/agendamentos/{id}",
     *     summary="Remove um agendamento pelo ID",
     *     tags={"Agendamentos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do agendamento",
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

    public function destroy(Agendamento $agendamento)
    {
        Gate::authorize('admin');

        try {
            $user = Auth()->user();

            $agendamento->delete();

            Log::info("Usuário {$user->id} deletou o agendamento {$agendamento->id}.");
            return response()->json(['message' => 'Agendamento deletado com sucesso.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao deletar agendamento: ' . $e->getMessage()], 500);
        }
    }
}
