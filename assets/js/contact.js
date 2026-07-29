/**
 * Notify — contact form.
 *
 * Submits to /api/contact.php via fetch and reports what actually happened.
 * The mockup faked a 1.5s delay and always showed "Message sent successfully!"
 * while discarding the data; this does the opposite in every respect.
 *
 * Behaviour:
 *   - client-side validation mirrors the server's rules (the server is still
 *     authoritative — this is only to save a round trip);
 *   - errors are announced via aria-live and tied to their field with
 *     aria-describedby / aria-invalid;
 *   - the submit button is disabled only for the duration of the request;
 *   - on failure the user's input is left untouched so nothing is retyped;
 *   - works without any framework and degrades to a normal POST if JS is off.
 */
(function () {
  'use strict';

  const form = document.getElementById('contact-form');
  if (!form) return;

  const btn = document.getElementById('submit-btn');
  const btnLabel = document.getElementById('submit-label');
  const status = document.getElementById('form-status');
  const startedAt = Date.now();

  /* Time-trap: a real person cannot complete this form in under 3 seconds. */
  const tsField = form.querySelector('input[name="started_at"]');
  if (tsField) tsField.value = String(startedAt);

  /* Attribution — captured for the server-side notification email only.
     No credentials, no tracking cookies; just where the lead came from. */
  (function captureAttribution() {
    const qs = new URLSearchParams(location.search);
    const set = (n, v) => {
      const el = form.querySelector(`input[name="${n}"]`);
      if (el) el.value = v || '';
    };
    set('source_url', location.href);
    set('referrer', document.referrer);
    set('utm_source', qs.get('utm_source'));
    set('utm_medium', qs.get('utm_medium'));
    set('utm_campaign', qs.get('utm_campaign'));
  })();

  /* GA4 contact_form_start — fired once, on the first genuine interaction with
     the form (not on page load). No field values are sent, only the page path. */
  let startTracked = false;
  form.addEventListener(
    'input',
    function () {
      if (startTracked) return;
      startTracked = true;
      if (typeof window.trackGA4Event === 'function') {
        window.trackGA4Event('contact_form_start', { source_page: location.pathname });
      }
    },
    { once: false }
  );

  const RULES = {
    name: {
      test: (v) => v.length >= 2 && v.length <= 100,
      message: 'Enter your full name (at least 2 characters).',
    },
    phone: {
      // Bangladeshi mobile: 01[3-9] + 8 digits, optionally +880 / 880 prefixed.
      test: (v) => /^(?:\+?880|0)1[3-9]\d{8}$/.test(v.replace(/[\s-]/g, '')),
      message: 'Enter a valid Bangladeshi mobile number, e.g. 01712345678.',
    },
    email: {
      optional: true,
      test: (v) => /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v),
      message: 'Enter a valid email address, or leave this field empty.',
    },
    message: {
      test: (v) => v.length >= 10 && v.length <= 2000,
      message: 'Tell us a little more (at least 10 characters).',
    },
  };

  function fieldError(name, message) {
    const input = form.elements[name];
    const err = document.getElementById(`err-${name}`);
    if (!input || !err) return;
    err.textContent = message || '';
    err.classList.toggle('show', Boolean(message));
    input.setAttribute('aria-invalid', message ? 'true' : 'false');
  }

  function validate() {
    let firstBad = null;
    for (const [name, rule] of Object.entries(RULES)) {
      const input = form.elements[name];
      if (!input) continue;
      const value = input.value.trim();

      if (!value) {
        if (rule.optional) {
          fieldError(name, '');
          continue;
        }
        fieldError(name, rule.message);
        firstBad = firstBad || input;
        continue;
      }

      if (!rule.test(value)) {
        fieldError(name, rule.message);
        firstBad = firstBad || input;
      } else {
        fieldError(name, '');
      }
    }
    return firstBad;
  }

  // Clear a field's error as soon as the user starts fixing it.
  for (const name of Object.keys(RULES)) {
    const input = form.elements[name];
    if (input) {
      input.addEventListener('input', () => {
        if (input.getAttribute('aria-invalid') === 'true') fieldError(name, '');
      });
    }
  }

  function setStatus(kind, message) {
    if (!status) return;
    status.textContent = message;
    status.classList.remove('is-success', 'is-error');
    status.classList.add('show', kind === 'success' ? 'is-success' : 'is-error');
  }

  function setBusy(busy) {
    btn.disabled = busy;
    btn.setAttribute('aria-busy', String(busy));
    if (btnLabel) btnLabel.textContent = busy ? 'Sending…' : 'Send Message';
  }

  let sent = false;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (btn.disabled) return; // guard against double submission

    if (status) status.classList.remove('show');

    const firstBad = validate();
    if (firstBad) {
      setStatus('error', 'Please correct the highlighted fields and try again.');
      firstBad.focus();
      return;
    }

    setBusy(true);

    try {
      const res = await fetch(form.action, {
        method: 'POST',
        headers: { Accept: 'application/json' },
        body: new FormData(form),
      });

      let data = {};
      try {
        data = await res.json();
      } catch {
        // Non-JSON response — treat as a server failure, not a success.
      }

      if (res.ok && data.success) {
        sent = true;
        // Meta Lead + GA4 generate_lead — ONLY after the server confirms success
        // (not on click, not on validation failure, not on API error). Both are
        // de-duped by NotifyTrack. `service` is the SMS category (not PII); read
        // it BEFORE form.reset() clears the field.
        const svcEl = form.elements['sms_type'];
        const service = svcEl && svcEl.value ? svcEl.value : 'unknown';
        if (window.NotifyTrack) window.NotifyTrack.lead('Contact Form', service);
        setStatus('success', data.message || 'Thank you. Our sales team will contact you shortly.');
        form.reset();
        if (tsField) tsField.value = String(Date.now());
        // Lock the button: the message is delivered, there is nothing to resend.
        btn.setAttribute('aria-busy', 'false');
        btn.disabled = true;
        if (btnLabel) btnLabel.textContent = 'Message sent';
        return;
      }

      // Field-level errors from the server take precedence over the summary.
      if (data.errors && typeof data.errors === 'object') {
        for (const [name, message] of Object.entries(data.errors)) {
          fieldError(name, message);
        }
      }
      setStatus(
        'error',
        data.message ||
          'We could not send your message. Please try again, or contact us directly.'
      );
    } catch {
      setStatus(
        'error',
        'We could not reach the server. Check your connection and try again.'
      );
    } finally {
      // On any failure, re-enable so the user can retry. Their input is intact.
      if (!sent) setBusy(false);
    }
  });
})();
