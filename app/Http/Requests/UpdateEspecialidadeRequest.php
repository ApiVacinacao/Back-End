<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEspecialidadeRequest extends FormRequest
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
            'nome' => 'sometimes|required|string|max:255',
            'descricao' => 'nullable|string',
            'area' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório quando fornecido.',
            'nome.string' => 'O campo nome deve ser uma string.',
            'nome.max' => 'O campo nome não deve exceder 255 caracteres.',
            'descricao.string' => 'O campo descrição deve ser uma string.',
            'area.required' => 'O campo área é obrigatório quando fornecido.',
            'area.string' => 'O campo área deve ser uma string.',
            'area.max' => 'O campo área não deve exceder 255 caracteres.',
            'status.boolean' => 'O campo status deve ser verdadeiro ou falso.',
        ];
    }
}
