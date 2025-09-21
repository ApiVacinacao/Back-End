<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'nome' => 'sometimes|required|string|max:255',
            'cpf' => 'required|string|unique:usuarios,cpf,' . $this->user->id,
            'email' => 'required|string|email|max:255|unique:usuarios,email,' . $this->user->id,
            'password' => 'sometimes|nullable|string|min:6',
            'status' => 'sometimes|boolean',
            'role' => 'sometimes|string|in:admin,user', 
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório quando fornecido.',
            'nome.string' => 'O campo nome deve ser uma string.',
            'nome.max' => 'O campo nome não deve exceder 255 caracteres.',
            'status.boolean' => 'O campo status deve ser verdadeiro ou falso.',
        ];
    }

}