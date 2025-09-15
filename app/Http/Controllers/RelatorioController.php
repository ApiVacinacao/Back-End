<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class RelatorioController
{
    public function relatorioAgendamento(Request $request)
    {
        $query = \App\Models\Agendamento::query();

        if($request->has('dataInicial') || $request->has('dataFinal')){
            $dataInicial = Carbon::parse($request->input('dataInicial'));
            $dataFinal = Carbon::parse($request->input('dataFinal'));

            $query->whereBetween('data', [$dataInicial, $dataFinal]);
        }

        //dd($query);

        if($request->has('medico_id')){
            $medico_id = $request->input('medico_id');
            $query->where('medico_id', $medico_id);
        }

        if($request->has('user_id')){
            $user_id = $request->input('user_id');
            $query->where('user_id', $user_id);
        }

        if($request->has('tipo_consulta_id')){
            $tipo_consulta_id = $request->input('tipo_consulta_id');
            $query->where('tipo_consulta_id', $tipo_consulta_id);
        }

        if($request->has('local_atendimento_id')){
            $local_atendimento_id = $request->input('local_atendimento_id');
            $query->where('local_atendimento_id', $local_atendimento_id);
        }

        $registro = $query->get();
        return response()->json($registro);

    }
}
