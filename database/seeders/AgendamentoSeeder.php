<?php

namespace Database\Seeders;

use App\Models\Agendamento;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgendamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('agendamentos')->insert([
            'user_id' => 1,
            'dataHora' => new DateTime('now'),
            'status' => true,
            'medico_id' => 1,
            'local_atendimento_id' => 1 ,
            'tipo_consulta_id' => 1
        ]);
    }
}
