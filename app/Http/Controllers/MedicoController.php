<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Http\Requests\StoreMedicoRequest;
use App\Http\Requests\UpdateMedicoRequest;
use Illuminate\Auth\Events\Validated;
use Illuminate\Container\Attributes\Log;
use Illuminate\Support\Facades\Validator;

class MedicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            $validated = Validated::validate($request, [
                'nome' => 'required|string|max:255',
                'CPF' => 'required|string|max:255',
                'CRM' => 'required|string|max:255',
                'especialidade' => 'required|string|max:255',
            ]);
            $medico = Medico::create($validated);
            return response()->json($medico, 201);
        } catch (\Throwable $th) {

            Log::error($th);
            return response()->json($th, 400);
            
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medico $medico)
    {
        //
    }
}
