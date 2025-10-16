<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class UpdatetipoConsultaRequest extends FormRequest
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
            'descricao' => 'sometimes|string|min:5|max:50|unique:tipo_consultas' 
        ];
    }

    public function messages(): array
    {
        return [
            'descricao.string' => 'A descrição deve ser uma string.',
            'descricao.max' => 'A descrição não pode exceder 50 caracteres.',
            'descricao.min'=> 'A descrição não pode ter a baixo de 5 caracteres',
            'descricao.unique' => 'A descrição já existe na base de dados.',
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
