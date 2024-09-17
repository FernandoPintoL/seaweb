<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreVehiculoRequest extends FormRequest
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
            'placa' => ['required', 'string', 'max:50', 'min:4', 'unique:vehiculos'],
            'tipo_vehiculo' => ['required'],
        ];
    }
    public function messages(){
        return [
            'tipo_vehiculo.required' => 'el :attribute es obligatorio.',
            'placa.required' => 'La :attribute es obligatorio.',
            'placa.max' => 'La :attribute tiene un maximo de 50 caracteres.',
            'placa.min' => 'La :attribute tiene un minimo de 4 caracteres.',
            'placa.unique' => 'La :attribute ya esta siendo usado.'
        ];
    }
    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            "isRequest"=> true,
            "isSuccess" => false,
            "isMessageError" => true,
            "message" => $validator->errors(),
            "messageError" => $validator->errors(),
            "data" => [],
            "statusCode" => 422
        ], 422));
    }
}
