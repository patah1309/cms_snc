<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TeamMemberController extends Controller
{
    private function authorizeMenu(Request $request, string $action): void
    {
        $user = $request->user();
        $user->loadMissing('menuPermissions');
        if (!$user->hasMenuPermission('team', $action)) {
            abort(403, 'Akses ditolak.');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeMenu($request, 'view');

        $members = TeamMember::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function ($member) {
                return $this->transform($member);
            });

        return response()->json(['members' => $members]);
    }

    public function store(Request $request)
    {
        $this->authorizeMenu($request, 'create');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'facebook_url' => ['nullable', 'string', 'max:255'],
            'twitter_url' => ['nullable', 'string', 'max:255'],
            'instagram_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $this->storePublicImage($request->file('photo'));
        }

        $member = TeamMember::create([
            'name' => $validated['name'],
            'position' => $validated['position'] ?? null,
            'photo_path' => $validated['photo_path'] ?? null,
            'facebook_url' => $validated['facebook_url'] ?? null,
            'twitter_url' => $validated['twitter_url'] ?? null,
            'instagram_url' => $validated['instagram_url'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json(['member' => $this->transform($member)], 201);
    }

    public function update(Request $request, TeamMember $member)
    {
        $this->authorizeMenu($request, 'edit');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'facebook_url' => ['nullable', 'string', 'max:255'],
            'twitter_url' => ['nullable', 'string', 'max:255'],
            'instagram_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('photo')) {
            if ($member->photo_path) {
                $this->deletePublicImage($member->photo_path);
            }
            $validated['photo_path'] = $this->storePublicImage($request->file('photo'));
        }

        $member->update([
            'name' => $validated['name'],
            'position' => $validated['position'] ?? null,
            'photo_path' => $validated['photo_path'] ?? $member->photo_path,
            'facebook_url' => $validated['facebook_url'] ?? null,
            'twitter_url' => $validated['twitter_url'] ?? null,
            'instagram_url' => $validated['instagram_url'] ?? null,
            'sort_order' => $validated['sort_order'] ?? $member->sort_order,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $member->is_active,
        ]);

        return response()->json(['member' => $this->transform($member)]);
    }

    public function destroy(Request $request, TeamMember $member)
    {
        $this->authorizeMenu($request, 'delete');

        if ($member->photo_path) {
            $this->deletePublicImage($member->photo_path);
        }
        $member->delete();

        return response()->json(['message' => 'Team member dihapus.']);
    }

    private function transform(TeamMember $member): array
    {
        return [
            'id' => $member->id,
            'name' => $member->name,
            'position' => $member->position,
            'facebook_url' => $member->facebook_url,
            'twitter_url' => $member->twitter_url,
            'instagram_url' => $member->instagram_url,
            'sort_order' => $member->sort_order,
            'is_active' => (bool) $member->is_active,
            'photo_url' => $member->photo_path ? url($member->photo_path) : null,
            'created_at' => $member->created_at ? $member->created_at->toDateTimeString() : null,
        ];
    }

    private function storePublicImage($file): string
    {
        $dir = public_path('uploads/team');
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        $filename = uniqid('team_', true) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);

        return 'uploads/team/' . $filename;
    }

    private function deletePublicImage(string $path): void
    {
        $full = public_path($path);
        if (File::exists($full)) {
            File::delete($full);
        }
    }
}
