<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    /** @use HasFactory<\Database\Factories\AgendamentoFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'data',
        'hora',
        'medico_id',
        'local_atendimento_id',
        'tipo_consulta_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
