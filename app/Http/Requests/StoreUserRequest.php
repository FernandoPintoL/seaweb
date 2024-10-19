<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Laravel\Jetstream\Jetstream;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
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
            'name' => ['required', 'unique:users'],
            'email' => ['email', 'unique:users'],
            'usernick' => ['required', 'unique:users'],
            'password' => ['required', Password::default(), 'confirmed'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El :attribute es obligatorio.',
            'name.unique' => 'El :attribute esta siendo usado.',
            'email.email' => 'El :attribute es de tipo email.',
            'email.unique' => 'El :attribute esta siendo usado.',
            'usernick.required' => 'El :attribute es obligatorio.',
            'usernick.unique' => 'El :attribute esta siendo usado.',
            'password.required' => ' :attribute es obligatorio.',
            'password.confirmed' => ' :attribute no esta confirmado.',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            "isRequest" => true,
            "isSuccess" => false,
            "isMessageError" => true,
            "message" => $validator->errors(),
            "messageError" => $validator->errors(),
            "data" => [],
            "statusCode" => 422
        ], 422));
    }
}
