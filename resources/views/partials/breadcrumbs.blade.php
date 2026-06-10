@php
    $base  = rtrim(config('app.url'), '/');
    $crumb = $crumb ?? ($meta['crumb'] ?? null);
    $key   = $pageKey ?? '';
    $url   = $key === '' ? $base.'/' : $base.'/'.ltrim($key, '/');
@endphp
@if ($crumb)
<nav aria-label="Breadcrumb" class="max-w-7xl mx-auto px-4 pt-28 pb-2">
  <ol class="flex flex-wrap items-center gap-2 text-sm text-slate-500">
    <li><a href="/" class="hover:text-ppnavy">Home</a></li>
    <li class="text-slate-300">/</li>
    <li class="font-semibold text-ppnavy" aria-current="page">{{ $crumb }}</li>
  </ol>
</nav>
@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $base.'/'],
        ['@type' => 'ListItem', 'position' => 2, 'name' => $crumb, 'item' => $url],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush
@endif
