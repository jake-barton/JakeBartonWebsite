(function () {
  'use strict';

  var body = document.body;
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var smoothScroller = null;

  function initRefreshTop() {
    if ('scrollRestoration' in history) {
      history.scrollRestoration = 'manual';
    }

    var navigation = performance.getEntriesByType && performance.getEntriesByType('navigation')[0];
    var isReload = navigation
      ? navigation.type === 'reload'
      : performance.navigation && performance.navigation.type === 1;

    if (!isReload) return;

    function resetScroll() {
      window.scrollTo(0, 0);
    }

    resetScroll();
    window.addEventListener('load', function () {
      resetScroll();
      requestAnimationFrame(resetScroll);
      window.setTimeout(resetScroll, 100);
    }, { once: true });
  }

  function initHeroNameZoomLock() {
    var name = document.querySelector('.jb-loader-name');
    if (!name || !window.outerWidth || !window.innerWidth) return;

    var initialViewportRatio = window.outerWidth / window.innerWidth;
    var baseViewportSize = window.matchMedia('(max-width: 760px)').matches ? 18.25 : 18;
    var frame = 0;

    function update() {
      frame = 0;
      if (!window.outerWidth || !window.innerWidth) return;

      var currentViewportRatio = window.outerWidth / window.innerWidth;
      var compensation = initialViewportRatio / currentViewportRatio;
      compensation = Math.min(4, Math.max(0.25, compensation));
      body.style.setProperty('--jb-title-final-size', (baseViewportSize * compensation) + 'vw');
    }

    function queueUpdate() {
      if (frame) window.cancelAnimationFrame(frame);
      frame = window.requestAnimationFrame(update);
    }

    window.addEventListener('resize', queueUpdate);
    if (window.visualViewport) {
      window.visualViewport.addEventListener('resize', queueUpdate);
    }
    update();
  }

  function initLoader() {
    var loader = document.getElementById('jbLoader');
    var percent = document.getElementById('jbLoaderPercent');
    var hero = document.querySelector('.jb-hero-video.is-active');
    var railDuration = 720;
    var exitDuration = 1450;
    if (!loader || !percent) {
      body.classList.add('is-loaded');
      return;
    }

    if (reduceMotion) {
      percent.textContent = '100%';
      loader.classList.add('is-exiting', 'is-hidden');
      body.classList.add('is-loaded');
      return;
    }

    var progress = 0;
    var completed = false;
    var startedAt = Date.now();
    var returning = false;
    try {
      returning = sessionStorage.getItem('jb-intro-seen') === '1';
      sessionStorage.setItem('jb-intro-seen', '1');
    } catch (error) {}

    function renderProgress(value) {
      progress = Math.min(100, Math.max(progress, value));
      percent.textContent = Math.round(progress) + '%';
    }

    function finish() {
      if (completed) return;
      completed = true;
      renderProgress(100);

      var minimum = returning ? 180 : 650;
      var remaining = Math.max(0, minimum - (Date.now() - startedAt));
      setTimeout(function () {
      loader.classList.add('is-softening');
      body.classList.add('is-loaded');

      setTimeout(function () {
        loader.classList.add('is-exiting');

        setTimeout(function () {
          loader.classList.add('is-hidden');
        }, exitDuration);
      }, railDuration);
      }, remaining);
    }

    renderProgress(0);

    var progressTimer = window.setInterval(function () {
      if (completed) {
        window.clearInterval(progressTimer);
        return;
      }
      renderProgress(progress + Math.max(1, (88 - progress) * 0.12));
    }, 90);

    if (!hero || hero.readyState >= 3 || returning) {
      finish();
    } else {
      hero.addEventListener('canplay', finish, { once: true });
      hero.addEventListener('error', finish, { once: true });
      window.setTimeout(finish, 2600);
    }
  }

  function initHeroVideos() {
    var videos = Array.from(document.querySelectorAll('.jb-hero-video[data-hero-video]'));
    if (!videos.length) return;

    var index = Math.max(0, videos.findIndex(function (video) {
      return video.classList.contains('is-active');
    }));

    function activate(nextIndex) {
      index = nextIndex % videos.length;
      videos.forEach(function (video, i) {
        var active = i === index;
        video.classList.toggle('is-active', active);
        if (active) {
          if (video.readyState > 0 && (video.currentTime < 0.25 || video.ended)) {
            var duration = Number.isFinite(video.duration) ? video.duration : 3;
            video.currentTime = Math.min(3, Math.max(0, duration - 0.5));
          }
          video.play().catch(function () {});
        } else if (!video.paused) {
          video.pause();
        }
      });
    }

    activate(index);

    if (videos.length > 1 && !reduceMotion) {
      window.setTimeout(function () {
        activate(index + 1);
        window.setInterval(function () {
          activate(index + 1);
        }, 4200);
      }, 6800);
    }
  }

  function initMenu() {
    var rail = document.getElementById('jbRail');
    var button = document.getElementById('jbMenuButton');
    if (!rail || !button) return;

    function setOpen(open) {
      rail.classList.toggle('is-open', open);
      body.classList.toggle('menu-open', open);
      button.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    button.addEventListener('click', function () {
      setOpen(!rail.classList.contains('is-open'));
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') setOpen(false);
    });

    rail.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        setOpen(false);
      });
    });
  }

  function initActionVideos() {
    var section = document.querySelector('.jb-actions');
    var links = Array.from(document.querySelectorAll('.jb-action-links a[data-video-index]'));
    var videos = Array.from(document.querySelectorAll('.jb-action-video[data-video-index]'));
    if (!section || !links.length || !videos.length) return;

    if (!reduceMotion) {
      section.classList.add('has-type-motion');
      var typeObserver = new IntersectionObserver(function (entries) {
        if (!entries[0].isIntersecting) return;
        section.classList.add('is-type-visible');
        typeObserver.disconnect();
      }, {threshold: 0.15, rootMargin: '0px 0px -10% 0px'});
      typeObserver.observe(section.querySelector('.jb-action-links'));
      section.addEventListener('focusin', function () { section.classList.add('is-type-visible'); });
    }

    var hoveredLink = null;
    var focusedLink = null;
    var activeVideo = null;
    var displayedVideo = null;
    var pendingFrame = null;
    var fadeTimer = null;
    var leaveTimer = null;
    var visible = false;
    var canHover = window.matchMedia('(hover: hover)');

    function preparePreview(video) {
      video.preload = 'auto';
      if (!video.getAttribute('src')) video.src = video.dataset.src;
    }

    function clearPendingFrame() {
      if (!pendingFrame) return;
      if (pendingFrame.video.cancelVideoFrameCallback) pendingFrame.video.cancelVideoFrameCallback(pendingFrame.id);
      pendingFrame = null;
    }

    // Fetch small preview copies before this section reaches the pointer.
    new IntersectionObserver(function (entries) {
      if (!entries[0].isIntersecting || reduceMotion || document.hidden || !canHover.matches || (navigator.connection && navigator.connection.saveData)) return;
      videos.forEach(preparePreview);
    }, {rootMargin: '600px 0px'}).observe(section);

    function reveal(video) {
      if (video !== activeVideo) return;
      if (displayedVideo !== video) {
        clearTimeout(fadeTimer);
        videos.forEach(function (item) {
          item.classList.toggle('is-previous', item === displayedVideo);
          item.classList.toggle('is-active', item === video);
        });
        displayedVideo = video;
        fadeTimer = setTimeout(function () {
          videos.forEach(function (item) { item.classList.remove('is-previous'); });
        }, 900);
      }
      section.classList.add('has-preview');
    }

    function revealWhenReady(video) {
      if (video !== activeVideo || (pendingFrame && pendingFrame.video === video)) return;
      if (reduceMotion) { reveal(video); return; }
      if (video.readyState < 2) return;
      var frame = {video: video, id: null};
      pendingFrame = frame;
      function ready() {
        if (pendingFrame !== frame) return;
        pendingFrame = null;
        if (video === activeVideo && visible && !document.hidden) reveal(video);
      }
      // Start the fade with a decoded frame, rather than an empty black player.
      if (video.requestVideoFrameCallback) frame.id = video.requestVideoFrameCallback(ready);
      else requestAnimationFrame(ready);
    }

    function update() {
      var link = hoveredLink || focusedLink;
      var show = !!link && visible && !document.hidden && !body.classList.contains('menu-open');
      var index = show ? link.dataset.videoIndex : null;
      var next = videos.find(function (video) { return video.dataset.videoIndex === index; }) || null;
      if (next !== activeVideo) clearPendingFrame();
      activeVideo = next;
      if (!activeVideo) section.classList.remove('has-preview');
      links.forEach(function (item) { item.classList.toggle('is-active', show && item === link); });
      videos.forEach(function (video) {
        if (video === activeVideo && !reduceMotion) {
          preparePreview(video);
          video.play().then(function () {
            if (video !== activeVideo || document.hidden || !visible) video.pause();
            else revealWhenReady(video);
          }).catch(function () {});
        } else {
          video.pause();
        }
      });
      if (activeVideo) revealWhenReady(activeVideo);
    }

    function leave() {
      // Bridge the tiny spaces between category lines without flashing the surface.
      clearTimeout(leaveTimer);
      leaveTimer = setTimeout(update, 120);
    }

    links.forEach(function (link) {
      link.addEventListener('mouseenter', function () {
        if (!canHover.matches) return;
        clearTimeout(leaveTimer);
        hoveredLink = link;
        update();
      });
      link.addEventListener('mouseleave', function () {
        if (hoveredLink === link) hoveredLink = null;
        leave();
      });
      link.addEventListener('focus', function () {
        clearTimeout(leaveTimer);
        if (link.matches(':focus-visible')) focusedLink = link;
        update();
      });
      link.addEventListener('blur', function () {
        if (focusedLink === link) focusedLink = null;
        leave();
      });
    });
    videos.forEach(function (video) {
      video.addEventListener('loadeddata', function () { revealWhenReady(video); });
      // A delayed play promise must not restart a clip after the pointer leaves.
      video.addEventListener('play', function () {
        if (video !== activeVideo || reduceMotion) video.pause();
      });
    });
    new IntersectionObserver(function (entries) {
      visible = entries[0].isIntersecting;
      if (!visible) hoveredLink = null;
      update();
    }, {threshold: 0}).observe(section);
    new MutationObserver(update).observe(body, {attributes: true, attributeFilter: ['class']});
    document.addEventListener('visibilitychange', update);
    window.addEventListener('blur', function () { hoveredLink = null; update(); });
    update();
  }

  function initActionScrollFrame() {
    var section = document.querySelector('.jb-actions');
    if (!section) return;

    if (reduceMotion) {
      section.style.setProperty('--jb-action-scale', '1');
      return;
    }

    var ticking = false;

    function clamp(value, min, max) {
      return Math.min(max, Math.max(min, value));
    }

    function smoothstep(value) {
      return value * value * (3 - 2 * value);
    }

    function update() {
      ticking = false;

      var rect = section.getBoundingClientRect();
      var viewport = window.innerHeight || document.documentElement.clientHeight;
      var distance = Math.abs(rect.top);
      var range = viewport;
      var proximity = clamp(1 - distance / range, 0, 1);
      var eased = smoothstep(proximity);
      var scale = 0.8 + eased * 0.2;

      section.style.setProperty('--jb-action-scale', scale.toFixed(4));
    }

    function requestUpdate() {
      if (ticking) return;
      ticking = true;
      requestAnimationFrame(update);
    }

    window.addEventListener('scroll', requestUpdate, { passive: true });
    window.addEventListener('resize', requestUpdate);
    requestUpdate();
  }

  function initReveals() {
    var elements = Array.from(document.querySelectorAll('.jb-reveal'));
    if (!elements.length) return;

    if (reduceMotion) {
      elements.forEach(function (element) {
        element.classList.add('is-inview');
      });
      return;
    }

    var ticking = false;

    function check() {
      ticking = false;
      elements = elements.filter(function (element) {
        var rect = element.getBoundingClientRect();
        var visible = rect.top < window.innerHeight * 0.88 && rect.bottom > window.innerHeight * 0.08;
        if (visible) {
          element.classList.add('is-inview');
          return false;
        }
        return true;
      });

      if (!elements.length) {
        window.removeEventListener('scroll', requestCheck);
        window.removeEventListener('resize', requestCheck);
      }
    }

    function requestCheck() {
      if (ticking) return;
      ticking = true;
      requestAnimationFrame(check);
    }

    window.addEventListener('scroll', requestCheck, { passive: true });
    window.addEventListener('resize', requestCheck);
    requestCheck();
  }

  function initSmoothAnchors() {
    document.addEventListener('click', function (event) {
      var link = event.target.closest('a[href^="#"]');
      if (!link) return;

      var target = document.querySelector(link.getAttribute('href'));
      if (!target) return;

      event.preventDefault();
      if (smoothScroller) {
        smoothScroller.scrollTo(target, { duration: 1.05 });
      } else {
        target.scrollIntoView({
          behavior: reduceMotion ? 'auto' : 'smooth',
          block: 'start'
        });
      }
    });
  }

  function initSmoothScroll() {
    if (window.jbSmoothScroller) return window.jbSmoothScroller;
    if (reduceMotion || typeof window.Lenis !== 'function') return null;

    var lenis = new window.Lenis({
      duration: 1.05,
      easing: function (value) {
        return Math.min(1, 1.001 - Math.pow(2, -10 * value));
      },
      smoothWheel: true,
      syncTouch: false,
      wheelMultiplier: 0.9
    });

    function frame(time) {
      lenis.raf(time);
      requestAnimationFrame(frame);
    }

    requestAnimationFrame(frame);
    window.jbSmoothScroller = lenis;
    return lenis;
  }

  function initFooterWordVideos() {
    var word = document.querySelector('.jb-footer-word');
    var videos = Array.from(document.querySelectorAll('[data-footer-video]'));
    if (!word || !videos.length) return;
    var activeIndex = 0;
    var timer = null;

    function activate(index) {
      activeIndex = index % videos.length;

      videos.forEach(function (video, index) {
        var active = index === activeIndex;
        video.classList.toggle('is-active', active);

        if (!active) {
          if (!video.paused) video.pause();
          return;
        }

        if (video.readyState > 0 && (video.currentTime < 0.25 || video.ended)) {
          var duration = Number.isFinite(video.duration) ? video.duration : 6;
          video.currentTime = Math.min(duration - 0.5, index * 0.85);
        }
        video.play().catch(function () {});
      });
    }

    function play() {
      if (timer) return;
      activate(activeIndex);
      timer = window.setInterval(function () {
        activate(activeIndex + 1);
      }, 1000);
    }

    function pause() {
      if (timer) {
        window.clearInterval(timer);
        timer = null;
      }
      videos.forEach(function (video) {
        if (!video.paused) video.pause();
      });
    }

    activate(0);
    word.addEventListener('mouseenter', play);
    word.addEventListener('focus', play);
    word.addEventListener('mouseleave', pause);
    word.addEventListener('blur', pause);
  }

  function initCurtainLinks() {
    var curtain = document.getElementById('jbCurtain');
    if (!curtain || reduceMotion) return;

    window.addEventListener('pageshow', function () {
      curtain.classList.remove('is-animating');
      body.classList.remove('menu-open');

      var rail = document.getElementById('jbRail');
      var button = document.getElementById('jbMenuButton');
      if (rail) rail.classList.remove('is-open');
      if (button) button.setAttribute('aria-expanded', 'false');
    });

    document.addEventListener('click', function (event) {
      var link = event.target.closest('a[href]');
      if (!link) return;

      var href = link.getAttribute('href');
      if (!href || href.charAt(0) === '#') return;
      if (link.target === '_blank' || link.hasAttribute('download')) return;
      if (/^(mailto:|tel:|javascript:)/i.test(href)) return;

      var url = new URL(link.href, window.location.href);
      if (url.origin !== window.location.origin) return;
      if (url.pathname === '/work' || url.pathname.indexOf('/work/') === 0) return;

      event.preventDefault();
      curtain.classList.add('is-animating');
      setTimeout(function () {
        window.location.href = url.href;
      }, 820);
    });
  }

  function boot() {
    initRefreshTop();
    initHeroNameZoomLock();
    initHeroVideos();
    initLoader();
    initMenu();
    initActionVideos();
    smoothScroller = initSmoothScroll();
    initActionScrollFrame();
    initReveals();
    initFooterWordVideos();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
