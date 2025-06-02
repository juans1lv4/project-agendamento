<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgendamentoRequest extends FormRequest
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
            'data_horario' => 'required|date_format:Y-m-d H:i|after:now',
            'name_client' => 'required|string|min:3|max:100',
            'phone_client' => 'required|string|digits_between:10,11',
        ];
    }
    public  function messages(): array
    {
        return [
            'data_horario.required' => 'Data e hora são obrigatórios.',
            'data_horario.date_format' => 'Formato inválido. Use: YYYY-MM-DD HH:MM ',
            'data_horario.after' => 'A data deve ser posterior ao momento atual.', 

            'name_client.required' => 'O nome do cliente é obrigatório.',
            'name_client.min' => 'O nome do cliente deve ter no mínimo 3 caracteres.',
            'name_client.max' => 'O nome do cliente deve ter no máximo 100 caracteres.',

            'phone_client.required' => 'O telefone do cliente é obrigatório.',
            'phone_client.min' => 'O telefone deve ter no mínimo 10 caracteres.',
            'phone_client.max' => 'O telefone deve ter no máximo 20 caracteres.',
            'phone_client.digits_between' => 'O telefone deve conter entre 10 e 11 dígitos numéricos.',
        ];
    }
}
