<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateCondominioRequest extends FormRequest
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
            'razonSocial' => ['required', 'unique:condominios', 'min:5'],
            'nit' => ['required', 'unique:condominios', 'min:5']
        ];
    }
    public function messages(){
        return [
            'razonSocial.required' => ' :attribute es requerido.',
            'razonSocial.unique' => ' :attribute ya está siendo ocupado.',
            'razonSocial.min' => ' :attribute debe tener un minimo de 5 caracteres.',
            'nit.required' => ' :attribute es requerido.',
            'nit.unique' => ' :attribute ya esta siendo ocupado.',
            'nit.min' => ' :attribute debe tener un minimo de 5 caracteres.',
        ];
    }
    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            "isRequest"=> true,
            "success" => false,
            "messageError" => true,
            "message" => $validator->errors(),
            "data" => []
        ], 422));
    }
}