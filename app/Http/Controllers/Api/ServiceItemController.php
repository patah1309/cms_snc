<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ServiceItemController extends Controller
{
    private function authorizeMenu(Request $request, string $action): void
    {
        $user = $request->user();
        $user->loadMissing('menuPermissions');
        if (!$user->hasMenuPermission('services', $action)) {
            abort(403, 'Akses ditolak.');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeMenu($request, 'view');

        $items = ServiceItem::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function ($item) {
                return $this->transform($item);
            });

        return response()->json(['items' => $items]);
    }

    public function store(Request $request)
    {
        $this->authorizeMenu($request, 'create');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image_path'] = $this->storePublicImage($request->file('cover_image'));
        }

        $item = ServiceItem::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
            'cover_image_path' => $validated['cover_image_path'] ?? null,
        ]);

        return response()->json(['item' => $this->transform($item)], 201);
    }

    public function update(Request $request, ServiceItem $item)
    {
        $this->authorizeMenu($request, 'edit');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('cover_image')) {
            if ($item->cover_image_path) {
                $this->deletePublicImage($item->cover_image_path);
            }
            $validated['cover_image_path'] = $this->storePublicImage($request->file('cover_image'));
        }

        $item->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? $item->sort_order,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $item->is_active,
            'cover_image_path' => $validated['cover_image_path'] ?? $item->cover_image_path,
        ]);

        return response()->json(['item' => $this->transform($item)]);
    }

    public function destroy(Request $request, ServiceItem $item)
    {
        $this->authorizeMenu($request, 'delete');

        if ($item->cover_image_path) {
            $this->deletePublicImage($item->cover_image_path);
        }
        $item->delete();

        return response()->json(['message' => 'Service dihapus.']);
    }

    private function transform(ServiceItem $item): array
    {
        return [
            'id' => $item->id,
            'title' => $item->title,
            'description' => $item->description,
            'sort_order' => $item->sort_order,
            'is_active' => (bool) $item->is_active,
            'cover_url' => $item->cover_image_path ? url($item->cover_image_path) : null,
            'created_at' => $item->created_at ? $item->created_at->toDateTimeString() : null,
        ];
    }

    private function storePublicImage($file): string
    {
        $dir = public_path('uploads/services');
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        $filename = uniqid('service_', true) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);

        return 'uploads/services/' . $filename;
    }

    private function deletePublicImage(string $path): void
    {
        $full = public_path($path);
        if (File::exists($full)) {
            File::delete($full);
        }
    }
}
