<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SiteController extends Controller
{
    /** Whitelisted extensions and their content types. */
    private const MIME = [
        'html' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'json' => 'application/json',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
    ];

    /** Absolute path to the bundled static frontend folder. */
    private function frontendDir(): string
    {
        return base_path('frontend');
    }

    public function serve(string $path = 'index.html'): Response|BinaryFileResponse
    {
        $path = $path === '' ? 'index.html' : $path;

        // Reject path traversal.
        if (str_contains($path, '..') || str_starts_with($path, '/')) {
            abort(404);
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (! array_key_exists($ext, self::MIME)) {
            abort(404);
        }

        $full = $this->frontendDir().DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $path);
        if (! is_file($full)) {
            abort(404);
        }

        return response()->file($full, ['Content-Type' => self::MIME[$ext]]);
    }
}
