<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Http\Requests\StoreAgendamentoRequest;
use App\Http\Requests\UpdateAgendamentoRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AgendamentoController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('admin', Agendamento::class);

        
        try {
            $agendamentos = Agendamento::all();
            $user = Auth()->user();

            if($agendamentos->isEmpty()) {
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
     * Store a newly created resource in storage.
     */
    public function store(StoreAgendamentoRequest $request)
    {
       // dd("paseii aqui");
        Gate::authorize('admin', Agendamento::class);

        try {
            if(Agendamento::where('data', $request->data)->where('hora', $request->hora)->exists()) {
                return response()->json(['error' => 'Já existe um agendamento para essa data e hora.'], 409);
            }

            $agendamento = Agendamento::create($request->validated());
            $user = Auth()->user();

            Log::info("Usuário {$user->id} criou o agendamento {$agendamento->id}.");
            return response()->json($agendamento, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar agendamento: ' . $e->getMessage()], 500);
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
     * Show the form for editing the specified resource.
     */
    public function edit(Agendamento $agendamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAgendamentoRequest $request, Agendamento $agendamento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agendamento $agendamento)
    {
        Gate::authorize('admin', Agendamento::class);

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
