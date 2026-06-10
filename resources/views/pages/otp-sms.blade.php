@extends('layouts.marketing')

@section('content')
@include('partials.breadcrumbs')

{{-- ───── HERO ───── --}}
<section class="gradient-hero pt-6 pb-20 px-4">
  <div class="max-w-5xl mx-auto text-center">
    <span class="inline-flex items-center gap-2 bg-white border border-blue-100 shadow-sm text-ppnavy text-xs font-bold px-4 py-2 rounded-full mb-6"><i class="fas fa-key" style="color:#009cde;"></i> OTP SMS · DELIVERED IN UNDER 3 SECONDS</span>
    <h1 class="text-4xl md:text-6xl font-black leading-tight mb-6" style="color:#003087;"><span class="gradient-text">OTP SMS</span> that lands instantly, every time</h1>
    <p class="text-lg md:text-xl text-slate-600 max-w-3xl mx-auto mb-9">Verify users, secure logins and protect transactions with high-priority one-time-password SMS. NotifySMS routes OTP traffic on a dedicated path with operator failover, so codes reach GP, Robi, Banglalink and Teletalk users in seconds.</p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
      <a href="/contact" class="font-bold px-8 py-4 rounded-2xl text-base hover:opacity-90" style="background:#ffc439;color:#003087;box-shadow:0 8px 28px rgba(255,196,57,.4);"><i class="fas fa-shield-halved mr-2"></i>Start Sending OTPs</a>
      <a href="/sms-api" class="font-semibold px-8 py-4 rounded-2xl text-base border-2 hover:bg-blue-50" style="border-color:#003087;color:#003087;"><i class="fas fa-code mr-2"></i>See the API</a>
    </div>
    <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm font-semibold text-slate-500">
      <span><i class="fas fa-circle-check mr-1.5" style="color:#10b981;"></i>&lt;3s delivery</span>
      <span><i class="fas fa-circle-check mr-1.5" style="color:#10b981;"></i>Priority routing</span>
      <span><i class="fas fa-circle-check mr-1.5" style="color:#10b981;"></i>24×7 reliability</span>
    </div>
  </div>
</section>

{{-- ───── WHY OTP ───── --}}
<section class="py-20 px-4 bg-white">
  <div class="max-w-6xl mx-auto">
    <div class="text-center mb-14">
      <h2 class="text-3xl md:text-4xl font-black mb-3" style="color:#003087;">Why businesses trust NotifySMS for OTP</h2>
      <p class="text-slate-500 max-w-2xl mx-auto">From fintech logins to e-commerce checkouts, fast and reliable verification is non-negotiable.</p>
    </div>
    <div class="grid md:grid-cols-3 gap-6">
      @foreach ([
        ['fa-bolt','#0ea5e9','Sub-3-second delivery','Dedicated high-priority routes mean verification codes arrive almost instantly — no abandoned signups.'],
        ['fa-arrows-split-up-and-left','#7c3aed','Operator failover','If one route slows down, traffic reroutes automatically across all four operators to protect delivery.'],
        ['fa-code','#059669','Simple API','Trigger an OTP with a single API call and verify the response in your app. SDK examples included.'],
        ['fa-chart-simple','#f97316','Delivery analytics','See success rates, latency and operator-wise performance for every OTP you send.'],
        ['fa-id-badge','#003087','Branded sender','Send OTPs from your brand name (masking) so users instantly recognise and trust the message.'],
        ['fa-lock','#10b981','Secure & compliant','BTRC-licensed, TLS-encrypted delivery with full audit logs for security and compliance teams.'],
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

{{-- ───── USE CASES ───── --}}
<section class="py-20 px-4" style="background:#f5f8ff;">
  <div class="max-w-5xl mx-auto">
    <h2 class="text-3xl md:text-4xl font-black text-center mb-12" style="color:#003087;">Where OTP SMS makes the difference</h2>
    <div class="grid sm:grid-cols-2 gap-6">
      @foreach ([
        ['fa-right-to-bracket','Login & 2FA','Add a second factor to logins and protect accounts from unauthorised access.'],
        ['fa-user-plus','Signup verification','Confirm real phone numbers at registration and cut fake or duplicate accounts.'],
        ['fa-cart-shopping','Checkout & COD','Verify cash-on-delivery orders to reduce fake orders and failed deliveries.'],
        ['fa-money-bill-transfer','Transaction approval','Authorise payments, transfers and password resets with a one-time code.'],
      ] as $u)
      <div class="bg-white border border-slate-100 rounded-2xl p-6 flex gap-4">
        <div class="w-11 h-11 shrink-0 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#003087,#009cde);"><i class="fas {{ $u[0] }} text-white"></i></div>
        <div><h3 class="font-bold text-slate-800 mb-1">{{ $u[1] }}</h3><p class="text-slate-600 text-sm">{{ $u[2] }}</p></div>
      </div>
      @endforeach
    </div>
  </div>
</section>

@include('partials.faq', ['faqs' => [
  ['q' => 'How fast are OTP SMS delivered?', 'a' => 'OTP messages are sent on a dedicated high-priority route and typically reach the recipient in under three seconds across all four Bangladeshi operators.'],
  ['q' => 'Can I send OTPs from my brand name?', 'a' => 'Yes. With <a href="/masking-sms" style="color:#009cde;font-weight:600;">masking SMS</a> your OTP appears from your brand name as the sender ID, which increases user trust and recognition.'],
  ['q' => 'How do I integrate OTP SMS into my app?', 'a' => 'Use our REST <a href="/sms-api" style="color:#009cde;font-weight:600;">SMS API</a> to trigger a code with a single request, then verify it in your backend. Full examples are in the <a href="/docs/api" style="color:#009cde;font-weight:600;">API docs</a>.'],
  ['q' => 'What happens if an OTP fails to deliver?', 'a' => 'Our system automatically fails over between operator routes to maximise delivery, and every attempt is logged with a status you can read via the API or dashboard.'],
  ['q' => 'Is OTP SMS suitable for banking and fintech?', 'a' => 'Yes — see our <a href="/otp-sms-for-banking" style="color:#009cde;font-weight:600;">OTP SMS for banking</a> solution for bank-grade reliability, security and compliance.'],
]])

@include('partials.cta', ['ctaHeading' => 'Secure every login with instant OTP', 'ctaSub' => 'Add fast, reliable phone verification to your product. Set up in minutes with our API or dashboard.'])
@endsection
