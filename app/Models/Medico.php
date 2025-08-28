<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    /** @use HasFactory<\Database\Factories\MedicoFactory> */
    use HasFactory;

    
    protected $fillable = [
        'nome',
        'CPF',
        'CRM',
        'especialidade_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        
    ];

   protected $appends = ['especialidade_nome'];

   public function getEspecialidadeNomeAttribute()
    {
        return $this->especialidade?->nome;
    }



    public function especialidade()
    {
        // indica que um médico pertence a uma especialidade
        // a espe
        return $this->belongsTo(Especialidade::class);
    }
}
