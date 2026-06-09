<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Faq;
use App\Models\HomepageSection;
use App\Models\SeoMeta;
use App\Models\Service;
use App\Models\Setting;
use App\Models\SmsRate;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Imports the original cms.js localStorage defaults into the database.
 */
class CmsContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSettings();
        $this->seedHomepage();
        $this->seedClients();
        $this->seedSmsRates();
        $this->seedFaqs();
        $this->seedTestimonials();
        $this->seedSeo();
        $this->seedServices();
    }

    private function seedSettings(): void
    {
        $general = [
            'siteName' => 'NotifySMS',
            'tagline' => '#1 Bulk SMS Platform in Bangladesh',
            'copyright' => '© 2025 NotifySMS. All rights reserved.',
            'btrc' => 'BTRC Licensed | Powered by NotifySMS Bangladesh',
            'loginUrl' => 'https://customer.notifysms.com.bd/login',
            'registerUrl' => '#',
        ];
        $contact = [
            'phone' => '+880 1XXXXXXXXX',
            'email' => 'info@notifysms.com.bd',
            'address' => 'Dhaka, Bangladesh',
            'whatsapp' => '+880 1XXXXXXXXX',
            'officeHours' => 'Sat–Thu: 9AM–9PM | Fri: 2PM–9PM',
            'responseTime' => '1 business hour',
        ];
        $social = [
            'facebook' => '#',
            'linkedin' => '#',
            'youtube' => '#',
        ];

        foreach ($general as $k => $v) {
            Setting::put('general', $k, $v);
        }
        foreach ($contact as $k => $v) {
            Setting::put('contact', $k, $v);
        }
        foreach ($social as $k => $v) {
            Setting::put('social', $k, $v);
        }
    }

    private function seedHomepage(): void
    {
        HomepageSection::updateOrCreate(['section' => 'hero'], [
            'content' => [
                'badge' => '#1 Bulk SMS Platform in Bangladesh',
                'line1' => 'Send Millions of',
                'line2Gradient' => 'SMS Messages',
                'line3' => 'Instantly',
                'subtitle' => "Bangladesh's most reliable bulk SMS service. Promotional, transactional, masking & API SMS — all in one platform with real-time delivery reports.",
                'btn1Text' => 'Get Started Free', 'btn1Link' => 'contact.html',
                'btn2Text' => 'Calculate Price', 'btn2Link' => 'calculator.html',
                'stat1Num' => '500M+', 'stat1Label' => 'SMS Delivered',
                'stat2Num' => '10K+', 'stat2Label' => 'Active Clients',
                'stat3Num' => '99.9%', 'stat3Label' => 'Uptime SLA',
            ],
        ]);

        HomepageSection::updateOrCreate(['section' => 'about'], [
            'content' => [
                'heading' => 'Our Story',
                'story1' => 'NotifySMS was founded in 2015 with a simple mission: make bulk SMS accessible, affordable, and reliable for every business in Bangladesh — from small startups to large enterprises.',
                'story2' => 'Today, we serve 10,000+ active clients across retail, banking, healthcare, e-commerce, and NGO sectors. Our platform delivers over 500 million SMS per year with an average delivery rate of 98.5%.',
                'mission' => "To become South Asia's most trusted bulk SMS platform by continuously innovating, expanding our network coverage, and delivering unmatched value to our clients — with 100% transparent pricing that includes all taxes and charges.",
                'vision' => 'Empowering businesses with reliable, fast, and affordable bulk SMS since 2015.',
                'stats' => [
                    ['value' => '2015', 'label' => 'Founded', 'sub' => 'Dhaka, Bangladesh'],
                    ['value' => '10K+', 'label' => 'Active Clients', 'sub' => 'Across Bangladesh'],
                    ['value' => '500M+', 'label' => 'SMS Delivered', 'sub' => 'Per year'],
                    ['value' => '98.5%', 'label' => 'Delivery Rate', 'sub' => 'Industry-leading'],
                ],
            ],
        ]);

        HomepageSection::updateOrCreate(['section' => 'why_us'], [
            'content' => [
                ['icon' => 'fa-tags', 'bg' => 'linear-gradient(135deg,#003087,#0055cc)', 'title' => 'Cheap SMS Rates', 'desc' => 'Starting at just ৳0.26/SMS including all taxes.', 'tag' => 'Starting ৳0.26/SMS'],
                ['icon' => 'fa-location-dot', 'bg' => 'linear-gradient(135deg,#059669,#10b981)', 'title' => 'Location Based SMS', 'desc' => 'Target by division, district or 500m–50km radius.', 'tag' => '64 Districts covered'],
                ['icon' => 'fa-headset', 'bg' => 'linear-gradient(135deg,#7c3aed,#8b5cf6)', 'title' => '24×7 Live Support', 'desc' => 'Expert support with under 1 hour response time.', 'tag' => 'Always online'],
                ['icon' => 'fa-arrows-left-right', 'bg' => 'linear-gradient(135deg,#f97316,#fb923c)', 'title' => 'Two-way SMS', 'desc' => 'Send and receive SMS — enable customer replies.', 'tag' => 'Send & Receive'],
                ['icon' => 'fa-bolt', 'bg' => 'linear-gradient(135deg,#06b6d4,#0ea5e9)', 'title' => 'Instant Delivery', 'desc' => '1,000 SMS in under 3 seconds with tracking.', 'tag' => '<3 seconds delivery'],
                ['icon' => 'fa-shield-halved', 'bg' => 'linear-gradient(135deg,#10b981,#059669)', 'title' => 'BTRC Certified', 'desc' => 'Fully licensed & 100% compliant and secure.', 'tag' => 'Licensed & Verified'],
                ['icon' => 'fa-code', 'bg' => 'linear-gradient(135deg,#6366f1,#8b5cf6)', 'title' => 'Simple REST API', 'desc' => 'Integrate in minutes — PHP, Python, Node.js SDKs.', 'tag' => 'REST API v2'],
                ['icon' => 'fa-signal', 'bg' => 'linear-gradient(135deg,#f59e0b,#ffc439)', 'title' => 'All 4 Operators', 'desc' => 'GP, Robi, Banglalink & Teletalk with auto failover.', 'tag' => '99.9% uptime'],
            ],
        ]);
    }

    private function seedClients(): void
    {
        $clients = [
            ['Daraz', 'fa-shopping-cart', '#ea580c', 'linear-gradient(135deg,#f97316,#ea580c)'],
            ['Chaldal', 'fa-leaf', '#15803d', 'linear-gradient(135deg,#16a34a,#15803d)'],
            ['Pathao', 'fa-motorcycle', '#dc2626', 'linear-gradient(135deg,#dc2626,#b91c1c)'],
            ['bKash', 'fa-mobile-screen-button', '#c2185b', 'linear-gradient(135deg,#e91e8c,#c2185b)'],
            ['Nagad', 'fa-coins', '#d97706', 'linear-gradient(135deg,#f59e0b,#d97706)'],
            ['Bangladesh Bank', 'fa-university', '#003087', 'linear-gradient(135deg,#003087,#0046b8)'],
            ['Shohoz', 'fa-car', '#0284c7', 'linear-gradient(135deg,#0ea5e9,#0284c7)'],
            ['HungryNaki', 'fa-utensils', '#dc2626', 'linear-gradient(135deg,#ef4444,#dc2626)'],
            ['ShopUp', 'fa-store', '#6d28d9', 'linear-gradient(135deg,#7c3aed,#6d28d9)'],
            ['LabAid', 'fa-hospital', '#0e7490', 'linear-gradient(135deg,#0891b2,#0e7490)'],
            ['10 Minute School', 'fa-graduation-cap', '#047857', 'linear-gradient(135deg,#059669,#047857)'],
            ['Bikroy', 'fa-tag', '#003087', 'linear-gradient(135deg,#003087,#009cde)'],
            ['DBBL', 'fa-landmark', '#1e293b', 'linear-gradient(135deg,#0f172a,#1e293b)'],
            ['Shajgoj', 'fa-leaf', '#15803d', 'linear-gradient(135deg,#16a34a,#15803d)'],
            ['RedX', 'fa-truck', '#b45309', 'linear-gradient(135deg,#d97706,#b45309)'],
            ['Grameenphone', 'fa-signal', '#14532d', 'linear-gradient(135deg,#16a34a,#14532d)'],
            ['Robi Axiata', 'fa-tv', '#0c4a6e', 'linear-gradient(135deg,#0284c7,#0c4a6e)'],
            ['Banglalink', 'fa-fire', '#9a3412', 'linear-gradient(135deg,#ea580c,#9a3412)'],
            ['BRAC', 'fa-globe', '#065f46', 'linear-gradient(135deg,#059669,#065f46)'],
            ['Prothom Alo', 'fa-newspaper', '#075985', 'linear-gradient(135deg,#0369a1,#075985)'],
        ];

        foreach ($clients as $i => [$name, $icon, $color, $bg]) {
            Client::updateOrCreate(
                ['name' => $name],
                ['icon' => $icon, 'color' => $color, 'bg' => $bg, 'sort_order' => $i + 1],
            );
        }
    }

    private function seedSmsRates(): void
    {
        $nonMasking = [
            ['Starter', 5000, 10000, 0.35],
            ['Business', 11000, 20000, 0.30],
            ['Enterprise', 40000, 99999, 0.28],
            ['Elite', 100000, null, 0.26],
        ];
        $masking = [
            ['Starter', 5000, 10000, 0.55],
            ['Business', 11000, 20000, 0.52],
            ['Enterprise', 40000, 99999, 0.50],
            ['Elite', 100000, null, 0.48],
        ];

        foreach ($nonMasking as $i => [$tier, $min, $max, $price]) {
            SmsRate::updateOrCreate(
                ['type' => 'non_masking', 'tier' => $tier],
                ['min_qty' => $min, 'max_qty' => $max, 'price' => $price, 'sort_order' => $i + 1],
            );
        }
        foreach ($masking as $i => [$tier, $min, $max, $price]) {
            SmsRate::updateOrCreate(
                ['type' => 'masking', 'tier' => $tier],
                ['min_qty' => $min, 'max_qty' => $max, 'price' => $price, 'sort_order' => $i + 1],
            );
        }
    }

    private function seedFaqs(): void
    {
        $faqs = [
            ['Bulk SMS ki ebong ki kaje lage?', 'Bulk SMS holo ek sathe baro sankhyok manush-ke SMS pathano-r service. Promotional offer, OTP, transactional alert, ebong marketing campaign-er jonno use kora hoy.'],
            ['SMS delivery time koto lage?', 'Amader system-e 1,000 SMS approximately 3 seconds-e deliver hoy. Network condition-er upor nirbhor kore kichukhon bethe jete pare tobe sobcheye beshi 30 seconds.'],
            ['Masking ebong Non-masking SMS er parthokyo ki?', 'Masking SMS-e sender ID hisebe apnar company naam dekhabe. Non-masking-e numeric number dekhabe. Masking SMS-er price ektu beshi kintu brand trust baraye.'],
            ['Minimum koto SMS order korte hobe?', 'Amader minimum order quantity 5,000 SMS. Tar kome custom pricing-er jonno sales team-er shathe contact korun.'],
            ['API integration possible ki?', 'Hya, amader REST API v2 available. PHP, Python, Node.js, Java shob popular language-er jonno SDK thakbe.'],
            ['Payment process ki?', 'bKash, Nagad, bank transfer, ebong online card payment accept kori. bKash/Nagad-e payment korar 5-10 minutes-er moddhe account-e credit add hoy.'],
        ];

        foreach ($faqs as $i => [$q, $a]) {
            Faq::updateOrCreate(
                ['question' => $q],
                ['answer' => $a, 'category' => 'General', 'sort_order' => $i + 1],
            );
        }
    }

    private function seedTestimonials(): void
    {
        $items = [
            ['Ahmed Rahman', 'CEO, TechBD Solutions', 'NotifySMS transformed our marketing campaigns. The delivery rate is exceptional and pricing is very competitive.', 5, 'AR', '#bfdbfe', '#1e40af', false],
            ['Farhan Khan', 'CTO, ShopBD.com', 'API integration was seamless. Within 2 hours we had SMS running. Support team is incredibly responsive!', 5, 'FK', '#3b82f6', '#ffffff', true],
            ['Tahmina Islam', 'VP Technology, FinTech BD', 'We send OTP for our banking app. NotifySMS delivers in under 2 seconds every time. Never let us down.', 5, 'TI', '#ddd6fe', '#5b21b6', false],
        ];

        foreach ($items as $i => [$name, $role, $text, $rating, $initials, $bg, $color, $featured]) {
            Testimonial::updateOrCreate(
                ['name' => $name],
                [
                    'role' => $role, 'text' => $text, 'rating' => $rating, 'initials' => $initials,
                    'bg' => $bg, 'color' => $color, 'is_featured' => $featured, 'sort_order' => $i + 1,
                ],
            );
        }
    }

    private function seedSeo(): void
    {
        $seo = [
            'home' => ['NotifySMS – #1 Bulk SMS Platform Bangladesh', 'Bangladesh most reliable bulk SMS service. Send promotional, transactional, masking & API SMS.', 'bulk sms bangladesh, sms gateway, masking sms'],
            'about' => ['About NotifySMS – Trusted Bulk SMS Provider', 'Learn about NotifySMS — founded 2015, 10K+ clients, BTRC certified.', 'about notifysms, sms company bangladesh'],
            'services' => ['Bulk SMS Services – NotifySMS', 'Complete SMS solutions: Promotional, Masking, OTP, API, Campaign SMS.', 'bulk sms services, masking sms, otp sms'],
            'pricing' => ['SMS Pricing – NotifySMS Bangladesh', 'Transparent SMS pricing including VAT, TAX & Dipping. From ৳0.26/SMS.', 'sms price bangladesh, bulk sms rate'],
            'calculator' => ['SMS Price Calculator – NotifySMS', 'Calculate your bulk SMS cost instantly. Masking & Non-masking rates.', 'sms calculator, bulk sms price calculator'],
            'faq' => ['FAQ – NotifySMS', 'Frequently asked questions about bulk SMS, pricing, API, and more.', 'sms faq, bulk sms questions'],
            'contact' => ['Contact NotifySMS – Get in Touch', 'Contact our SMS experts. 24/7 support available.', 'contact notifysms, sms support'],
        ];

        foreach ($seo as $page => [$title, $desc, $keywords]) {
            SeoMeta::updateOrCreate(
                ['page' => $page],
                ['title' => $title, 'description' => $desc, 'keywords' => $keywords],
            );
        }
    }

    private function seedServices(): void
    {
        $services = [
            ['Promotional SMS', 'fa-bullhorn', 'Boost sales with targeted promotional campaigns to massive audiences instantly. Perfect for offers, discounts, and events.', ['Bulk delivery', 'Scheduled sending', 'Delivery reports']],
            ['Transactional SMS', 'fa-exchange-alt', 'Send automated alerts, payment confirmations, order updates, and notifications with instant guaranteed delivery.', ['Payment alerts', 'Order updates', 'System notifications']],
            ['Masking SMS', 'fa-id-badge', 'Show your brand name as the sender ID instead of a number. Build customer trust with every message sent.', ['Custom sender ID', 'Brand recognition', 'Higher open rates']],
            ['API SMS', 'fa-code', 'Integrate bulk SMS into any application with our powerful REST API. Full documentation and SDKs available.', ['REST API v2', 'PHP, Python, Node SDKs', 'Webhook callbacks']],
            ['OTP SMS', 'fa-key', 'Secure one-time passwords for 2FA authentication, user verification, and login processes. Sub-2-second delivery.', ['<2s delivery', '99.9% delivery rate', 'All operators']],
            ['Non-Masking SMS', 'fa-satellite-dish', 'Cost-effective SMS with numeric sender IDs. Perfect for large-scale bulk promotional campaigns on a budget.', ['Lowest cost option', 'High volume support', 'All 4 operators']],
            ['Campaign SMS', 'fa-paper-plane', 'Schedule, run, and analyze large-scale SMS marketing campaigns with detailed analytics and reports.', ['Scheduled delivery', 'A/B testing', 'Campaign analytics']],
        ];

        $slugs = [];
        foreach ($services as $i => [$name, $icon, $desc, $features]) {
            $slug = Str::slug($name);
            $slugs[] = $slug;
            Service::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name, 'icon' => $icon, 'description' => $desc,
                    'features' => $features, 'cta_text' => 'Get Started', 'cta_link' => 'contact.html',
                    'sort_order' => $i + 1,
                ],
            );
        }

        // Remove services that are no longer part of the seed set (e.g. legacy "Voice SMS").
        Service::whereNotIn('slug', $slugs)->delete();
    }
}
