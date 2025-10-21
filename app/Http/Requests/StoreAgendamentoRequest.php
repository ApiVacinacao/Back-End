<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreAgendamentoRequest extends FormRequest
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
            'data' => 'required|date',
            'hora' => 'required',
            'user_id' => 'required|exists:users,id',
            'medico_id' => 'required|exists:medicos,id',
            'tipo_consulta_id' => 'required|exists:tipo_consultas,id',
            'local_atendimento_id' => 'required|exists:local_atendimentos,id'
        ];
    }

    public function messages(): array
    {
        return[
            'data.required' => "O campo data é obrigatorio",

            'hora.required' => "O campo hora é obrigatorio",

            'user_id.required' => "O campo usuario é obrigatorio",
            'user_id.exists' => "Esse campo ainda não foi cadastrado",

            'medico_id.required' => "O campo medico é obrigatorio",
            'medico_id.exists' => "Esse campo ainda não foi cadastrado",

            'tipo_consulta_id.required' => "O campo tipo de consulta é obrigatorio",
            'tipo_consulta_id.exists' => "Esse campo ainda não foi cadastrado",

            'local_atendimento_id.required' => "O campo local de atendimento é obrigatorio",
            'local_atendimento_id.exists' => "Esse campo ainda não foi cadastrado",

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
