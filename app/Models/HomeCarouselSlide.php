<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeCarouselSlide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'button_label',
        'button_url',
        'buttons',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'buttons' => 'array',
        'is_active' => 'boolean',
    ];
}
