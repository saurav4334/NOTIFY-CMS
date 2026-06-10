<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Faq;
use App\Models\HomepageSection;
use App\Models\SeoMeta;
use App\Models\Service;
use App\Models\Setting;
use App\Models\SmsRate;
use App\Models\Testimonial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Bridges the database tables and the legacy `cms.js` data structure
 * (the shape the admin panel and the static frontend understand).
 */
class CmsRepository
{
    /** SEO pages exposed in the admin panel, in display order. */
    private const SEO_PAGES = ['home', 'about', 'services', 'pricing', 'calculator', 'faq', 'contact'];

    /**
     * Assemble the full CMS structure from the database,
     * matching the original CMS_DEFAULTS object.
     */
    public function assemble(): array
    {
        $general = Setting::group('general');
        $contact = Setting::group('contact');
        $social = Setting::group('social');

        return [
            'settings' => [
                'siteName' => $general['siteName'] ?? '',
                'tagline' => $general['tagline'] ?? '',
                'phone' => $contact['phone'] ?? '',
                'email' => $contact['email'] ?? '',
                'whatsapp' => $contact['whatsapp'] ?? '',
                'address' => $contact['address'] ?? '',
                'facebook' => $social['facebook'] ?? '#',
                'linkedin' => $social['linkedin'] ?? '#',
                'youtube' => $social['youtube'] ?? '#',
                'copyright' => $general['copyright'] ?? '',
                'btrc' => $general['btrc'] ?? '',
                'loginUrl' => $general['loginUrl'] ?? '#',
                'registerUrl' => $general['registerUrl'] ?? '#',
            ],
            'hero' => HomepageSection::content('hero', []),
            'whyUs' => array_values(HomepageSection::content('why_us', [])),
            'about' => HomepageSection::content('about', []),
            'clients' => Client::active()->get()->map(fn ($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'icon' => $c->icon,
                'color' => $c->color,
                'bg' => $c->bg,
            ])->all(),
            'services' => Service::active()->get()->map(fn ($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'slug' => $s->slug,
                'icon' => $s->icon,
                'description' => $s->description,
                'features' => $s->features ?? [],
                'cta_text' => $s->cta_text,
                'cta_link' => $s->cta_link,
            ])->all(),
            'pricingNM' => $this->ratesArray('non_masking'),
            'pricingM' => $this->ratesArray('masking'),
            'faq' => Faq::active()->get()->map(fn ($f) => [
                'id' => $f->id,
                'q' => $f->question,
                'a' => $f->answer,
                'category' => $f->category,
            ])->all(),
            'testimonials' => Testimonial::active()->get()->map(fn ($t) => [
                'id' => $t->id,
                'name' => $t->name,
                'role' => $t->role,
                'text' => $t->text,
                'rating' => $t->rating,
                'initials' => $t->initials,
                'bg' => $t->bg,
                'color' => $t->color,
                'featured' => $t->is_featured,
            ])->all(),
            'seo' => $this->seoMap(),
            'contactInfo' => [
                'phone' => $contact['phone'] ?? '',
                'email' => $contact['email'] ?? '',
                'whatsapp' => $contact['whatsapp'] ?? '',
                'address' => $contact['address'] ?? '',
                'officeHours' => $contact['officeHours'] ?? '',
                'responseTime' => $contact['responseTime'] ?? '',
            ],
        ];
    }

    private function ratesArray(string $type): array
    {
        return SmsRate::active()->ofType($type)->get()->map(fn ($r) => [
            'id' => $r->id,
            'tier' => $r->tier,
            'min' => $r->min_qty,
            'max' => $r->max_qty,
            'price' => (float) $r->price,
        ])->all();
    }

    private function seoMap(): array
    {
        $rows = SeoMeta::all()->keyBy('page');
        $out = [];
        foreach (self::SEO_PAGES as $page) {
            $row = $rows->get($page);
            $out[$page] = [
                'title' => $row->title ?? '',
                'desc' => $row->description ?? '',
                'keywords' => $row->keywords ?? '',
            ];
        }
        return $out;
    }

    /**
     * Persist a full CMS structure (sent by the admin panel) back to the DB.
     * Collections are replaced wholesale to mirror the array semantics the UI uses.
     */
    public function persist(array $data): void
    {
        DB::transaction(function () use ($data) {
            $this->persistSettings($data['settings'] ?? [], $data['contactInfo'] ?? []);
            $this->persistHomepage($data);
            $this->persistClients($data['clients'] ?? []);
            if (array_key_exists('services', $data)) {
                $this->persistServices($data['services']);
            }
            $this->persistRates('non_masking', $data['pricingNM'] ?? []);
            $this->persistRates('masking', $data['pricingM'] ?? []);
            $this->persistFaqs($data['faq'] ?? []);
            $this->persistTestimonials($data['testimonials'] ?? []);
            $this->persistSeo($data['seo'] ?? []);
        });
    }

