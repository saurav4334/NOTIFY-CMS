<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadsController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

// Public marketing site (served from the sibling static frontend folder).
// Relative asset/page links resolve under the /site/ base, so enter via /site/index.html.
Route::get('/', fn () => redirect('/site/index.html'));
Route::get('/site/{path?}', [SiteController::class, 'serve'])->where('path', '.*')->name('site');

// SEO endpoints for crawlers (generated from the DB).
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
