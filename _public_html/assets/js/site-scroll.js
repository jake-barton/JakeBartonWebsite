(function () {
  'use strict';

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (!reduceMotion && typeof window.Lenis === 'function' && !window.jbSmoothScroller) {
    window.jbSmoothScroller = new window.Lenis({
      duration: 1.05,
      easing: function (value) {
        return Math.min(1, 1.001 - Math.pow(2, -10 * value));
      },
      smoothWheel: true,
      syncTouch: false,
      wheelMultiplier: 0.9
    });

    function frame(time) {
      window.jbSmoothScroller.raf(time);
      window.requestAnimationFrame(frame);
    }

    window.requestAnimationFrame(frame);
  }

  document.addEventListener('click', function (event) {
    var link = event.target.closest('a[href*="#"]');
    if (!link) return;

    var url;
    try {
      url = new URL(link.href, window.location.href);
    } catch (error) {
      return;
    }

    if (url.pathname !== window.location.pathname || !url.hash) return;

    var target;
    try {
      target = document.querySelector(url.hash);
    } catch (error) {
      return;
    }

    if (!target) return;
    event.preventDefault();

    if (window.jbSmoothScroller) {
      var margin = parseFloat(window.getComputedStyle(target).scrollMarginTop) || 0;
      window.jbSmoothScroller.scrollTo(window.scrollY + target.getBoundingClientRect().top - margin, { duration: 1.05 });
    } else {
      target.scrollIntoView({
        behavior: reduceMotion ? 'auto' : 'smooth',
        block: 'start'
      });
    }

    window.history.pushState(null, '', url.hash);
  });
})();
