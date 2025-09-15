<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ajustar conforme regras de permissão
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|unique:medicos,cpf',
            'CRM' => 'required|string|unique:medicos,CRM',
            'senha' => 'required|string|min:6',
            'especialidade' => 'required|string|max:255',
            'status' => 'boolean',
        ];
    }
}
