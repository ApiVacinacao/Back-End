<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreEspecialidadeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => 'required|string|min:10|max:90|unique:especialidades',
            'descricao' => 'required|string|min:5|max:250',
            'area' => 'required|string|',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'=> 'O campo nome é obrigatório.',
            'nome.string'=> 'O campo nome deve ser uma string.',
            'nome.min'=> 'O campo nome deve ter no mínimo 10 caracteres.',
            'nome.max'=> 'O campo nome deve ter no máximo 90 caracteres.',
            'nome.unique'=> 'Este nome já está cadastrado. Escolha outro.',
            'descricao.required'=> 'O campo descrição é obrigatório.',
            'descricao.string'=> 'O campo descrição deve ser uma string.',
            'descricao.min'=> 'O campo descrição deve ter no mínimo 5 caracteres.',
            'descricao.max'=> 'O campo descrição deve ter no máximo 250 caracteres.',
            'area.required'=> 'O campo área é obrigatório.',
            'area.string'=> 'O campo área deve ser uma string.',
            'status.required'=> 'O campo status é obrigatório.',
            'status.boolean'=> 'O campo status deve ser verdadeiro ou falso (booleano).',
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
