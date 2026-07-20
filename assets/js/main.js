/**
 * Notify — shared behaviour for every page.
 * Loaded with `defer`, so the DOM is ready when this runs.
 */
(function () {
  'use strict';

  /* ── Navbar: solid background once scrolled; scroll-to-top button ──── */
  const navbar = document.getElementById('navbar');
  const scrollTopBtn = document.getElementById('scrollTop');

  let ticking = false;
  function onScroll() {
    if (ticking) return;
    ticking = true;
    // Batch reads/writes into one frame — the mockup ran two layout-thrashing
    // class toggles on every scroll event.
    requestAnimationFrame(() => {
      const y = window.scrollY;
      if (navbar) navbar.classList.toggle('scrolled', y > 50);
      if (scrollTopBtn) scrollTopBtn.classList.toggle('visible', y > 300);
      ticking = false;
    });
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  if (scrollTopBtn) {
    scrollTopBtn.addEventListener('click', () => {
      const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
    });
  }

  /* ── Mobile menu ──────────────────────────────────────────────────────
     Keyboard accessible: real <button>, aria-expanded/aria-controls, Escape
     closes and returns focus, and the panel is inert (hidden) when closed. */
  const toggle = document.getElementById('menu-toggle');
  const menu = document.getElementById('mobile-menu');

  if (toggle && menu) {
    const setOpen = (open) => {
      menu.classList.toggle('open', open);
      toggle.setAttribute('aria-expanded', String(open));
      // Keep collapsed links out of the tab order and the a11y tree.
      menu.hidden = !open;
    };

    setOpen(false);

    toggle.addEventListener('click', () => {
      setOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
        setOpen(false);
        toggle.focus();
      }
    });

    // Close when a link is chosen, and when the layout returns to desktop.
    menu.addEventListener('click', (e) => {
      if (e.target.closest('a')) setOpen(false);
    });
    window.matchMedia('(min-width: 1024px)').addEventListener('change', (e) => {
      if (e.matches) setOpen(false);
    });
  }
})();

/* The logo animation is intentionally CSS-only and has no JS counterpart.
   The standalone demo shipped a replay button, which does not translate here:
   the header logo is wrapped in <a href="/">, so a click navigates home and any
   replay would be destroyed by the page load. It plays once per page load,
   which is the effect we want anyway. */
