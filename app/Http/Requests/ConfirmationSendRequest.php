<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ConfirmationSendRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|'
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => self::REQUIRED_MESSAGE,
            'email.*' => self::INVALID_TYPE_MESSAGE,
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
