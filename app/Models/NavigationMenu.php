<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavigationMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'url',
        'header_image_path',
        'header_title',
        'parent_id',
        'sort_order',
        'is_visible',
    ];

    protected $appends = [
        'header_image_url',
    ];

    public function getHeaderImageUrlAttribute(): ?string
    {
        if (!$this->header_image_path) {
            return null;
        }
        return url($this->header_image_path);
    }

    public function page()
    {
        return $this->hasOne(MenuPage::class, 'menu_id');
    }
}
