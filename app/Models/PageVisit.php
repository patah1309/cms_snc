<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'visited_on',
        'path',
        'ip_address',
        'user_agent',
        'session_id',
    ];

    protected $casts = [
        'visited_on' => 'date',
    ];
}
