<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    /**
     * Render a Blade marketing page (entries in config/pages.php with a 'view').
     * Passes the registry meta to the shared layout for SEO/canonical/JSON-LD.
     */
    public function show(string $key): View
    {
        $pages = (array) config('pages');
        $view = $pages[$key]['view'] ?? null;

        if (! is_string($view) || ! view()->exists($view)) {
            abort(404);
        }

        return view($view, [
            'pageKey' => $key,
            'meta' => $pages[$key],
        ]);
    }
}
