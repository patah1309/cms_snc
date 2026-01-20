<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfileSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'is_visible',
    ];
}
