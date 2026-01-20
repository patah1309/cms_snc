<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserMenuPermission;
use Illuminate\Http\Request;

class UserPermissionController extends Controller
{
    private const MENUS = ['home', 'about', 'services', 'news', 'settings', 'menus', 'users'];

    private function authorizeManage(Request $request)
    {
        $user = $request->user();
        $user->loadMissing('menuPermissions');

        $hasSettingsEdit = $user->menuPermissions
            ->firstWhere('menu', 'settings')?->can_edit;

        if ($user->role === 'super_admin' || $user->role === 'admin' || $hasSettingsEdit || $user->menuPermissions->isEmpty()) {
            return;
        }

        if (!$hasSettingsEdit) {
            abort(403, 'Akses ditolak.');
        }
    }

    public function users(Request $request)
    {
        $this->authorizeManage($request);

        $users = User::query()
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return response()->json(['users' => $users]);
    }

    public function show(Request $request, User $user)
    {
        $this->authorizeManage($request);

        $base = collect(self::MENUS)->mapWithKeys(function ($menu) {
            return [
                $menu => [
                    'can_view' => false,
                    'can_create' => false,
                    'can_edit' => false,
                    'can_delete' => false,
                ],
            ];
        });

        $permissions = $base->merge(
            UserMenuPermission::query()
                ->where('user_id', $user->id)
                ->get()
                ->mapWithKeys(function ($permission) {
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

        return response()->json([
            'user' => $user->only(['id', 'name', 'email']),
            'permissions' => $permissions,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeManage($request);

        $data = $request->validate([
            'permissions' => ['required', 'array'],
        ]);

        foreach (self::MENUS as $menu) {
            $payload = $data['permissions'][$menu] ?? [];
            UserMenuPermission::updateOrCreate(
                ['user_id' => $user->id, 'menu' => $menu],
                [
                    'can_view' => (bool) ($payload['can_view'] ?? false),
                    'can_create' => (bool) ($payload['can_create'] ?? false),
                    'can_edit' => (bool) ($payload['can_edit'] ?? false),
                    'can_delete' => (bool) ($payload['can_delete'] ?? false),
                ]
            );
        }

        return response()->json(['message' => 'Permissions updated.']);
    }
}
