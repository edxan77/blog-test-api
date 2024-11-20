<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';
    const STATUS_FAILED = 'failed';

    protected $table = 'confirmation_emails';

    protected $fillable = [
        'from',
        'from_name',
        'to',
        'subject',
        'body',
        'sent_date',
        'status',
    ];
}
