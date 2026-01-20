<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfileContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu',
        'title',
        'body',
        'created_by',
    ];
}
