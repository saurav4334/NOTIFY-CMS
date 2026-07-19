/**
 * NotifyBD — homepage hero canvas.
 *
 * Purely decorative drifting message bubbles behind the hero.
 *
 * Performance/accessibility rules (Phase 13):
 *   - never starts if `prefers-reduced-motion: reduce` is set;
 *   - never starts on low-power devices (few cores / small viewport / save-data);
 *   - pauses entirely when the hero scrolls out of view or the tab is hidden,
 *     so it cannot burn battery in the background;
 *   - capped at ~30fps and sized to devicePixelRatio <= 2.
 *
 * The mockup ran an uncapped rAF loop plus three setIntervals that never
 * stopped, and animated a fake "live delivery feed" of invented phone numbers.
 * The feed has been removed (Phase 5); only the ambient canvas remains.
 */
(function () {
  'use strict';

  const canvas = document.getElementById('sms-canvas');
  if (!canvas) return;

  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const lowPower =
    (navigator.hardwareConcurrency || 8) <= 4 ||
    window.innerWidth < 768 ||
    (navigator.connection && navigator.connection.saveData === true);

  if (prefersReduced || lowPower) {
    canvas.remove();
    return;
  }

  const ctx = canvas.getContext('2d', { alpha: true });
  if (!ctx) return;

  const DPR = Math.min(window.devicePixelRatio || 1, 2);
  const COLORS = ['rgba(0,48,135,', 'rgba(0,156,222,', 'rgba(255,196,57,'];
  let w = 0;
  let h = 0;
  let bubbles = [];
  let rafId = null;
  let running = false;
  let last = 0;

  function resize() {
    const rect = canvas.getBoundingClientRect();
    w = rect.width;
    h = rect.height;
    canvas.width = Math.round(w * DPR);
    canvas.height = Math.round(h * DPR);
    ctx.setTransform(DPR, 0, 0, DPR, 0, 0);
  }

  function seed() {
    const count = Math.min(18, Math.round(w / 90));
    bubbles = Array.from({ length: count }, () => ({
      x: Math.random() * w,
      y: Math.random() * h,
      r: 10 + Math.random() * 22,
      vx: (Math.random() - 0.5) * 0.18,
      vy: -0.12 - Math.random() * 0.25,
      a: 0.05 + Math.random() * 0.09,
      c: COLORS[Math.floor(Math.random() * COLORS.length)],
    }));
  }

  function draw(now) {
    if (!running) return;
    rafId = requestAnimationFrame(draw);
    if (now - last < 33) return; // ~30fps cap
    last = now;

    ctx.clearRect(0, 0, w, h);
    for (const b of bubbles) {
      b.x += b.vx;
      b.y += b.vy;
      if (b.y + b.r < 0) {
        b.y = h + b.r;
        b.x = Math.random() * w;
      }
      if (b.x < -b.r) b.x = w + b.r;
      if (b.x > w + b.r) b.x = -b.r;

      // rounded "message bubble"
      const rr = b.r * 0.32;
      const bw = b.r * 1.7;
      const bh = b.r;
      ctx.beginPath();
      ctx.moveTo(b.x + rr, b.y);
      ctx.arcTo(b.x + bw, b.y, b.x + bw, b.y + bh, rr);
      ctx.arcTo(b.x + bw, b.y + bh, b.x, b.y + bh, rr);
      ctx.arcTo(b.x, b.y + bh, b.x, b.y, rr);
      ctx.arcTo(b.x, b.y, b.x + bw, b.y, rr);
      ctx.closePath();
      ctx.fillStyle = b.c + b.a + ')';
      ctx.fill();
    }
  }

  function start() {
    if (running) return;
    running = true;
    last = 0;
    rafId = requestAnimationFrame(draw);
  }

  function stop() {
    running = false;
    if (rafId) cancelAnimationFrame(rafId);
    rafId = null;
  }

  resize();
  seed();

  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      resize();
      seed();
    }, 200);
  }, { passive: true });

  // Only animate while the hero is actually on screen and the tab is visible.
  const hero = canvas.closest('section') || canvas.parentElement;
  if ('IntersectionObserver' in window && hero) {
    new IntersectionObserver(
      ([entry]) => (entry.isIntersecting && !document.hidden ? start() : stop()),
      { threshold: 0 }
    ).observe(hero);
  } else {
    start();
  }

  document.addEventListener('visibilitychange', () => {
    if (document.hidden) stop();
    else if (hero && hero.getBoundingClientRect().bottom > 0) start();
  });
})();
