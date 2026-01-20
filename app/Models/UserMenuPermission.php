<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMenuPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'menu',
        'can_view',
        'can_create',
        'can_edit',
        'can_delete',
    ];
}
