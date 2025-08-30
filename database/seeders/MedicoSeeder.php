<?php

namespace Database\Seeders;

use App\Models\Medico;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Medico::create([
            'nome' => 'Dr. João Silva',
            'especialidade' => 'Cardiologia',
            'crm' => '123456',
            'especialidade_id' => 1]);
    }
}
