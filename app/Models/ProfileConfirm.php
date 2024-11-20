<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileConfirm extends Model
{
    protected $table = 'profile_confirmations';

    const TOKEN_STATUS_VALID = 'valid';
    const TOKEN_STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'user_id',
        'type',
        'email',
        'token',
        'code',
        'status'
    ];
}
