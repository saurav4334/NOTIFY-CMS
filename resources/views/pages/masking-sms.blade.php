@extends('layouts.marketing')

@section('content')
@include('partials.breadcrumbs')

{{-- ───── HERO ───── --}}
<section class="gradient-hero pt-6 pb-20 px-4">
  <div class="max-w-5xl mx-auto text-center">
    <span class="inline-flex items-center gap-2 bg-white border border-blue-100 shadow-sm text-ppnavy text-xs font-bold px-4 py-2 rounded-full mb-6"><i class="fas fa-id-badge" style="color:#009cde;"></i> MASKING SMS · YOUR BRAND AS SENDER ID</span>
    <h1 class="text-4xl md:text-6xl font-black leading-tight mb-6" style="color:#003087;"><span class="gradient-text">Masking SMS</span> — send from your brand name</h1>
    <p class="text-lg md:text-xl text-slate-600 max-w-3xl mx-auto mb-9">Replace anonymous numbers with your business name as the SMS sender ID. Masking (branded) SMS builds instant trust, lifts open rates and makes every promotional or transactional message unmistakably yours.</p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
      <a href="/contact" class="font-bold px-8 py-4 rounded-2xl text-base hover:opacity-90" style="background:#ffc439;color:#003087;box-shadow:0 8px 28px rgba(255,196,57,.4);"><i class="fas fa-id-badge mr-2"></i>Register a Sender ID</a>
      <a href="/pricing" class="font-semibold px-8 py-4 rounded-2xl text-base border-2 hover:bg-blue-50" style="border-color:#003087;color:#003087;"><i class="fas fa-tags mr-2"></i>See Masking Rates</a>
    </div>
    <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm font-semibold text-slate-500">
      <span><i class="fas fa-circle-check mr-1.5" style="color:#10b981;"></i>BTRC-approved sender IDs</span>
      <span><i class="fas fa-circle-check mr-1.5" style="color:#10b981;"></i>Higher trust & open rates</span>
      <span><i class="fas fa-circle-check mr-1.5" style="color:#10b981;"></i>All 4 operators</span>
    </div>
  </div>
</section>

{{-- ───── MASKING VS NON-MASKING ───── --}}
<section class="py-20 px-4 bg-white">
  <div class="max-w-5xl mx-auto">
    <h2 class="text-3xl md:text-4xl font-black text-center mb-12" style="color:#003087;">Masking vs non-masking SMS</h2>
    <div class="grid md:grid-cols-2 gap-6">
      <div class="rounded-2xl border-2 p-8" style="border-color:#003087;background:#f5f8ff;">
        <div class="flex items-center gap-3 mb-4"><i class="fas fa-id-badge text-2xl" style="color:#003087;"></i><h3 class="font-black text-xl text-slate-800">Masking (Branded)</h3></div>
        <p class="text-slate-600 text-sm mb-5">Sender shows your brand name, e.g. <span class="font-mono bg-white px-2 py-0.5 rounded border">NotifySMS</span>. Ideal for marketing, OTP and customer-facing alerts where trust matters.</p>
        <ul class="space-y-2.5 text-sm text-slate-700">
          <li><i class="fas fa-circle-check mr-2" style="color:#10b981;"></i>Instantly recognisable</li>
          <li><i class="fas fa-circle-check mr-2" style="color:#10b981;"></i>Higher open & response rates</li>
          <li><i class="fas fa-circle-check mr-2" style="color:#10b981;"></i>Looks professional & legitimate</li>
        </ul>
      </div>
      <div class="rounded-2xl border border-slate-200 p-8 bg-slate-50/60">
        <div class="flex items-center gap-3 mb-4"><i class="fas fa-hashtag text-2xl text-slate-400"></i><h3 class="font-black text-xl text-slate-800">Non-Masking (Numeric)</h3></div>
        <p class="text-slate-600 text-sm mb-5">Sender shows a numeric short/long code. The most economical option — great for high-volume, cost-sensitive campaigns.</p>
        <ul class="space-y-2.5 text-sm text-slate-700">
          <li><i class="fas fa-circle-check mr-2" style="color:#10b981;"></i>Lowest per-SMS cost</li>
          <li><i class="fas fa-circle-check mr-2" style="color:#10b981;"></i>Best for bulk promo blasts</li>
          <li><i class="fas fa-circle-check mr-2" style="color:#10b981;"></i>Two-way replies supported</li>
        </ul>
      </div>
    </div>
    <p class="text-center text-slate-500 text-sm mt-8">Not sure which to pick? <a href="/calculator" class="font-semibold" style="color:#009cde;">Compare costs in the calculator →</a></p>
  </div>
</section>

{{-- ───── HOW TO GET ───── --}}
<section class="py-20 px-4" style="background:#f5f8ff;">
  <div class="max-w-5xl mx-auto">
    <h2 class="text-3xl md:text-4xl font-black text-center mb-14" style="color:#003087;">Get your branded sender ID in 3 steps</h2>
    <div class="grid md:grid-cols-3 gap-8">
      @foreach ([
        ['1','Submit your brand','Tell us the sender ID (brand name) you want, with supporting business documents.'],
        ['2','We get BTRC approval','Our team handles operator and BTRC registration so your masking ID is fully compliant.'],
        ['3','Start sending','Once approved, select your branded sender ID for any campaign, OTP or alert.'],
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

@include('partials.faq', ['faqs' => [
  ['q' => 'What is masking SMS?', 'a' => 'Masking SMS replaces the numeric sender with your brand name (for example "NotifySMS") as the sender ID, so recipients immediately see who the message is from.'],
  ['q' => 'How do I register a masking sender ID in Bangladesh?', 'a' => 'Submit your desired brand name and business documents to NotifySMS. We handle operator and BTRC approval on your behalf, which usually takes a few working days.'],
  ['q' => 'Is masking SMS more expensive than non-masking?', 'a' => 'Masking SMS is priced slightly higher than numeric non-masking SMS because of the operator routing involved. You can compare exact slab rates in our <a href="/pricing" style="color:#009cde;font-weight:600;">pricing</a> or <a href="/calculator" style="color:#009cde;font-weight:600;">calculator</a>.'],
  ['q' => 'Can I use masking SMS for OTP?', 'a' => 'Yes. Branded OTP messages increase trust and reduce phishing confusion. See our <a href="/otp-sms" style="color:#009cde;font-weight:600;">OTP SMS</a> solution.'],
  ['q' => 'Which operators support masking SMS?', 'a' => 'NotifySMS delivers masking SMS across all four operators in Bangladesh — Grameenphone, Robi, Banglalink and Teletalk.'],
]])

@include('partials.cta', ['ctaHeading' => 'Make every SMS unmistakably yours', 'ctaSub' => 'Register your branded sender ID with NotifySMS and start sending masking SMS that customers trust.'])
@endsection
