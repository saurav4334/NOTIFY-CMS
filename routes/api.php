<?php

use App\Http\Controllers\Api\PublicController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API (no auth) — consumed by the marketing frontend
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {
    Route::get('/content', [PublicController::class, 'content']);
    Route::get('/sms-rates', [PublicController::class, 'rates']);
    Route::post('/calculate', [PublicController::class, 'calculate']);
    Route::post('/contact', [PublicController::class, 'contact'])
        ->middleware('throttle:10,1'); // basic spam protection

    // TODO: GET /services, /faqs, /clients, /testimonials, /settings, /seo/{page}
});

/*
|--------------------------------------------------------------------------
| Admin API (Sanctum token auth) — consumed by the admin panel
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user()->load('role'));

    // TODO: CMS CRUD resources (services, faqs, pricing, clients, leads, media, settings)
});
