<?php

namespace App\Models\Auth;

use App\Models\User;
use Firebase\JWT\JWT as FirebaseJWT;
use Illuminate\Auth\AuthenticationException;
use Firebase\JWT\Key;

class Jwt
{
    private function payload()
    {
        return [
            'iss' => env('BLOG_SITE_URL'),
            'aud' => env('BLOG_SITE_URL'),
            'iat' => time(),
            'nbf' => time(),
            'exp' => time() + 15 * 24 * 60 * 60, //15 days,
        ];
    }

    public function fromUser(User $user)
    {
        $payload = $this->payload();
        $payload['id'] = $user->id;

        return FirebaseJWT::encode($payload, env('JWT_SECRET_KEY'), 'HS256');
    }

    public function toUser($jwt)
    {
        try {
            return FirebaseJWT::decode($jwt, new Key(env('JWT_SECRET_KEY'), 'HS256'));
        } catch (\Exception $e) {
            throw new AuthenticationException();
        }
    }

    public function refreshToken($jwt)
    {
        FirebaseJWT::$leeway = 7000;
        $decoded = FirebaseJWT::decode($jwt, new Key(env('JWT_SECRET_KEY'), 'HS256'));
        $payload = $this->payload();
        $payload['id'] = $decoded->id;

        return FirebaseJWT::encode($payload, config('JWT_SECRET_KEY'), 'HS256');
    }
}
