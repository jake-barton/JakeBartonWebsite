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
    groups.push({ element: section, videos: members, visible: false, starting: false });
  });
  videos.filter(function (video) { return !paired.has(video); }).forEach(function (video) {
    video.loop = true;
    groups.push({ element: video, videos: [video], visible: false, starting: false });
  });

  function pause(group) {
    group.videos.forEach(function (video) { video.pause(); });
  }

  function active(group) { return group.visible && !document.hidden; }

  function play(group) {
    if (!active(group) || group.starting) return;
    group.videos.forEach(function (video) { video.preload = 'auto'; });
    if (group.videos.some(function (video) { return video.readyState < 3; })) return;
    var time = group.videos[0].currentTime;
    group.videos.slice(1).forEach(function (video) {
      if (Math.abs(video.currentTime - time) > 0.06) video.currentTime = time;
    });
    group.starting = true;
    Promise.all(group.videos.map(function (video) { return video.play(); }))
      .catch(function () {})
      .finally(function () {
        group.starting = false;
        if (!active(group)) pause(group);
      });
  }

  groups.forEach(function (group) {
    group.videos.forEach(function (video) {
      video.addEventListener('canplay', function () { play(group); });
      if (group.videos.length > 1) {
        video.addEventListener('waiting', function () { pause(group); });
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

  // Both recordings are trimmed to the same events and duration. The gameplay
  // clock keeps the phone in step after buffering, tab changes and loop resets.
  window.setInterval(function () {
    groups.filter(function (group) { return group.videos.length > 1 && active(group); })
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
