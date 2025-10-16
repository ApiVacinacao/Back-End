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
            'nome' => 'required|string|min:5|max:255|regex:/\s/',
            'cpf' => 'required|min:11|max:11|unique:medicos,cpf',
            'CRM' => 'required|string|unique:medicos,CRM|min:4|max:11|',
            'especialidade_id' => 'required|exists:especialidades,id',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.', #ok
            'nome.string' => 'O campo nome deve ser uma string.', #ok
            'nome.min' => 'O campo nome deve ter no mínimo 5 caracteres.', #ok
            'nome.max' => 'O campo nome deve ter no máximo 255 caracteres.', #ok
            'nome.regex' => 'O campo nome deve conter pelo menos um espaço (nome e sobrenome).', #ok

            'cpf.required' => 'O campo CPF é obrigatório.', #ok
            'cpf.min' => 'O CPF informado não é válido.', #ok
            'cpf.max' => 'O CPF informado não é válido.', #ok
            'cpf.unique' => 'Este CPF já está cadastrado.', #ok

            'CRM.required' => 'O campo CRM é obrigatório.', #ok
            'CRM.string' => 'O campo CRM deve ser uma string.', #ok
            'CRM.unique' => 'Este CRM já está cadastrado.', #ok
            'CRM.min' => 'O campo CRM deve ter no mínimo 4 caracteres.', #ok
            'CRM.max' => 'O campo CRM deve ter no máximo 11 caracteres.', #ok

            'especialidade_id.required' => 'O campo especialidade é obrigatório.', #ok
            'especialidade_id.exists' => 'A especialidade selecionada não existe.', #ok

            'status.boolean' => 'O campo status deve ser verdadeiro ou falso.', #ok
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
