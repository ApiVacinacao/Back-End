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
            'cpf' => 'sometimes|cpf|unique:medicos,cpf',
            'CRM' => 'sometimes|string|unique:medicos,CRM|min:4|max:11|',
            'especialidade_id' => 'sometimes|integer|exists:especialidades,id',
            'status' => 'boolean',
        ];
    }

        public function messages(): array
    {
        return [
            'nome.string' => 'O campo nome deve ser uma string.',
            'nome.min' => 'O campo nome deve ter no mínimo 5 caracteres.',
            'nome.max' => 'O campo nome deve ter no máximo 255 caracteres.',
            'nome.regex' => 'O campo nome deve conter pelo menos um espaço (nome e sobrenome).',

            'cpf.cpf' => 'O CPF informado não é válido.',
            'cpf.unique' => 'Este CPF já está cadastrado.',

            'CRM.string' => 'O campo CRM deve ser uma string.',
            'CRM.unique' => 'Este CRM já está cadastrado.',
            'CRM.min' => 'O campo CRM deve ter no mínimo 4 caracteres.',
            'CRM.max' => 'O campo CRM deve ter no máximo 11 caracteres.',

            'especialidade_id.required' => 'O campo especialidade é obrigatório.',
            'especialidade_id.exists' => 'A especialidade selecionada não é válida.',

            'status.boolean' => 'O campo status deve ser verdadeiro ou falso.',
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
