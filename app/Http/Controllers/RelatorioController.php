<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class RelatorioController
{
    public function relatorioAgendamento(Request $request)
    {

        $query = \App\Models\Agendamento::query()->with(['user','local_atendimento', 'tipo_consulta', 'medico']);

        if($request->user_id)
        {
            $query->where('user_id', $request->user_id);
        }

        if($request->data_inicial && $request->data_final)
        {

            $query->whereBetween('data',[$request->data_inicial,$request->data_final]);

        }elseif ($request->data_inicial) {

            $query->whereDate('data', $request->data_inicial);

        }elseif ($request->data_final){

            $query->whereDate('data', $request->data_final);
        }

        if($request->medico_id)
        {
            $query->where('medico_id', $request->medico_id);
        }

        if($request->local_atendimento_id)
        {
            $query->where('local_atendimento_id', $request->local_atendimento_id);
        }

        if($request->tipo_consulta_id)
        {
            $query->where('tipo_consulta_id', $request->tipo_consulta_id);
        }

        $registro = $query->get();
        return response()->json($registro);

    }
}
