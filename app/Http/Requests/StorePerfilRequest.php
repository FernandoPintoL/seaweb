<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class StorePerfilRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'min:5'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:perfils'],
            'nroDocumento' => ['required','min:5'],
            'tipo_documento_id' => ['required', 'numeric']
        ];
    }

    public function messages(){
        return [
            'name.required' => 'El :attribute es obligatorio.',
            'email.required' => 'El :attribute es obligatorio.',
            'email.email' => 'El :attribute es de tipo email.',
            'email.unique' => 'El :attribute esta siendo usado.',
            'nroDocumento.required' => 'El :attribute es obligatorio.',
            'nroDocumento.min' => 'Ingrese al menos 5 carácteres.',
            'tipo_documento_id.required' => ' :attribute es obligatorio.',
            'tipo_documento:id.numeric' => ' :attribute solo admite números',
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