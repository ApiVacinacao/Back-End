<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class localAtendimento extends Model
{
    /** @use HasFactory<\Database\Factories\LocalAtendimentoFactory> */
    use HasFactory;

    protected $fillable = [
        'nome',
        'endereco',
        'telefone',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
