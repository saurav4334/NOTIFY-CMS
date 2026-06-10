@extends('layouts.marketing')

@section('content')
@include('partials.breadcrumbs')

<section class="gradient-hero pt-6 pb-20 px-4">
  <div class="max-w-5xl mx-auto text-center">
    <span class="inline-flex items-center gap-2 bg-white border border-blue-100 shadow-sm text-ppnavy text-xs font-bold px-4 py-2 rounded-full mb-6"><i class="fas fa-building-columns" style="color:#009cde;"></i> OTP SMS FOR BANKING & FINTECH</span>
    <h1 class="text-4xl md:text-6xl font-black leading-tight mb-6" style="color:#003087;"><span class="gradient-text">OTP SMS</span> for Banking & Fintech</h1>
    <p class="text-lg md:text-xl text-slate-600 max-w-3xl mx-auto mb-9">Protect every login and transaction with bank-grade one-time-password SMS. Sub-3-second delivery, dedicated high-priority routing and BTRC-compliant, encrypted infrastructure trusted by Bangladeshi banks, MFS and fintech apps.</p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
      <a href="/contact" class="font-bold px-8 py-4 rounded-2xl text-base hover:opacity-90" style="background:#ffc439;color:#003087;box-shadow:0 8px 28px rgba(255,196,57,.4);"><i class="fas fa-shield-halved mr-2"></i>Talk to Sales</a>
      <a href="/sms-api" class="font-semibold px-8 py-4 rounded-2xl text-base border-2 hover:bg-blue-50" style="border-color:#003087;color:#003087;"><i class="fas fa-code mr-2"></i>View the API</a>
    </div>
    <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm font-semibold text-slate-500">
      <span><i class="fas fa-circle-check mr-1.5" style="color:#10b981;"></i>&lt;3s delivery</span>
      <span><i class="fas fa-circle-check mr-1.5" style="color:#10b981;"></i>Dedicated routes</span>
      <span><i class="fas fa-circle-check mr-1.5" style="color:#10b981;"></i>BTRC compliant</span>
    </div>
  </div>
</section>

<section class="py-20 px-4 bg-white">
  <div class="max-w-6xl mx-auto">
    <div class="text-center mb-14"><h2 class="text-3xl md:text-4xl font-black mb-3" style="color:#003087;">Built for financial-grade reliability</h2><p class="text-slate-500 max-w-2xl mx-auto">When money is involved, delivery speed and security are everything.</p></div>
    <div class="grid md:grid-cols-3 gap-6">
      @foreach ([
        ['fa-bolt','#0ea5e9','Sub-3-second OTP','Dedicated high-priority routing ensures verification codes arrive instantly, every time.'],
        ['fa-arrows-split-up-and-left','#7c3aed','Operator failover','Automatic rerouting across all four operators protects delivery during peak load.'],
        ['fa-lock','#10b981','Encrypted & compliant','TLS-encrypted delivery, BTRC-licensed routes and full audit logs for regulators.'],
        ['fa-money-bill-transfer','#003087','Transaction alerts','Notify customers of debits, credits and transfers the instant they happen.'],
        ['fa-id-badge','#f97316','Branded sender','Send OTPs and alerts from your bank or app name to fight phishing and build trust.'],
        ['fa-gauge-high','#059669','Scale & uptime','99.9% uptime and high-throughput infrastructure for millions of monthly OTPs.'],
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
    <h2 class="text-2xl md:text-3xl font-black text-center mb-10" style="color:#003087;">Where banks & fintechs use OTP SMS</h2>
    <div class="grid sm:grid-cols-2 gap-5">
      @foreach ([
        ['fa-right-to-bracket','Secure login & 2FA','Add a phone-based second factor to internet and mobile banking.'],
        ['fa-money-check-dollar','Transaction authorisation','Approve transfers, payments and high-value actions with a one-time code.'],
        ['fa-user-shield','KYC & onboarding','Verify customer phone numbers during digital account opening.'],
        ['fa-key','Password & PIN reset','Let customers reset credentials safely with time-limited codes.'],
      ] as $u)
      <div class="bg-white border border-slate-100 rounded-2xl p-6 flex gap-4">
        <div class="w-11 h-11 shrink-0 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#003087,#009cde);"><i class="fas {{ $u[0] }} text-white"></i></div>
        <div><h3 class="font-bold text-slate-800 mb-1">{{ $u[1] }}</h3><p class="text-slate-600 text-sm">{{ $u[2] }}</p></div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<section class="py-16 px-4 bg-white"><div class="max-w-6xl mx-auto"><h2 class="text-2xl font-black mb-8 text-center" style="color:#003087;">Recommended solutions</h2>
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
    @foreach ([
      ['/otp-sms','fa-key','OTP SMS','Core one-time-password delivery.'],
      ['/sms-api','fa-code','SMS API','Integrate OTP into your apps.'],
      ['/transactional-sms','fa-bell','Transactional SMS','Debit, credit & transfer alerts.'],
      ['/masking-sms','fa-id-badge','Masking SMS','Send from your bank/app name.'],
    ] as $r)
    <a href="{{ $r[0] }}" class="feat-card block bg-slate-50/70 border border-slate-100 rounded-2xl p-6 hover:border-blue-200"><i class="fas {{ $r[1] }} text-xl mb-3" style="color:#009cde;"></i><h3 class="font-bold text-slate-800 mb-1">{{ $r[2] }}</h3><p class="text-slate-500 text-sm">{{ $r[3] }}</p></a>
    @endforeach
  </div>
</div></section>

@include('partials.faq', ['faqs' => [
  ['q' => 'How fast is OTP delivery for banking apps?', 'a' => 'OTPs are sent on a dedicated high-priority route and typically arrive in under three seconds, with automatic operator failover to protect delivery during peak times.'],
  ['q' => 'Is the service secure and compliant for financial use?', 'a' => 'Yes. Delivery is over TLS-encrypted, BTRC-licensed routes with full audit logging, suitable for banks, MFS providers and regulated fintechs.'],
  ['q' => 'Can we send OTPs from our bank or app name?', 'a' => 'Yes, using <a href="/masking-sms" style="color:#009cde;font-weight:600;">masking SMS</a> your branded sender ID appears on every OTP, which helps customers spot phishing.'],
  ['q' => 'Can it handle millions of OTPs per month?', 'a' => 'Yes. Our high-throughput infrastructure delivers 99.9% uptime at scale. Integrate via the <a href="/sms-api" style="color:#009cde;font-weight:600;">SMS API</a> and contact us for enterprise throughput.'],
]])

@include('partials.cta', ['ctaHeading' => 'Secure every transaction with bank-grade OTP', 'ctaSub' => 'Trusted by Bangladeshi banks and fintechs for fast, compliant OTP delivery. Talk to our team about your volume.'])
@endsection
