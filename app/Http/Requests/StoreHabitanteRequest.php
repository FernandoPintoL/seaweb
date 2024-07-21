<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreHabitanteRequest extends FormRequest
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
            /* 'perfil.name' => ['required', 'string', 'max:255', 'min:5'],
            'perfil.email' => ['required', 'string', 'email', 'max:255', 'unique:perfils'],
            'perfil.nroDocumento' => ['required','min:5'],
            'perfil.tipo_documento_id' => ['required', 'numeric'] */
        ];
    }
    /* public function messages(){
        return [
            'perfil.name.required' => 'El :attribute es obligatorio.',
            'perfil.email.required' => 'El :attribute es obligatorio.',
            'perfil.email.email' => 'El :attribute es de tipo email.',
            'perfil.email.unique' => 'El :attribute esta siendo usado.',
            'perfil.nroDocumento.required' => 'El :attribute es obligatorio.',
            'perfil.nroDocumento.min' => 'Ingrese al menos 5 carácteres.',
            'perfil.tipo_documento_id.required' => ' :attribute es obligatorio.',
            'perfil.tipo_documento:id.numeric' => ' :attribute solo admite números',
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
    } */
}