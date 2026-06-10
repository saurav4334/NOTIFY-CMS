<?php

/*
|--------------------------------------------------------------------------
| Marketing page registry — single source of truth
|--------------------------------------------------------------------------
| Drives clean-URL routing (routes/web.php), per-page SEO meta + JSON-LD
| (resources/views/layouts/marketing.blade.php), and sitemap.xml
| (App\Http\Controllers\SeoController).
|
| Key   = clean path (no leading slash; '' is the homepage "/").
| Each entry:
|   title       — <title> + og:title (unique per page)
|   description — meta description + og:description (≤ ~160 chars)
|   priority    — sitemap <priority>
|   changefreq  — sitemap <changefreq>
|   crumb       — breadcrumb label (null = not shown / homepage)
|   og_image    — absolute or root-relative OG/Twitter image
|   file        — static html served from frontend/ (existing pages), OR
|   view        — blade view rendered for the page (new pages)
*/

return [

    // ── Homepage + existing static pages (served from frontend/*.html) ──
    '' => [
        'title' => 'NotifySMS — #1 Bulk SMS Platform in Bangladesh',
        'description' => "Bangladesh's most reliable bulk SMS gateway. Send promotional, transactional, OTP, masking & API SMS to GP, Robi, Banglalink & Teletalk. From ৳0.26/SMS.",
        'priority' => '1.0', 'changefreq' => 'daily', 'crumb' => null,
        'file' => 'index.html',
    ],
    'about' => [
        'title' => 'About NotifySMS — Trusted BTRC-Certified SMS Provider',
        'description' => 'NotifySMS is a BTRC-certified bulk SMS provider serving 10,000+ Bangladeshi businesses since 2015 with 99.9% uptime and 24×7 support.',
        'priority' => '0.7', 'changefreq' => 'monthly', 'crumb' => 'About',
        'file' => 'about.html',
    ],
    'services' => [
        'title' => 'Bulk SMS Services in Bangladesh — NotifySMS',
        'description' => 'Complete SMS solutions for Bangladesh: promotional, masking, OTP, transactional, two-way and API SMS — one platform, all four operators.',
        'priority' => '0.8', 'changefreq' => 'weekly', 'crumb' => 'Services',
        'file' => 'services.html',
    ],
    'pricing' => [
        'title' => 'SMS Pricing in Bangladesh — Transparent Rates | NotifySMS',
        'description' => 'Transparent bulk SMS pricing including VAT & dipping. Masking and non-masking slab rates starting from ৳0.26/SMS. No hidden fees.',
        'priority' => '0.9', 'changefreq' => 'weekly', 'crumb' => 'Pricing',
        'file' => 'pricing.html',
    ],
    'calculator' => [
        'title' => 'SMS Price Calculator — Estimate Bulk SMS Cost | NotifySMS',
        'description' => 'Instantly calculate your bulk SMS cost in Bangladesh. Compare masking vs non-masking rates and see your total with VAT included.',
        'priority' => '0.8', 'changefreq' => 'monthly', 'crumb' => 'Calculator',
        'file' => 'calculator.html',
    ],
    'faq' => [
        'title' => 'Bulk SMS FAQ — Pricing, API, Masking & Delivery | NotifySMS',
        'description' => 'Answers to common questions about bulk SMS in Bangladesh: pricing, masking approval, API integration, delivery reports and operator coverage.',
        'priority' => '0.6', 'changefreq' => 'monthly', 'crumb' => 'FAQ',
        'file' => 'faq.html',
    ],
    'contact' => [
        'title' => 'Contact NotifySMS — Talk to an SMS Expert',
        'description' => 'Get in touch with NotifySMS. 24×7 support, 1-hour response time, and a dedicated team to set up your bulk SMS campaigns across Bangladesh.',
        'priority' => '0.7', 'changefreq' => 'monthly', 'crumb' => 'Contact',
        'file' => 'contact.html',
    ],

    // ── New product landing pages (Blade) ──
    'bulk-sms' => [
        'title' => 'Bulk SMS Bangladesh — Send Mass SMS Campaigns | NotifySMS',
        'description' => 'Send bulk SMS in Bangladesh to GP, Robi, Banglalink & Teletalk. Real-time delivery reports, scheduling, location targeting and ৳0.26/SMS pricing.',
        'priority' => '0.9', 'changefreq' => 'weekly', 'crumb' => 'Bulk SMS',
        'view' => 'pages.bulk-sms',
    ],
    'sms-api' => [
        'title' => 'SMS API Bangladesh — REST SMS Gateway API | NotifySMS',
        'description' => 'Integrate SMS in minutes with the NotifySMS REST API. Send OTP, alerts & campaigns from PHP, Python, Node.js or Laravel with 99.9% uptime.',
        'priority' => '0.9', 'changefreq' => 'weekly', 'crumb' => 'SMS API',
        'view' => 'pages.sms-api',
    ],
    'otp-sms' => [
        'title' => 'OTP SMS Bangladesh — Verification & 2FA SMS | NotifySMS',
        'description' => 'Deliver one-time passwords in under 3 seconds. High-priority OTP SMS routing for login, signup and 2FA across all Bangladeshi operators.',
        'priority' => '0.9', 'changefreq' => 'weekly', 'crumb' => 'OTP SMS',
        'view' => 'pages.otp-sms',
    ],
    'masking-sms' => [
        'title' => 'Masking SMS Bangladesh — Branded Sender ID | NotifySMS',
        'description' => 'Send masking SMS with your brand name as the sender ID. BTRC-approved branded sender IDs that boost trust and open rates across Bangladesh.',
        'priority' => '0.9', 'changefreq' => 'weekly', 'crumb' => 'Masking SMS',
        'view' => 'pages.masking-sms',
    ],
    'transactional-sms' => [
        'title' => 'Transactional SMS Bangladesh — Alerts & Notifications | NotifySMS',
        'description' => 'Send transactional SMS — order updates, payment alerts and account notifications — delivered 24×7 with priority routing and full logs.',
        'priority' => '0.9', 'changefreq' => 'weekly', 'crumb' => 'Transactional SMS',
        'view' => 'pages.transactional-sms',
    ],

    // ── Resource pages (Blade) ──
    'docs/api' => [
        'title' => 'SMS API Documentation — REST Reference | NotifySMS',
        'description' => 'NotifySMS REST API documentation: authentication, send SMS, balance, delivery reports and pricing endpoints with curl, PHP, Node.js & Python samples.',
        'priority' => '0.7', 'changefreq' => 'weekly', 'crumb' => 'API Docs',
        'view' => 'pages.docs-api',
    ],
    'blog' => [
        'title' => 'Blog & SMS Marketing Resources | NotifySMS',
        'description' => 'Guides, tips and best practices for bulk SMS marketing, OTP delivery and SMS API integration for businesses in Bangladesh.',
        'priority' => '0.6', 'changefreq' => 'weekly', 'crumb' => 'Blog',
        'view' => 'pages.blog',
    ],

    // ── Industry landing pages (Blade) ──
    'bulk-sms-for-ecommerce' => [
        'title' => 'Bulk SMS for E-commerce in Bangladesh | NotifySMS',
        'description' => 'Boost e-commerce sales with bulk SMS: order confirmations, COD verification, delivery updates and promo campaigns that reach every customer instantly.',
        'priority' => '0.8', 'changefreq' => 'weekly', 'crumb' => 'SMS for E-commerce',
        'view' => 'pages.industries.ecommerce',
    ],
    'bulk-sms-for-schools' => [
        'title' => 'Bulk SMS for Schools & Coaching in Bangladesh | NotifySMS',
        'description' => 'Bulk SMS for schools, colleges and coaching centres: attendance alerts, results, fee reminders and parent notifications across Bangladesh.',
        'priority' => '0.8', 'changefreq' => 'weekly', 'crumb' => 'SMS for Schools',
        'view' => 'pages.industries.schools',
    ],
    'bulk-sms-for-hospitals' => [
        'title' => 'Bulk SMS for Hospitals & Clinics in Bangladesh | NotifySMS',
        'description' => 'Hospital SMS solutions: appointment reminders, report-ready alerts, OTP for patient portals and emergency notifications — HIPAA-minded and reliable.',
        'priority' => '0.8', 'changefreq' => 'weekly', 'crumb' => 'SMS for Hospitals',
        'view' => 'pages.industries.hospitals',
    ],
    'bulk-sms-for-restaurants' => [
        'title' => 'Bulk SMS for Restaurants in Bangladesh | NotifySMS',
        'description' => 'Restaurant SMS marketing: reservation confirmations, offers, loyalty rewards and order-ready alerts that bring diners back again and again.',
        'priority' => '0.8', 'changefreq' => 'weekly', 'crumb' => 'SMS for Restaurants',
        'view' => 'pages.industries.restaurants',
    ],
    'otp-sms-for-banking' => [
        'title' => 'OTP SMS for Banking & Fintech in Bangladesh | NotifySMS',
        'description' => 'Bank-grade OTP SMS for fintech and banking: sub-3-second 2FA, transaction alerts and high-priority routing with BTRC-compliant, secure delivery.',
        'priority' => '0.8', 'changefreq' => 'weekly', 'crumb' => 'OTP for Banking',
        'view' => 'pages.industries.banking-otp',
    ],
];
