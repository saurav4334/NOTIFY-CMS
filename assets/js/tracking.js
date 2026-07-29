/**
 * Notify — shared Meta Pixel event layer.
 * ============================================================================
 * The Meta Pixel BASE code (init + PageView) is installed inline in each page's
 * <head> so it loads and fires immediately. This file adds the EVENT layer on
 * top of it: reusable helpers plus the auto-wired events. It is loaded once per
 * page (a single <script defer> after main.js), so listeners bind once.
 *
 * Pixel ID: 883853861046480  (managed in the inline <head> snippet, not here).
 *
 * Design rules:
 *   - Every event carries the Notify product metadata automatically.
 *   - Helpers fail silently if fbq is missing/blocked — never throw.
 *   - Once-per-page events are de-duplicated.
 *   - No PageView here (the inline snippet already fires it once).
 * ============================================================================
 */
(function () {
  'use strict';

  var META = { product: 'Notify', business_unit: 'Bulk SMS', website: 'notifybd.com' };
  var fired = Object.create(null); // de-dupe once-per-page events

  function ready() {
    return typeof window.fbq === 'function';
  }
  function merge(params) {
    var out = { product: META.product, business_unit: META.business_unit, website: META.website };
    if (params) for (var k in params) if (Object.prototype.hasOwnProperty.call(params, k)) out[k] = params[k];
    return out;
  }
  function standard(name, params) {
    if (!ready()) return;
    try { window.fbq('track', name, merge(params)); } catch (e) { /* silent */ }
  }
  function custom(name, params) {
    if (!ready()) return;
    try { window.fbq('trackCustom', name, merge(params)); } catch (e) { /* silent */ }
  }
  function once(key, fn) {
    if (fired[key]) return;
    fired[key] = true;
    try { fn(); } catch (e) { /* silent */ }
  }

  /* ── Google Analytics 4 ───────────────────────────────────────────────────
   * GA4 runs ALONGSIDE the Meta Pixel — the two are independent. gtag.js is
   * loaded by the inline snippet in each page's <head>; this helper only sends
   * events, and no-ops (never throws) if gtag is missing or blocked, so an
   * analytics failure can never break the site. NEVER pass PII here (no names,
   * phone numbers, email addresses or message text) — only page paths and
   * non-identifying service/package metadata.
   */
  function trackGA4Event(eventName, parameters) {
    if (typeof window.gtag === 'function') {
      try { window.gtag('event', eventName, parameters || {}); } catch (e) { /* silent */ }
    }
  }
  window.trackGA4Event = trackGA4Event; // exposed globally (matches spec §10)

  // Expose so calculator.js / contact.js route their events through here
  // instead of calling fbq() / gtag() directly (keeps all tracking centralised).
  window.NotifyTrack = {
    standard: standard,
    custom: custom,
    once: once,
    ga4: trackGA4Event,
    meta: META,
    lead: function (source, service) {
      once('lead', function () {
        standard('Lead', { source: source || 'Contact Form' });
        // CompleteRegistration mirrors a successful enquiry submission.
        standard('CompleteRegistration', { source: source || 'Contact Form', status: true });
      });
      // GA4 generate_lead — de-duped alongside Meta Lead. `service` is a category
      // (e.g. "Masking"), never PII; source_page is the pathname only.
      trackGA4Event('generate_lead', { service: service || 'unknown', source_page: path });
    },
    calculatorUse: function (data) {
      // Fired on genuine interaction (debounced by the caller). Not once-only —
      // but throttled upstream — and never carries personal data.
      custom('CalculatorUse', data || {});
      trackGA4Event('calculator_use', data || {});
    },
  };

  var path = location.pathname.replace(/\/index\.html$/, '/');

  /* ── ViewContent on important content pages ──────────────────────────────── */
  var CONTENT = ['/pricing/', '/calculator/', '/services/'];
  if (CONTENT.indexOf(path) !== -1) {
    once('viewcontent', function () {
      standard('ViewContent', { content_name: document.title, content_category: 'Bulk SMS' });
    });
  }

  /* ── PricingView (custom) once on /pricing/ ──────────────────────────────── */
  if (path === '/pricing/') {
    once('pricingview', function () {
      custom('PricingView', {});
      trackGA4Event('pricing_view', { source_page: path });
    });
  }

  /* ── api_docs_view once on the API-docs page ─────────────────────────────── */
  // No dedicated /api-docs/ page exists yet; this stays inert until one is added
  // (or the API section moves to its own URL), at which point it fires once.
  if (path === '/api-docs/' || path === '/api/' || path === '/docs/') {
    once('apidocsview', function () {
      trackGA4Event('api_docs_view', { source_page: path });
    });
  }

  /* ── DeepScroll (custom) once at 75% page depth ──────────────────────────── */
  function onScroll() {
    var doc = document.documentElement;
    var scrolled = (window.scrollY || doc.scrollTop) + window.innerHeight;
    var total = doc.scrollHeight;
    if (total > 0 && scrolled / total >= 0.75) {
      once('deepscroll', function () { custom('DeepScroll', { depth: 75, page: path }); });
      window.removeEventListener('scroll', onScroll);
    }
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  /* ── Delegated click tracking: Contact, PackageInterest, CTA_Click ───────── */
  var CTA_TEXT = [
    'get a quote', 'calculate sms cost', 'contact us', 'contact sales',
    'talk to sales', 'get started', 'request pricing', 'buy sms',
    'order now', 'send message', 'send a message', 'send enquiry', 'start now',
  ];

  function textOf(el) {
    return (el.textContent || '').replace(/\s+/g, ' ').trim();
  }

  document.addEventListener('click', function (ev) {
    var link = ev.target.closest && ev.target.closest('a, button');
    if (!link) return;

    var href = (link.getAttribute && link.getAttribute('href')) || '';

    // Contact events — tel / mailto / WhatsApp.
    // GA4 carries only the source page — never the dialled number or address.
    if (/^tel:/i.test(href)) {
      standard('Contact', { method: 'Phone' });
      trackGA4Event('phone_click', { source_page: path });
      return;
    }
    if (/^mailto:/i.test(href)) {
      standard('Contact', { method: 'Email' });
      trackGA4Event('email_click', { source_page: path });
      return;
    }
    if (/wa\.me|api\.whatsapp\.com|whatsapp/i.test(href) || link.id === 'waBtn') {
      standard('Contact', { method: 'WhatsApp' });
      trackGA4Event('whatsapp_click', { source_page: path });
      return;
    }

    // PackageInterest — a CTA inside a pricing plan card (more specific than CTA)
    var card = link.closest && link.closest('.plan-card');
    if (card) {
      var tierEl = card.querySelector('[class*="tracking-widest"]');
      var rateEl = card.querySelector('.text-5xl');
      var isMasking = !!(link.closest('#panel-m'));
      var pkg = {
        package_name: tierEl ? textOf(tierEl) : '',
        sms_type: isMasking ? 'Masking' : 'Non-Masking',
        unit_rate: rateEl ? textOf(rateEl) : '',
        currency: 'BDT',
      };
      custom('PackageInterest', pkg);
      trackGA4Event('package_interest', pkg);
      return;
    }

    // CTA_Click — recognised conversion buttons/links
    var t = textOf(link).toLowerCase();
    if (t && CTA_TEXT.indexOf(t) !== -1) {
      custom('CTA_Click', { button_text: textOf(link), page: path });
    }
  }, true);
})();
