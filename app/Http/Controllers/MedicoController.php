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
     * Listar todos os médicos com a especialidade
     */
    public function index()
    {
        // Retorna todos os médicos com a especialidade relacionada
        $medicos = Medico::with('especialidade')->get();
        return response()->json($medicos, 200);
    }

    /**
     * Criar novo médico
     */
    public function store(StoreMedicoRequest $request)
    {
        Gate::authorize('admin', [Auth::user()->role]);

        try {
            $validated = $request->validated();
            $validated['status'] = true; // sempre ativo

            // Pega a especialidade pelo ID
            if (isset($validated['especialidade_id'])) {
                $especialidade = Especialidade::find($validated['especialidade_id']);
                if ($especialidade) {
                    $validated['especialidade'] = $especialidade->nome; // salva o nome no médico
                }
            }

            $medico = Medico::create($validated);

            Log::info('Médico criado: ' . $medico->id);

            return response()->json($medico, 201);
        } catch (\Exception $e) {
            Log::error('Erro ao criar Médico: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Exibir um médico específico
     */
    public function show(Medico $medico)
    {
        return response()->json($medico, 200);
    }

    /**
     * Atualizar médico existente
     */
    public function update(UpdateMedicoRequest $request, Medico $medico)
    {
        Gate::authorize('admin', [Auth::user()->role]);

        try {
            $validated = $request->validated();
            $validated['status'] = true; // força ativo

            // Atualiza o nome da especialidade se vier o ID
            if (isset($validated['especialidade_id'])) {
                $especialidade = Especialidade::find($validated['especialidade_id']);
                if ($especialidade) {
                    $validated['especialidade'] = $especialidade->nome;
                }
            }

            $medico->update($validated);

            Log::info('Médico atualizado: ' . $medico->id);
            return response()->json($medico, 200);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar Médico: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Deletar médico
     */
    public function destroy(Medico $medico)
    {
        Gate::authorize('admin', [Auth::user()->role]);

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
