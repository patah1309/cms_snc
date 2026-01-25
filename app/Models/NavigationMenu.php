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
        'parent_id',
        'sort_order',
        'is_visible',
    ];

    public function page()
    {
        return $this->hasOne(MenuPage::class, 'menu_id');
    }
}
