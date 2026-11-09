(function () {
  'use strict';
  var videos = Array.from(document.querySelectorAll('[data-site-reel]'));
  var players = Array.from(document.querySelectorAll('[data-reel-player]'));
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  var visible = new Set();
  var activeVideo = null;
  var time = 0;
  try { time = Number(sessionStorage.getItem('jb-reel-time')) || 0; } catch (ignore) {}

  function saveTime() {
    if (activeVideo && activeVideo.readyState > 0) time = activeVideo.currentTime;
    try { sessionStorage.setItem('jb-reel-time', String(time)); } catch (ignore) {}
  }

  function allowed(video) {
    if (document.hidden || reduceMotion.matches || !visible.has(video)) return false;
    if (players.some(function (player) { return !player.paused && !player.ended; })) return false;
    var context = video.dataset.siteReel;
    if (context === 'menu') return document.body.classList.contains('menu-open');
    if (document.body.classList.contains('menu-open')) return false;
    if (context === 'hero') return document.body.classList.contains('is-loaded');
    return video.classList.contains('is-active');
  }

  function update() {
    if (document.hidden || document.body.classList.contains('menu-open')) {
      players.forEach(function (player) { player.pause(); });
    }
    var priority = {menu: 4, hero: 3, action: 2, footer: 1};
    var next = videos.filter(allowed).sort(function (a, b) {
      return priority[b.dataset.siteReel] - priority[a.dataset.siteReel];
    })[0] || null;
    if (next !== activeVideo) {
      saveTime();
      activeVideo = next;
      if (next && next.readyState > 0 && Number.isFinite(next.duration)) next.currentTime = time % next.duration;
    }
    videos.forEach(function (video) {
      if (video === activeVideo) {
        video.play().catch(function () {});
      } else {
        video.pause();
      }
    });
  }

  videos.forEach(function (video) {
    video.controls = false;
    video.muted = true;
    video.addEventListener('contextmenu', function (event) { event.preventDefault(); });
    video.addEventListener('ratechange', function () {
      if (video.playbackRate !== 1) video.playbackRate = 1;
    });
    video.addEventListener('loadedmetadata', function () {
      if (video === activeVideo && Number.isFinite(video.duration)) video.currentTime = time % video.duration;
    });
    video.addEventListener('play', function () { if (video !== activeVideo) video.pause(); });
  });
  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) visible.add(entry.target);
      else visible.delete(entry.target);
    });
    update();
  }, {threshold: 0.01});
  videos.forEach(function (video) { observer.observe(video); });
  var playerObserver = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      var player = entry.target;
      if (!entry.isIntersecting && document.fullscreenElement !== player && !player.webkitDisplayingFullscreen) player.pause();
    });
  }, {threshold: 0});
  players.forEach(function (player) {
    player.addEventListener('play', update);
    player.addEventListener('pause', update);
    player.addEventListener('ended', update);
    playerObserver.observe(player);
  });
  new MutationObserver(update).observe(document.body, {subtree: true, attributes: true, attributeFilter: ['class']});
  document.addEventListener('visibilitychange', update);
  window.addEventListener('pagehide', saveTime);
  window.addEventListener('pageshow', update);
  reduceMotion.addEventListener('change', update);
})();
