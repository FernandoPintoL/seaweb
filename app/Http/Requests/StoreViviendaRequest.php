<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreViviendaRequest extends FormRequest
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
            'nroVivienda' => ['required', 'unique:viviendas']
        ];
    }

    public function messages(){
        return [
            'nroVivienda.unique' => ' :attribute tiene que ser único y ya se encuentra registrado.'
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