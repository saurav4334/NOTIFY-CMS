<?php

namespace App\Http\Controllers;

use App\Models\SeoMeta;
use App\Models\Setting;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /**
     * Site base URL — taken from settings(general.siteUrl) or APP_URL, so it
     * works in any environment.
     */
    private function baseUrl(): string
    {
        $url = Setting::value('general', 'siteUrl') ?: config('app.url');
        return rtrim((string) $url, '/');
    }

    public function sitemap(): Response
    {
        $base = $this->baseUrl();
        $lastmod = SeoMeta::max('updated_at');
        $lastmod = $lastmod ? date('Y-m-d', strtotime($lastmod)) : date('Y-m-d');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ((array) config('pages') as $path => $meta) {
            $loc = $base.'/'.ltrim((string) $path, '/');
            $xml .= "  <url>\n";
            $xml .= '    <loc>'.htmlspecialchars(rtrim($loc, '/').($path === '' ? '/' : ''), ENT_XML1).'</loc>'."\n";
            $xml .= '    <lastmod>'.$lastmod.'</lastmod>'."\n";
            $xml .= '    <changefreq>'.($meta['changefreq'] ?? 'weekly').'</changefreq>'."\n";
            $xml .= '    <priority>'.($meta['priority'] ?? '0.5').'</priority>'."\n";
            $xml .= "  </url>\n";
        }
        $xml .= '</urlset>'."\n";

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    public function robots(): Response
    {
        $base = $this->baseUrl();
        $body = "User-agent: *\n";
        $body .= "Allow: /\n";
        $body .= "Disallow: /admin\n";
        $body .= "Disallow: /api\n\n";
        $body .= 'Sitemap: '.$base."/sitemap.xml\n";

        return response($body, 200, ['Content-Type' => 'text/plain']);
    }
}
