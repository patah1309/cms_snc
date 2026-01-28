<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuPage;
use App\Models\NavigationMenu;
use App\Support\ImageCompression;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class NavigationMenuController extends Controller
{
    use ImageCompression;
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
            ->with('page')
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
            'header_title' => ['nullable', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer', 'exists:navigation_menus,id'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_visible' => ['nullable', 'boolean'],
            'page_title' => ['nullable', 'string', 'max:255'],
            'page_body' => ['nullable', 'string'],
            'page_image' => ['nullable', 'image', 'max:10240'],
            'remove_page_image' => ['nullable', 'boolean'],
        ]);

        $menu = NavigationMenu::create([
            'title' => $validated['title'],
            'slug' => $this->nullIfEmpty($validated['slug'] ?? null),
            'url' => $this->nullIfEmpty($validated['url'] ?? null),
            'header_title' => $this->nullIfEmpty($validated['header_title'] ?? null),
            'parent_id' => $validated['parent_id'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_visible' => $request->boolean('is_visible', true),
        ]);

        $this->syncPage($request, $menu, $validated);
        $this->removeParentPage($menu->parent_id);

        return response()->json(['menu' => $menu->load('page')], 201);
    }

    public function update(Request $request, NavigationMenu $menu)
    {
        $this->authorizeMenu($request, 'edit');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'header_title' => ['nullable', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer', 'exists:navigation_menus,id'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_visible' => ['nullable', 'boolean'],
            'page_title' => ['nullable', 'string', 'max:255'],
            'page_body' => ['nullable', 'string'],
            'page_image' => ['nullable', 'image', 'max:10240'],
            'remove_page_image' => ['nullable', 'boolean'],
        ]);

        $menu->update([
            'title' => $validated['title'],
            'slug' => $this->nullIfEmpty($validated['slug'] ?? null),
            'url' => $this->nullIfEmpty($validated['url'] ?? null),
            'header_title' => $this->nullIfEmpty($validated['header_title'] ?? null),
            'parent_id' => $validated['parent_id'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_visible' => $request->boolean('is_visible', $menu->is_visible),
        ]);

        $this->syncPage($request, $menu, $validated);
        $this->removeParentPage($menu->parent_id);

        return response()->json(['menu' => $menu->load('page')]);
    }

    public function destroy(Request $request, NavigationMenu $menu)
    {
        $this->authorizeMenu($request, 'delete');

        if ($menu->page && $menu->page->image_path) {
            $this->deletePublicImage($menu->page->image_path);
        }
        if ($menu->header_image_path) {
            $this->deletePublicHeader($menu->header_image_path);
        }
        $menu->delete();

        return response()->json(['message' => 'Menu dihapus.']);
    }

    public function updateHeader(Request $request, NavigationMenu $menu)
    {
        $this->authorizeMenu($request, 'edit');

        $validated = $request->validate([
            'header_image' => ['nullable', 'image', 'max:10240'],
            'remove_header' => ['nullable', 'boolean'],
        ]);

        if ($request->boolean('remove_header')) {
            if ($menu->header_image_path) {
                $this->deletePublicHeader($menu->header_image_path);
            }
            $menu->header_image_path = null;
        }

        if ($request->hasFile('header_image')) {
            if ($menu->header_image_path) {
                $this->deletePublicHeader($menu->header_image_path);
            }
            $menu->header_image_path = $this->storePublicHeader($request->file('header_image'), $menu->id);
        }

        $menu->save();

        return response()->json([
            'menu' => $menu->load('page'),
        ]);
    }

    private function syncPage(Request $request, NavigationMenu $menu, array $validated): void
    {
        $hasChildren = NavigationMenu::query()
            ->where('parent_id', $menu->id)
            ->exists();

        if ($hasChildren) {
            if ($menu->page?->image_path) {
                $this->deletePublicImage($menu->page->image_path);
            }
            $menu->page()?->delete();
            return;
        }

        $data = [
            'title' => $this->nullIfEmpty($validated['page_title'] ?? null),
            'body' => $this->nullIfEmpty($validated['page_body'] ?? null),
        ];

        if ($request->boolean('remove_page_image')) {
            if ($menu->page?->image_path) {
                $this->deletePublicImage($menu->page->image_path);
            }
            $data['image_path'] = null;
        }

        if ($request->hasFile('page_image')) {
            if ($menu->page?->image_path) {
                $this->deletePublicImage($menu->page->image_path);
            }
            $data['image_path'] = $this->storePublicImage($request->file('page_image'));
        }

        MenuPage::updateOrCreate(
            ['menu_id' => $menu->id],
            $data
        );
    }

    private function removeParentPage(?int $parentId): void
    {
        if (!$parentId) {
            return;
        }

        $parent = NavigationMenu::query()->with('page')->find($parentId);
        if (!$parent || !$parent->page) {
            return;
        }

        if ($parent->page->image_path) {
            $this->deletePublicImage($parent->page->image_path);
        }
        $parent->page()->delete();
    }

    private function nullIfEmpty(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $trimmed = trim($value);
        return $trimmed === '' ? null : $trimmed;
    }

    private function storePublicImage($file): string
    {
        $dir = public_path('uploads/pages');
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        $filename = uniqid('page_', true) . '.' . $file->getClientOriginalExtension();
        $this->saveUploadedImage($file, $dir, $filename);

        return 'uploads/pages/' . $filename;
    }

    private function deletePublicImage(string $path): void
    {
        $full = public_path($path);
        if (File::exists($full)) {
            File::delete($full);
        }
    }

    private function storePublicHeader($file, int $menuId): string
    {
        $dir = public_path('uploads/menu-headers');
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        $filename = uniqid('menu_' . $menuId . '_', true) . '.' . $file->getClientOriginalExtension();
        $this->saveUploadedImage($file, $dir, $filename);

        return 'uploads/menu-headers/' . $filename;
    }

    private function deletePublicHeader(string $path): void
    {
        $full = public_path($path);
        if (File::exists($full)) {
            File::delete($full);
        }
    }
}
