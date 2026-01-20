<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfileContent;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    private const MENUS = ['home', 'about', 'services', 'news'];

    private function authorizeMenu(Request $request, string $menu, string $action): void
    {
        $user = $request->user();
        $user->loadMissing('menuPermissions');

        if (!in_array($menu, self::MENUS, true)) {
            abort(404);
        }

        if (!$user->hasMenuPermission($menu, $action)) {
            abort(403, 'Akses ditolak.');
        }
    }

    public function index(Request $request)
    {
        $menu = (string) $request->query('menu', '');
        $this->authorizeMenu($request, $menu, 'view');

        $contents = CompanyProfileContent::query()
            ->where('menu', $menu)
            ->orderByDesc('id')
            ->get(['id', 'title', 'body', 'created_by', 'created_at']);

        return response()->json([
            'contents' => $contents,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu' => ['required', 'string'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
        ]);

        $this->authorizeMenu($request, $validated['menu'], 'create');

        $content = CompanyProfileContent::create([
            'menu' => $validated['menu'],
            'title' => $validated['title'],
            'body' => $validated['body'] ?? null,
            'created_by' => $request->user()->id,
        ]);

        return response()->json([
            'content' => $content,
        ], 201);
    }

    public function update(Request $request, CompanyProfileContent $content)
    {
        $this->authorizeMenu($request, $content->menu, 'edit');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
        ]);

        $content->update([
            'title' => $validated['title'],
            'body' => $validated['body'] ?? null,
        ]);

        return response()->json([
            'content' => $content,
        ]);
    }

    public function destroy(Request $request, CompanyProfileContent $content)
    {
        $this->authorizeMenu($request, $content->menu, 'delete');
        $content->delete();

        return response()->json([
            'message' => 'Konten dihapus.',
        ]);
    }
}
