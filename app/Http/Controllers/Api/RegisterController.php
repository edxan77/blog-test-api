<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConfirmationRequest;
use App\Http\Requests\ConfirmationSendRequest;
use App\Http\Requests\RegistrationRequest;
use App\Services\User\IUserService;

class RegisterController extends Controller
{
    public function __construct(
        private IUserService $userService
    ){}

    public function register(RegistrationRequest $request)
    {
        $data = $request->validated();
        return $this->userService->register($data);
    }

    public function confirm(ConfirmationRequest $request)
    {
        $data = $request->validated();
        return $this->userService->confirm($data['token']);
    }

    public function sendConfirmationMessage(ConfirmationSendRequest $request)
    {
        $data = $request->validated();
        return $this->userService->sendConfirmationMessage($data['email']);
    }
}
