<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class NewsPostController extends Controller
{
    private function authorizeMenu(Request $request, string $action): void
    {
        $user = $request->user();
        $user->loadMissing('menuPermissions');
        if (!$user->hasMenuPermission('news', $action)) {
            abort(403, 'Akses ditolak.');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeMenu($request, 'view');

        $posts = NewsPost::query()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->get()
            ->map(function ($post) {
                return $this->transform($post);
            });

        return response()->json(['posts' => $posts]);
    }

    public function store(Request $request)
    {
        $this->authorizeMenu($request, 'create');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:news_posts,slug'],
            'category' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image_path'] = $this->storePublicImage($request->file('cover_image'));
        }

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = Carbon::now();
        }

        $post = NewsPost::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'category' => $validated['category'] ?? null,
            'summary' => $validated['summary'] ?? null,
            'body' => $validated['body'] ?? null,
            'status' => $validated['status'],
            'published_at' => $validated['published_at'] ?? null,
            'cover_image_path' => $validated['cover_image_path'] ?? null,
            'created_by' => $request->user()->id,
        ]);

        return response()->json(['post' => $this->transform($post)], 201);
    }

    public function update(Request $request, NewsPost $post)
    {
        $this->authorizeMenu($request, 'edit');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:news_posts,slug,' . $post->id],
            'category' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'remove_cover_image' => ['nullable', 'boolean'],
        ]);

        $coverImagePath = $post->cover_image_path;
        if ($request->boolean('remove_cover_image')) {
            if ($post->cover_image_path) {
                $this->deletePublicImage($post->cover_image_path);
            }
            $coverImagePath = null;
        }
        if ($request->hasFile('cover_image')) {
            if ($post->cover_image_path) {
                $this->deletePublicImage($post->cover_image_path);
            }
            $coverImagePath = $this->storePublicImage($request->file('cover_image'));
        }

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = Carbon::now();
        }

        $post->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'category' => $validated['category'] ?? null,
            'summary' => $validated['summary'] ?? null,
            'body' => $validated['body'] ?? null,
            'status' => $validated['status'],
            'published_at' => $validated['published_at'] ?? null,
            'cover_image_path' => $coverImagePath,
        ]);

        return response()->json(['post' => $this->transform($post)]);
    }

    public function destroy(Request $request, NewsPost $post)
    {
        $this->authorizeMenu($request, 'delete');

        if ($post->cover_image_path) {
            $this->deletePublicImage($post->cover_image_path);
        }
        $post->delete();

        return response()->json(['message' => 'News dihapus.']);
    }

    private function transform(NewsPost $post): array
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'category' => $post->category,
            'summary' => $post->summary,
            'body' => $post->body,
            'status' => $post->status,
            'published_at' => $post->published_at ? $post->published_at->toDateTimeString() : null,
            'cover_url' => $post->cover_image_path ? url($post->cover_image_path) : null,
            'created_at' => $post->created_at ? $post->created_at->toDateTimeString() : null,
        ];
    }

    private function storePublicImage($file): string
    {
        $dir = public_path('uploads/news');
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        $filename = uniqid('news_', true) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);

        return 'uploads/news/' . $filename;
    }

    private function deletePublicImage(string $path): void
    {
        $full = public_path($path);
        if (File::exists($full)) {
            File::delete($full);
        }
    }
}
