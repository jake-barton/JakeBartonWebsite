/**
 * cursor-ribbons.js  —  Jake Barton Portfolio Style Kit
 * Cyan ribbon trail that follows the mouse cursor.
 * Pure Canvas 2D — no dependencies, drop into any page.
 *
 * Usage: just include this script before </body>.
 * A cursor dot + ribbon canvas are injected automatically.
 *
 * Config (edit below):
 */
(function () {
  'use strict';

  // ── Configuration ───────────────────────────────────────
  var CONFIG = {
    colors: ['#00D9FF', '#1E40AF', '#00D9FF'],  // ribbon colour stops along the trail
    lineCount: 0,          // set to 0 to disable ribbon trails (cursor dot/ring still active)
    pointCount: 60,        // trail length (number of tracked positions per line)
    maxAge: 400,           // ms before tail fades to nothing
    baseThickness: 18,     // px width of the ribbon at the head
    speedMultiplier: 0.35, // how fast the tail chases the mouse (0-1)
    spring: 0.05,          // spring force towards mouse
    friction: 0.85,        // velocity damping
    offsetFactor: 0.02,    // how far apart parallel ribbons are spread
    enableFade: true,      // fade opacity toward the tail
    backgroundColor: 'transparent'
  };

  // ── State ────────────────────────────────────────────────
  var canvas, ctx;
  var mouseX = window.innerWidth / 2;
  var mouseY = window.innerHeight / 2;
  var lines = [];
  var frameId;
  var lastTime = performance.now();

  // ── Helpers ──────────────────────────────────────────────
  function lerp(a, b, t) { return a + (b - a) * t; }

  function hexToRgb(hex) {
    var r = parseInt(hex.slice(1, 3), 16);
    var g = parseInt(hex.slice(3, 5), 16);
    var b = parseInt(hex.slice(5, 7), 16);
    return { r: r, g: g, b: b };
  }

  function buildColorStops() {
    return CONFIG.colors.map(function (c, i) {
      return { t: i / Math.max(CONFIG.colors.length - 1, 1), rgb: hexToRgb(c) };
    });
  }

  function colorAtT(stops, t) {
    for (var i = 0; i < stops.length - 1; i++) {
      var s = stops[i], e = stops[i + 1];
      if (t >= s.t && t <= e.t) {
        var lt = (t - s.t) / (e.t - s.t);
        return {
          r: Math.round(lerp(s.rgb.r, e.rgb.r, lt)),
          g: Math.round(lerp(s.rgb.g, e.rgb.g, lt)),
          b: Math.round(lerp(s.rgb.b, e.rgb.b, lt))
        };
      }
    }
    return stops[stops.length - 1].rgb;
  }

  // ── Initialise Lines ─────────────────────────────────────
  function initLines() {
    lines = [];
    var colorStops = buildColorStops();
    for (var i = 0; i < CONFIG.lineCount; i++) {
      var spread = (i - (CONFIG.lineCount - 1) / 2) * CONFIG.offsetFactor * window.innerWidth;
      var pts = [];
      for (var j = 0; j < CONFIG.pointCount; j++) {
        pts.push({ x: mouseX + spread, y: mouseY });
      }
      lines.push({
        offsetX: spread,
        offsetY: (i - (CONFIG.lineCount - 1) / 2) * CONFIG.offsetFactor * window.innerHeight * 0.5,
        vx: 0,
        vy: 0,
        points: pts,
        colorStops: colorStops
      });
    }
  }

  // ── Canvas Setup ─────────────────────────────────────────
  function createCanvas() {
    canvas = document.createElement('canvas');
    canvas.id = 'ribbons-canvas';
    canvas.style.cssText = [
      'position:fixed',
      'top:0',
      'left:0',
      'width:100%',
      'height:100%',
      'pointer-events:none',
      'z-index:9990'
    ].join(';');
    document.body.appendChild(canvas);
    ctx = canvas.getContext('2d');
    resize();
  }

  function createCursorDot() {
    if (document.getElementById('cursor-dot')) return;
    var dot = document.createElement('div');
    dot.id = 'cursor-dot';
    document.body.appendChild(dot);

    var ring = document.createElement('div');
    ring.id = 'cursor-outline';
    document.body.appendChild(ring);
  }

  function resize() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
  }

  // ── Mouse tracking ───────────────────────────────────────
  var ringX = mouseX, ringY = mouseY;

  function onMouseMove(e) {
    mouseX = e.clientX;
    mouseY = e.clientY;
    var dot = document.getElementById('cursor-dot');
    if (dot) {
      dot.style.left = mouseX + 'px';
      dot.style.top = mouseY + 'px';
    }
  }

  function onTouchMove(e) {
    if (!e.touches.length) return;
    mouseX = e.touches[0].clientX;
    mouseY = e.touches[0].clientY;
  }

  // ── Draw single ribbon line ───────────────────────────────
  function drawLine(line) {
    if (line.points.length < 2) return;
    var n = line.points.length;

    for (var i = 0; i < n - 1; i++) {
      var t = i / (n - 1);
      var tNext = (i + 1) / (n - 1);
      var col = colorAtT(line.colorStops, t);
      var alpha = CONFIG.enableFade ? (1 - t) * 0.85 : 0.85;
      var thickness = CONFIG.baseThickness * (1 - t * 0.85);

      var p0 = line.points[i];
      var p1 = line.points[i + 1];

      ctx.beginPath();
      ctx.moveTo(p0.x, p0.y);
      ctx.lineTo(p1.x, p1.y);
      ctx.strokeStyle = 'rgba(' + col.r + ',' + col.g + ',' + col.b + ',' + alpha + ')';
      ctx.lineWidth = thickness;
      ctx.lineCap = 'round';
      ctx.lineJoin = 'round';
      ctx.stroke();
    }
  }

  // ── Animation Loop ───────────────────────────────────────
  function update(now) {
    frameId = requestAnimationFrame(update);
    var dt = now - lastTime;
    lastTime = now;

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Animate cursor ring toward mouse (lerp for smooth lag)
    ringX = lerp(ringX, mouseX, 0.15);
    ringY = lerp(ringY, mouseY, 0.15);
    var ring = document.getElementById('cursor-outline');
    if (ring) {
      ring.style.left = ringX + 'px';
      ring.style.top = ringY + 'px';
    }

    lines.forEach(function (line) {
      var targetX = mouseX + line.offsetX;
      var targetY = mouseY + line.offsetY;

      // Spring physics: head chases mouse
      line.vx += (targetX - line.points[0].x) * CONFIG.spring;
      line.vy += (targetY - line.points[0].y) * CONFIG.spring;
      line.vx *= CONFIG.friction;
      line.vy *= CONFIG.friction;
      line.points[0].x += line.vx;
      line.points[0].y += line.vy;

      // Each subsequent point chases the one before it
      var alpha = Math.min(1, (dt * CONFIG.speedMultiplier) / (CONFIG.maxAge / (line.points.length - 1)));
      for (var i = 1; i < line.points.length; i++) {
        line.points[i].x = lerp(line.points[i].x, line.points[i - 1].x, alpha);
        line.points[i].y = lerp(line.points[i].y, line.points[i - 1].y, alpha);
      }

      drawLine(line);
    });
  }

  // ── Boot ────────────────────────────────────────────────
  function init() {
    createCanvas();
    createCursorDot();
    initLines();

    window.addEventListener('mousemove', onMouseMove);
    window.addEventListener('touchmove', onTouchMove, { passive: true });
    window.addEventListener('resize', function () {
      resize();
      initLines();
    });

    requestAnimationFrame(update);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
