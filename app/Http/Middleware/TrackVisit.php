<?php

namespace App\Http\Middleware;

use App\Models\PageVisit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TrackVisit
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (!$request->isMethod('get')) {
            return $response;
        }

        if ($request->expectsJson() || $request->is('api/*') || $request->is('admin/*')) {
            return $response;
        }

        if ($this->isAssetRequest($request->path())) {
            return $response;
        }

        $dateKey = Carbon::now()->toDateString();
        $sessionKey = 'visit_logged_' . $dateKey;
        if ($request->session()->has($sessionKey)) {
            return $response;
        }

        $request->session()->put($sessionKey, true);

        PageVisit::create([
            'visited_on' => $dateKey,
            'path' => '/' . ltrim($request->path(), '/'),
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
            'session_id' => $request->session()->getId(),
        ]);

        return $response;
    }

    private function isAssetRequest(string $path): bool
    {
        if ($path === '' || $path === '/') {
            return false;
        }
        $path = trim($path, '/');
        $assetPrefixes = [
            'css/', 'js/', 'img/', 'lib/', 'fonts/', 'storage/', 'uploads/', 'build/',
        ];
        foreach ($assetPrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return true;
            }
        }

        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if ($ext) {
            return in_array(strtolower($ext), ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'webp', 'ico', 'woff', 'woff2', 'ttf', 'map'], true);
        }

        return false;
    }
}
