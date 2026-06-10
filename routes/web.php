<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadsController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public marketing site — clean SEO-friendly URLs
|--------------------------------------------------------------------------
| All public pages are registered from the central registry in
| config/pages.php. Entries with a 'file' key are existing static pages
| (frontend/*.html) served by SiteController; entries with a 'view' key are
| Blade landing pages rendered by PageController. The clean path is the
| array key ('' = "/").
*/
foreach ((array) config('pages') as $path => $meta) {
    $uri = $path === '' ? '/' : $path;
    $name = 'page.'.($path === '' ? 'home' : str_replace('/', '.', $path));

    if (isset($meta['file'])) {
        Route::get($uri, [SiteController::class, 'page'])->defaults('key', $path)->name($name);
    } else {
        Route::get($uri, [PageController::class, 'show'])->defaults('key', $path)->name($name);
    }
}

/*
|--------------------------------------------------------------------------
| Legacy URL 301 redirects (old /site/*.html and *.html structure)
|--------------------------------------------------------------------------
| Mirrored by public/.htaccess for production speed; these keep the same
| behaviour under `artisan serve` and any non-Apache host.
*/
Route::redirect('/site', '/', 301);
Route::redirect('/index.html', '/', 301);

// /site/<anything>  → strip the /site/ prefix and any .html (index → /)
Route::get('site/{path}', [SiteController::class, 'redirectLegacy'])
    ->where('path', '.*');

// /<anything>.html  → strip the .html extension (index.html → /)
Route::get('{path}.html', [SiteController::class, 'redirectHtml'])
    ->where('path', '.*');

/*
|--------------------------------------------------------------------------
| SEO endpoints for crawlers (generated from the registry / DB)
|--------------------------------------------------------------------------
*/
Route::get('sitemap.xml', [SeoController::class, 'sitemap']);
Route::get('robots.txt', [SeoController::class, 'robots']);

/*
|--------------------------------------------------------------------------
| Admin panel (session auth)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')->name('login.attempt');

    // Authenticated
    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        // Everyone authenticated can change their own password.
        Route::post('password', [CmsController::class, 'changePassword'])->name('password');

        // Site content — requires the "cms" capability.
        Route::middleware('permission:cms')->group(function () {
            Route::get('cms', [CmsController::class, 'show'])->name('cms.show');
            Route::post('cms', [CmsController::class, 'update'])->name('cms.update');
        });

        // Contact leads (inbox) — requires the "leads" capability.
        Route::middleware('permission:leads')->group(function () {
            Route::get('leads/export', [LeadsController::class, 'export'])->name('leads.export');
            Route::get('leads', [LeadsController::class, 'index'])->name('leads.index');
            Route::patch('leads/{lead}', [LeadsController::class, 'update'])->name('leads.update');
            Route::delete('leads/{lead}', [LeadsController::class, 'destroy'])->name('leads.destroy');
        });

        // Media library — requires the "media" capability.
        Route::middleware('permission:media')->group(function () {
            Route::get('media', [MediaController::class, 'index'])->name('media.index');
            Route::post('media', [MediaController::class, 'store'])->name('media.store');
            Route::delete('media/{medium}', [MediaController::class, 'destroy'])->name('media.destroy');
        });
    });
});
