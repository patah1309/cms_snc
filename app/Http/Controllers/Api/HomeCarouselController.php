<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HomeCarouselSlide;
use App\Support\ImageCompression;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HomeCarouselController extends Controller
{
    use ImageCompression;
    private function authorizeMenu(Request $request, string $action): void
    {
        $user = $request->user();
        $user->loadMissing('menuPermissions');
        if (!$user->hasMenuPermission('home', $action)) {
            abort(403, 'Akses ditolak.');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeMenu($request, 'view');

        $slides = HomeCarouselSlide::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function ($slide) {
                return $this->transform($slide);
            });

        return response()->json(['slides' => $slides]);
    }

    public function store(Request $request)
    {
        $this->authorizeMenu($request, 'create');

        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'button_label' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'buttons' => ['nullable', 'array'],
            'buttons.*.label' => ['nullable', 'string', 'max:100'],
            'buttons.*.url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:10240'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $this->storePublicImage($request->file('image'));
        }

        $buttons = $validated['buttons'] ?? null;
        if (!$buttons && !empty($validated['button_label']) && !empty($validated['button_url'])) {
            $buttons = [[
                'label' => $validated['button_label'],
                'url' => $validated['button_url'],
            ]];
        }

        $slide = HomeCarouselSlide::create([
            'title' => $validated['title'] ?? null,
            'description' => $validated['description'] ?? null,
            'button_label' => $validated['button_label'] ?? null,
            'button_url' => $validated['button_url'] ?? null,
            'buttons' => $buttons,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
            'image_path' => $validated['image_path'] ?? null,
        ]);

        return response()->json([
            'slide' => $this->transform($slide),
        ], 201);
    }

    public function update(Request $request, HomeCarouselSlide $slide)
    {
        $this->authorizeMenu($request, 'edit');

        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'button_label' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'buttons' => ['nullable', 'array'],
            'buttons.*.label' => ['nullable', 'string', 'max:100'],
            'buttons.*.url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:10240'],
            'remove_image' => ['nullable', 'boolean'],
            'remove_buttons' => ['nullable', 'boolean'],
        ]);

        $imagePath = $slide->image_path;
        if ($request->boolean('remove_image')) {
            if ($slide->image_path) {
                $this->deletePublicImage($slide->image_path);
            }
            $imagePath = null;
        }
        if ($request->hasFile('image')) {
            if ($slide->image_path) {
                $this->deletePublicImage($slide->image_path);
            }
            $imagePath = $this->storePublicImage($request->file('image'));
        }

        $buttons = array_key_exists('buttons', $validated) ? $validated['buttons'] : $slide->buttons;
        if ($request->boolean('remove_buttons')) {
            $buttons = [];
        }
        if (array_key_exists('button_label', $validated) || array_key_exists('button_url', $validated)) {
            if (!empty($validated['button_label']) && !empty($validated['button_url'])) {
                $buttons = [[
                    'label' => $validated['button_label'],
                    'url' => $validated['button_url'],
                ]];
            } elseif (array_key_exists('buttons', $validated)) {
                $buttons = $validated['buttons'];
            }
        }

        $slide->fill([
            'title' => $validated['title'] ?? $slide->title,
            'description' => $validated['description'] ?? $slide->description,
            'button_label' => $validated['button_label'] ?? $slide->button_label,
            'button_url' => $validated['button_url'] ?? $slide->button_url,
            'buttons' => $buttons,
            'sort_order' => $validated['sort_order'] ?? $slide->sort_order,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $slide->is_active,
            'image_path' => $imagePath,
        ]);
        $slide->save();

        return response()->json([
            'slide' => $this->transform($slide),
        ]);
    }

    public function destroy(Request $request, HomeCarouselSlide $slide)
    {
        $this->authorizeMenu($request, 'delete');

        if ($slide->image_path) {
            $this->deletePublicImage($slide->image_path);
        }
        $slide->delete();

        return response()->json(['message' => 'Slide dihapus.']);
    }

    private function transform(HomeCarouselSlide $slide): array
    {
        $buttons = $slide->buttons;
        if (!$buttons && $slide->button_label && $slide->button_url) {
            $buttons = [[
                'label' => $slide->button_label,
                'url' => $slide->button_url,
            ]];
        }

        return [
            'id' => $slide->id,
            'title' => $slide->title,
            'description' => $slide->description,
            'button_label' => $slide->button_label,
            'button_url' => $slide->button_url,
            'buttons' => $buttons,
            'sort_order' => $slide->sort_order,
            'is_active' => (bool) $slide->is_active,
            'image_url' => $slide->image_path ? url($slide->image_path) : null,
        ];
    }

    private function storePublicImage($file): string
    {
        $dir = public_path('uploads/carousels');
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        $filename = uniqid('carousel_', true) . '.' . $file->getClientOriginalExtension();
        $this->saveUploadedImage($file, $dir, $filename);

        return 'uploads/carousels/' . $filename;
    }

    private function deletePublicImage(string $path): void
    {
        $full = public_path($path);
        if (File::exists($full)) {
            File::delete($full);
        }
    }

}
