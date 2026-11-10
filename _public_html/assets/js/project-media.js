(function () {
  'use strict';

  var videos = Array.from(document.querySelectorAll('.jb-detail-main video'));
  if (!videos.length) return;

  // Project footage is a silent, automatic presentation rather than a player UI.
  videos.forEach(function (video) {
    video.controls = false;
    video.muted = true;
    video.defaultPlaybackRate = 1;
    video.playbackRate = 1;
    video.tabIndex = -1;
    video.disablePictureInPicture = true;
    video.addEventListener('contextmenu', function (event) { event.preventDefault(); });
    video.addEventListener('ratechange', function () {
      if (video.playbackRate !== 1) video.playbackRate = 1;
      if (video.defaultPlaybackRate !== 1) video.defaultPlaybackRate = 1;
    });
  });

  var groups = [];
  var paired = new Set();
  document.querySelectorAll('[data-synced-videos]').forEach(function (section) {
    var members = Array.from(section.querySelectorAll('video'));
    members.forEach(function (video) { paired.add(video); video.loop = false; });
    groups.push({ element: section, videos: members, visible: false, starting: false, buffering: false });
  });
  videos.filter(function (video) { return !paired.has(video); }).forEach(function (video) {
    video.loop = true;
    groups.push({ element: video, videos: [video], visible: false, starting: false, buffering: false });
  });

  function pause(group) {
    group.videos.forEach(function (video) { video.pause(); });
  }

  function active(group) { return group.visible && !document.hidden; }

  function play(group) {
    if (!active(group) || group.starting) return;
    group.videos.forEach(function (video) { video.preload = 'auto'; });
    // play() starts the network request, including on Safari where preload is a
    // hint. Waiting for canplay before requesting playback can leave a poster stuck.
    if (group.buffering && group.videos.some(function (video) { return video.readyState < 3; })) return;
    group.buffering = false;
    var time = group.videos[0].currentTime;
    group.videos.slice(1).forEach(function (video) {
      if (video.readyState > 0 && Math.abs(video.currentTime - time) > 0.06) video.currentTime = time;
    });
    group.starting = true;
    Promise.all(group.videos.map(function (video) { return video.play(); }))
      .catch(function () { pause(group); })
      .finally(function () {
        group.starting = false;
        if (!active(group)) pause(group);
        else if (group.buffering && group.videos.every(function (video) { return video.readyState >= 3; })) play(group);
      });
  }

  groups.forEach(function (group) {
    group.videos.forEach(function (video) {
      video.addEventListener('canplay', function () { play(group); });
      video.addEventListener('playing', function () { if (!active(group)) pause(group); });
      if (group.videos.length > 1) {
        video.addEventListener('waiting', function () { group.buffering = true; pause(group); });
        video.addEventListener('ended', function () {
          pause(group);
          group.videos.forEach(function (member) { member.currentTime = 0; });
          play(group);
        });
      }
    });
  });

  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      var group = groups.find(function (item) { return item.element === entry.target; });
      group.visible = entry.isIntersecting;
      if (active(group)) play(group);
      else pause(group);
    });
  }, { threshold: 0 });
  groups.forEach(function (group) { observer.observe(group.element); });

  // Warm upcoming footage before it enters view; leave distant galleries alone.
  var preloadObserver = new IntersectionObserver(function (entries) {
    if (document.hidden || (navigator.connection && navigator.connection.saveData)) return;
    entries.forEach(function (entry) {
      if (!entry.isIntersecting) return;
      var group = groups.find(function (item) { return item.element === entry.target; });
      group.videos.forEach(function (video) { video.preload = 'auto'; });
    });
  }, {rootMargin: '600px 0px'});
  groups.forEach(function (group) { preloadObserver.observe(group.element); });

  // Both recordings are trimmed to the same events and duration. The gameplay
  // clock keeps the phone in step after buffering, tab changes and loop resets.
  window.setInterval(function () {
    groups.filter(function (group) { return group.videos.length > 1 && active(group) && !group.starting; })
      .forEach(function (group) {
        var master = group.videos[0];
        if (group.videos.some(function (video) { return video.readyState < 3; })) {
          pause(group);
          return;
        }
        group.videos.slice(1).forEach(function (video) {
          if (Math.abs(video.currentTime - master.currentTime) > 0.06) video.currentTime = master.currentTime;
        });
        if (group.videos.some(function (video) { return video.paused; })) play(group);
      });
  }, 100);

  document.addEventListener('visibilitychange', function () {
    groups.forEach(function (group) { if (active(group)) play(group); else pause(group); });
  });
})();
