@extends('layouts.marketing')

@section('content')
@include('partials.breadcrumbs')

<section class="gradient-hero pt-6 pb-20 px-4">
  <div class="max-w-5xl mx-auto text-center">
    <span class="inline-flex items-center gap-2 bg-white border border-blue-100 shadow-sm text-ppnavy text-xs font-bold px-4 py-2 rounded-full mb-6"><i class="fas fa-utensils" style="color:#009cde;"></i> BULK SMS FOR RESTAURANTS</span>
    <h1 class="text-4xl md:text-6xl font-black leading-tight mb-6" style="color:#003087;">Bulk SMS for <span class="gradient-text">Restaurants</span> in Bangladesh</h1>
    <p class="text-lg md:text-xl text-slate-600 max-w-3xl mx-auto mb-9">Fill tables and bring diners back. Send reservation confirmations, special offers, loyalty rewards and order-ready alerts that turn first-time guests into regulars — all from one simple dashboard.</p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
      <a href="/contact" class="font-bold px-8 py-4 rounded-2xl text-base hover:opacity-90" style="background:#ffc439;color:#003087;box-shadow:0 8px 28px rgba(255,196,57,.4);"><i class="fas fa-fire-burner mr-2"></i>Boost Footfall</a>
      <a href="/bulk-sms" class="font-semibold px-8 py-4 rounded-2xl text-base border-2 hover:bg-blue-50" style="border-color:#003087;color:#003087;"><i class="fas fa-paper-plane mr-2"></i>See Bulk SMS</a>
    </div>
  </div>
</section>

<section class="py-20 px-4 bg-white">
  <div class="max-w-6xl mx-auto">
    <div class="text-center mb-14"><h2 class="text-3xl md:text-4xl font-black mb-3" style="color:#003087;">SMS that keeps your restaurant full</h2><p class="text-slate-500 max-w-2xl mx-auto">Drive repeat visits with the right message at the right time.</p></div>
    <div class="grid md:grid-cols-3 gap-6">
      @foreach ([
        ['fa-calendar-check','#003087','Reservation confirmations','Confirm and remind guests about bookings to reduce no-shows on busy nights.'],
        ['fa-percent','#f97316','Offers & promotions','Announce weekend deals, new menus and festival specials to your whole list.'],
        ['fa-gift','#7c3aed','Loyalty rewards','Send birthday treats, points updates and exclusive member-only discounts.'],
        ['fa-bell-concierge','#059669','Order-ready alerts','Tell takeaway and delivery customers the moment their food is ready.'],
        ['fa-star','#0ea5e9','Feedback requests','Ask for a review after the visit and turn happy diners into advocates.'],
        ['fa-bullhorn','#10b981','Event invites','Promote live music nights, buffets and grand openings instantly.'],
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
    <h2 class="text-2xl md:text-3xl font-black text-center mb-10" style="color:#003087;">Messages that bring guests back</h2>
    <div class="grid sm:grid-cols-2 gap-5">
      @foreach ([
        'Your table for 4 is confirmed for tonight 8 PM. See you soon! — Spice Garden',
        'Weekend treat 🍔 Buy 1 Get 1 on all burgers, Fri–Sat only. Show this SMS!',
        'Happy Birthday, Nadia! 🎂 Enjoy a free dessert on us this week. — Spice Garden',
        'Your order is ready for pickup at counter 2. Bon appétit!',
      ] as $m)
      <div class="bg-white border border-slate-100 rounded-2xl p-5 flex gap-3"><i class="fas fa-comment-sms mt-1" style="color:#009cde;"></i><p class="text-slate-700 text-sm italic">{{ $m }}</p></div>
      @endforeach
    </div>
  </div>
</section>

<section class="py-16 px-4 bg-white"><div class="max-w-6xl mx-auto"><h2 class="text-2xl font-black mb-8 text-center" style="color:#003087;">Recommended solutions</h2>
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
    @foreach ([
      ['/bulk-sms','fa-paper-plane','Bulk SMS','Promo & offer campaigns.'],
      ['/masking-sms','fa-id-badge','Masking SMS','Send from your restaurant name.'],
      ['/transactional-sms','fa-bell','Transactional SMS','Reservation & order-ready alerts.'],
      ['/calculator','fa-calculator','Price Calculator','Estimate your campaign cost.'],
    ] as $r)
    <a href="{{ $r[0] }}" class="feat-card block bg-slate-50/70 border border-slate-100 rounded-2xl p-6 hover:border-blue-200"><i class="fas {{ $r[1] }} text-xl mb-3" style="color:#009cde;"></i><h3 class="font-bold text-slate-800 mb-1">{{ $r[2] }}</h3><p class="text-slate-500 text-sm">{{ $r[3] }}</p></a>
    @endforeach
  </div>
</div></section>

@include('partials.faq', ['faqs' => [
  ['q' => 'How can SMS help my restaurant get repeat customers?', 'a' => 'Collect guest numbers and send loyalty rewards, birthday treats and weekend offers. Timely, personal SMS keeps your restaurant top-of-mind and drives repeat visits.'],
  ['q' => 'Can I send offers from my restaurant’s name?', 'a' => 'Yes — with <a href="/masking-sms" style="color:#009cde;font-weight:600;">masking SMS</a> your restaurant name appears as the sender, making promotions instantly recognisable.'],
  ['q' => 'Is bulk SMS affordable for a single outlet?', 'a' => 'Yes. Rates start from ৳0.26/SMS with no setup fee, so even small restaurants can run effective campaigns. Try the <a href="/calculator" style="color:#009cde;font-weight:600;">calculator</a>.'],
  ['q' => 'Can I confirm reservations automatically?', 'a' => 'Yes, using <a href="/transactional-sms" style="color:#009cde;font-weight:600;">transactional SMS</a> you can auto-send booking confirmations and reminders.'],
]])

@include('partials.cta', ['ctaHeading' => 'Fill more tables with SMS', 'ctaSub' => 'Send offers, confirmations and loyalty rewards that bring diners back. Get started with NotifySMS today.'])
@endsection
