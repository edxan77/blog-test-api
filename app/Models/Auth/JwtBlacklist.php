<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class JwtBlacklist extends Model
{
    protected $table = 'jwt_blacklist';

    protected $fillable = [
        'token',
        'updated_at'
    ];
}
