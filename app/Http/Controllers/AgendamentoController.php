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
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller;
use Spatie\GoogleCalendar\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Mail as FacadesMail;

class AgendamentoController extends Controller
{

    /**
     * Listar todos os agendamentos
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
            $agendamentos = Agendamento::with(['medico', 'local_atendimento', 'tipo_consulta'])->get();

            if ($agendamentos->isEmpty()) {
                return response()->json(['message' => 'Nenhum agendamento encontrado.'], 200);
            }

            $user = auth()->user();
            Log::info("Usuário {$user->id} acessou a lista de agendamentos.");

            return response()->json($agendamentos, 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar agendamentos: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar agendamentos.'], 500);
        }
    }

    /**
     * Criar novo agendamento
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
            $validated = $request->validated();

            // Verifica se já existe agendamento na mesma data e hora
            if (Agendamento::where('data', $request->data)->where('hora', $request->hora)->exists()) {
                return response()->json(['error' => 'Já existe um agendamento para essa data e hora.'], 409);
            }

            $agendamento = Agendamento::create($validated);

            $user = auth()->user();

            // // google calendar
            // $start = Carbon::parse("{$agendamento->data} {$agendamento->hora}");
            // $end = $start->copy()->addMinutes(30); // define 30min de duração, pode ajustar
            
            // $googleEvent = new Event;
            // $googleEvent->name = "Consulta com Dr(a). {$agendamento->medico->nome}";
            // $googleEvent->description = "Tipo: {$agendamento->tipo_consulta->nome}\nLocal: {$agendamento->local_atendimento->nome}";
            // $googleEvent->startDateTime = $start;
            // $googleEvent->endDateTime = $end;
            // $googleEvent->save();
            
            // // Armazenar o ID do evento no agendamento (adicione coluna `google_event_id` na tabela)
            // $agendamento->update(['google_event_id' => $googleEvent->id]);
            
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
    public function toggleStatus(Agendamento $agendamento)
    {
        Gate::authorize('admin');

        try {
            $agendamento->status = !$agendamento->status;
            $agendamento->save();

            $user = auth()->user();
            Log::info("Usuário {$user->id} alterou status do agendamento {$agendamento->id} para " . ($agendamento->status ? 'ativo' : 'inativo'));

            return response()->json($agendamento, 200);
        } catch (\Exception $e) {
            Log::error('Erro ao alterar status do agendamento: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao alterar status do agendamento.'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/meus_agendamentos",
     *     summary="Exibe o agendamento do paciente logado",
     *     tags={"Agendamentos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=204,
     *         description="Operação bem-sucedida"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Requisição inválida"
     *     )
     * )
     */
    public function show()
    {
        try {
            $user = auth()->user();
            $agendamentos = Agendamento::with(['medico', 'local_atendimento', 'tipo_consulta'])
                ->where('user_id', $user->id)
                ->get();

            if ($agendamentos->isEmpty()) {
                return response()->json(['message' => 'Nenhum agendamento encontrado.'], 200);
            }

            Log::info("Usuário {$user->id} visualizou seus agendamentos.");
            return response()->json($agendamentos, 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar agendamentos do usuário: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar agendamentos.'], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/agendamentos/{id}",
     *     summary="Atualiza um agendamento pelo ID",
     *     tags={"Agendamentos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do agendamento",
     *         @OA\Schema(type="integer")
     *     ),
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
     *         response=204,
     *         description="Tipo de consutla atualizado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tipo de consutla não encontrado"
     *     )
     * )
     */
    public function update(UpdateAgendamentoRequest $request, Agendamento $agendamento)
    {
        Gate::authorize('admin');

        try {
            $validated = $request->validated();

            $agendamento->update($validated);

            //Atualizar evento no Google Calendar
            if ($agendamento->google_event_id) {
                $event = Event::find($agendamento->google_event_id);
                if ($event) {
                    $start = Carbon::parse("{$agendamento->data} {$agendamento->hora}");
                    $end = $start->copy()->addMinutes(30);

                    $event->name = "Consulta com Dr(a). {$agendamento->medico->nome}";
                    $event->description = "Tipo: {$agendamento->tipo_consulta->nome}\nLocal: {$agendamento->local_atendimento->nome}";
                    $event->startDateTime = $start;
                    $event->endDateTime = $end;
                    $event->save();
                }
            }
            
            $user = auth()->user();
            Log::info("Usuário {$user->id} atualizou o agendamento {$agendamento->id}.");

            return response()->json($agendamento, 200);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar agendamento: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao atualizar agendamento.'], 500);
        }
    }

    /**
     * Deletar agendamento
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
            // Remover evento do Google Calendar
            if ($agendamento->google_event_id) {
                $event = Event::find($agendamento->google_event_id);
                if ($event) {
                    $event->delete();
                }
            }
            
            $agendamento->delete();
            $user = auth()->user();
        

            Log::info("Usuário {$user->id} deletou o agendamento {$agendamento->id}.");
            return response()->json(['message' => 'Agendamento deletado com sucesso.'], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao deletar agendamento: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao deletar agendamento.'], 500);
        }
    }
}