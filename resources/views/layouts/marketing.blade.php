@php
    $base      = rtrim(config('app.url'), '/');
    $meta      = $meta ?? [];
    $pageKey   = $pageKey ?? '';
    $pageTitle = $meta['title'] ?? config('app.name', 'NotifySMS');
    $pageDesc  = $meta['description'] ?? '';
    $canonical = $pageKey === '' ? $base.'/' : $base.'/'.ltrim($pageKey, '/');
    $ogImage   = $meta['og_image'] ?? $base.'/og-image.png';

    // Primary nav products / industries (kept in sync with config/pages.php).
    $navProducts = [
        '/bulk-sms'          => 'Bulk SMS',
        '/sms-api'           => 'SMS API',
        '/otp-sms'           => 'OTP SMS',
        '/masking-sms'       => 'Masking SMS',
        '/transactional-sms' => 'Transactional SMS',
    ];
    $navIndustries = [
        '/bulk-sms-for-ecommerce'   => 'E-commerce',
        '/bulk-sms-for-schools'     => 'Schools & Coaching',
        '/bulk-sms-for-hospitals'   => 'Hospitals & Clinics',
        '/bulk-sms-for-restaurants' => 'Restaurants',
        '/otp-sms-for-banking'      => 'Banking & Fintech',
    ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">

{{-- ───── Primary SEO ───── --}}
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDesc }}">
<link rel="canonical" href="{{ $canonical }}">
<meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
<meta name="theme-color" content="#003087">

{{-- ───── Open Graph ───── --}}
<meta property="og:type" content="website">
<meta property="og:site_name" content="NotifySMS">
<meta property="og:locale" content="en_US">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDesc }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $ogImage }}">

{{-- ───── Twitter ───── --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDesc }}">
<meta name="twitter:image" content="{{ $ogImage }}">

