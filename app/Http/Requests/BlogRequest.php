<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class BlogRequest extends Request
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpg|max:2048',
            'id' => 'nullable|integer|exists:blogs,id'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => self::REQUIRED_MESSAGE,
            'title.*' => self::INVALID_TYPE_MESSAGE,
            'description.required' => self::REQUIRED_MESSAGE,
            'description.*' => self::INVALID_TYPE_MESSAGE,
            'image.required' => self::REQUIRED_MESSAGE,
            'image.*' => self::INVALID_TYPE_MESSAGE,
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
