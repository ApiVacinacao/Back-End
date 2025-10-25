<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUsuerRequest extends FormRequest
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
            'name' => 'required|string|min:5|max:255|regex:/\s/',
            'cpf' => 'required|min:11|max:11|unique:users,cpf',
            // no regex
            // (?=.*[\W_]) = exige pelo menos um caractere especial
            // (?=.*\d) = exige pelo menos um dígito
            'password' => 'required|string|min:8|max:25|regex:/^(?=.*[\W_])(?=.*\d).+$/|confirmed',
            'email' => 'required|email|unique:users',
            'status' => 'sometimes|boolean',
            'role' => 'sometimes|in:user,admin',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.', #ok
            'name.string' => 'O campo nome deve ser uma string.', #ok
            'name.min' => 'O campo nome deve ter no mínimo 5 caracteres.', #ok
            'name.max' => 'O campo nome deve ter no máximo 255 caracteres.', #ok
            'name.regex' => 'O campo nome deve conter pelo menos um espaço (nome e sobrenome).', # ok

            'cpf.required' => 'O campo CPF é obrigatório.', #ok
            'cpf.min' => 'O CPF informado não é válido.', #ok
            'cpf.max' => 'O CPF informado não é válido.', #ok
            'cpf.unique' => 'Este CPF já está cadastrado.', # ok

            'password.required' => 'O campo senha é obrigatório.', #ok
            'password.string' => 'O campo senha deve ser uma string.', #ok
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.', #ok
            'password.max' => 'A senha deve ter no máximo 25 caracteres.', #
            'password.confirmed' => 'A confirmação da senha não corresponde.', #ok
            'password.regex' => 'A senha deve conter pelo menos um caractere especial e um numero.', #ok

            'email.required' => 'O campo email é obrigatório.', #ok
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
