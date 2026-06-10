<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SiteController extends Controller
{
    /** Absolute path to the bundled static frontend folder. */
    private function frontendDir(): string
    {
        return base_path('frontend');
    }

    /**
     * Serve an existing static marketing page (frontend/*.html) at its clean URL.
     * The clean-path → file mapping lives in config/pages.php (entries with a
     * 'file' key). SEO meta is baked into each .html <head>, so this just streams
     * the file with the right content type.
     */
    public function page(string $key = ''): Response|BinaryFileResponse
    {
        $pages = (array) config('pages');

        $file = $pages[$key]['file'] ?? null;
        if (! is_string($file) || $file === '') {
            abort(404);
        }

        $full = $this->frontendDir().DIRECTORY_SEPARATOR.$file;
        if (! is_file($full)) {
            abort(404);
        }

        return response()->file($full, ['Content-Type' => 'text/html; charset=UTF-8']);
    }

    /** Legacy `/site/<path>` → clean URL (strips /site/ and any .html). */
    public function redirectLegacy(string $path): RedirectResponse
    {
        return $this->cleanRedirect($path);
    }

    /** Legacy `/<path>.html` → clean URL (strips the .html extension). */
    public function redirectHtml(string $path): RedirectResponse
    {
        return $this->cleanRedirect($path);
    }

    /** Normalise a legacy path to its clean URL and 301-redirect to it. */
    private function cleanRedirect(string $path): RedirectResponse
    {
        $path = preg_replace('#\.html$#i', '', trim($path, '/'));

        if ($path === '' || $path === 'index') {
            return redirect('/', 301);
        }

        return redirect('/'.$path, 301);
    }
}
