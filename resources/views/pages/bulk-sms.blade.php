@extends('layouts.marketing')

@section('content')
@include('partials.breadcrumbs')

{{-- ───── HERO ───── --}}
<section class="gradient-hero pt-6 pb-20 px-4">
  <div class="max-w-5xl mx-auto text-center">
    <span class="inline-flex items-center gap-2 bg-white border border-blue-100 shadow-sm text-ppnavy text-xs font-bold px-4 py-2 rounded-full mb-6"><i class="fas fa-paper-plane" style="color:#009cde;"></i> BULK SMS BANGLADESH · FROM ৳0.26/SMS</span>
    <h1 class="text-4xl md:text-6xl font-black leading-tight mb-6" style="color:#003087;">Send <span class="gradient-text">Bulk SMS</span> across Bangladesh in seconds</h1>
    <p class="text-lg md:text-xl text-slate-600 max-w-3xl mx-auto mb-9">Reach customers on Grameenphone, Robi, Banglalink & Teletalk from one dashboard. Upload your list, write your message, and deliver thousands of SMS with real-time delivery reports — no technical setup required.</p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
      <a href="/contact" class="font-bold px-8 py-4 rounded-2xl text-base hover:opacity-90" style="background:#ffc439;color:#003087;box-shadow:0 8px 28px rgba(255,196,57,.4);"><i class="fas fa-rocket mr-2"></i>Get Started Free</a>
      <a href="/calculator" class="font-semibold px-8 py-4 rounded-2xl text-base border-2 hover:bg-blue-50" style="border-color:#003087;color:#003087;"><i class="fas fa-calculator mr-2"></i>Calculate Your Cost</a>
    </div>
    <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm font-semibold text-slate-500">
      <span><i class="fas fa-circle-check mr-1.5" style="color:#10b981;"></i>All 4 operators</span>
      <span><i class="fas fa-circle-check mr-1.5" style="color:#10b981;"></i>BTRC certified</span>
      <span><i class="fas fa-circle-check mr-1.5" style="color:#10b981;"></i>99.9% uptime</span>
      <span><i class="fas fa-circle-check mr-1.5" style="color:#10b981;"></i>1,000 SMS in &lt;3s</span>
    </div>
  </div>
</section>

{{-- ───── FEATURES ───── --}}
<section class="py-20 px-4 bg-white">
  <div class="max-w-6xl mx-auto">
    <div class="text-center mb-14">
      <h2 class="text-3xl md:text-4xl font-black mb-3" style="color:#003087;">Everything you need to run SMS campaigns</h2>
      <p class="text-slate-500 max-w-2xl mx-auto">A complete bulk SMS toolkit built for Bangladeshi marketers, agencies and enterprises.</p>
    </div>
    <div class="grid md:grid-cols-3 gap-6">
      @foreach ([
        ['fa-layer-group','#003087','Smart Campaigns','Schedule campaigns, throttle send-speed and run A/B tests. Personalise each message with name, balance or due-date fields.'],
        ['fa-location-dot','#059669','Location Targeting','Target by division, district, or a 500m–50km radius so your offer reaches the right neighbourhood.'],
        ['fa-chart-line','#7c3aed','Real-Time Reports','Live delivery status per number, operator-wise breakdown, and downloadable CSV reports for every campaign.'],
        ['fa-address-book','#f97316','Contact Manager','Import Excel/CSV lists, auto-deduplicate, build segments and manage opt-outs to keep your lists clean.'],
        ['fa-clock','#0ea5e9','Scheduling','Queue messages for the perfect time — Eid offers, Friday reminders or off-peak rates — down to the minute.'],
        ['fa-shield-halved','#10b981','Compliant & Secure','BTRC-licensed routes, DND handling and TLS-encrypted delivery keep your campaigns safe and legal.'],
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

{{-- ───── HOW IT WORKS ───── --}}
<section class="py-20 px-4" style="background:#f5f8ff;">
  <div class="max-w-5xl mx-auto">
    <h2 class="text-3xl md:text-4xl font-black text-center mb-14" style="color:#003087;">Send your first campaign in 3 steps</h2>
    <div class="grid md:grid-cols-3 gap-8">
      @foreach ([
        ['1','Upload contacts','Import your customer list from Excel or CSV, or paste numbers directly. We clean and validate every number.'],
        ['2','Write your message','Compose in English or Bangla, add personalisation fields, and pick masking (brand name) or non-masking sender.'],
        ['3','Send & track','Send instantly or schedule it. Watch live delivery reports roll in across all four operators.'],
      ] as $s)
      <div class="text-center">
        <div class="w-14 h-14 mx-auto rounded-2xl flex items-center justify-center text-white font-black text-xl mb-5" style="background:linear-gradient(135deg,#003087,#009cde);">{{ $s[0] }}</div>
        <h3 class="font-bold text-lg mb-2 text-slate-800">{{ $s[1] }}</h3>
        <p class="text-slate-600 text-sm leading-relaxed">{{ $s[2] }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ───── RELATED ───── --}}
<section class="py-16 px-4 bg-white">
  <div class="max-w-6xl mx-auto">
    <h2 class="text-2xl font-black mb-8 text-center" style="color:#003087;">Explore related SMS solutions</h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
      @foreach ([
        ['/masking-sms','fa-id-badge','Masking SMS','Send with your brand name as sender ID.'],
        ['/otp-sms','fa-key','OTP SMS','Sub-3-second one-time passwords & 2FA.'],
        ['/transactional-sms','fa-bell','Transactional SMS','Order, payment & account alerts 24×7.'],
        ['/sms-api','fa-code','SMS API','Automate sending from your own app.'],
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
  ['q' => 'What is bulk SMS and how does it work in Bangladesh?', 'a' => 'Bulk SMS lets you send the same message to thousands of recipients at once through a licensed gateway. With NotifySMS you upload your contact list, compose your message, and our platform routes it to Grameenphone, Robi, Banglalink and Teletalk with live delivery tracking.'],
  ['q' => 'How much does bulk SMS cost?', 'a' => 'Pricing is slab-based and starts from ৳0.26 per SMS (non-masking), with masking SMS priced slightly higher. All rates include applicable VAT. Use our <a href="/calculator" style="color:#009cde;font-weight:600;">SMS price calculator</a> for an instant quote.'],
  ['q' => 'Can I send SMS in Bangla?', 'a' => 'Yes. NotifySMS fully supports Unicode Bangla as well as English. Note that Unicode messages have a 70-character segment limit versus 160 for English (GSM) text.'],
  ['q' => 'Do you provide delivery reports?', 'a' => 'Every campaign includes real-time, per-number delivery status with an operator-wise breakdown, and you can export the full report as CSV at any time.'],
  ['q' => 'Is bulk SMS marketing legal in Bangladesh?', 'a' => 'Yes, when sent through a BTRC-licensed provider like NotifySMS and in line with DND (Do-Not-Disturb) rules for promotional content. We handle compliance and operator approvals for you.'],
]])

@include('partials.cta', ['ctaHeading' => 'Launch your first bulk SMS campaign today'])
@endsection
