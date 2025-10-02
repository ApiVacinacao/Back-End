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

    /**
     * Atualizar apenas o status do médico
     */
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
     * Atualizar médico existente
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
     * Deletar médico
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
