<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'address',
        'email',
        'phone',
        'vision',
        'mission',
        'logo_path',
        'operating_hours',
        'business_type',
        'header_home_path',
        'header_about_path',
        'header_services_path',
        'header_news_path',
        'header_kontak_path',
        'header_seo_path',
        'seo_title',
        'seo_description',
        'seo_og_image_path',
    ];
}
