<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blogs';

    protected $fillable = [
        'user_id',
        'image',
        'title',
        'description'
    ];

    protected $casts = [
        'created_at' => 'date:M d,Y',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
