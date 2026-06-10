@extends('layouts.marketing')

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'TechArticle',
    'headline' => 'NotifySMS REST API Documentation',
    'description' => $meta['description'] ?? '',
    'about' => 'SMS Gateway API',
    'inLanguage' => 'en',
    'author' => ['@type' => 'Organization', 'name' => 'NotifySMS'],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')
@include('partials.breadcrumbs')

<section class="gradient-hero pt-6 pb-16 px-4">
  <div class="max-w-5xl mx-auto text-center">
    <span class="inline-flex items-center gap-2 bg-white border border-blue-100 shadow-sm text-ppnavy text-xs font-bold px-4 py-2 rounded-full mb-6"><i class="fas fa-book" style="color:#009cde;"></i> API DOCUMENTATION</span>
    <h1 class="text-4xl md:text-5xl font-black leading-tight mb-5" style="color:#003087;">NotifySMS <span class="gradient-text">REST API</span></h1>
    <p class="text-lg text-slate-600 max-w-2xl mx-auto">A simple, predictable HTTPS + JSON API to send SMS, fetch live rates, calculate costs and capture leads. Authenticate with a bearer token and you are ready to go.</p>
  </div>
</section>

<section class="py-14 px-4 bg-white">
  <div class="max-w-5xl mx-auto grid lg:grid-cols-[200px_1fr] gap-10">
    {{-- side nav --}}
    <aside class="hidden lg:block">
      <div class="sticky top-28 text-sm space-y-1">
        <p class="font-bold text-ppnavy uppercase text-xs tracking-widest mb-2">On this page</p>
        @foreach (['base-url'=>'Base URL','auth'=>'Authentication','send-sms'=>'Send SMS','sms-rates'=>'Get SMS rates','calculate'=>'Calculate cost','contact'=>'Submit lead','errors'=>'Errors'] as $id => $label)
          <a href="#{{ $id }}" class="block py-1.5 text-slate-500 hover:text-ppblue">{{ $label }}</a>
        @endforeach
      </div>
    </aside>

    <div class="space-y-12 min-w-0">
      {{-- BASE URL --}}
      <div id="base-url">
        <h2 class="text-2xl font-black mb-3" style="color:#003087;">Base URL</h2>
        <p class="text-slate-600 mb-4">All endpoints are served over HTTPS and return JSON. The public API is versioned under <code class="bg-slate-100 px-1.5 py-0.5 rounded text-sm">/api/v1</code>.</p>
        <pre class="bg-slate-900 text-slate-100 text-sm p-4 rounded-xl overflow-x-auto"><code>https://notifybd.com/api/v1</code></pre>
      </div>

      {{-- AUTH --}}
      <div id="auth">
        <h2 class="text-2xl font-black mb-3" style="color:#003087;">Authentication</h2>
        <p class="text-slate-600 mb-4">Messaging endpoints require an API key issued from your dashboard. Pass it as a bearer token in the <code class="bg-slate-100 px-1.5 py-0.5 rounded text-sm">Authorization</code> header. Read-only public endpoints (rates, calculate, contact) do not require a key.</p>
        <pre class="bg-slate-900 text-slate-100 text-sm p-4 rounded-xl overflow-x-auto"><code>Authorization: Bearer YOUR_API_KEY
Content-Type: application/json</code></pre>
      </div>

      {{-- SEND SMS --}}
      <div id="send-sms">
        <div class="flex items-center gap-3 mb-3"><span class="text-xs font-black text-white px-2.5 py-1 rounded bg-emerald-600">POST</span><h2 class="text-2xl font-black" style="color:#003087;">Send SMS</h2></div>
        <p class="text-slate-600 mb-2"><code class="bg-slate-100 px-1.5 py-0.5 rounded text-sm">/api/v1/sms/send</code> — queue a message to one or many recipients. Requires an API key.</p>
        <p class="font-semibold text-slate-700 mt-5 mb-2 text-sm">Request</p>
        <pre class="bg-slate-900 text-slate-100 text-xs p-4 rounded-xl overflow-x-auto"><code>curl -X POST https://notifybd.com/api/v1/sms/send \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "to": ["8801XXXXXXXXX", "8801YYYYYYYYY"],
    "sender": "NotifySMS",
    "message": "Your OTP is 4821. Valid for 5 minutes.",
    "type": "masking"
  }'</code></pre>
        <p class="font-semibold text-slate-700 mt-5 mb-2 text-sm">Response <span class="text-emerald-600">201 Created</span></p>
        <pre class="bg-slate-900 text-slate-100 text-xs p-4 rounded-xl overflow-x-auto"><code>{
  "status": "queued",
  "message_id": "a1b2c3d4",
  "recipients": 2,
  "cost": 1.04,
  "currency": "BDT"
}</code></pre>
        <p class="font-semibold text-slate-700 mt-5 mb-2 text-sm">Other languages</p>
        <pre class="bg-slate-900 text-slate-100 text-xs p-4 rounded-xl overflow-x-auto"><code>// Node.js (fetch)
await fetch("https://notifybd.com/api/v1/sms/send", {
  method: "POST",
  headers: { Authorization: "Bearer YOUR_API_KEY", "Content-Type": "application/json" },
  body: JSON.stringify({ to: "8801XXXXXXXXX", sender: "NotifySMS", message: "Hello!" })
});

