<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\User\IUserService;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(
        private IUserService $userService
    ){}

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $data['is_confirmed'] = true;
        return response()->json($this->userService->authorize($data));
    }

    public function logout() {
        Auth::guard('api')->logout();
        return response()->json([
            'status' => 'OK'
        ]);
    }
}
