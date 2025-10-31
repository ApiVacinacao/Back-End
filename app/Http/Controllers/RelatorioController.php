<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class RelatorioController
{
    
        /**
     * @OA\Post(
     *     path="/api/relatorios/agendamentos",
     *     summary="relatorio de agendamentos",
     *     tags={"Relatorios"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"data_inicial", "data_final", "user_id", "medico_id", "local_atendimento_id", "tipo_consulta_id"},
        *             @OA\Property(property="data_inicial", type="string", format="date", example="2024-01-01"),
        *             @OA\Property(property="data_final", type="string", format="date", example="2024-01-31"),
        *             @OA\Property(property="user_id", type="integer", example=1),
        *             @OA\Property(property="medico_id", type="integer", example=2),
        *             @OA\Property(property="local_atendimento_id", type="integer", example=3),
        *             @OA\Property(property="tipo_consulta_id", type="integer", example=4)
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Relatorio tirado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao gerar relatorio"
     *     )
     * )
     */

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
