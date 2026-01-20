<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    private function authorizeMenu(Request $request, string $action): void
    {
        $user = $request->user();
        $user->loadMissing('menuPermissions');
        if (!$user->hasMenuPermission('users', $action)) {
            abort(403, 'Akses ditolak.');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeMenu($request, 'view');

        $users = User::query()
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role', 'created_at']);

        return response()->json(['users' => $users]);
    }

    public function store(Request $request)
    {
        $this->authorizeMenu($request, 'create');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['nullable', 'string', 'max:50'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? User::ROLE_USER,
        ]);

        $this->seedDefaultPermissions($user);

        return response()->json(['user' => $user], 201);
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeMenu($request, 'edit');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['nullable', 'string', 'max:50'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        if (array_key_exists('role', $validated)) {
            $user->role = $validated['role'] ?: User::ROLE_USER;
        }
        $user->save();

        return response()->json(['user' => $user]);
    }

    public function destroy(Request $request, User $user)
    {
        $this->authorizeMenu($request, 'delete');

        $user->delete();

        return response()->json(['message' => 'User dihapus.']);
    }

    private function seedDefaultPermissions(User $user): void
    {
        $menus = ['home', 'about', 'services', 'news', 'settings', 'menus', 'users'];
        foreach ($menus as $menu) {
            $user->menuPermissions()->firstOrCreate(
                ['menu' => $menu],
                [
                    'can_view' => true,
                    'can_create' => false,
                    'can_edit' => false,
                    'can_delete' => false,
                ]
            );
        }
    }
}
