(function () {
  'use strict';
  var reduced = window.matchMedia('(prefers-reduced-motion: reduce)');
  var hover = window.matchMedia('(hover: hover) and (pointer: fine)');
  var stops = [];

  document.querySelectorAll('[data-quiet-egg]').forEach(function (element) {
    var pending = null;
    var finish = null;
    var lastPlay = -Infinity;
    var kart = element.dataset.quietEgg === 'kart';
    var sprite = kart ? element.querySelector('.jb-kart-cameo') : null;
    var frameRequest = null;
    // Actual lod0 turning frames and celebration pose from yoshi_sprites.js.
    var kartFrames = [
      [0,    233, 0,   30, 32],
      [0.26, 265, 0,   29, 32],
      [0.30, 298, 0,   29, 32],
      [0.34, 331, 0,   29, 32],
      [0.38, 365, 0,   28, 32],
      [0.43, 217, 149, 28, 31],
      [0.56, 365, 0,   28, 32],
      [0.60, 331, 0,   29, 32],
      [0.64, 298, 0,   29, 32],
      [0.68, 265, 0,   29, 32],
      [0.72, 233, 0,   30, 32]
    ];

    function animateKart(startedAt) {
      var previousFrame = -1;
      function frame(now) {
        var progress = Math.min((now - startedAt) / 2400, 1);
        if (progress >= 1) { stop(); return; }
        var index = 0;
        kartFrames.forEach(function (pose, i) { if (progress >= pose[0]) index = i; });
        if (index !== previousFrame) {
          var pose = kartFrames[index];
          // Keep every pose centered in a 30x32 viewport, with its wheels on the baseline.
          sprite.setAttribute('viewBox', [pose[1] - (30 - pose[3]) / 2, pose[2] + pose[4] - 32, 30, 32].join(' '));
          previousFrame = index;
        }
        frameRequest = requestAnimationFrame(frame);
      }
      frameRequest = requestAnimationFrame(frame);
    }

    function stop() {
      clearTimeout(pending);
      clearTimeout(finish);
      cancelAnimationFrame(frameRequest);
      frameRequest = null;
      if (sprite) sprite.setAttribute('viewBox', '233 0 30 32');
      element.classList.remove('is-egg-playing');
    }
    function play() {
      clearTimeout(pending);
      if (reduced.matches || document.hidden || element.classList.contains('is-egg-playing') || performance.now() - lastPlay < (kart ? 0 : 8000)) return;
      lastPlay = performance.now();
      element.style.setProperty('--egg-travel', (element.clientWidth + (kart ? 32 : 24)) + 'px');
      element.classList.add('is-egg-playing');
      if (sprite) animateKart(lastPlay);
      finish = setTimeout(stop, kart ? 2600 : 1800);
    }
    function wait() {
      clearTimeout(pending);
      pending = setTimeout(play, kart ? 180 : 650);
    }
    element.addEventListener('mouseenter', function () { if (hover.matches) wait(); });
    element.addEventListener('mouseleave', stop);
    element.addEventListener('focus', function () { if (element.matches(':focus-visible')) wait(); });
    element.addEventListener('blur', stop);
    element.addEventListener('click', play);
    new IntersectionObserver(function (entries) { if (!entries[0].isIntersecting) stop(); }).observe(element);
    stops.push(stop);
  });
  function stopAll() { stops.forEach(function (stop) { stop(); }); }
  document.addEventListener('visibilitychange', function () { if (document.hidden) stopAll(); });
  window.addEventListener('blur', stopAll);
  reduced.addEventListener('change', function () { if (reduced.matches) stopAll(); });
})();
