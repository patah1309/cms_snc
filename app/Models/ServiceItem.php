<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'cover_image_path',
        'sort_order',
        'is_active',
    ];
}
