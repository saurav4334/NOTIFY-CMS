<?php

namespace App\Http\Controllers;

use App\Models\SeoMeta;
use App\Models\Setting;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /**
     * The public marketing pages (static .html files). The site base URL is
     * taken from settings(general.siteUrl) or APP_URL, so it works in any env.
     */
    private function pages(): array
    {
        return [
            'index.html' => '1.0',
            'about.html' => '0.7',
            'services.html' => '0.8',
            'pricing.html' => '0.9',
            'calculator.html' => '0.8',
            'faq.html' => '0.6',
            'contact.html' => '0.7',
        ];
    }

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
        foreach ($this->pages() as $path => $priority) {
            $loc = $base.'/'.$path;
            $xml .= "  <url>\n";
            $xml .= '    <loc>'.htmlspecialchars($loc, ENT_XML1).'</loc>'."\n";
            $xml .= '    <lastmod>'.$lastmod.'</lastmod>'."\n";
            $xml .= '    <changefreq>weekly</changefreq>'."\n";
            $xml .= '    <priority>'.$priority.'</priority>'."\n";
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
