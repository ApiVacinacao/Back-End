<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Medico extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'cpf',
        'CRM',
        'especialidade_id',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function especialidade()
    {
        return $this->belongsTo(Especialidade::class);
    }
}
