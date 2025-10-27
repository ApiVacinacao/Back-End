<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'name' => 'sometimes|string|min:5|max:255|regex:/\s/',
            'cpf' => 'sometimes|min:11|max:11|unique:users,cpf',
            // no regex
            // (?=.*[\W_]) = exige pelo menos um caractere especial
            // (?=.*\d) = exige pelo menos um dígito
            'telefone' => 'sometimes|string|min:11|max:14',
            'password' => 'sometimes|string|min:8|max:25|regex:/^(?=.*[\W_])(?=.*\d).+$/|confirmed',
            'email' => 'sometimes|email|unique:users',
            'status' => 'sometimes|boolean',
            'role' => 'sometimes|in:user,admin',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'O campo nome deve ser uma string.', #ok
            'name.min' => 'O campo nome deve ter no mínimo 5 caracteres.', #ok
            'name.max' => 'O campo nome deve ter no máximo 255 caracteres.', #ok
            'name.regex' => 'O campo nome deve conter pelo menos um espaço (nome e sobrenome).', # ok

            'cpf.min' => 'O CPF informado não é válido.', #ok
            'cpf.max' => 'O CPF informado não é válido.', #ok
            'cpf.unique' => 'Este CPF já está cadastrado.', # ok

            'telefone.min' => 'O telefone deve ter no minimo 11 caracteres',
            'telefone.max' => 'O telefone deve ter no maximo 14 caracteres',
            'telefone.string' => 'O campo precisa ser em string',

            'password.string' => 'O campo senha deve ser uma string.', #ok
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.', #ok
            'password.max' => 'A senha deve ter no máximo 25 caracteres.', #
            'password.confirmed' => 'A confirmação da senha não corresponde.', #ok
            'password.regex' => 'A senha deve conter pelo menos um caractere especial e um numero.', #ok

            'email.email' => 'O email informado não é válido.', #ok
            'email.unique' => 'Este email já está cadastrado.', #ok

            'status.boolean' => 'O campo status deve ser verdadeiro ou falso.', #ok

            'role.in' => 'O campo função deve ser bom base nas consifuraçoes.', #ok
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