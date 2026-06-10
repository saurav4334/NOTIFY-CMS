@extends('layouts.marketing')

@section('content')
@include('partials.breadcrumbs')

<section class="gradient-hero pt-6 pb-16 px-4">
  <div class="max-w-4xl mx-auto text-center">
    <span class="inline-flex items-center gap-2 bg-white border border-blue-100 shadow-sm text-ppnavy text-xs font-bold px-4 py-2 rounded-full mb-6"><i class="fas fa-newspaper" style="color:#009cde;"></i> NOTIFYSMS BLOG & RESOURCES</span>
    <h1 class="text-4xl md:text-5xl font-black leading-tight mb-5" style="color:#003087;">SMS marketing <span class="gradient-text">guides & resources</span></h1>
    <p class="text-lg text-slate-600 max-w-2xl mx-auto">Practical guides on bulk SMS, OTP delivery, API integration and SMS strategy for businesses in Bangladesh.</p>
  </div>
</section>

{{-- CATEGORIES --}}
<section class="py-16 px-4 bg-white">
  <div class="max-w-6xl mx-auto">
    <h2 class="text-2xl md:text-3xl font-black text-center mb-10" style="color:#003087;">Browse by topic</h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach ([
        ['/bulk-sms','fa-paper-plane','Bulk SMS','How to plan, send and measure high-impact SMS campaigns across all four operators.'],
        ['/sms-api','fa-code','SMS API & Developers','Integration guides, code samples and best practices for the NotifySMS REST API.'],
        ['/otp-sms','fa-key','OTP & Security','Designing fast, secure phone verification and two-factor authentication flows.'],
        ['/masking-sms','fa-id-badge','Branding & Masking','Getting a branded sender ID approved and using it to lift trust and open rates.'],
        ['/pricing','fa-tags','Pricing & Costs','Understanding slab rates, VAT and how to estimate your monthly SMS spend.'],
        ['/transactional-sms','fa-bell','Notifications','Building reliable order, payment and alert flows with transactional SMS.'],
      ] as $c)
      <a href="{{ $c[0] }}" class="feat-card block bg-slate-50/70 border border-slate-100 rounded-2xl p-7 hover:border-blue-200">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5" style="background:linear-gradient(135deg,#003087,#009cde);"><i class="fas {{ $c[1] }} text-white"></i></div>
        <h3 class="font-bold text-lg mb-2 text-slate-800">{{ $c[2] }}</h3>
        <p class="text-slate-600 text-sm leading-relaxed">{{ $c[3] }}</p>
        <span class="inline-block mt-4 text-sm font-semibold" style="color:#009cde;">Explore →</span>
      </a>
      @endforeach
    </div>
  </div>
</section>

{{-- INDUSTRY GUIDES --}}
<section class="py-16 px-4" style="background:#f5f8ff;">
  <div class="max-w-6xl mx-auto">
    <h2 class="text-2xl md:text-3xl font-black text-center mb-10" style="color:#003087;">SMS playbooks by industry</h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-5">
      @foreach ([
        ['/bulk-sms-for-ecommerce','fa-cart-shopping','E-commerce'],
        ['/bulk-sms-for-schools','fa-graduation-cap','Schools'],
        ['/bulk-sms-for-hospitals','fa-house-medical','Hospitals'],
        ['/bulk-sms-for-restaurants','fa-utensils','Restaurants'],
        ['/otp-sms-for-banking','fa-building-columns','Banking'],
      ] as $i)
      <a href="{{ $i[0] }}" class="feat-card block bg-white border border-slate-100 rounded-2xl p-6 text-center hover:border-blue-200">
        <i class="fas {{ $i[1] }} text-2xl mb-3" style="color:#009cde;"></i>
        <h3 class="font-bold text-slate-800 text-sm">{{ $i[2] }}</h3>
      </a>
      @endforeach
    </div>
  </div>
</section>

{{-- NEWSLETTER --}}
<section class="py-16 px-4 bg-white">
  <div class="max-w-2xl mx-auto text-center rounded-3xl border border-blue-100 bg-blue-50/40 p-10">
    <i class="fas fa-envelope-open-text text-3xl mb-4" style="color:#009cde;"></i>
    <h2 class="text-2xl font-black mb-2" style="color:#003087;">Get SMS tips in your inbox</h2>
    <p class="text-slate-600 mb-6">New guides, product updates and pricing tips — no spam, unsubscribe anytime.</p>
    <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto" onsubmit="return false;">
      <input type="email" required placeholder="you@company.com" class="flex-1 px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-400">
      <button class="font-bold px-6 py-3 rounded-xl text-white" style="background:#003087;">Subscribe</button>
    </form>
    <p class="text-slate-400 text-xs mt-4">Prefer to talk? <a href="/contact" class="font-semibold" style="color:#009cde;">Contact our team →</a></p>
  </div>
</section>

@include('partials.cta')
@endsection
