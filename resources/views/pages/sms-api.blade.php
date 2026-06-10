@extends('layouts.marketing')

@section('content')
@include('partials.breadcrumbs')

{{-- ───── HERO ───── --}}
<section class="gradient-hero pt-6 pb-20 px-4">
  <div class="max-w-5xl mx-auto text-center">
    <span class="inline-flex items-center gap-2 bg-white border border-blue-100 shadow-sm text-ppnavy text-xs font-bold px-4 py-2 rounded-full mb-6"><i class="fas fa-code" style="color:#009cde;"></i> REST SMS GATEWAY API · 99.9% UPTIME</span>
    <h1 class="text-4xl md:text-6xl font-black leading-tight mb-6" style="color:#003087;">The <span class="gradient-text">SMS API</span> built for Bangladeshi developers</h1>
    <p class="text-lg md:text-xl text-slate-600 max-w-3xl mx-auto mb-9">Integrate SMS into any application with a clean REST API. Send OTPs, alerts and campaigns from PHP, Python, Node.js or Laravel — with predictable JSON responses, webhooks and delivery callbacks.</p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
      <a href="/docs/api" class="font-bold px-8 py-4 rounded-2xl text-base hover:opacity-90" style="background:#ffc439;color:#003087;box-shadow:0 8px 28px rgba(255,196,57,.4);"><i class="fas fa-book mr-2"></i>Read API Docs</a>
      <a href="/contact" class="font-semibold px-8 py-4 rounded-2xl text-base border-2 hover:bg-blue-50" style="border-color:#003087;color:#003087;"><i class="fas fa-key mr-2"></i>Get an API Key</a>
    </div>
    <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm font-semibold text-slate-500">
      <span><i class="fas fa-circle-check mr-1.5" style="color:#10b981;"></i>REST + JSON</span>
      <span><i class="fas fa-circle-check mr-1.5" style="color:#10b981;"></i>Delivery webhooks</span>
      <span><i class="fas fa-circle-check mr-1.5" style="color:#10b981;"></i>5-minute setup</span>
    </div>
  </div>
</section>

{{-- ───── CODE SAMPLE ───── --}}
<section class="py-20 px-4 bg-white">
  <div class="max-w-5xl mx-auto grid lg:grid-cols-2 gap-10 items-center">
    <div>
      <h2 class="text-3xl md:text-4xl font-black mb-4" style="color:#003087;">Send your first SMS with one request</h2>
      <p class="text-slate-600 mb-6 leading-relaxed">Authenticate with your API key and POST a JSON payload. You get back a message ID you can track via the delivery-report endpoint or a webhook. No SDK lock-in — it is plain HTTP.</p>
      <ul class="space-y-3 text-sm text-slate-700">
        <li><i class="fas fa-circle-check mr-2" style="color:#10b981;"></i>Single, bulk and personalised sends</li>
        <li><i class="fas fa-circle-check mr-2" style="color:#10b981;"></i>Masking & non-masking sender IDs</li>
        <li><i class="fas fa-circle-check mr-2" style="color:#10b981;"></i>Balance check & delivery reports</li>
        <li><i class="fas fa-circle-check mr-2" style="color:#10b981;"></i>Rate-limited, idempotent, predictable errors</li>
      </ul>
    </div>
    <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-lg">
      <div class="bg-slate-800 px-4 py-2.5 flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-red-400"></span><span class="w-3 h-3 rounded-full bg-yellow-400"></span><span class="w-3 h-3 rounded-full bg-green-400"></span><span class="text-slate-400 text-xs ml-2">send-sms.sh</span></div>
<pre class="bg-slate-900 text-slate-100 text-xs leading-relaxed p-5 overflow-x-auto"><code>curl -X POST https://notifybd.com/api/v1/sms/send \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "to": "8801XXXXXXXXX",
    "sender": "NotifySMS",
    "message": "Your OTP is 4821. Valid 5 min."
  }'</code></pre>
    </div>
  </div>
</section>

{{-- ───── FEATURES ───── --}}
<section class="py-20 px-4" style="background:#f5f8ff;">
  <div class="max-w-6xl mx-auto">
    <h2 class="text-3xl md:text-4xl font-black text-center mb-14" style="color:#003087;">Built for production workloads</h2>
    <div class="grid md:grid-cols-3 gap-6">
      @foreach ([
        ['fa-bolt','#0ea5e9','High throughput','Send thousands of messages per minute with auto failover across all four operators for maximum deliverability.'],
        ['fa-rotate','#7c3aed','Delivery webhooks','Receive real-time HTTP callbacks for every status change — sent, delivered, failed — straight to your endpoint.'],
        ['fa-plug','#059669','SDKs & samples','Copy-paste examples for PHP, Laravel, Node.js, Python and more. Integrate in minutes, not days.'],
        ['fa-gauge-high','#f97316','Balance & reports','Programmatically check balance, pull delivery reports and reconcile usage from the API.'],
        ['fa-lock','#10b981','Secure by default','Bearer-token auth over TLS, IP allow-listing and per-key rate limits keep your integration safe.'],
        ['fa-key','#003087','OTP-ready','Dedicated high-priority routing so verification codes land in under 3 seconds, every time.'],
      ] as $f)
      <div class="feat-card bg-white border border-slate-100 rounded-2xl p-7">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5" style="background:{{ $f[1] }};"><i class="fas {{ $f[0] }} text-white"></i></div>
        <h3 class="font-bold text-lg mb-2 text-slate-800">{{ $f[2] }}</h3>
        <p class="text-slate-600 text-sm leading-relaxed">{{ $f[3] }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ───── RELATED ───── --}}
<section class="py-16 px-4 bg-white">
  <div class="max-w-6xl mx-auto">
    <h2 class="text-2xl font-black mb-8 text-center" style="color:#003087;">Popular API use cases</h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
      @foreach ([
        ['/otp-sms','fa-key','OTP & 2FA','Verify logins and signups instantly.'],
        ['/transactional-sms','fa-bell','Transactional alerts','Trigger order & payment notifications.'],
        ['/otp-sms-for-banking','fa-building-columns','Banking OTP','Bank-grade verification at scale.'],
        ['/bulk-sms','fa-paper-plane','Bulk campaigns','Push promo blasts programmatically.'],
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
  ['q' => 'How do I get an SMS API key?', 'a' => 'Create a NotifySMS account and request API access from your dashboard, or <a href="/contact" style="color:#009cde;font-weight:600;">contact our team</a>. You will receive a bearer token you can use immediately in the sandbox and in production.'],
  ['q' => 'Which programming languages are supported?', 'a' => 'Any language that can make an HTTPS request. We provide ready-made examples for PHP, Laravel, Node.js, Python and cURL, and the full reference lives in our <a href="/docs/api" style="color:#009cde;font-weight:600;">API documentation</a>.'],
  ['q' => 'Can I receive delivery reports via the API?', 'a' => 'Yes. Poll the delivery-report endpoint with a message ID, or register a webhook URL to receive real-time status callbacks (sent, delivered, failed) for each message.'],
  ['q' => 'Is the SMS API suitable for sending OTPs?', 'a' => 'Absolutely. OTP traffic is routed on a high-priority path with operator failover so codes are delivered in under three seconds across Bangladesh.'],
  ['q' => 'Are there rate limits?', 'a' => 'Each API key has a generous per-second and per-minute limit to protect deliverability. Enterprise plans can request higher throughput — just reach out.'],
]])

@include('partials.cta', ['ctaHeading' => 'Integrate SMS into your app today', 'ctaSub' => 'Grab an API key and send your first message in five minutes. Full docs, code samples and 24×7 developer support included.'])
@endsection
