<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function me(Request $request)
    {
        $user = $request->user();
        $user->load('menuPermissions');

        $defaultMenus = ['home', 'about', 'services', 'news', 'settings', 'menus', 'users'];
        $permissions = collect($defaultMenus)->mapWithKeys(function ($menu) {
            return [
                $menu => [
                    'can_view' => false,
                    'can_create' => false,
                    'can_edit' => false,
                    'can_delete' => false,
                ],
            ];
        });

        $permissions = $permissions->merge(
            $user->menuPermissions->mapWithKeys(function ($permission) {
                return [
                    $permission->menu => [
                        'can_view' => (bool) $permission->can_view,
                        'can_create' => (bool) $permission->can_create,
                        'can_edit' => (bool) $permission->can_edit,
                        'can_delete' => (bool) $permission->can_delete,
                    ],
                ];
            })
        );

        if ($user->role === 'super_admin') {
            $permissions = collect($defaultMenus)->mapWithKeys(function ($menu) {
                return [
                    $menu => [
                        'can_view' => true,
                        'can_create' => true,
                        'can_edit' => true,
                        'can_delete' => true,
                    ],
                ];
            });
        }

        $canManageUsers = $this->canManageUsers($user);

        return response()->json([
            'permissions' => $permissions,
            'can_manage_users' => $canManageUsers,
        ]);
    }

    private function canManageUsers($user): bool
    {
        if ($user->role === 'super_admin' || $user->role === 'admin') {
            return true;
        }

        $hasSettingsEdit = $user->menuPermissions
            ->firstWhere('menu', 'settings')?->can_edit;

        if ($hasSettingsEdit) {
            return true;
        }

        return $user->menuPermissions->isEmpty();
    }
}
