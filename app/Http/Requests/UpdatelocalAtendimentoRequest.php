<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatelocalAtendimentoRequest extends FormRequest
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
            'nome' => 'string|min:5|max:100|unique:local_atendimentos',
            'endereco' => 'string|min:5|max:100',
            'telefone' => 'string|min:10|max:12',
        ];
    }

    public function menssages(): array
    {
        return [
            
            'nome.min' => 'deve ter entre 5 a 100 carateres',
            'nome.max' => 'deve ter entre 5 a 100 carateres',
            'nome.unique' => 'ja exite com esse mesmo nome',
            'endereco.min' => 'deve ter entre 5 a 100 carateres',
            'endereco.max' => 'deve ter entre 5 a 100 carateres',
            'telefone.required' => 'O campo telefone é obrigatório.',
            'telefone.min' => 'deve ter entre 10 há 12 numeros',
            'telefone.max' => 'deve ter entre 10 há 12 numeros',
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
