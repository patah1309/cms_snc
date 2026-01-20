<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfileSection;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $user->loadMissing('menuPermissions');
        $allowed = $user->menuPermissions
            ->where('can_view', true)
            ->pluck('menu')
            ->all();

        $sections = CompanyProfileSection::query()
            ->whereIn('slug', $allowed)
            ->orderBy('id')
            ->get(['slug', 'title', 'is_visible']);

        return response()->json([
            'sections' => $sections,
        ]);
    }

    public function toggleVisibility(Request $request, string $slug)
    {
        $user = $request->user();
        $user->loadMissing('menuPermissions');
        if (!$user->hasMenuPermission($slug, 'edit')) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $section = CompanyProfileSection::where('slug', $slug)->firstOrFail();
        $section->is_visible = !$section->is_visible;
        $section->save();

        return response()->json([
            'slug' => $section->slug,
            'is_visible' => (bool) $section->is_visible,
        ]);
    }
}
