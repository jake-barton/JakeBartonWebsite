(function () {
  'use strict';

  var filters = Array.prototype.slice.call(document.querySelectorAll('[data-work-filter]'));
  var sections = Array.prototype.slice.call(document.querySelectorAll('[data-work-section]'));
  var cards = Array.prototype.slice.call(document.querySelectorAll('[data-work-card]'));
  var motion = window.matchMedia('(prefers-reduced-motion: reduce)');
  var saveData = navigator.connection && navigator.connection.saveData;
  var canHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  var stopPreviews = new Map();

  function applyFilter(key) {
    filters.forEach(function (button) {
      var active = button.getAttribute('data-work-filter') === key;
      button.classList.toggle('is-active', active);
      button.setAttribute('aria-pressed', active ? 'true' : 'false');
    });

    cards.forEach(function (card) {
      var categories = (card.getAttribute('data-filters') || '').split(/\s+/);
      var visible = key === 'all' || categories.indexOf(key) !== -1 || (key === 'art' && (categories.indexOf('design') !== -1 || categories.indexOf('3d') !== -1));
      card.classList.toggle('is-filtered-out', !visible);
      if (!visible && stopPreviews.has(card)) stopPreviews.get(card)();
    });
  }

  filters.forEach(function (button) {
    button.addEventListener('click', function () {
      sections.forEach(function (section) { section.classList.remove('is-active'); });
      applyFilter(button.getAttribute('data-work-filter'));
    });
  });

  var requestedFilter = new URLSearchParams(window.location.search).get('filter');
  if (filters.some(function (button) { return button.getAttribute('data-work-filter') === requestedFilter; })) {
    applyFilter(requestedFilter);
  }

  sections.forEach(function (button) {
    button.addEventListener('click', function () {
      var key = button.getAttribute('data-work-section');
      sections.forEach(function (section) {
        section.classList.toggle('is-active', section === button);
      });
      applyFilter(key);
    });
  });

  cards.forEach(function (card) {
    var url = card.getAttribute('data-project-url');
    if (!url) return;

    card.setAttribute('role', 'link');
    var video = card.querySelector('[data-card-video]');
    if (video) {
      var playbackRequest = 0;
      var wantsPreview = false;
      var pendingFrame = null;

      function preparePreview() {
        video.preload = 'auto';
        video.muted = true;
        if (!video.getAttribute('src')) video.src = video.dataset.src;
      }

      function stopPreview() {
        wantsPreview = false;
        playbackRequest += 1;
        if (pendingFrame !== null && video.cancelVideoFrameCallback) video.cancelVideoFrameCallback(pendingFrame);
        pendingFrame = null;
        video.pause();
        if (video.readyState > 0) video.currentTime = 0;
        card.classList.remove('is-previewing');
      }

      function allowed() {
        return wantsPreview && !motion.matches && !document.hidden && !card.classList.contains('is-filtered-out');
      }

      function revealPreview() {
        if (!allowed() || video.readyState < 2 || video.paused || pendingFrame !== null) return;
        var request = playbackRequest;
        function reveal() {
          if (request !== playbackRequest) return;
          pendingFrame = null;
          if (allowed() && !video.paused) card.classList.add('is-previewing');
        }
        // Keep the authentic poster visible until a decoded video frame is ready.
        if (video.requestVideoFrameCallback) pendingFrame = video.requestVideoFrameCallback(reveal);
        else reveal();
      }

      function startPreview() {
        if (motion.matches || document.hidden || card.classList.contains('is-filtered-out') || wantsPreview) return;
        wantsPreview = true;
        var request = ++playbackRequest;
        preparePreview();
        // Request playback immediately; waiting for canplay first can stall Safari.
        video.play().then(function () {
          if (request !== playbackRequest) return;
          if (allowed()) revealPreview();
          else stopPreview();
        }).catch(function () {
          if (request === playbackRequest) stopPreview();
        });
      }

      stopPreviews.set(card, stopPreview);
      video.addEventListener('playing', function () {
        if (allowed()) revealPreview();
        else stopPreview();
      });
      video.addEventListener('error', stopPreview);
      card.addEventListener('mouseenter', startPreview);
      card.addEventListener('mouseleave', function () {
        if (!card.contains(document.activeElement)) stopPreview();
      });
      card.addEventListener('focusin', startPreview);
      card.addEventListener('focusout', function (event) {
        if (!card.contains(event.relatedTarget) && !card.matches(':hover')) stopPreview();
      });
      // Load small clips before the visitor reaches a card, without playing them.
      var warmObserver = new IntersectionObserver(function (entries) {
        if (entries[0].isIntersecting && canHover && !motion.matches && !saveData && !document.hidden && !card.classList.contains('is-filtered-out')) preparePreview();
      }, {rootMargin: '400px 0px'});
      warmObserver.observe(card);
      new IntersectionObserver(function (entries) {
        if (!entries[0].isIntersecting) stopPreview();
      }).observe(card);
      document.addEventListener('visibilitychange', function () {
        if (document.hidden) stopPreview();
        else if (card.matches(':hover') || card.contains(document.activeElement)) startPreview();
      });
      window.addEventListener('blur', stopPreview);
      motion.addEventListener('change', function () { if (motion.matches) stopPreview(); });
    }
    card.addEventListener('click', function () {
      if (window.jbNavigateWithCurtain) window.jbNavigateWithCurtain(url);
      else window.location.href = url;
    });
    card.addEventListener('keydown', function (event) {
      if (event.key !== 'Enter' && event.key !== ' ') return;
      event.preventDefault();
      if (window.jbNavigateWithCurtain) window.jbNavigateWithCurtain(url);
      else window.location.href = url;
    });
  });

  var footerWord = document.querySelector('.jb-footer-word');
  var footerVideos = Array.prototype.slice.call(document.querySelectorAll('[data-footer-video]'));

  if (footerWord && footerVideos.length) {
    var activeFooterVideo = 0;
    var footerTimer = null;

    function activateFooterVideo(index) {
      activeFooterVideo = index % footerVideos.length;
      footerVideos.forEach(function (video, videoIndex) {
        var active = videoIndex === activeFooterVideo;
        video.classList.toggle('is-active', active);
        if (active) video.play().catch(function () {});
        else if (!video.paused) video.pause();
      });
    }

    function startFooterVideos() {
      if (footerTimer) return;
      activateFooterVideo(activeFooterVideo);
      footerTimer = window.setInterval(function () {
        activateFooterVideo(activeFooterVideo + 1);
      }, 1000);
    }

    function stopFooterVideos() {
      window.clearInterval(footerTimer);
      footerTimer = null;
      footerVideos.forEach(function (video) {
        if (!video.paused) video.pause();
      });
    }

    activateFooterVideo(0);
    footerWord.addEventListener('mouseenter', startFooterVideos);
    footerWord.addEventListener('focus', startFooterVideos);
    footerWord.addEventListener('mouseleave', stopFooterVideos);
    footerWord.addEventListener('blur', stopFooterVideos);
  }

})();
