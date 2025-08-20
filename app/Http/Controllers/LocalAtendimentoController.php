<?php

namespace App\Http\Controllers;

use App\Models\localAtendimento;
use App\Http\Requests\StorelocalAtendimentoRequest;
use App\Http\Requests\UpdatelocalAtendimentoRequest;
use App\Models\User;
use Auth;
use Log;

class LocalAtendimentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $dados = localAtendimento::all();

        if($dados->isEmpty()){
            return response()->json(['message' => 'não foi encontrado nenhum local'], 404);
        }

        return response()->json($dados, 200);
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
    public function store(StorelocalAtendimentoRequest $request)
    {
        try{

            $localAtendimento = localAtendimento::create($request->validated());

            //pegar o id do usuario autenticado
            $idUser = Auth::id(); 
            $user = User::find($idUser);

            Log::info('o usuario '. $user->id .' Local de Atendimento created successfully: ' . $localAtendimento->id);
            return response()->json($localAtendimento, 201);

        }catch (\Exception $e) {

            // Log the error message
            Log::error('Error storing Local Atendimento: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(localAtendimento $localAtendimento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(localAtendimento $localAtendimento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatelocalAtendimentoRequest $request, localAtendimento $localAtendimento)
    {
        try{

            dd($request->validated());
            $localAtendimento->update($request->validated());

            $idUser = Auth::id();
            $user = User::find($idUser);

            Log::info('usuraio: ' . $user->id . ' Local Atendimento updated successfully: ' . $localAtendimento->id);
            return response()->json($localAtendimento, 200);
        }catch (\Exception $e) {
            
            // Log the error message
            Log::error('Error updating Local Atendimento: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(localAtendimento $localAtendimento)
    {
        try {

            $localAtendimento->delete();

            $idUser = Auth::id();
            $user = User::find($idUser);

            Log::info('usuraio: ' . $user->id . ' Local Atendimento deleted successfully: ' . $localAtendimento->id);
            return response()->json(['message' => 'Local Atendimento deleted successfully'], 200);

        } catch (\Exception $e) {
            Log::error('Error deleting Local Atendimento: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
