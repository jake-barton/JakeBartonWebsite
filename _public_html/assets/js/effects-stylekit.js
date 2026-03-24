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
  // 1. Page Transition (Wix-style curtain wipe)
  //    - Curtain starts covering the page (translateY 0)
  //    - On DOMContentLoaded it lifts up (translateY -100%)
  //    - On any same-site link click, the curtain drops in
  //      from the bottom (translateY 100% → 0), then navigates
  //    - pageshow handles bfcache restores (browser back/forward)
  // ────────────────────────────────────────────────────────
  function initPageTransition() {
    // Inject the curtain div once
    var curtain = document.getElementById('page-transition');
    if (!curtain) {
      curtain = document.createElement('div');
      curtain.id = 'page-transition';
      curtain.className = 'page-transition';
      document.body.insertBefore(curtain, document.body.firstChild);
    }

    // Lift the curtain — shared function used on both fresh load and bfcache restore
    function liftCurtain() {
      curtain.classList.remove('is-entering', 'is-covering');
      requestAnimationFrame(function () {
        requestAnimationFrame(function () {
          curtain.classList.add('is-out');
        });
      });
    }

    // Initial page load
    liftCurtain();

    // bfcache restore (browser back / forward button)
    // persisted === true means the page was served from bfcache
    window.addEventListener('pageshow', function (e) {
      if (e.persisted) {
        liftCurtain();
      }
    });

    // Intercept same-site link clicks
    document.addEventListener('click', function (e) {
      var link = e.target.closest('a');
      if (!link) return;

      var href = link.getAttribute('href');
      if (!href) return;

      // Skip: external, anchors, mailto/tel, new-tab, javascript:
      var isExternal = link.hostname && link.hostname !== window.location.hostname;
      var isAnchor   = href.charAt(0) === '#';
      var isSpecial  = /^(mailto:|tel:|javascript:)/.test(href);
      var isNewTab   = link.target === '_blank';
      if (isExternal || isAnchor || isSpecial || isNewTab) return;

      e.preventDefault();
      var destination = link.href;

      // Snap curtain to bottom (no transition), then slide it in
      curtain.classList.remove('is-out', 'is-covering');
      curtain.classList.add('is-entering');

      // One frame later, apply covering class to trigger the slide-in transition
      requestAnimationFrame(function () {
        requestAnimationFrame(function () {
          curtain.classList.remove('is-entering');
          curtain.classList.add('is-covering');

          // Navigate after the curtain covers the screen
          setTimeout(function () {
            window.location.href = destination;
          }, 680);
        });
      });
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
  //    Supports: .reveal, .reveal-up, .reveal-left, .reveal-right
  //              .stagger-children, .stagger-children-left
  // ────────────────────────────────────────────────────────
  function initScrollReveal() {
    var selector = '.reveal, .reveal-up, .reveal-left, .reveal-right, .stagger-children, .stagger-children-left, .stagger-pop';

    if (!window.IntersectionObserver) {
      document.querySelectorAll(selector).forEach(function (el) {
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
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    });

    document.querySelectorAll(selector).forEach(function (el) {
      observer.observe(el);
    });
  }

  // ────────────────────────────────────────────────────────
  // 3b. Hero word-split entrance
  //     Wraps each word in .hero-name and .hero-tagline
  //     in a <span> and staggers them in on load
  // ────────────────────────────────────────────────────────
  function initHeroTextReveal() {
    // Name: reveal word by word
    var nameEl = document.querySelector('.hero-name');
    if (nameEl) {
      var words = nameEl.textContent.trim().split(/\s+/);
      nameEl.innerHTML = words.map(function (w, i) {
        return '<span class="word-reveal" style="transition-delay:' + (i * 120 + 200) + 'ms">' + w + '</span>';
      }).join(' ');
      // Trigger after a short frame
      requestAnimationFrame(function () {
        requestAnimationFrame(function () {
          nameEl.querySelectorAll('.word-reveal').forEach(function (span) {
            span.classList.add('in');
          });
        });
      });
    }

    // Tagline: fade up line by line
    var taglineEl = document.querySelector('.hero-tagline');
    if (taglineEl) {
      taglineEl.style.opacity = '0';
      taglineEl.style.transform = 'translateY(22px)';
      taglineEl.style.transition = 'opacity 0.8s cubic-bezier(0.16,1,0.3,1) 0.55s, transform 0.8s cubic-bezier(0.16,1,0.3,1) 0.55s';
      requestAnimationFrame(function () {
        requestAnimationFrame(function () {
          taglineEl.style.opacity = '1';
          taglineEl.style.transform = 'translateY(0)';
        });
      });
    }

    // Subtitle, cta, stats — cascade in
    ['.hero-subtitle', '.hero-cta', '.hero-stats'].forEach(function (sel, i) {
      var el = document.querySelector(sel);
      if (!el) return;
      el.style.opacity = '0';
      el.style.transform = 'translateY(18px)';
      el.style.transition = 'opacity 0.7s cubic-bezier(0.16,1,0.3,1) ' + (0.75 + i * 0.15) + 's, transform 0.7s cubic-bezier(0.16,1,0.3,1) ' + (0.75 + i * 0.15) + 's';
      requestAnimationFrame(function () {
        requestAnimationFrame(function () {
          el.style.opacity = '1';
          el.style.transform = 'translateY(0)';
        });
      });
    });
  }

  // ────────────────────────────────────────────────────────
  // 3c. Animated number counters
  //     <span class="hero-stat-num" data-count="20">20+</span>
  //     Counts up from 0 to data-count when scrolled into view
  // ────────────────────────────────────────────────────────
  function initCounters() {
    var counters = document.querySelectorAll('[data-count]');
    if (!counters.length) return;

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        observer.unobserve(entry.target);

        var el = entry.target;
        var target = parseFloat(el.dataset.count);
        var suffix = el.dataset.suffix || '';
        var isDecimal = target % 1 !== 0;
        var duration = 1400;
        var start = null;

        function step(ts) {
          if (!start) start = ts;
          var progress = Math.min((ts - start) / duration, 1);
          // ease out expo
          var eased = 1 - Math.pow(1 - progress, 4);
          var val = target * eased;
          el.textContent = isDecimal ? val.toFixed(1) + suffix : Math.floor(val) + suffix;
          if (progress < 1) {
            requestAnimationFrame(step);
          } else {
            el.textContent = (isDecimal ? target.toFixed(1) : target) + suffix;
          }
        }
        requestAnimationFrame(step);
      });
    }, { threshold: 0.5 });

    counters.forEach(function (el) {
      observer.observe(el);
    });
  }

  // ────────────────────────────────────────────────────────
  // 3d. Magnetic button hover
  //     Elements with class .magnetic pull toward the cursor
  // ────────────────────────────────────────────────────────
  function initMagneticButtons() {
    document.querySelectorAll('.magnetic').forEach(function (btn) {
      btn.addEventListener('mousemove', function (e) {
        var rect = btn.getBoundingClientRect();
        var cx = rect.left + rect.width / 2;
        var cy = rect.top + rect.height / 2;
        var dx = (e.clientX - cx) * 0.28;
        var dy = (e.clientY - cy) * 0.28;
        btn.style.transform = 'translate(' + dx + 'px,' + dy + 'px)';
        btn.style.transition = 'transform 0.1s linear';
      });

      btn.addEventListener('mouseleave', function () {
        btn.style.transform = 'translate(0,0)';
        btn.style.transition = 'transform 0.5s cubic-bezier(0.16,1,0.3,1)';
      });
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
  // 5. Mobile Nav Toggle — handled by staggered-menu.js
  // ────────────────────────────────────────────────────────
  function initMobileNav() {
    // No-op: staggered-menu.js owns the hamburger & panel
  }

  // ────────────────────────────────────────────────────────
  // 6. Rotating / Cycling Text
  //    For <em class="rotating-text">:
  //      - After fonts load, measures the px width of every word
  //      - Sets a fixed pixel width on the <em> equal to the widest
  //      - Wraps text in an inner <span class="rt-text"> that slides
  //        in/out while the outer <em> never changes size
  //    For <span class="rotating-text">: simple text-swap
  // ────────────────────────────────────────────────────────
  function initRotatingText() {
    document.querySelectorAll('.rotating-text').forEach(function (el) {
      var wordsRaw = el.dataset.words || '["..."]';
      var words;
      try { words = JSON.parse(wordsRaw); } catch (e) { return; }
      if (!words.length) return;

      var intervalMs = parseInt(el.dataset.interval) || 2500;
      var current = 0;

      if (el.tagName === 'EM') {
        function setup() {
          // 1. Measure every word using a ghost <em> inside the same parent
          //    so font-family/size inherit correctly
          var parent = el.parentNode;
          var ghost = document.createElement('em');
          ghost.setAttribute('aria-hidden', 'true');
          ghost.style.cssText = [
            'position:absolute',
            'top:-9999px',
            'left:-9999px',
            'visibility:hidden',
            'white-space:nowrap',
            'pointer-events:none',
            'font-style:italic',
            'display:inline-block'
          ].join(';');
          parent.appendChild(ghost);

          var maxPx = 0;
          words.forEach(function (w) {
            ghost.textContent = w;
            var pw = ghost.getBoundingClientRect().width;
            if (pw > maxPx) maxPx = pw;
          });
          parent.removeChild(ghost);

          // 2. Lock the <em>'s width to the widest word (+ 4px safety)
          el.classList.add('rt-inline');
          el.style.width = Math.ceil(maxPx + 4) + 'px';

          // 3. Wrap current text in the animating inner span
          var inner = document.createElement('span');
          inner.className = 'rt-text';
          inner.textContent = words[0];
          el.textContent = '';
          el.appendChild(inner);

          // 4. Cycle
          setInterval(function () {
            inner.classList.add('exiting');
            setTimeout(function () {
              current = (current + 1) % words.length;
              inner.textContent = words[current];
              inner.classList.remove('exiting');
            }, 300);
          }, intervalMs);
        }

        if (document.fonts && document.fonts.ready) {
          document.fonts.ready.then(setup);
        } else {
          setTimeout(setup, 400);
        }
        return;
      }

      // Generic <span> fallback
      el.textContent = words[0];
      el.classList.add('rotating-text-word');
      setInterval(function () {
        el.classList.add('exiting');
        setTimeout(function () {
          current = (current + 1) % words.length;
          el.textContent = words[current];
          el.classList.remove('exiting');
        }, 450);
      }, intervalMs);
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
    document.querySelectorAll('.nav-links a, .sm-panel-item').forEach(function (link) {
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
  // 10. Custom Cursor (dot + outline ring)
  // ────────────────────────────────────────────────────────
  function initCustomCursor() {
    // Don't run on touch / coarse pointer devices
    if (window.matchMedia('(hover: none), (pointer: coarse)').matches) return;

    // Inject elements if not already present
    var dot = document.getElementById('cursor-dot');
    var ring = document.getElementById('cursor-outline');
    if (!dot) {
      dot = document.createElement('div');
      dot.id = 'cursor-dot';
      document.body.appendChild(dot);
    }
    if (!ring) {
      ring = document.createElement('div');
      ring.id = 'cursor-outline';
      document.body.appendChild(ring);
    }

    var mx = -100, my = -100; // off-screen initially
    var rx = -100, ry = -100;
    var raf;

    document.addEventListener('mousemove', function (e) {
      mx = e.clientX; my = e.clientY;
      dot.style.left = mx + 'px';
      dot.style.top  = my + 'px';
      dot.style.opacity = '1';
    });

    document.addEventListener('mouseleave', function () {
      dot.style.opacity = '0';
      ring.style.opacity = '0';
    });
    document.addEventListener('mouseenter', function () {
      dot.style.opacity = '1';
      ring.style.opacity = '1';
    });

    // Smooth-follow ring
    function lerp(a, b, t) { return a + (b - a) * t; }
    function loop() {
      rx = lerp(rx, mx, 0.14);
      ry = lerp(ry, my, 0.14);
      ring.style.left = rx + 'px';
      ring.style.top  = ry + 'px';
      raf = requestAnimationFrame(loop);
    }
    loop();

    // Hover state — expand ring on interactive elements
    var hoverTargets = 'a, button, .btn, .btn-primary, .btn-secondary, .showcase-card, .glass-card, .skill-pill, .work-card, .magnetic, .tilt-card, input, textarea, label';
    document.addEventListener('mouseover', function (e) {
      if (e.target.closest(hoverTargets)) {
        document.body.classList.add('cursor-hover');
      }
    });
    document.addEventListener('mouseout', function (e) {
      if (e.target.closest(hoverTargets)) {
        document.body.classList.remove('cursor-hover');
      }
    });
  }

  // ────────────────────────────────────────────────────────
  // 11. Active Nav Link on Scroll (section spy)
  //     Highlights the nav link for the currently visible section
  // ────────────────────────────────────────────────────────
  function initActiveNavOnScroll() {
    var sections = document.querySelectorAll('section[id]');
    if (!sections.length) return;

    var navLinks = document.querySelectorAll('.nav-links a[href^="#"]');
    if (!navLinks.length) return;

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          var id = entry.target.id;
          navLinks.forEach(function (link) {
            var isMatch = link.getAttribute('href') === '#' + id;
            link.classList.toggle('active', isMatch);
          });
        }
      });
    }, {
      threshold: 0.35,
      rootMargin: '-10% 0px -55% 0px'
    });

    sections.forEach(function (s) { observer.observe(s); });
  }

  // ────────────────────────────────────────────────────────
  // Boot
  // ────────────────────────────────────────────────────────
  function boot() {
    initPageTransition();
    initSmoothScroll();
    initScrollReveal();
    initHeroTextReveal();
    initCounters();
    initMagneticButtons();
    initStickyNav();
    initMobileNav();
    initRotatingText();
    initCardTilt();
    initActiveNavLink();
    initParallax();
    initCustomCursor();
    initActiveNavOnScroll();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
