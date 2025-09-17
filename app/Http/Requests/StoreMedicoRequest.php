<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

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
            'especialidade_id' => 'required|numeric|max:255',
            'status' => 'boolean',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Erros de validação',
            'errors' => $validator->errors()
        ], 422));
    }
}
