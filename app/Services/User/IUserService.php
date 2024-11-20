<?php

namespace App\Services\User;

use App\Models\ProfileConfirm;
use App\Models\User;
use Illuminate\Http\JsonResponse;

interface IUserService
{
    public function register(array $data): JsonResponse;
    public function authorize(array $credentials): JsonResponse;
    public function getUserByEmail(string $username): ?User;
    public function getUserById(int $id): ?User;
    public function getUserByJwtToken(string $token): ?User;
    public function getProfileConfirmByToken(string $token): ?ProfileConfirm;
    public function confirm(string $token): JsonResponse;
    public function sendConfirmationMessage(string $email): JsonResponse;
}
