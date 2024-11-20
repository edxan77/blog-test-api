<?php

namespace App\Services\BruteForce;

interface IBruteForceProtector
{
    public static function check(string $key):bool;
}
