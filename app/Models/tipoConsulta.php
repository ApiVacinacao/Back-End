<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipoConsulta extends Model
{
    /** @use HasFactory<\Database\Factories\TipoConsultaFactory> */
    use HasFactory;

    protected $fillable = [
        'descricao',
    ];

    
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
