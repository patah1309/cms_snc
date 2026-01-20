<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NavigationMenu;
use Illuminate\Http\Request;

class NavigationMenuController extends Controller
{
    private function authorizeMenu(Request $request, string $action): void
    {
        $user = $request->user();
        $user->loadMissing('menuPermissions');
        if (!$user->hasMenuPermission('menus', $action)) {
            abort(403, 'Akses ditolak.');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeMenu($request, 'view');

        $menus = NavigationMenu::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return response()->json(['menus' => $menus]);
    }

    public function store(Request $request)
    {
        $this->authorizeMenu($request, 'create');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer', 'exists:navigation_menus,id'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_visible' => ['nullable', 'boolean'],
        ]);

        $menu = NavigationMenu::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? null,
            'url' => $validated['url'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_visible' => $request->boolean('is_visible', true),
        ]);

        return response()->json(['menu' => $menu], 201);
    }

    public function update(Request $request, NavigationMenu $menu)
    {
        $this->authorizeMenu($request, 'edit');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer', 'exists:navigation_menus,id'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_visible' => ['nullable', 'boolean'],
        ]);

        $menu->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? null,
            'url' => $validated['url'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_visible' => $request->boolean('is_visible', $menu->is_visible),
        ]);

        return response()->json(['menu' => $menu]);
    }

    public function destroy(Request $request, NavigationMenu $menu)
    {
        $this->authorizeMenu($request, 'delete');

        $menu->delete();

        return response()->json(['message' => 'Menu dihapus.']);
    }
}
