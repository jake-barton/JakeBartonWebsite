/**
 * beams-bg.js  —  Jake Barton Portfolio Style Kit
 * Animated beam / aurora background effect.
 * Pure Canvas 2D — no WebGL / no dependencies.
 *
 * Usage: just include this script. A <canvas id="beams-canvas"> is
 * injected automatically behind all page content.
 *
 * Or create your own canvas with id="beams-canvas" in HTML and it
 * will be used instead.
 *
 * Config (edit below):
 */
(function () {
  'use strict';

  var CONFIG = {
    beamCount: 8,
    colors: [
      'rgba(204, 120, 92, ALPHA)',   // terracotta accent
      'rgba(201, 169, 110, ALPHA)',  // warm gold
      'rgba(204, 120, 92, ALPHA)',   // terracotta (double weight)
      'rgba(150, 80, 55, ALPHA)',    // deeper terracotta
      'rgba(232, 149, 109, ALPHA)',  // accent-light
      'rgba(80, 45, 25, ALPHA)',     // dark amber
      'rgba(201, 169, 110, ALPHA)',  // gold
      'rgba(204, 120, 92, ALPHA)'    // terracotta
    ],
    minAlpha: 0.02,
    maxAlpha: 0.07,
    minWidth: 60,     // px
    maxWidth: 280,    // px
    minSpeed: 0.1,    // px per frame
    maxSpeed: 0.4,
    blur: 90,         // canvas filter blur (px)
    fps: 50
  };

  // ── Types / State ─────────────────────────────────────────
  var canvas, ctx;
  var beams = [];
  var frameId;
  var lastTime = 0;
  var frameDuration = 1000 / CONFIG.fps;

  function rand(min, max) { return min + Math.random() * (max - min); }

  function makeBeam(index) {
    var alpha = rand(CONFIG.minAlpha, CONFIG.maxAlpha);
    var colorTemplate = CONFIG.colors[index % CONFIG.colors.length];
    return {
      x: rand(-100, canvas.width + 100),
      color: colorTemplate.replace('ALPHA', alpha.toFixed(3)),
      width: rand(CONFIG.minWidth, CONFIG.maxWidth),
      speed: rand(CONFIG.minSpeed, CONFIG.maxSpeed) * (Math.random() > 0.5 ? 1 : -1),
      angle: rand(-30, 30),           // degrees
      length: canvas.height * rand(1.2, 2.0),
      opacity: rand(0.4, 1.0)
    };
  }

  function initBeams() {
    beams = [];
    for (var i = 0; i < CONFIG.beamCount; i++) {
      beams.push(makeBeam(i));
    }
  }

  function createCanvas() {
    canvas = document.getElementById('beams-canvas');
    if (!canvas) {
      canvas = document.createElement('canvas');
      canvas.id = 'beams-canvas';
      canvas.style.cssText = [
        'position:fixed',
        'top:0',
        'left:0',
        'width:100%',
        'height:100%',
        'pointer-events:none',
        'z-index:0'
      ].join(';');
      // Insert as first child of body so it's behind everything
      document.body.insertBefore(canvas, document.body.firstChild);
    }
    ctx = canvas.getContext('2d');
    resize();
  }

  function resize() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    initBeams();
  }

  // ── Render a single beam (tall thin glowing stripe) ───────
  function drawBeam(beam) {
    ctx.save();
    ctx.translate(beam.x, -100);
    ctx.rotate((beam.angle * Math.PI) / 180);
    ctx.globalAlpha = beam.opacity;

    // Radial gradient for the "glow" cross-section
    var halfW = beam.width / 2;
    var grad = ctx.createLinearGradient(-halfW, 0, halfW, 0);
    grad.addColorStop(0,    'transparent');
    grad.addColorStop(0.3,  beam.color);
    grad.addColorStop(0.5,  beam.color);
    grad.addColorStop(0.7,  beam.color);
    grad.addColorStop(1,    'transparent');

    ctx.fillStyle = grad;
    ctx.fillRect(-halfW, 0, beam.width, beam.length);
    ctx.restore();
  }

  function update(now) {
    frameId = requestAnimationFrame(update);

    if (now - lastTime < frameDuration) return;
    lastTime = now;

    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.filter = 'blur(' + CONFIG.blur + 'px)';

    beams.forEach(function (beam) {
      beam.x += beam.speed;

      // Wrap around screen
      if (beam.speed > 0 && beam.x > canvas.width + 200) {
        beam.x = -200;
        beam.color = CONFIG.colors[Math.floor(Math.random() * CONFIG.colors.length)].replace('ALPHA', rand(CONFIG.minAlpha, CONFIG.maxAlpha).toFixed(3));
      }
      if (beam.speed < 0 && beam.x < -200) {
        beam.x = canvas.width + 200;
        beam.color = CONFIG.colors[Math.floor(Math.random() * CONFIG.colors.length)].replace('ALPHA', rand(CONFIG.minAlpha, CONFIG.maxAlpha).toFixed(3));
      }

      drawBeam(beam);
    });

    ctx.filter = 'none';
  }

  function init() {
    createCanvas();
    window.addEventListener('resize', resize);
    requestAnimationFrame(update);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
