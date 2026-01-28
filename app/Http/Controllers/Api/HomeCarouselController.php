<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HomeCarouselSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HomeCarouselController extends Controller
{
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
        $destPath = $dir . DIRECTORY_SEPARATOR . $filename;
        $ext = strtolower($file->getClientOriginalExtension());
        if (!$this->compressAndSaveImage($file->getRealPath(), $destPath, $ext)) {
            $file->move($dir, $filename);
        }

        return 'uploads/carousels/' . $filename;
    }

    private function deletePublicImage(string $path): void
    {
        $full = public_path($path);
        if (File::exists($full)) {
            File::delete($full);
        }
    }

    private function compressAndSaveImage(string $srcPath, string $destPath, string $ext): bool
    {
        if (!extension_loaded('gd') || !function_exists('getimagesize')) {
            return false;
        }
        $info = @getimagesize($srcPath);
        if (!$info) {
            return false;
        }
        [$width, $height] = $info;
        if (!$width || !$height) {
            return false;
        }

        $source = $this->createImageFromPath($srcPath, $ext, $info['mime'] ?? null);
        if (!$source) {
            return false;
        }

        $dest = imagecreatetruecolor($width, $height);
        if (!$dest) {
            imagedestroy($source);
            return false;
        }

        if ($ext === 'png') {
            imagealphablending($dest, false);
            imagesavealpha($dest, true);
            $transparent = imagecolorallocatealpha($dest, 0, 0, 0, 127);
            imagefilledrectangle($dest, 0, 0, $width, $height, $transparent);
        } elseif ($ext === 'gif') {
            $transparentIndex = imagecolortransparent($source);
            if ($transparentIndex >= 0) {
                $transparentColor = imagecolorsforindex($source, $transparentIndex);
                $transparentIndex = imagecolorallocate(
                    $dest,
                    $transparentColor['red'],
                    $transparentColor['green'],
                    $transparentColor['blue']
                );
                imagefill($dest, 0, 0, $transparentIndex);
                imagecolortransparent($dest, $transparentIndex);
            }
        }

        imagecopy($dest, $source, 0, 0, 0, 0, $width, $height);

        $saved = $this->saveImageToPath($dest, $destPath, $ext);

        imagedestroy($source);
        imagedestroy($dest);

        return $saved;
    }

    private function createImageFromPath(string $path, string $ext, ?string $mime)
    {
        $type = $mime ?: $ext;
        switch ($type) {
            case 'image/jpeg':
            case 'image/jpg':
            case 'jpg':
            case 'jpeg':
                return @imagecreatefromjpeg($path);
            case 'image/png':
            case 'png':
                return @imagecreatefrompng($path);
            case 'image/gif':
            case 'gif':
                return @imagecreatefromgif($path);
            case 'image/webp':
            case 'webp':
                return function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : null;
            default:
                return null;
        }
    }

    private function saveImageToPath($image, string $path, string $ext): bool
    {
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                return @imagejpeg($image, $path, 85);
            case 'png':
                return @imagepng($image, $path, 6);
            case 'gif':
                return @imagegif($image, $path);
            case 'webp':
                return function_exists('imagewebp') ? @imagewebp($image, $path, 80) : false;
            default:
                return false;
        }
    }
}
