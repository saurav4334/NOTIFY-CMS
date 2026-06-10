@extends('layouts.marketing')

@section('content')
@include('partials.breadcrumbs')

{{-- ───── HERO ───── --}}
<section class="gradient-hero pt-6 pb-20 px-4">
  <div class="max-w-5xl mx-auto text-center">
    <span class="inline-flex items-center gap-2 bg-white border border-blue-100 shadow-sm text-ppnavy text-xs font-bold px-4 py-2 rounded-full mb-6"><i class="fas fa-bell" style="color:#009cde;"></i> TRANSACTIONAL SMS · 24×7 PRIORITY DELIVERY</span>
    <h1 class="text-4xl md:text-6xl font-black leading-tight mb-6" style="color:#003087;"><span class="gradient-text">Transactional SMS</span> your customers rely on</h1>
    <p class="text-lg md:text-xl text-slate-600 max-w-3xl mx-auto mb-9">Deliver order confirmations, payment receipts, account alerts and delivery updates the moment they happen. Transactional SMS is sent on priority routes 24×7 — including weekends and holidays — so critical messages always get through.</p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
      <a href="/contact" class="font-bold px-8 py-4 rounded-2xl text-base hover:opacity-90" style="background:#ffc439;color:#003087;box-shadow:0 8px 28px rgba(255,196,57,.4);"><i class="fas fa-bell mr-2"></i>Start Sending Alerts</a>
      <a href="/sms-api" class="font-semibold px-8 py-4 rounded-2xl text-base border-2 hover:bg-blue-50" style="border-color:#003087;color:#003087;"><i class="fas fa-code mr-2"></i>Automate via API</a>
    </div>
    <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm font-semibold text-slate-500">
      <span><i class="fas fa-circle-check mr-1.5" style="color:#10b981;"></i>24×7 delivery</span>
      <span><i class="fas fa-circle-check mr-1.5" style="color:#10b981;"></i>Priority routing</span>
      <span><i class="fas fa-circle-check mr-1.5" style="color:#10b981;"></i>Full delivery logs</span>
    </div>
  </div>
</section>

{{-- ───── MESSAGE EXAMPLES ───── --}}
<section class="py-20 px-4 bg-white">
  <div class="max-w-6xl mx-auto">
    <div class="text-center mb-14">
      <h2 class="text-3xl md:text-4xl font-black mb-3" style="color:#003087;">Alerts for every moment that matters</h2>
      <p class="text-slate-500 max-w-2xl mx-auto">Trigger the right message automatically from your app, ERP or e-commerce platform.</p>
    </div>
    <div class="grid md:grid-cols-3 gap-6">
      @foreach ([
        ['fa-box','#003087','Order confirmations','“Your order #1042 is confirmed and will be delivered by tomorrow. Track: …”'],
        ['fa-money-check-dollar','#059669','Payment receipts','“We received your payment of ৳2,500. Thank you for shopping with us.”'],
        ['fa-truck-fast','#0ea5e9','Delivery updates','“Your parcel is out for delivery and will arrive within 2 hours.”'],
        ['fa-right-to-bracket','#7c3aed','Account alerts','“A new login to your account was detected. If this wasn’t you, contact support.”'],
        ['fa-calendar-check','#f97316','Appointment reminders','“Reminder: your appointment is scheduled for tomorrow at 11:00 AM.”'],
        ['fa-triangle-exclamation','#ef4444','Service notices','“Scheduled maintenance tonight 12–2 AM. Services may be briefly unavailable.”'],
      ] as $f)
      <div class="feat-card bg-slate-50/70 border border-slate-100 rounded-2xl p-7">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5" style="background:{{ $f[1] }};"><i class="fas {{ $f[0] }} text-white"></i></div>
        <h3 class="font-bold text-lg mb-2 text-slate-800">{{ $f[2] }}</h3>
        <p class="text-slate-600 text-sm leading-relaxed italic">{{ $f[3] }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ───── WHY ───── --}}
<section class="py-20 px-4" style="background:#f5f8ff;">
  <div class="max-w-5xl mx-auto">
    <h2 class="text-3xl md:text-4xl font-black text-center mb-12" style="color:#003087;">Why transactional SMS works</h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 text-center">
      @foreach ([
        ['98%','Average SMS open rate'],
        ['<3s','Typical delivery time'],
        ['24×7','Priority delivery window'],
        ['4','Operators covered'],
      ] as $stat)
      <div class="bg-white border border-slate-100 rounded-2xl p-7">
        <div class="text-3xl font-black gradient-text mb-1">{{ $stat[0] }}</div>
        <p class="text-slate-500 text-sm font-medium">{{ $stat[1] }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ───── RELATED ───── --}}
<section class="py-16 px-4 bg-white">
  <div class="max-w-6xl mx-auto">
    <h2 class="text-2xl font-black mb-8 text-center" style="color:#003087;">Pair it with</h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
      @foreach ([
        ['/sms-api','fa-code','SMS API','Fire alerts automatically from your system.'],
        ['/masking-sms','fa-id-badge','Masking SMS','Send alerts from your brand name.'],
        ['/otp-sms','fa-key','OTP SMS','Add verification to sensitive actions.'],
        ['/bulk-sms-for-ecommerce','fa-cart-shopping','SMS for E-commerce','Order & delivery flows for online stores.'],
      ] as $r)
      <a href="{{ $r[0] }}" class="feat-card block bg-slate-50/70 border border-slate-100 rounded-2xl p-6 hover:border-blue-200">
        <i class="fas {{ $r[1] }} text-xl mb-3" style="color:#009cde;"></i>
        <h3 class="font-bold text-slate-800 mb-1">{{ $r[2] }}</h3>
        <p class="text-slate-500 text-sm">{{ $r[3] }}</p>
      </a>
      @endforeach
    </div>
  </div>
</section>

@include('partials.faq', ['faqs' => [
  ['q' => 'What is transactional SMS?', 'a' => 'Transactional SMS delivers non-promotional, information-critical messages — order confirmations, payment receipts, OTPs and account alerts — triggered by a user action or event.'],
  ['q' => 'Can transactional SMS be sent 24×7?', 'a' => 'Yes. Unlike promotional SMS, transactional messages are delivered around the clock, including nights, weekends and public holidays, on priority routes.'],
  ['q' => 'How do I automate transactional SMS?', 'a' => 'Connect your application, ERP or e-commerce store to our <a href="/sms-api" style="color:#009cde;font-weight:600;">SMS API</a> and trigger messages on events like order placed or payment received.'],
  ['q' => 'Can I send transactional SMS from my brand name?', 'a' => 'Yes, using <a href="/masking-sms" style="color:#009cde;font-weight:600;">masking SMS</a> your alerts appear from your branded sender ID for instant recognition.'],
  ['q' => 'Do I get delivery confirmation?', 'a' => 'Every transactional message is logged with a delivery status you can view in the dashboard or retrieve via the API and webhooks.'],
]])

@include('partials.cta', ['ctaHeading' => 'Keep customers informed in real time', 'ctaSub' => 'Automate order, payment and account notifications with reliable 24×7 transactional SMS from NotifySMS.'])
@endsection