    private function persistSettings(array $settings, array $contactInfo): void
    {
        foreach (['siteName', 'tagline', 'copyright', 'btrc', 'loginUrl', 'registerUrl'] as $k) {
            if (array_key_exists($k, $settings)) {
                Setting::put('general', $k, $settings[$k]);
            }
        }
        foreach (['facebook', 'linkedin', 'youtube'] as $k) {
            if (array_key_exists($k, $settings)) {
                Setting::put('social', $k, $settings[$k]);
            }
        }
        // Contact fields come from both the settings form and the dedicated contact form.
        $contact = array_merge(
            array_intersect_key($settings, array_flip(['phone', 'email', 'whatsapp', 'address'])),
            $contactInfo, // contact form is authoritative + adds officeHours/responseTime
        );
        foreach ($contact as $k => $v) {
            Setting::put('contact', $k, $v);
        }
    }

    private function persistHomepage(array $data): void
    {
        if (isset($data['hero'])) {
            HomepageSection::updateOrCreate(['section' => 'hero'], ['content' => $data['hero']]);
        }
        if (isset($data['whyUs'])) {
            HomepageSection::updateOrCreate(['section' => 'why_us'], ['content' => array_values($data['whyUs'])]);
        }
        if (isset($data['about'])) {
            HomepageSection::updateOrCreate(['section' => 'about'], ['content' => $data['about']]);
        }
    }

    private function persistClients(array $clients): void
    {
        Client::query()->delete();
        foreach (array_values($clients) as $i => $c) {
            Client::create([
                'name' => $c['name'] ?? 'Client',
                'icon' => $c['icon'] ?? null,
                'color' => $c['color'] ?? null,
                'bg' => $c['bg'] ?? null,
                'sort_order' => $i + 1,
            ]);
        }
    }

    private function persistServices(array $services): void
    {
        $keep = [];
        foreach (array_values($services) as $i => $s) {
            $name = $s['name'] ?? 'Service';
            $slug = ! empty($s['slug']) ? Str::slug($s['slug']) : Str::slug($name).'-'.($i + 1);
            $service = Service::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'icon' => $s['icon'] ?? null,
                    'description' => $s['description'] ?? null,
                    'features' => array_values(array_filter((array) ($s['features'] ?? []), fn ($f) => trim((string) $f) !== '')),
                    'cta_text' => $s['cta_text'] ?? 'Get Started',
                    'cta_link' => $s['cta_link'] ?? 'contact.html',
                    'sort_order' => $i + 1,
                    'is_active' => true,
                ],
            );
            $keep[] = $service->id;
        }
        Service::whereNotIn('id', $keep ?: [0])->delete();
    }

    private function persistRates(string $type, array $rates): void
    {
        SmsRate::where('type', $type)->delete();
        foreach (array_values($rates) as $i => $r) {
            $max = $r['max'] ?? null;
            SmsRate::create([
                'type' => $type,
                'tier' => $r['tier'] ?? 'Tier',
                'min_qty' => (int) ($r['min'] ?? 0),
                'max_qty' => $max ? (int) $max : null, // 0/null = unlimited
                'price' => (float) ($r['price'] ?? 0),
                'sort_order' => $i + 1,
            ]);
        }
    }

    private function persistFaqs(array $faqs): void
    {
        Faq::query()->delete();
        foreach (array_values($faqs) as $i => $f) {
            Faq::create([
                'question' => $f['q'] ?? '',
                'answer' => $f['a'] ?? '',
                'category' => $f['category'] ?? 'General',
                'sort_order' => $i + 1,
            ]);
        }
    }

    private function persistTestimonials(array $items): void
    {
        Testimonial::query()->delete();
        foreach (array_values($items) as $i => $t) {
            Testimonial::create([
                'name' => $t['name'] ?? '',
                'role' => $t['role'] ?? null,
                'text' => $t['text'] ?? '',
                'rating' => (int) ($t['rating'] ?? 5),
                'initials' => $t['initials'] ?? null,
                'bg' => $t['bg'] ?? null,
                'color' => $t['color'] ?? null,
                'is_featured' => (bool) ($t['featured'] ?? false),
                'sort_order' => $i + 1,
            ]);
        }
    }

    private function persistSeo(array $seo): void
    {
        foreach ($seo as $page => $meta) {
            SeoMeta::updateOrCreate(
                ['page' => $page],
                [
                    'title' => $meta['title'] ?? null,
                    'description' => $meta['desc'] ?? null,
                    'keywords' => $meta['keywords'] ?? null,
                ],
            );
        }
    }
}
