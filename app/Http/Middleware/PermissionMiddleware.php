<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $menu, string $action = 'view')
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        $user->loadMissing('menuPermissions');
        if (!$user->hasMenuPermission($menu, $action)) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        return $next($request);
    }
}
