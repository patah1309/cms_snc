<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\UserMenuPermission;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_USER = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function menuPermissions()
    {
        return $this->hasMany(UserMenuPermission::class);
    }

    public function hasMenuPermission(string $menu, string $action = 'view'): bool
    {
        if ($this->role === self::ROLE_SUPER_ADMIN) {
            return true;
        }

        $permission = $this->menuPermissions->firstWhere('menu', $menu);
        if (!$permission) {
            return false;
        }

        $key = 'can_' . $action;
        return (bool) $permission->{$key};
    }
}
