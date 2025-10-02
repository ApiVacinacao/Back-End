<?php

namespace App\Http\Controllers;

use App\Mail\AgendamentoEmail;
use App\Models\Agendamento;
use App\Http\Requests\StoreAgendamentoRequest;
use App\Http\Requests\UpdateAgendamentoRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Routing\Controller;

class AgendamentoController extends Controller
{
    /**
     * Listar todos os agendamentos
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
     */
    public function store(StoreAgendamentoRequest $request)
    {
        Gate::authorize('admin');

        try {
            $validated = $request->validated();

            // Verifica se já existe agendamento na mesma data e hora
            $exists = Agendamento::where('data', $validated['data'])
                ->where('hora', $validated['hora'])
                ->where('medico_id', $validated['medico_id'])
                ->exists();

            if ($exists) {
                return response()->json(['error' => 'Já existe um agendamento para este médico, data e hora.'], 409);
            }

            $agendamento = Agendamento::create($validated);

            $user = auth()->user();
            Mail::to($user->email)->send(new AgendamentoEmail($user, $agendamento));

            Log::info("Usuário {$user->id} criou o agendamento {$agendamento->id}.");

            return response()->json($agendamento, 201);
        } catch (\Exception $e) {
            Log::error('Erro ao criar agendamento: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao criar agendamento.'], 500);
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
     * Mostrar agendamentos do usuário logado
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
     * Atualizar agendamento existente
     */
    public function update(UpdateAgendamentoRequest $request, Agendamento $agendamento)
    {
        Gate::authorize('admin');

        try {
            $validated = $request->validated();

            $agendamento->update($validated);

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
     */
    public function destroy(Agendamento $agendamento)
    {
        Gate::authorize('admin');

        try {
            $user = auth()->user();
            $agendamento->delete();

            Log::info("Usuário {$user->id} deletou o agendamento {$agendamento->id}.");
            return response()->json(['message' => 'Agendamento deletado com sucesso.'], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao deletar agendamento: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao deletar agendamento.'], 500);
        }
    }
}
