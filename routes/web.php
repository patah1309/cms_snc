<?php

use App\Models\NavigationMenu;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

$getFrontendData = function () {
    $settings = WebsiteSetting::query()->first();
    $menus = NavigationMenu::query()
        ->where('is_visible', true)
        ->orderBy('sort_order')
        ->orderBy('id')
        ->get();

    $grouped = $menus->groupBy('parent_id');
    $currentPath = request()->path();
    $currentPath = $currentPath === '/' ? '/' : '/' . ltrim($currentPath, '/');

    $makeHref = function ($menu) {
        if (!empty($menu->url)) {
            return $menu->url;
        }
        if (!empty($menu->slug)) {
            $slug = ltrim($menu->slug, '/');
            return $slug === '' || $slug === 'home' ? url('/') : url('/' . $slug);
        }
        return '#';
    };

    $buildTree = function ($parentId) use (&$buildTree, $grouped, $makeHref, $currentPath) {
        $items = collect($grouped->get($parentId, []));
        return $items->map(function ($menu) use ($buildTree, $makeHref, $currentPath) {
            $href = $makeHref($menu);
            $hrefPath = parse_url($href, PHP_URL_PATH) ?? '';
            $children = $buildTree($menu->id);
            $childActive = $children->contains(function ($child) {
                return !empty($child['is_active']);
            });
            $isActive = $hrefPath !== '' && $hrefPath === $currentPath;
            if ($childActive) {
                $isActive = true;
            }
            return [
                'title' => $menu->title,
                'href' => $href,
                'is_active' => $isActive,
                'children' => $children->values()->all(),
            ];
        });
    };

    return [
        'settings' => $settings,
        'navMenus' => $buildTree(null)->values()->all(),
    ];
};

Route::get('/', fn () => view('snc_asia.index', $getFrontendData()));
Route::get('/about', fn () => view('snc_asia.about', $getFrontendData()));
Route::get('/service', fn () => view('snc_asia.service', $getFrontendData()));
Route::get('/news', fn () => view('snc_asia.news', $getFrontendData()));
Route::get('/contact', fn () => view('snc_asia.contact', $getFrontendData()));
Route::get('/feature', fn () => view('snc_asia.feature', $getFrontendData()));
Route::get('/project', fn () => view('snc_asia.project', $getFrontendData()));
Route::get('/team', fn () => view('snc_asia.team', $getFrontendData()));
Route::get('/testimonial', fn () => view('snc_asia.testimonial', $getFrontendData()));

Route::view('/admin/{any?}', 'app')->where('any', '.*');
