<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Http\Requests\StoreMedicoRequest;
use App\Http\Requests\UpdateMedicoRequest;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class MedicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Medico::all(), 200);
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
    public function store(StoreMedicoRequest $request)
    {
       
        try {
            // Log the validated data

            $medico = Medico::create($request->validated());

            Log::info('Medico created successfully: ' . $medico->id);
            return response()->json($medico, 201);

        } catch (\Exception $e) {

            // Log the error message
            Log::error('Error storing Medico: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Medico $medico)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Medico $medico)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedicoRequest $request, Medico $medico)
    {
        try {

            $medico->update($request->validated());

            Log::info('Medico updated successfully: ' . $medico->id);
            return response()->json($medico, 200);

        } catch (\Exception $e) {

            // Log the error message
            Log::error('Error updating Medico: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medico $medico)
    {
        try {
            $medico->delete();
            
            Log::info('Medico deleted successfully: ' . $medico->id);
            return response()->json(['message' => 'Medico deleted successfully'], 200);

        } catch (\Exception $e) {

            // Log the error message
            Log::error('Error deleting Medico: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
