/**
 * effects.js  —  Jake Barton Portfolio Style Kit
 * Main effects orchestrator:
 *   - Loading screen (fade out on DOMContentLoaded)
 *   - Smooth scroll for anchor links
 *   - Scroll-reveal  (.reveal and .stagger-children)
 *   - Sticky nav (adds .scrolled class)
 *   - Mobile hamburger / stagger-menu
 *   - Rotating text   (.rotating-text, data-words='["a","b"]')
 *   - Card tilt       (.tilt-card)
 *   - Active nav link highlighting
 *
 * No dependencies — pure vanilla JS.
 */
(function () {
  'use strict';

  // ────────────────────────────────────────────────────────
  // 1. Loading Screen
  // ────────────────────────────────────────────────────────
  function initLoadingScreen() {
    var screen = document.querySelector('.loading-screen');
    if (!screen) return;

    window.addEventListener('load', function () {
      setTimeout(function () {
        screen.classList.add('hidden');
        screen.addEventListener('transitionend', function () {
          screen.remove();
        }, { once: true });
      }, 500);
    });
  }

  // ────────────────────────────────────────────────────────
  // 2. Smooth Scroll for anchor <a href="#...">
  // ────────────────────────────────────────────────────────
  function initSmoothScroll() {
    document.addEventListener('click', function (e) {
      var target = e.target.closest('a[href^="#"]');
      if (!target) return;
      var id = target.getAttribute('href');
      if (id === '#') return;
      var el = document.querySelector(id);
      if (!el) return;
      e.preventDefault();
      el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  }

  // ────────────────────────────────────────────────────────
  // 3. Scroll Reveal (IntersectionObserver)
  // ────────────────────────────────────────────────────────
  function initScrollReveal() {
    if (!window.IntersectionObserver) {
      // Fallback: just show everything
      document.querySelectorAll('.reveal, .stagger-children').forEach(function (el) {
        el.classList.add('is-visible');
      });
      return;
    }

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.12,
      rootMargin: '0px 0px -40px 0px'
    });

    document.querySelectorAll('.reveal, .stagger-children').forEach(function (el) {
      observer.observe(el);
    });
  }

  // ────────────────────────────────────────────────────────
  // 4. Sticky Navigation
  // ────────────────────────────────────────────────────────
  function initStickyNav() {
    var nav = document.querySelector('.site-nav, nav, header');
    if (!nav) return;

    function onScroll() {
      if (window.scrollY > 40) {
        nav.classList.add('scrolled');
      } else {
        nav.classList.remove('scrolled');
      }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  // ────────────────────────────────────────────────────────
  // 5. Mobile Nav Toggle / Stagger Menu
  // ────────────────────────────────────────────────────────
  function initMobileNav() {
    var toggle = document.querySelector('.nav-toggle');
    var overlay = document.querySelector('.stagger-menu-overlay');
    if (!toggle) return;

    toggle.addEventListener('click', function () {
      var isOpen = toggle.classList.toggle('open');
      if (overlay) overlay.classList.toggle('open', isOpen);
      document.body.style.overflow = isOpen ? 'hidden' : '';
    });

    // Close when a link is clicked
    if (overlay) {
      overlay.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function () {
          toggle.classList.remove('open');
          overlay.classList.remove('open');
          document.body.style.overflow = '';
        });
      });
    }
  }

  // ────────────────────────────────────────────────────────
  // 6. Rotating / Cycling Text
  //    <span class="rotating-text" data-words='["Designer","Developer","Creator"]'></span>
  // ────────────────────────────────────────────────────────
  function initRotatingText() {
    document.querySelectorAll('.rotating-text').forEach(function (el) {
      var wordsRaw = el.dataset.words || '["..."]';
      var words;
      try { words = JSON.parse(wordsRaw); } catch (e) { return; }
      if (!words.length) return;

      var interval = parseInt(el.dataset.interval) || 2500;
      var current = 0;

      el.textContent = words[0];
      el.classList.add('rotating-text-word');

      setInterval(function () {
        el.classList.add('exiting');
        setTimeout(function () {
          current = (current + 1) % words.length;
          el.textContent = words[current];
          el.classList.remove('exiting');
        }, 450);
      }, interval);
    });
  }

  // ────────────────────────────────────────────────────────
  // 7. Card Tilt  (.tilt-card)
  // ────────────────────────────────────────────────────────
  function initCardTilt() {
    var MAX_TILT = 8; // degrees

    document.querySelectorAll('.tilt-card').forEach(function (card) {
      card.addEventListener('mousemove', function (e) {
        var rect = card.getBoundingClientRect();
        var x = (e.clientX - rect.left) / rect.width  - 0.5;
        var y = (e.clientY - rect.top)  / rect.height - 0.5;
        card.style.transform = 'perspective(800px) rotateY(' + (x * MAX_TILT) + 'deg) rotateX(' + (-y * MAX_TILT) + 'deg) translateZ(10px)';
      });

      card.addEventListener('mouseleave', function () {
        card.style.transform = 'perspective(800px) rotateY(0deg) rotateX(0deg) translateZ(0)';
        card.style.transition = 'transform 0.5s ease';
      });

      card.addEventListener('mouseenter', function () {
        card.style.transition = 'transform 0.1s linear';
      });
    });
  }

  // ────────────────────────────────────────────────────────
  // 8. Active Nav Link (highlight based on current URL)
  // ────────────────────────────────────────────────────────
  function initActiveNavLink() {
    var current = window.location.pathname.split('/').pop() || 'index.php';
    document.querySelectorAll('.nav-links a, .stagger-menu-overlay a').forEach(function (link) {
      var href = link.getAttribute('href') || '';
      if (href === current || href.endsWith('/' + current)) {
        link.classList.add('active');
        link.style.color = 'var(--cyan)';
      }
    });
  }

  // ────────────────────────────────────────────────────────
  // 9. Parallax on scroll (simple vertical translate)
  //    <div class="parallax" data-speed="0.3">...</div>
  // ────────────────────────────────────────────────────────
  function initParallax() {
    var els = document.querySelectorAll('.parallax');
    if (!els.length) return;

    function onScroll() {
      var scrollY = window.scrollY;
      els.forEach(function (el) {
        var speed = parseFloat(el.dataset.speed) || 0.2;
        el.style.transform = 'translateY(' + (scrollY * speed) + 'px)';
      });
    }

    window.addEventListener('scroll', onScroll, { passive: true });
  }

  // ────────────────────────────────────────────────────────
  // 10. Inject a minimal Loading Screen (if not in HTML)
  // ────────────────────────────────────────────────────────
  function maybeInjectLoadingScreen() {
    if (document.querySelector('.loading-screen')) return; // already in HTML

    var screen = document.createElement('div');
    screen.className = 'loading-screen';
    screen.innerHTML = [
      '<span style="font-family:\'Bebas Neue\',sans-serif;font-size:2.5rem;color:#00D9FF;letter-spacing:0.15em;text-shadow:0 0 30px rgba(0,217,255,0.5)">JAKE BARTON</span>',
      '<div class="loading-bar-track"><div class="loading-bar-fill"></div></div>'
    ].join('');
    document.body.insertBefore(screen, document.body.firstChild);
  }

  // ────────────────────────────────────────────────────────
  // Boot
  // ────────────────────────────────────────────────────────
  function boot() {
    maybeInjectLoadingScreen();
    initLoadingScreen();
    initSmoothScroll();
    initScrollReveal();
    initStickyNav();
    initMobileNav();
    initRotatingText();
    initCardTilt();
    initActiveNavLink();
    initParallax();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