{{-- ───── Site-wide structured data ───── --}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'Organization',
            '@id' => $base.'/#organization',
            'name' => 'NotifySMS',
            'url' => $base.'/',
            'logo' => $base.'/og-image.png',
            'description' => "Bangladesh's BTRC-certified bulk SMS gateway for promotional, transactional, OTP, masking and API SMS.",
            'areaServed' => 'BD',
            'sameAs' => ['https://www.facebook.com/', 'https://www.linkedin.com/'],
        ],
        [
            '@type' => 'WebSite',
            '@id' => $base.'/#website',
            'url' => $base.'/',
            'name' => 'NotifySMS',
            'publisher' => ['@id' => $base.'/#organization'],
            'inLanguage' => 'en',
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@stack('schema')

{{-- ───── Assets ───── --}}
<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" type="image/png" href="/favicon-32.png">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<script>window.NOTIFY_API_BASE='/api/v1';</script>
<script src="/cms.js" defer></script>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="/mobile.css">
<link rel="stylesheet" href="/_common.css">
<script>tailwind.config={theme:{extend:{fontFamily:{sans:['Inter','sans-serif']},colors:{ppnavy:'#003087',ppblue:'#009cde',ppgold:'#ffc439'}}}}</script>
<style>
:root{--pp-navy:#003087;--pp-blue:#009cde;--pp-gold:#ffc439;--pp-light:#f5f8ff;--pp-cream:#fafbff;}
html{scroll-behavior:smooth;}
body{font-family:'Inter',sans-serif;color:#0f172a;}
.gradient-text{background:linear-gradient(90deg,#003087,#009cde,#ffc439);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.gradient-hero{background:radial-gradient(ellipse at 15% 60%,rgba(0,156,222,.08) 0,transparent 55%),radial-gradient(ellipse at 85% 20%,rgba(255,196,57,.07) 0,transparent 50%),linear-gradient(160deg,#fff 0%,#f0f7ff 40%,#e8f3fd 70%,#f5f9ff 100%);}
#navbar{transition:all .3s ease;background:rgba(255,255,255,.92)!important;backdrop-filter:blur(12px);border-bottom:1px solid rgba(0,48,135,.08);box-shadow:0 1px 16px rgba(0,48,135,.06);}
#navbar.scrolled{background:rgba(0,48,135,.97)!important;border-bottom:none;box-shadow:0 2px 24px rgba(0,0,0,.2);}
#navbar.scrolled .nav-link{color:rgba(255,255,255,.85)!important;}
#navbar.scrolled .nav-link:hover{color:#fff!important;}
#navbar.scrolled .brand-text{color:#fff!important;}
#navbar.scrolled .menu-icon{color:#fff!important;}
.nav-link{color:#003087;}
.dropdown:hover .dropdown-menu{opacity:1;visibility:visible;transform:translateY(0);}
.dropdown-menu{opacity:0;visibility:hidden;transform:translateY(8px);transition:all .2s ease;}
#mobile-menu{max-height:0;overflow:hidden;transition:max-height .35s ease;}#mobile-menu.open{max-height:900px;}
.feat-card{transition:all .3s ease;}.feat-card:hover{transform:translateY(-5px);box-shadow:0 20px 40px rgba(0,48,135,.1);}
.faq-q{cursor:pointer;}.faq-a{max-height:0;overflow:hidden;transition:max-height .3s ease;}.faq-item.open .faq-a{max-height:400px;}.faq-item.open .faq-chev{transform:rotate(180deg);}
#scrollTop{position:fixed;bottom:28px;right:28px;z-index:999;width:44px;height:44px;background:linear-gradient(135deg,#003087,#009cde);border-radius:12px;display:flex;align-items:center;justify-content:center;cursor:pointer;opacity:0;pointer-events:none;transition:all .3s;box-shadow:0 4px 20px rgba(0,48,135,.4);}
#scrollTop.visible{opacity:1;pointer-events:all;}#scrollTop:hover{transform:translateY(-3px);}
#waBtn{position:fixed;bottom:82px;right:28px;z-index:999;width:44px;height:44px;background:#25d366;border-radius:12px;display:flex;align-items:center;justify-content:center;transition:all .3s;box-shadow:0 4px 20px rgba(37,211,102,.45);text-decoration:none;}
#waBtn:hover{transform:translateY(-3px);}
</style>
</head>
<body class="bg-white antialiased">

{{-- ═══════════ NAVBAR ═══════════ --}}
<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 px-4 py-4">
  <div class="max-w-7xl mx-auto flex items-center justify-between">
    <a href="/" class="flex items-center gap-2"><img src="/logo.png" alt="NotifySMS — Delivering Confidence" class="brand-logo"></a>
    <div class="hidden lg:flex items-center gap-6">
      <div class="dropdown relative">
        <button class="nav-link text-sm font-medium flex items-center gap-1.5">Products <i class="fas fa-chevron-down text-[10px] opacity-70"></i></button>
        <div class="dropdown-menu absolute left-0 mt-3 w-60 bg-white rounded-2xl shadow-xl border border-blue-50 p-2">
          @foreach ($navProducts as $href => $label)
            <a href="{{ $href }}" class="block px-4 py-2.5 rounded-xl text-sm font-medium text-slate-700 hover:bg-blue-50 hover:text-ppnavy">{{ $label }}</a>
          @endforeach
        </div>
      </div>
      <div class="dropdown relative">
        <button class="nav-link text-sm font-medium flex items-center gap-1.5">Industries <i class="fas fa-chevron-down text-[10px] opacity-70"></i></button>
        <div class="dropdown-menu absolute left-0 mt-3 w-60 bg-white rounded-2xl shadow-xl border border-blue-50 p-2">
          @foreach ($navIndustries as $href => $label)
            <a href="{{ $href }}" class="block px-4 py-2.5 rounded-xl text-sm font-medium text-slate-700 hover:bg-blue-50 hover:text-ppnavy">{{ $label }}</a>
          @endforeach
        </div>
      </div>
      <a href="/pricing" class="nav-link text-sm font-medium hover:opacity-70">Pricing</a>
      <a href="/docs/api" class="nav-link text-sm font-medium hover:opacity-70">API Docs</a>
      <a href="/about" class="nav-link text-sm font-medium hover:opacity-70">About</a>
      <a href="/contact" class="nav-link text-sm font-medium hover:opacity-70">Contact</a>
    </div>
    <div class="hidden lg:flex items-center gap-3">
      <a href="https://customer.notifysms.com.bd/login" class="nav-link text-sm font-medium px-4 py-2 rounded-lg hover:opacity-70"><i class="fas fa-sign-in-alt mr-1.5 text-xs"></i>Login</a>
      <a href="/contact" class="text-sm font-bold px-5 py-2.5 rounded-xl hover:opacity-90 shadow-md" style="background:#003087;color:#fff;">Get Started Free</a>
    </div>
    <button onclick="toggleMenu()" class="lg:hidden menu-icon p-2 rounded-lg" style="color:#003087;"><i class="fas fa-bars text-lg"></i></button>
  </div>
  <div id="mobile-menu" class="lg:hidden">
    <div class="max-w-7xl mx-auto mt-3 bg-slate-800/95 backdrop-blur rounded-2xl p-5 flex flex-col gap-1">
      <p class="text-gray-500 text-[11px] uppercase tracking-widest px-3 pt-1">Products</p>
      @foreach ($navProducts as $href => $label)
        <a href="{{ $href }}" class="text-gray-300 hover:text-white text-sm font-medium py-2 px-3 rounded-xl hover:bg-white/5">{{ $label }}</a>
      @endforeach
      <p class="text-gray-500 text-[11px] uppercase tracking-widest px-3 pt-3">Industries</p>
      @foreach ($navIndustries as $href => $label)
        <a href="{{ $href }}" class="text-gray-300 hover:text-white text-sm font-medium py-2 px-3 rounded-xl hover:bg-white/5">{{ $label }}</a>
      @endforeach
      <div class="border-t border-white/10 mt-3 pt-3 flex flex-col gap-1">
        <a href="/pricing" class="text-gray-300 hover:text-white text-sm font-medium py-2 px-3 rounded-xl hover:bg-white/5">Pricing</a>
        <a href="/docs/api" class="text-gray-300 hover:text-white text-sm font-medium py-2 px-3 rounded-xl hover:bg-white/5">API Docs</a>
        <a href="/about" class="text-gray-300 hover:text-white text-sm font-medium py-2 px-3 rounded-xl hover:bg-white/5">About</a>
        <a href="/contact" class="text-gray-300 hover:text-white text-sm font-medium py-2 px-3 rounded-xl hover:bg-white/5">Contact</a>
      </div>
      <div class="flex gap-3 mt-3 pt-3 border-t border-white/10">
        <a href="https://customer.notifysms.com.bd/login" class="flex-1 border border-white/20 text-white text-sm font-semibold py-3 rounded-xl text-center hover:bg-white/10">Login</a>
        <a href="/contact" class="flex-1 text-sm font-semibold py-3 rounded-xl text-center" style="background:#ffc439;color:#003087;">Get Started</a>
      </div>
    </div>
  </div>
</nav>

{{-- ═══════════ PAGE CONTENT ═══════════ --}}
<main>
@yield('content')
</main>

{{-- ═══════════ FOOTER ═══════════ --}}
<footer class="bg-slate-900 text-gray-400 pt-16">
  <div class="max-w-7xl mx-auto px-4">
    <div class="grid md:grid-cols-2 lg:grid-cols-6 gap-8 pb-12">
      <div class="lg:col-span-2">
        <img src="/logo-white.png" alt="NotifySMS — Delivering Confidence" class="h-10 w-auto mb-5">
        <p class="text-gray-500 text-sm leading-relaxed mb-6 max-w-xs">Bangladesh's most reliable bulk SMS platform. BTRC-certified, enterprise-grade infrastructure with transparent pricing.</p>
        <div class="flex gap-2.5">
          <a href="#" aria-label="Facebook" class="w-9 h-9 bg-gray-800 hover:bg-blue-600 rounded-xl flex items-center justify-center transition-colors"><i class="fab fa-facebook-f text-sm"></i></a>
          <a href="#" aria-label="LinkedIn" class="w-9 h-9 bg-gray-800 hover:bg-blue-500 rounded-xl flex items-center justify-center transition-colors"><i class="fab fa-linkedin-in text-sm"></i></a>
          <a href="#" aria-label="WhatsApp" class="w-9 h-9 bg-gray-800 hover:bg-green-600 rounded-xl flex items-center justify-center transition-colors"><i class="fab fa-whatsapp text-sm"></i></a>
          <a href="#" aria-label="YouTube" class="w-9 h-9 bg-gray-800 hover:bg-red-600 rounded-xl flex items-center justify-center transition-colors"><i class="fab fa-youtube text-sm"></i></a>
        </div>
      </div>
      <div><h4 class="text-white font-semibold mb-5 text-xs uppercase tracking-widest">Products</h4><ul class="space-y-3 text-sm">
        @foreach ($navProducts as $href => $label)<li><a href="{{ $href }}" class="hover:text-white transition-colors">{{ $label }}</a></li>@endforeach
      </ul></div>
      <div><h4 class="text-white font-semibold mb-5 text-xs uppercase tracking-widest">Industries</h4><ul class="space-y-3 text-sm">
        @foreach ($navIndustries as $href => $label)<li><a href="{{ $href }}" class="hover:text-white transition-colors">{{ $label }}</a></li>@endforeach
      </ul></div>
      <div><h4 class="text-white font-semibold mb-5 text-xs uppercase tracking-widest">Company</h4><ul class="space-y-3 text-sm">
        <li><a href="/about" class="hover:text-white transition-colors">About Us</a></li>
        <li><a href="/services" class="hover:text-white transition-colors">Services</a></li>
        <li><a href="/pricing" class="hover:text-white transition-colors">Pricing</a></li>
        <li><a href="/blog" class="hover:text-white transition-colors">Blog</a></li>
      </ul></div>
      <div><h4 class="text-white font-semibold mb-5 text-xs uppercase tracking-widest">Support</h4><ul class="space-y-3 text-sm">
        <li><a href="/faq" class="hover:text-white transition-colors">FAQ</a></li>
        <li><a href="/contact" class="hover:text-white transition-colors">Contact Us</a></li>
        <li><a href="/calculator" class="hover:text-white transition-colors">SMS Calculator</a></li>
        <li><a href="/docs/api" class="hover:text-white transition-colors">API Docs</a></li>
      </ul></div>
    </div>
  </div>
  <div class="border-t border-gray-800"><div class="max-w-7xl mx-auto px-4 py-5 flex flex-col md:flex-row items-center justify-between gap-3"><p class="text-gray-600 text-sm">© {{ date('Y') }} NotifySMS. All rights reserved.</p><p class="text-gray-700 text-xs">BTRC Licensed &nbsp;|&nbsp; Powered by NotifySMS Bangladesh</p></div></div>
</footer>

<a href="https://wa.me/8801000000000" id="waBtn" aria-label="WhatsApp"><i class="fab fa-whatsapp text-white text-xl"></i></a>
<div id="scrollTop" onclick="window.scrollTo({top:0,behavior:'smooth'})"><i class="fas fa-arrow-up text-white"></i></div>

<script>
function toggleMenu(){document.getElementById('mobile-menu').classList.toggle('open');}
window.addEventListener('scroll',()=>{
  document.getElementById('navbar').classList.toggle('scrolled',window.scrollY>50);
  document.getElementById('scrollTop').classList.toggle('visible',window.scrollY>300);
});
document.querySelectorAll('.faq-item').forEach(it=>{
  const q=it.querySelector('.faq-q');
  if(q)q.addEventListener('click',()=>it.classList.toggle('open'));
});
</script>
@stack('scripts')
</body>
</html>
