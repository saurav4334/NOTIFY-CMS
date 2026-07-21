/**
 * Notify — central pricing configuration.
 * ============================================================================
 * THE SINGLE SOURCE OF TRUTH FOR EVERY RATE ON THE SITE.
 *
 * Consumed by:
 *   - build/build.mjs  → generates the pricing page cards + comparison table
 *   - assets/js/calculator.js → the live calculator (window.NOTIFY_PRICING)
 *
 * In the mockup these numbers were hardcoded in THREE places (pricing.html
 * markup, calculator.js, cms.js). They now live here and nowhere else.
 *
 * ── RATES ARE PRESERVED EXACTLY AS PUBLISHED IN THE MOCKUP. ──
 * Do not change any `rate` value without explicit instruction from the
 * business. All rates are inclusive of VAT, TAX and Dipping.
 *
 * ── KNOWN GAP (see docs/01-MOCKUP-AUDIT.md §17) ──────────────────────────
 * The published bands run 5,000–10,000 and then 11,000–20,000. Quantities of
 * 10,001–10,999 therefore fall into NO band, exactly as in the mockup, and
 * are shown as "Contact sales" — the same treatment the published table gives
 * to 20,001–39,999. This preserves the visible rates rather than inventing a
 * band boundary. The business must confirm whether the first band should end
 * at 10,999 or the second should start at 10,001.
 * ============================================================================
 */
const NOTIFY_PRICING = {
  currency: '৳',
  currencyCode: 'BDT',
  locale: 'en-BD',

  /** Minimum order the business accepts. Enforced by the calculator. */
  minQuantity: 5000,
  /** Upper bound of the calculator's slider/input (not a business limit). */
  maxQuantity: 1000000,

  /** All listed rates include VAT, TAX & Dipping. */
  priceIncludesTax: true,
  taxNote: 'All rates include VAT, TAX & Dipping.',

  types: {
    nm: {
      id: 'nm',
      label: 'Non-Masking',
      shortLabel: 'Non-Masking SMS',
      description:
        'Numeric sender ID (for example 01711XXXXXX). The lower-cost option, ' +
        'suited to high-volume promotional campaigns.',
      slabs: [
        { tier: 'Starter', min: 5000, max: 10000, rate: 0.35, support: 'Standard support' },
        { tier: 'Business', min: 11000, max: 20000, rate: 0.33, support: 'Priority support', popular: true },
        { tier: 'Enterprise', min: 40000, max: 99999, rate: 0.31, support: 'Dedicated account manager' },
        { tier: 'Elite', min: 100000, max: null, rate: 0.30, support: 'Priority support', best: true },
      ],
    },
    m: {
      id: 'm',
      label: 'Masking',
      shortLabel: 'Masking SMS',
      description:
        'Your approved brand name appears as the sender ID. A premium service ' +
        'that reinforces brand recognition.',
      slabs: [
        { tier: 'Starter', min: 5000, max: 10000, rate: 0.55, support: 'Standard support' },
        { tier: 'Business', min: 11000, max: 20000, rate: 0.53, support: 'Priority support', popular: true },
        { tier: 'Enterprise', min: 40000, max: 99999, rate: 0.51, support: 'Dedicated account manager' },
        { tier: 'Elite', min: 100000, max: null, rate: 0.50, support: 'Priority support', best: true },
      ],
    },
  },

  /**
   * Volume bands with no published rate. Shown as "Contact sales" on the
   * pricing table and in the calculator.
   */
  contactSalesBands: [
    { min: 10001, max: 10999 },
    { min: 20001, max: 39999 },
  ],

  /** Features listed on every plan card (identical across tiers in the mockup). */
  planFeatures: [
    'Real-time delivery reports',
    'REST API access',
    'CSV / Excel contact upload',
    'Campaign scheduler',
    'OTP SMS support',
    'bKash / Nagad payment',
  ],
};

/**
 * Find the slab that covers `qty` for a given type id ('nm' | 'm').
 * Returns null when the quantity falls in a gap or below the minimum —
 * callers must then show the "contact sales" state.
 */
function findSlab(typeId, qty) {
  const type = NOTIFY_PRICING.types[typeId];
  if (!type || !Number.isFinite(qty)) return null;
  return (
    type.slabs.find((s) => qty >= s.min && (s.max === null || qty <= s.max)) || null
  );
}

if (typeof window !== 'undefined') {
  window.NOTIFY_PRICING = NOTIFY_PRICING;
  window.NOTIFY_FIND_SLAB = findSlab;
}
if (typeof module !== 'undefined' && module.exports) {
  module.exports = { NOTIFY_PRICING, findSlab };
}
