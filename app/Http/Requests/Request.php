<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Request extends  FormRequest
{
    const REQUIRED_MESSAGE = 'Field is required';
    const INVALID_TYPE_MESSAGE = 'Field is invalid';
    const UNIQUE_MESSAGE = 'Value was used please change to someone else';
    const INVALID_CONFIRMATION_MESSAGE = 'Field not matching with field above';
}
