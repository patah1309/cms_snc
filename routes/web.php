<?php

use App\Http\Controllers\ContactFormController;
use App\Models\NavigationMenu;
use App\Models\NewsPost;
use App\Models\TeamMember;
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

    $allMenus = NavigationMenu::query()
        ->orderBy('sort_order')
        ->orderBy('id')
        ->get();

    $menuById = $menus->keyBy('id');
    $grouped = $menus->groupBy('parent_id');
    $currentPath = request()->path();
    $currentPath = $currentPath === '/' ? '/' : '/' . ltrim($currentPath, '/');

    $makeHref = function ($menu) use ($menuById) {
        if (!empty($menu->url)) {
            return $menu->url;
        }
        if (!empty($menu->slug)) {
            $parts = [];
            $current = $menu;
            while ($current) {
                if (!empty($current->slug)) {
                    $parts[] = trim($current->slug, '/');
                }
                $current = $current->parent_id ? ($menuById[$current->parent_id] ?? null) : null;
            }
            $parts = array_reverse($parts);
            if (count($parts) === 1 && $parts[0] === 'home') {
                return url('/');
            }
            $path = implode('/', array_filter($parts));
            return $path === '' ? url('/') : url('/' . $path);
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
        'menuHeaderPaths' => $allMenus->mapWithKeys(function ($menu) {
            return [$menu->slug => $menu->header_image_path];
        }),
        'menuHeaderTitles' => $allMenus->mapWithKeys(function ($menu) {
            return [$menu->slug => $menu->header_title];
        }),
        'footerServices' => \App\Models\ServiceItem::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get(['title']),
    ];
};

Route::get('/', function () use ($getFrontendData) {
    $data = $getFrontendData();
    $data['homeSlides'] = \App\Models\HomeCarouselSlide::query()
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->orderBy('id')
        ->get();
    return view('konten.index', $data);
});
Route::redirect('/home', '/');
Route::get('/about', function () use ($getFrontendData) {
    $data = $getFrontendData();
    $data['teamMembers'] = TeamMember::query()
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->orderBy('id')
        ->get();
    return view('konten.about', $data);
});
Route::get('/services', function () use ($getFrontendData) {
    $data = $getFrontendData();
    $data['serviceItems'] = \App\Models\ServiceItem::query()
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->orderBy('id')
        ->get();
    return view('konten.service', $data);
});
Route::redirect('/service', '/services');
Route::get('/news', function () use ($getFrontendData) {
    $data = $getFrontendData();
    $category = request()->query('category');
    $query = NewsPost::query()
        ->where('status', 'published')
        ->when($category, function ($builder) use ($category) {
            $builder->where('category', $category);
        })
        ->orderByDesc('published_at')
        ->orderByDesc('id');
    $data['newsPosts'] = $query->paginate(6)->withQueryString();
    $data['featuredPost'] = request()->query('page', 1) == 1 ? $data['newsPosts']->first() : null;
    $data['recentPosts'] = NewsPost::query()
        ->where('status', 'published')
        ->orderByDesc('published_at')
        ->orderByDesc('id')
        ->limit(3)
        ->get();
    $data['categories'] = NewsPost::query()
        ->select('category')
        ->whereNotNull('category')
        ->where('category', '!=', '')
        ->distinct()
        ->orderBy('category')
        ->pluck('category');
    $data['activeCategory'] = $category;
    return view('konten.news', $data);
});
Route::get('/news/{slug}', function (string $slug) use ($getFrontendData) {
    $data = $getFrontendData();
    $post = NewsPost::query()
        ->where('slug', $slug)
        ->where('status', 'published')
        ->firstOrFail();
    $data['post'] = $post;
    $data['recentPosts'] = NewsPost::query()
        ->where('status', 'published')
        ->orderByDesc('published_at')
        ->orderByDesc('id')
        ->limit(3)
        ->get();
    return view('konten.news-detail', $data);
});
Route::get('/contact', fn () => view('konten.contact', $getFrontendData()));
Route::post('/contact', [ContactFormController::class, 'store'])->name('contact.submit');
Route::get('/feature', fn () => view('konten.feature', $getFrontendData()));
Route::get('/project', fn () => view('konten.project', $getFrontendData()));
Route::get('/team', function () use ($getFrontendData) {
    $data = $getFrontendData();
    $data['teamMembers'] = TeamMember::query()
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->orderBy('id')
        ->get();
    return view('konten.team', $data);
});
Route::get('/testimonial', fn () => view('konten.testimonial', $getFrontendData()));

Route::view('/admin/{any?}', 'app')->where('any', '.*');

Route::get('/{parent}/{child}', function (string $parent, string $child) use ($getFrontendData) {
    $parentMenu = NavigationMenu::query()
        ->where('slug', $parent)
        ->firstOrFail();

    $menu = NavigationMenu::query()
        ->where('slug', $child)
        ->where('parent_id', $parentMenu->id)
        ->firstOrFail();

    if (!empty($menu->url) && $menu->url !== '/' . $parent . '/' . $child) {
        abort(404);
    }

    $hasChildren = NavigationMenu::query()
        ->where('parent_id', $menu->id)
        ->exists();

    if ($hasChildren) {
        abort(404);
    }

    $page = $menu->page;
    if (!$page) {
        abort(404);
    }

    $data = $getFrontendData();
    $data['page'] = $page;
    $data['menu'] = $menu;

    return view('konten.menu-page', $data);
})->where(['parent' => '^(?!admin$).+', 'child' => '.+']);

Route::get('/{slug}', function (string $slug) use ($getFrontendData) {
    $menu = NavigationMenu::query()
        ->where('slug', $slug)
        ->firstOrFail();

    if (!empty($menu->url) && $menu->url !== '/' . $slug) {
        abort(404);
    }

    if (!empty($menu->parent_id)) {
        abort(404);
    }

    $hasChildren = NavigationMenu::query()
        ->where('parent_id', $menu->id)
        ->exists();

    if ($hasChildren) {
        abort(404);
    }

    $page = $menu->page;
    if (!$page) {
        abort(404);
    }

    $data = $getFrontendData();
    $data['page'] = $page;
    $data['menu'] = $menu;

    return view('konten.menu-page', $data);
})->where('slug', '^(?!admin$).+');
