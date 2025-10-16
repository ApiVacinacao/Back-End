<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateMedicoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow all users to make this request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => 'sometimes|string|min:5|max:255|regex:/\s/',
            'cpf' => 'sometimes|min:11|max:11|unique:medicos,cpf',
            'CRM' => 'sometimes|string|unique:medicos,CRM|min:4|max:11|',
            'especialidade_id' => 'sometimes|exists:especialidades,id',
            'status' => 'sometimes|boolean',
        ];
    }

        public function messages(): array
    {
        return [
            'nome.string' => 'O campo nome deve ser uma string.', #ok
            'nome.min' => 'O campo nome deve ter no mínimo 5 caracteres.', #ok
            'nome.max' => 'O campo nome deve ter no máximo 255 caracteres.', #ok
            'nome.regex' => 'O campo nome deve conter pelo menos um espaço (nome e sobrenome).', #ok

            'cpf.min' => 'O CPF informado não é válido.', #ok
            'cpf.max' => 'O CPF informado não é válido.', #ok
            'cpf.unique' => 'Este CPF já está cadastrado.', #ok

            'CRM.string' => 'O campo CRM deve ser uma string.', #ok
            'CRM.unique' => 'Este CRM já está cadastrado.', #ok
            'CRM.min' => 'O campo CRM deve ter no mínimo 4 caracteres.', #ok
            'CRM.max' => 'O campo CRM deve ter no máximo 11 caracteres.', #ok

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