# Python (requests)
import requests
requests.post("https://notifybd.com/api/v1/sms/send",
  headers={"Authorization": "Bearer YOUR_API_KEY"},
  json={"to": "8801XXXXXXXXX", "sender": "NotifySMS", "message": "Hello!"})</code></pre>
      </div>

      {{-- RATES --}}
      <div id="sms-rates">
        <div class="flex items-center gap-3 mb-3"><span class="text-xs font-black text-white px-2.5 py-1 rounded bg-sky-600">GET</span><h2 class="text-2xl font-black" style="color:#003087;">Get SMS rates</h2></div>
        <p class="text-slate-600 mb-2"><code class="bg-slate-100 px-1.5 py-0.5 rounded text-sm">/api/v1/sms-rates</code> — returns active masking & non-masking slab rates and the current VAT percentage. Public.</p>
        <pre class="bg-slate-900 text-slate-100 text-xs p-4 rounded-xl overflow-x-auto"><code>curl https://notifybd.com/api/v1/sms-rates</code></pre>
      </div>

      {{-- CALCULATE --}}
      <div id="calculate">
        <div class="flex items-center gap-3 mb-3"><span class="text-xs font-black text-white px-2.5 py-1 rounded bg-emerald-600">POST</span><h2 class="text-2xl font-black" style="color:#003087;">Calculate cost</h2></div>
        <p class="text-slate-600 mb-2"><code class="bg-slate-100 px-1.5 py-0.5 rounded text-sm">/api/v1/calculate</code> — compute the cost for a quantity and message type. Public.</p>
        <p class="font-semibold text-slate-700 mt-5 mb-2 text-sm">Request</p>
        <pre class="bg-slate-900 text-slate-100 text-xs p-4 rounded-xl overflow-x-auto"><code>curl -X POST https://notifybd.com/api/v1/calculate \
  -H "Content-Type: application/json" \
  -d '{"type":"masking","quantity":15000}'</code></pre>
        <p class="font-semibold text-slate-700 mt-5 mb-2 text-sm">Response <span class="text-emerald-600">200 OK</span></p>
        <pre class="bg-slate-900 text-slate-100 text-xs p-4 rounded-xl overflow-x-auto"><code>{
  "type": "masking",
  "quantity": 15000,
  "unit_price": 0.52,
  "subtotal": 7800,
  "vat_percent": 0,
  "vat": 0,
  "total": 7800,
  "currency": "BDT"
}</code></pre>
        <p class="text-slate-500 text-sm mt-3">Parameters: <code class="bg-slate-100 px-1 rounded">type</code> = <code class="bg-slate-100 px-1 rounded">masking</code> | <code class="bg-slate-100 px-1 rounded">non_masking</code>, <code class="bg-slate-100 px-1 rounded">quantity</code> = integer ≥ 1.</p>
      </div>

      {{-- CONTACT --}}
      <div id="contact">
        <div class="flex items-center gap-3 mb-3"><span class="text-xs font-black text-white px-2.5 py-1 rounded bg-emerald-600">POST</span><h2 class="text-2xl font-black" style="color:#003087;">Submit a lead</h2></div>
        <p class="text-slate-600 mb-2"><code class="bg-slate-100 px-1.5 py-0.5 rounded text-sm">/api/v1/contact</code> — store a contact-form submission. Public, rate-limited.</p>
        <pre class="bg-slate-900 text-slate-100 text-xs p-4 rounded-xl overflow-x-auto"><code>curl -X POST https://notifybd.com/api/v1/contact \
  -H "Content-Type: application/json" \
  -d '{"name":"Rahim","phone":"8801XXXXXXXXX","message":"Need 50k SMS/month"}'</code></pre>
      </div>

      {{-- ERRORS --}}
      <div id="errors">
        <h2 class="text-2xl font-black mb-3" style="color:#003087;">Errors</h2>
        <p class="text-slate-600 mb-4">The API uses standard HTTP status codes. Validation failures return <code class="bg-slate-100 px-1.5 py-0.5 rounded text-sm">422</code> with a JSON <code class="bg-slate-100 px-1.5 py-0.5 rounded text-sm">message</code>; auth failures return <code class="bg-slate-100 px-1.5 py-0.5 rounded text-sm">401</code>.</p>
        <div class="overflow-x-auto"><table class="w-full text-sm border border-slate-200 rounded-xl overflow-hidden">
          <thead class="bg-slate-50 text-slate-600"><tr><th class="text-left px-4 py-2.5 font-semibold">Code</th><th class="text-left px-4 py-2.5 font-semibold">Meaning</th></tr></thead>
          <tbody class="divide-y divide-slate-100">
            <tr><td class="px-4 py-2.5 font-mono">200 / 201</td><td class="px-4 py-2.5 text-slate-600">Success</td></tr>
            <tr><td class="px-4 py-2.5 font-mono">401</td><td class="px-4 py-2.5 text-slate-600">Missing or invalid API key</td></tr>
            <tr><td class="px-4 py-2.5 font-mono">422</td><td class="px-4 py-2.5 text-slate-600">Validation error</td></tr>
            <tr><td class="px-4 py-2.5 font-mono">429</td><td class="px-4 py-2.5 text-slate-600">Rate limit exceeded</td></tr>
          </tbody>
        </table></div>
      </div>

      <div class="rounded-2xl border border-blue-100 bg-blue-50/50 p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-slate-700 font-medium">Ready to build? Grab an API key and start sending.</p>
        <a href="/contact" class="shrink-0 font-bold px-6 py-3 rounded-xl text-white" style="background:#003087;">Get API Access</a>
      </div>
    </div>
  </div>
</section>

@include('partials.cta', ['ctaHeading' => 'Start building with the NotifySMS API', 'ctaSub' => 'Send your first SMS in five minutes. Bearer-token auth, delivery webhooks and 24×7 developer support.'])
@endsection
