<?php

namespace App\Services\BruteForce;


use Illuminate\Support\Facades\RateLimiter;

class BruteForceProtector implements IBruteForceProtector
{
    public static function check(string $key): bool
    {
        return RateLimiter::attempt($key, 3, function() {});
    }
}
