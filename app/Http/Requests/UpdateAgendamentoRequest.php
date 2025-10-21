<?php

namespace App\Http\Requests;

use App\Rules\DataValida;
use App\Rules\TimeValidate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateAgendamentoRequest extends FormRequest
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
            'data' => ['sometimes','date',new DataValida],
            'hora' => ['sometimes', new TimeValidate],
            'user_id' => 'sometimes|exists:users,id',
            'medico_id' => 'sometimes|exists:medicos,id',
            'tipo_consulta_id' => 'sometimes|exists:tipo_consultas,id',
            'local_atendimento_id' => 'sometimes|exists:local_atendimentos,id'
        ];
    }

    public function messages(): array
    {
        return[
            'user_id.exists' => "Esse campo ainda não foi cadastrado",

            'medico_id.exists' => "Esse campo ainda não foi cadastrado",

            'tipo_consulta_id.exists' => "Esse campo ainda não foi cadastrado",

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
