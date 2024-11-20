<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class RegistrationRequest extends Request
{
    public function rules(): array
    {
        return [
            'username' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:100|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'password_confirmation' => 'required_with:password|same:password',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => self::REQUIRED_MESSAGE,
            'username.*' => self::INVALID_TYPE_MESSAGE,
            'email.required' => self::REQUIRED_MESSAGE,
            'email.unique' => self::UNIQUE_MESSAGE,
            'email.*' => self::INVALID_TYPE_MESSAGE,
            'password.required' => self::REQUIRED_MESSAGE,
            'password.*' => self::INVALID_TYPE_MESSAGE,
            'password_confirmation.required' => self::REQUIRED_MESSAGE,
            'password_confirmation.*' => self::INVALID_CONFIRMATION_MESSAGE,
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = [];

        foreach ($validator->errors()->getMessages() as $key => $error) {
            $errors[$key] =  $error[0];
        }

        throw new HttpResponseException(response()->json([
            'status' => 'INVALID_DATA',
            'errors' => $errors
        ], 200));
    }
}
