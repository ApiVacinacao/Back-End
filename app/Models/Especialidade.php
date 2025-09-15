<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialidade extends Model
{
    /** @use HasFactory<\Database\Factories\EspecialidadeFactory> */
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'area',
        'status',
    ];

        protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function medicos()
    {
        return $this->belongsTo(Medico::class);
    }
}
