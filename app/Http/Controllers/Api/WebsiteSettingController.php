<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class WebsiteSettingController extends Controller
{
    private function getSettings(): WebsiteSetting
    {
        return WebsiteSetting::query()->firstOrCreate([]);
    }

    private function authorizeMenu(Request $request, string $action): void
    {
        $user = $request->user();
        $user->loadMissing('menuPermissions');
        if (!$user->hasMenuPermission('settings', $action)) {
            abort(403, 'Akses ditolak.');
        }
    }

    public function show(Request $request)
    {
        $this->authorizeMenu($request, 'view');

        $settings = $this->getSettings();

        return response()->json([
            'settings' => $this->transform($settings),
        ]);
    }

    public function update(Request $request)
    {
        $this->authorizeMenu($request, 'edit');

        $validated = $request->validate([
            'company_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp_number' => ['nullable', 'string', 'max:50'],
            'about_us' => ['nullable', 'string'],
            'core_values' => ['nullable', 'string'],
            'approach' => ['nullable', 'string'],
            'operating_hours' => ['nullable', 'string', 'max:255'],
            'business_type' => ['nullable', 'string', 'max:255'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'logo' => ['nullable', 'mimes:png,jpg,jpeg,svg', 'max:4096'],
            'header_home' => ['nullable', 'mimes:png,jpg,jpeg,svg', 'max:4096'],
            'header_about' => ['nullable', 'mimes:png,jpg,jpeg,svg', 'max:4096'],
            'header_services' => ['nullable', 'mimes:png,jpg,jpeg,svg', 'max:4096'],
            'header_news' => ['nullable', 'mimes:png,jpg,jpeg,svg', 'max:4096'],
            'header_kontak' => ['nullable', 'mimes:png,jpg,jpeg,svg', 'max:4096'],
            'header_seo' => ['nullable', 'mimes:png,jpg,jpeg,svg', 'max:4096'],
            'seo_og_image' => ['nullable', 'mimes:png,jpg,jpeg,svg', 'max:4096'],
            'remove_header_home' => ['nullable', 'boolean'],
            'remove_header_about' => ['nullable', 'boolean'],
            'remove_header_services' => ['nullable', 'boolean'],
            'remove_header_news' => ['nullable', 'boolean'],
            'remove_header_kontak' => ['nullable', 'boolean'],
            'remove_header_seo' => ['nullable', 'boolean'],
            'remove_seo_og_image' => ['nullable', 'boolean'],
        ]);

        $settings = $this->getSettings();

        if ($request->hasFile('logo')) {
            if ($settings->logo_path) {
                $this->deletePublicLogo($settings->logo_path);
            }
            $validated['logo_path'] = $this->storePublicLogo($request->file('logo'));
        }

        $headerMap = [
            'header_home' => 'header_home_path',
            'header_about' => 'header_about_path',
            'header_services' => 'header_services_path',
            'header_news' => 'header_news_path',
            'header_kontak' => 'header_kontak_path',
            'header_seo' => 'header_seo_path',
        ];

        $removeMap = [
            'remove_header_home' => 'header_home_path',
            'remove_header_about' => 'header_about_path',
            'remove_header_services' => 'header_services_path',
            'remove_header_news' => 'header_news_path',
            'remove_header_kontak' => 'header_kontak_path',
            'remove_header_seo' => 'header_seo_path',
        ];

        foreach ($removeMap as $input => $column) {
            if ($request->boolean($input)) {
                if ($settings->{$column}) {
                    $this->deletePublicHeader($settings->{$column});
                }
                $validated[$column] = null;
            }
        }

        foreach ($headerMap as $input => $column) {
            if ($request->hasFile($input)) {
                if ($settings->{$column}) {
                    $this->deletePublicHeader($settings->{$column});
                }
                $validated[$column] = $this->storePublicHeader($request->file($input), $input);
            }
        }

        if ($request->boolean('remove_seo_og_image')) {
            if ($settings->seo_og_image_path) {
                $this->deletePublicSeoImage($settings->seo_og_image_path);
            }
            $validated['seo_og_image_path'] = null;
        }

        if ($request->hasFile('seo_og_image')) {
            if ($settings->seo_og_image_path) {
                $this->deletePublicSeoImage($settings->seo_og_image_path);
            }
            $validated['seo_og_image_path'] = $this->storePublicSeoImage($request->file('seo_og_image'));
        }

        unset($validated['logo']);
        $settings->fill($validated);
        $settings->save();

        return response()->json([
            'settings' => $this->transform($settings),
        ]);
    }

    private function transform(WebsiteSetting $settings): array
    {
        return [
            'company_name' => $settings->company_name,
            'address' => $settings->address,
            'email' => $settings->email,
            'phone' => $settings->phone,
            'whatsapp_number' => $settings->whatsapp_number,
            'about_us' => $settings->about_us,
            'core_values' => $settings->core_values,
            'approach' => $settings->approach,
            'operating_hours' => $settings->operating_hours,
            'business_type' => $settings->business_type,
            'seo_title' => $settings->seo_title,
            'seo_description' => $settings->seo_description,
            'seo_og_image_url' => $settings->seo_og_image_path ? url($settings->seo_og_image_path) : null,
            'logo_url' => $settings->logo_path ? url($settings->logo_path) : null,
            'header_home_url' => $settings->header_home_path ? url($settings->header_home_path) : null,
            'header_about_url' => $settings->header_about_path ? url($settings->header_about_path) : null,
            'header_services_url' => $settings->header_services_path ? url($settings->header_services_path) : null,
            'header_news_url' => $settings->header_news_path ? url($settings->header_news_path) : null,
            'header_kontak_url' => $settings->header_kontak_path ? url($settings->header_kontak_path) : null,
            'header_seo_url' => $settings->header_seo_path ? url($settings->header_seo_path) : null,
        ];
    }

    private function storePublicLogo($file): string
    {
        $dir = public_path('uploads/logos');
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        $filename = uniqid('logo_', true) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);

        return 'uploads/logos/' . $filename;
    }

    private function deletePublicLogo(string $path): void
    {
        $full = public_path($path);
        if (File::exists($full)) {
            File::delete($full);
        }
    }

    private function storePublicHeader($file, string $key): string
    {
        $dir = public_path('uploads/headers');
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        $filename = uniqid($key . '_', true) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);

        return 'uploads/headers/' . $filename;
    }

    private function deletePublicHeader(string $path): void
    {
        $full = public_path($path);
        if (File::exists($full)) {
            File::delete($full);
        }
    }

    private function storePublicSeoImage($file): string
    {
        $dir = public_path('uploads/seo');
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        $filename = uniqid('seo_og_', true) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);

        return 'uploads/seo/' . $filename;
    }

    private function deletePublicSeoImage(string $path): void
    {
        $full = public_path($path);
        if (File::exists($full)) {
            File::delete($full);
        }
    }
}
