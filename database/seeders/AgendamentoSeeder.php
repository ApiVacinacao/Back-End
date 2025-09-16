<?php

namespace Database\Seeders;

use DB;
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
            'data' => '2025-09-16',
            'hora' => '08:54:00',
            'medico_id' => 1,
            'local_atendimento_id' => 1 ,
            'tipo_consulta_id' => 1
        ]);
    }
}
