<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Medico extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'cpf',
        'CRM',
        'senha',
        'especialidade',
        'status',
    ];

    protected $hidden = [
        'senha', // não expor senha
        'created_at',
        'updated_at',
    ];

    public function phone(): HasOne
    {
        return $this->hasOne(Especialidade::class);
    }
}
