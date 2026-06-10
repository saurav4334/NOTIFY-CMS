@php $faqs = $faqs ?? []; $faqHeading = $faqHeading ?? 'Frequently asked questions'; @endphp
@if (count($faqs))
<section class="py-20 px-4 bg-white">
  <div class="max-w-3xl mx-auto">
    <h2 class="text-3xl md:text-4xl font-black text-center mb-3" style="color:#003087;">{{ $faqHeading }}</h2>
    <p class="text-slate-500 text-center mb-10">Everything you need to know. Still curious? <a href="/contact" class="font-semibold" style="color:#009cde;">Talk to our team →</a></p>
    <div class="space-y-4">
      @foreach ($faqs as $f)
      <div class="faq-item border border-slate-200 rounded-2xl px-6 py-1 bg-slate-50/60">
        <div class="faq-q flex items-center justify-between gap-4 py-4 font-semibold text-slate-800">
          <span>{{ $f['q'] }}</span>
          <i class="fas fa-chevron-down faq-chev text-sm text-slate-400 transition-transform"></i>
        </div>
        <div class="faq-a"><p class="text-slate-600 text-sm leading-relaxed pb-5">{!! $f['a'] !!}</p></div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => collect($faqs)->map(fn ($f) => [
        '@type' => 'Question',
        'name' => $f['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($f['a'])],
    ])->all(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush
@endif
