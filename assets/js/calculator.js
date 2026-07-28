/**
 * Notify — SMS price calculator.
 * Every rate comes from assets/js/pricing-config.js. No prices are defined here.
 */
(function () {
  'use strict';

  const CFG = window.NOTIFY_PRICING;
  const findSlab = window.NOTIFY_FIND_SLAB;
  if (!CFG || !findSlab) return;

  const qtyInput = document.getElementById('calc-qty');
  if (!qtyInput) return;

  const slider = document.getElementById('qty-slider');
  const totalEl = document.getElementById('calc-total');
  const rateEl = document.getElementById('calc-rate');
  const qtyShowEl = document.getElementById('calc-qty-show');
  const msgEl = document.getElementById('calc-msg');
  const errEl = document.getElementById('calc-error');
  const typeLabel = document.getElementById('slab-type-label');
  const slabList = document.getElementById('slabs-list');
  const typeButtons = Array.from(document.querySelectorAll('[data-sms-type]'));

  let currentType = 'nm';
  let userInteracted = false; // don't track the initial render, only real use
  let calcTimer;

  const nf = new Intl.NumberFormat(CFG.locale);
  const fmtQty = (n) => nf.format(n);
  /** ৳1,234.50 — two decimals only when the amount is not whole. */
  const fmtMoney = (n) =>
    CFG.currency +
    new Intl.NumberFormat(CFG.locale, {
      minimumFractionDigits: Number.isInteger(n) ? 0 : 2,
      maximumFractionDigits: 2,
    }).format(n);
  const fmtRate = (r) => CFG.currency + r.toFixed(2);

  const bandLabel = (s) =>
    s.max === null
      ? `${fmtQty(s.min)}+ SMS`
      : `${fmtQty(s.min)} – ${fmtQty(s.max)} SMS`;

  /** Renders the slab table for the active SMS type. */
  function renderSlabs() {
    if (!slabList) return;
    const type = CFG.types[currentType];

    // Merge published slabs and contact-sales bands into one ordered list.
    const rows = [
      ...type.slabs.map((s) => ({ min: s.min, max: s.max, rate: s.rate, best: s.best })),
      ...CFG.contactSalesBands.map((b) => ({ ...b, rate: null })),
    ].sort((a, b) => a.min - b.min);

    slabList.innerHTML = '';
    for (const row of rows) {
      const label =
        row.max === null
          ? `${fmtQty(row.min)}+`
          : `${fmtQty(row.min)} – ${fmtQty(row.max)}`;

      const isContact = row.rate === null;
      const el = document.createElement(isContact ? 'div' : 'button');
      el.className = 'slab-row';
      el.dataset.min = String(row.min);
      if (row.max !== null) el.dataset.max = String(row.max);

      if (!isContact) {
        el.type = 'button';
        el.addEventListener('click', () => setQty(row.min));
      }

      const left = document.createElement('span');
      left.className = 'text-slate-300 text-sm';
      left.textContent = label;

      const right = document.createElement('span');
      if (isContact) {
        right.className =
          'text-amber-300 font-bold text-sm bg-amber-400/10 px-2.5 py-1 rounded-lg';
        right.textContent = 'Contact sales';
      } else {
        right.className =
          'text-white font-bold text-sm bg-blue-900/50 px-2.5 py-1 rounded-lg';
        right.textContent = fmtRate(row.rate) + '/SMS';
        if (row.best) right.textContent += ' ★';
      }

      el.append(left, right);
      slabList.appendChild(el);
    }
  }

  function highlightSlab(qty) {
    if (!slabList) return;
    for (const el of slabList.children) {
      const min = Number(el.dataset.min);
      const max = el.dataset.max ? Number(el.dataset.max) : Infinity;
      el.classList.toggle('slab-active', qty >= min && qty <= max);
    }
  }

  function showError(message) {
    if (!errEl) return;
    errEl.textContent = message || '';
    errEl.classList.toggle('show', Boolean(message));
    qtyInput.setAttribute('aria-invalid', message ? 'true' : 'false');
  }

  function calculate() {
    const raw = qtyInput.value.trim();
    const qty = Math.floor(Number(raw));

    qtyShowEl.textContent = Number.isFinite(qty) && qty > 0 ? fmtQty(qty) : '—';

    // ── Validation ──────────────────────────────────────────────────────
    if (raw === '' || !Number.isFinite(qty) || qty <= 0) {
      totalEl.textContent = '—';
      rateEl.textContent = '—';
      msgEl.textContent = 'Enter the number of SMS you plan to send.';
      showError(raw === '' ? '' : 'Enter a whole number greater than zero.');
      highlightSlab(-1);
      return;
    }

    if (qty < CFG.minQuantity) {
      totalEl.textContent = '—';
      rateEl.textContent = '—';
      msgEl.textContent = `The minimum order is ${fmtQty(CFG.minQuantity)} SMS.`;
      showError(`Minimum order is ${fmtQty(CFG.minQuantity)} SMS.`);
      highlightSlab(-1);
      return;
    }

    showError('');
    if (slider) slider.value = String(Math.min(qty, Number(slider.max)));

    const slab = findSlab(currentType, qty);

    // No published rate for this volume → tell the truth, don't guess.
    if (!slab) {
      totalEl.textContent = 'Contact sales';
      totalEl.classList.add('text-3xl');
      totalEl.classList.remove('text-6xl');
      rateEl.textContent = '—';
      msgEl.textContent =
        'We do not publish a rate for this volume. Contact our sales team for a quote.';
      highlightSlab(qty);
      return;
    }

    totalEl.classList.add('text-6xl');
    totalEl.classList.remove('text-3xl');
    rateEl.textContent = fmtRate(slab.rate);
    totalEl.textContent = fmtMoney(qty * slab.rate);
    msgEl.textContent = `${fmtRate(slab.rate)} per SMS · ${bandLabel(slab)} · ${CFG.taxNote}`;
    highlightSlab(qty);

    // Meta CalculatorUse — only on genuine interaction, debounced so a burst of
    // keystrokes sends one event. No personal data, only the estimate itself.
    if (userInteracted && window.NotifyTrack) {
      clearTimeout(calcTimer);
      calcTimer = setTimeout(function () {
        window.NotifyTrack.calculatorUse({
          sms_type: CFG.types[currentType].label,
          quantity: qty,
          estimated_value: Math.round(qty * slab.rate),
          currency: 'BDT',
        });
      }, 1200);
    }
  }

  function setQty(q) {
    qtyInput.value = String(q);
    calculate();
  }

  function setType(typeId) {
    if (!CFG.types[typeId]) return;
    currentType = typeId;
    for (const btn of typeButtons) {
      const active = btn.dataset.smsType === typeId;
      btn.setAttribute('aria-pressed', String(active));
      btn.classList.toggle('ring-2', active);
      btn.classList.toggle('ring-brandblue', active);
      btn.classList.toggle('bg-white/10', active);
      btn.classList.toggle('bg-white/5', !active);
    }
    if (typeLabel) typeLabel.textContent = CFG.types[typeId].label;
    renderSlabs();
    calculate();
  }

  /* ── Wiring ─────────────────────────────────────────────────────────── */
  qtyInput.addEventListener('input', () => { userInteracted = true; calculate(); });
  if (slider) {
    slider.addEventListener('input', () => { userInteracted = true; setQty(Number(slider.value)); });
  }
  for (const btn of typeButtons) {
    btn.addEventListener('click', () => { userInteracted = true; setType(btn.dataset.smsType); });
  }
  for (const btn of document.querySelectorAll('[data-quick-qty]')) {
    btn.addEventListener('click', () => { userInteracted = true; setQty(Number(btn.dataset.quickQty)); });
  }

  setType('nm');
})();
