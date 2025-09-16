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
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
