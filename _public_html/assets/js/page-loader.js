(function () {
  'use strict';

  var body = document.body;
  var loader = document.getElementById('pageLoader');
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var timers = [];
  var navigating = false;

  function clearTimers() {
    timers.forEach(function (timer) { window.clearTimeout(timer); });
    timers = [];
  }

  function stage(className, delay) {
    timers.push(window.setTimeout(function () {
      loader.classList.add(className);
    }, delay));
  }

  function play() {
    clearTimers();
    navigating = false;

    if (!loader || reduceMotion) {
      if (loader) loader.classList.add('is-finished');
      try { window.sessionStorage.removeItem('jb-work-transition-covered'); } catch (ignore) {}
      body.classList.add('is-loaded');
      return;
    }

    var continueCovered = false;
    try {
      continueCovered = window.sessionStorage.getItem('jb-work-transition-covered') === '1';
      if (continueCovered) window.sessionStorage.removeItem('jb-work-transition-covered');
    } catch (ignore) {}

    loader.classList.remove('is-entering', 'is-masking', 'is-exiting', 'is-finished');
    body.classList.remove('is-loaded', 'menu-open');

    var rail = document.getElementById('jbRail');
    var button = document.getElementById('jbMenuButton');
    if (rail) rail.classList.remove('is-open');
    if (button) button.setAttribute('aria-expanded', 'false');

    if (continueCovered) {
      loader.classList.add('is-entering', 'is-masking');
      void loader.offsetWidth;
      requestAnimationFrame(function () {
        requestAnimationFrame(function () {
          loader.classList.add('is-exiting');
          body.classList.add('is-loaded');
          timers.push(window.setTimeout(function () { loader.classList.add('is-finished'); }, 1150));
        });
      });
      return;
    }

    void loader.offsetWidth;

    requestAnimationFrame(function () {
      requestAnimationFrame(function () {
        stage('is-entering', 60);
        stage('is-masking', 1180);
        stage('is-exiting', 2250);
        timers.push(window.setTimeout(function () {
          body.classList.add('is-loaded');
        }, 2250));
        timers.push(window.setTimeout(function () {
          loader.classList.add('is-finished');
        }, 3400));
      });
    });
  }

  function isHome(url) {
    return url.pathname === '/' || url.pathname === '/index.php' || url.pathname === '/home' || url.pathname === '/home.php';
  }

  function navigate(href) {
    if (navigating) return;
    var url = new URL(href, window.location.href);
    if (!loader || reduceMotion || isHome(url)) {
      window.location.href = url.href;
      return;
    }

    navigating = true;
    clearTimers();
    loader.classList.remove('is-entering', 'is-masking', 'is-exiting', 'is-finished');
    void loader.offsetWidth;
    loader.classList.add('is-entering');
    stage('is-masking', 1050);
    timers.push(window.setTimeout(function () {
      try { window.sessionStorage.setItem('jb-work-transition-covered', '1'); } catch (ignore) {}
      window.location.href = url.href;
    }, 2020));
  }

  window.jbNavigateWithCurtain = navigate;

  document.addEventListener('click', function (event) {
    var link = event.target.closest('a[href]');
    if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
    if (link.target === '_blank' || link.hasAttribute('download')) return;
    var href = link.getAttribute('href');
    if (!href || href.charAt(0) === '#' || /^(mailto:|tel:|javascript:)/i.test(href)) return;
    var url = new URL(link.href, window.location.href);
    if (url.origin !== window.location.origin || isHome(url)) return;
    event.preventDefault();
    navigate(url.href);
  });

  if (body.classList.contains('jb-work-page') || body.classList.contains('jb-detail-page')) {
    play();
  } else if (loader) {
    loader.classList.add('is-finished');
  }

  window.addEventListener('pageshow', function (event) {
    if (event.persisted) {
      navigating = false;
      if (body.classList.contains('jb-work-page') || body.classList.contains('jb-detail-page')) play();
      else if (loader) loader.classList.add('is-finished');
    }
  });
})();
