@extends('layouts.marketing')

@section('content')
@include('partials.breadcrumbs')

<section class="gradient-hero pt-6 pb-20 px-4">
  <div class="max-w-5xl mx-auto text-center">
    <span class="inline-flex items-center gap-2 bg-white border border-blue-100 shadow-sm text-ppnavy text-xs font-bold px-4 py-2 rounded-full mb-6"><i class="fas fa-cart-shopping" style="color:#009cde;"></i> BULK SMS FOR E-COMMERCE</span>
    <h1 class="text-4xl md:text-6xl font-black leading-tight mb-6" style="color:#003087;">Bulk SMS for <span class="gradient-text">E-commerce</span> in Bangladesh</h1>
    <p class="text-lg md:text-xl text-slate-600 max-w-3xl mx-auto mb-9">Turn browsers into buyers and reduce failed deliveries. From COD verification and order updates to flash-sale blasts, NotifySMS helps online stores reach every customer instantly on GP, Robi, Banglalink and Teletalk.</p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
      <a href="/contact" class="font-bold px-8 py-4 rounded-2xl text-base hover:opacity-90" style="background:#ffc439;color:#003087;box-shadow:0 8px 28px rgba(255,196,57,.4);"><i class="fas fa-store mr-2"></i>Grow Your Store</a>
      <a href="/sms-api" class="font-semibold px-8 py-4 rounded-2xl text-base border-2 hover:bg-blue-50" style="border-color:#003087;color:#003087;"><i class="fas fa-plug mr-2"></i>Connect Your Store</a>
    </div>
  </div>
</section>

<section class="py-20 px-4 bg-white">
  <div class="max-w-6xl mx-auto">
    <div class="text-center mb-14"><h2 class="text-3xl md:text-4xl font-black mb-3" style="color:#003087;">SMS for every step of the customer journey</h2><p class="text-slate-500 max-w-2xl mx-auto">Automate the messages that drive sales and cut costs.</p></div>
    <div class="grid md:grid-cols-3 gap-6">
      @foreach ([
        ['fa-shield-halved','#003087','COD verification','Confirm cash-on-delivery orders with an OTP to slash fake orders and failed deliveries.'],
        ['fa-box-open','#059669','Order & shipping updates','Keep buyers informed from confirmation to delivery — fewer “where is my order?” calls.'],
        ['fa-tags','#f97316','Promotions & flash sales','Announce Eid offers, discounts and restocks to your whole list in seconds.'],
        ['fa-cart-arrow-down','#7c3aed','Abandoned cart recovery','Nudge shoppers who left items behind and win back lost revenue.'],
        ['fa-rotate-left','#0ea5e9','Delivery & returns','Notify customers about dispatch, delays and return pickups automatically.'],
        ['fa-star','#10b981','Reviews & loyalty','Request reviews after delivery and reward repeat buyers with exclusive codes.'],
      ] as $f)
      <div class="feat-card bg-slate-50/70 border border-slate-100 rounded-2xl p-7">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5" style="background:{{ $f[1] }};"><i class="fas {{ $f[0] }} text-white"></i></div>
        <h3 class="font-bold text-lg mb-2 text-slate-800">{{ $f[2] }}</h3>
        <p class="text-slate-600 text-sm leading-relaxed">{{ $f[3] }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>

<section class="py-16 px-4" style="background:#f5f8ff;">
  <div class="max-w-5xl mx-auto">
    <h2 class="text-2xl md:text-3xl font-black text-center mb-10" style="color:#003087;">Sample messages that convert</h2>
    <div class="grid sm:grid-cols-2 gap-5">
      @foreach ([
        'Hi Rahim! Your order #2043 is confirmed (৳1,850 COD). Reply 1 to verify. — ShopBD',
        'Flash Sale ⚡ 30% off everything till midnight! Shop now: shopbd.com/sale',
        'Your parcel is out for delivery and will arrive today by 6 PM. Track: …',
        'You left 2 items in your cart 🛒 Complete your order now and get free delivery!',
      ] as $m)
      <div class="bg-white border border-slate-100 rounded-2xl p-5 flex gap-3"><i class="fas fa-comment-sms mt-1" style="color:#009cde;"></i><p class="text-slate-700 text-sm italic">{{ $m }}</p></div>
      @endforeach
    </div>
  </div>
</section>

<section class="py-16 px-4 bg-white"><div class="max-w-6xl mx-auto"><h2 class="text-2xl font-black mb-8 text-center" style="color:#003087;">Recommended for online stores</h2>
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
    @foreach ([
      ['/bulk-sms','fa-paper-plane','Bulk SMS','Mass promo & announcement blasts.'],
      ['/transactional-sms','fa-bell','Transactional SMS','Order, payment & delivery alerts.'],
      ['/otp-sms','fa-key','OTP SMS','COD & checkout verification.'],
      ['/sms-api','fa-code','SMS API','Plug SMS into your store platform.'],
    ] as $r)
    <a href="{{ $r[0] }}" class="feat-card block bg-slate-50/70 border border-slate-100 rounded-2xl p-6 hover:border-blue-200"><i class="fas {{ $r[1] }} text-xl mb-3" style="color:#009cde;"></i><h3 class="font-bold text-slate-800 mb-1">{{ $r[2] }}</h3><p class="text-slate-500 text-sm">{{ $r[3] }}</p></a>
    @endforeach
  </div>
</div></section>

@include('partials.faq', ['faqs' => [
  ['q' => 'How does SMS reduce failed COD deliveries?', 'a' => 'By sending an OTP or confirmation SMS at checkout, you verify the buyer’s number and intent before dispatch — cutting fake orders and return-to-origin costs. See <a href="/otp-sms" style="color:#009cde;font-weight:600;">OTP SMS</a>.'],
  ['q' => 'Can I connect SMS to my e-commerce platform?', 'a' => 'Yes. Use our <a href="/sms-api" style="color:#009cde;font-weight:600;">SMS API</a> to trigger order, shipping and payment messages automatically from WooCommerce, Shopify, Laravel or a custom store.'],
  ['q' => 'How much does e-commerce SMS cost?', 'a' => 'Rates start from ৳0.26/SMS. Use the <a href="/calculator" style="color:#009cde;font-weight:600;">calculator</a> to estimate your monthly cost based on order volume.'],
  ['q' => 'Can I send promotional and order SMS from the same account?', 'a' => 'Yes — run promotional campaigns with <a href="/bulk-sms" style="color:#009cde;font-weight:600;">bulk SMS</a> and order/shipping updates with <a href="/transactional-sms" style="color:#009cde;font-weight:600;">transactional SMS</a> from one dashboard.'],
]])

@include('partials.cta', ['ctaHeading' => 'Grow your online store with SMS', 'ctaSub' => 'Verify orders, recover carts and delight customers with timely SMS. Set up your e-commerce flows with NotifySMS today.'])
@endsection
