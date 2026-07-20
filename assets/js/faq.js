/**
 * Notify — FAQ accordion.
 *
 * Accessible by construction: each question is a real <button> carrying
 * aria-expanded + aria-controls, and the answer region is hidden from the
 * accessibility tree when collapsed. Buttons work with Enter/Space for free.
 */
(function () {
  'use strict';

  const questions = Array.from(document.querySelectorAll('.faq-q'));
  if (!questions.length) return;

  function setExpanded(btn, expanded) {
    btn.setAttribute('aria-expanded', String(expanded));
    const panel = document.getElementById(btn.getAttribute('aria-controls'));
    if (panel) panel.hidden = !expanded;
  }

  for (const btn of questions) {
    setExpanded(btn, btn.getAttribute('aria-expanded') === 'true');

    btn.addEventListener('click', () => {
      const willOpen = btn.getAttribute('aria-expanded') !== 'true';
      // One panel open at a time, matching the mockup's behaviour.
      for (const other of questions) setExpanded(other, false);
      setExpanded(btn, willOpen);
    });
  }
})();
