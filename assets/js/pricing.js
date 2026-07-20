/**
 * Notify — pricing page type switcher (non-masking ⇄ masking).
 *
 * The cards themselves are generated at build time from pricing-config.js;
 * this only swaps which panel is visible. Implemented as a proper ARIA tablist
 * so it is keyboard operable (arrow keys, Home/End) — the mockup used
 * click-only <div>s.
 */
(function () {
  'use strict';

  const tabs = Array.from(document.querySelectorAll('[role="tab"]'));
  if (!tabs.length) return;

  function select(tab, setFocus = true) {
    for (const t of tabs) {
      const selected = t === tab;
      t.setAttribute('aria-selected', String(selected));
      t.setAttribute('tabindex', selected ? '0' : '-1');
      const panel = document.getElementById(t.getAttribute('aria-controls'));
      if (panel) panel.hidden = !selected;
    }
    if (setFocus) tab.focus();
  }

  for (const tab of tabs) {
    tab.addEventListener('click', () => select(tab, false));

    tab.addEventListener('keydown', (e) => {
      const i = tabs.indexOf(tab);
      let next = null;
      if (e.key === 'ArrowRight') next = tabs[(i + 1) % tabs.length];
      else if (e.key === 'ArrowLeft') next = tabs[(i - 1 + tabs.length) % tabs.length];
      else if (e.key === 'Home') next = tabs[0];
      else if (e.key === 'End') next = tabs[tabs.length - 1];
      if (next) {
        e.preventDefault();
        select(next);
      }
    });
  }

  select(tabs.find((t) => t.getAttribute('aria-selected') === 'true') || tabs[0], false);
})();
