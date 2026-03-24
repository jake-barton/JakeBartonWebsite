/**
 * fuzzy-text.js  —  Jake Barton Portfolio Style Kit
 * Canvas-based fuzzy/glitch text animation.
 * Ported directly from FuzzyText.tsx (Canvas API only, zero deps).
 *
 * Usage — auto mode (data attributes):
 *   <canvas class="fuzzy-text"
 *           data-text="JAKE BARTON"
 *           data-color="#00D9FF"
 *           data-size="72"
 *           data-weight="400"
 *           data-family="Bebas Neue, sans-serif"
 *           data-intensity="0.18"
 *           data-hover-intensity="0.5"
 *           data-fuzz-range="30"
 *   ></canvas>
 *
 * Usage — JS API:
 *   var cleanup = FuzzyText.create(canvasElement, {
 *     text: 'HELLO',
 *     color: '#00D9FF',
 *     fontSize: 72,
 *     baseIntensity: 0.18
 *   });
 *   // call cleanup() to stop the animation
 */
window.FuzzyText = (function () {
  'use strict';

  var DEFAULTS = {
    text: 'HELLO',
    fontSize: 72,
    fontWeight: '400',
    fontFamily: 'Bebas Neue, Oswald, sans-serif',
    color: '#ffffff',
    gradient: null,           // array of hex strings for a gradient, e.g. ['#00D9FF','#FF006B']
    baseIntensity: 0.18,
    hoverIntensity: 0.5,
    fuzzRange: 30,
    fps: 60,
    direction: 'horizontal',  // 'horizontal' | 'vertical' | 'both'
    enableHover: true,
    transitionDuration: 400,  // ms
    clickEffect: true,
    glitchMode: false,
    glitchInterval: 3000,
    glitchDuration: 350,
    letterSpacing: 0
  };

  function create(canvas, userOpts) {
    var o = Object.assign({}, DEFAULTS, userOpts);

    var ctx = canvas.getContext('2d');
    var frameDuration = 1000 / o.fps;

    // ── Build offscreen canvas with the clean text ─────────
    var offscreen = document.createElement('canvas');
    var offCtx = offscreen.getContext('2d');

    var fontSizeStr = (typeof o.fontSize === 'number' ? o.fontSize + 'px' : o.fontSize);
    var numericFontSize = parseFloat(fontSizeStr);
    offCtx.font = o.fontWeight + ' ' + fontSizeStr + ' ' + o.fontFamily;

    // Measure text width (respecting letterSpacing)
    var totalWidth = 0;
    if (o.letterSpacing !== 0) {
      for (var c = 0; c < o.text.length; c++) {
        totalWidth += offCtx.measureText(o.text[c]).width + o.letterSpacing;
      }
      totalWidth -= o.letterSpacing;
    } else {
      totalWidth = offCtx.measureText(o.text).width;
    }

    var metrics = offCtx.measureText(o.text);
    var actualLeft = metrics.actualBoundingBoxLeft || 0;
    var actualRight = o.letterSpacing !== 0 ? totalWidth : (metrics.actualBoundingBoxRight || metrics.width);
    var actualAscent = metrics.actualBoundingBoxAscent || numericFontSize;
    var actualDescent = metrics.actualBoundingBoxDescent || numericFontSize * 0.2;

    var textBoundingWidth = Math.ceil(o.letterSpacing !== 0 ? totalWidth : actualLeft + actualRight);
    var tightHeight = Math.ceil(actualAscent + actualDescent);

    var extraWidthBuffer = 10;
    var offscreenWidth = textBoundingWidth + extraWidthBuffer;

    offscreen.width = offscreenWidth;
    offscreen.height = tightHeight;

    offCtx.font = o.fontWeight + ' ' + fontSizeStr + ' ' + o.fontFamily;
    offCtx.textBaseline = 'alphabetic';

    if (o.gradient && Array.isArray(o.gradient) && o.gradient.length >= 2) {
      var grad = offCtx.createLinearGradient(0, 0, offscreenWidth, 0);
      o.gradient.forEach(function (col, idx) {
        grad.addColorStop(idx / (o.gradient.length - 1), col);
      });
      offCtx.fillStyle = grad;
    } else {
      offCtx.fillStyle = o.color;
    }

    var xOffset = extraWidthBuffer / 2;
    if (o.letterSpacing !== 0) {
      var xPos = xOffset;
      for (var ci = 0; ci < o.text.length; ci++) {
        offCtx.fillText(o.text[ci], xPos, actualAscent);
        xPos += offCtx.measureText(o.text[ci]).width + o.letterSpacing;
      }
    } else {
      offCtx.fillText(o.text, xOffset - actualLeft, actualAscent);
    }

    // ── Resize main canvas to hold the fuzz margin ──────────
    var fuzzRange = o.fuzzRange;
    var horizontalMargin = fuzzRange + 20;
    var verticalMargin = (o.direction === 'vertical' || o.direction === 'both') ? fuzzRange + 10 : 0;

    canvas.width = offscreenWidth + horizontalMargin * 2;
    canvas.height = tightHeight + verticalMargin * 2;
    ctx.translate(horizontalMargin, verticalMargin);

    var interactiveLeft = horizontalMargin + xOffset;
    var interactiveTop = verticalMargin;
    var interactiveRight = interactiveLeft + textBoundingWidth;
    var interactiveBottom = interactiveTop + tightHeight;

    // ── Animation state ──────────────────────────────────────
    var isHovering = false;
    var isClicking = false;
    var isGlitching = false;
    var currentIntensity = o.baseIntensity;
    var targetIntensity = o.baseIntensity;
    var lastFrameTime = 0;
    var cancelled = false;
    var frameId;
    var glitchTimerId, glitchEndTimerId, clickTimerId;

    function startGlitchLoop() {
      if (!o.glitchMode || cancelled) return;
      glitchTimerId = setTimeout(function () {
        if (cancelled) return;
        isGlitching = true;
        glitchEndTimerId = setTimeout(function () {
          isGlitching = false;
          startGlitchLoop();
        }, o.glitchDuration);
      }, o.glitchInterval);
    }

    if (o.glitchMode) startGlitchLoop();

    // ── Render loop ──────────────────────────────────────────
    function run(timestamp) {
      if (cancelled) return;
      if (timestamp - lastFrameTime < frameDuration) {
        frameId = requestAnimationFrame(run);
        return;
      }
      lastFrameTime = timestamp;

      ctx.clearRect(
        -fuzzRange - 20,
        -fuzzRange - 10,
        offscreenWidth + 2 * (fuzzRange + 20),
        tightHeight + 2 * (fuzzRange + 10)
      );

      if (isClicking || isGlitching) {
        targetIntensity = 1;
      } else if (isHovering) {
        targetIntensity = o.hoverIntensity;
      } else {
        targetIntensity = o.baseIntensity;
      }

      if (o.transitionDuration > 0) {
        var step = 1 / (o.transitionDuration / frameDuration);
        if (currentIntensity < targetIntensity) {
          currentIntensity = Math.min(currentIntensity + step, targetIntensity);
        } else if (currentIntensity > targetIntensity) {
          currentIntensity = Math.max(currentIntensity - step, targetIntensity);
        }
      } else {
        currentIntensity = targetIntensity;
      }

      for (var j = 0; j < tightHeight; j++) {
        var dx = 0, dy = 0;
        if (o.direction === 'horizontal' || o.direction === 'both') {
          dx = Math.floor(currentIntensity * (Math.random() - 0.5) * fuzzRange);
        }
        if (o.direction === 'vertical' || o.direction === 'both') {
          dy = Math.floor(currentIntensity * (Math.random() - 0.5) * fuzzRange * 0.5);
        }
        ctx.drawImage(offscreen, 0, j, offscreenWidth, 1, dx, j + dy, offscreenWidth, 1);
      }

      frameId = requestAnimationFrame(run);
    }

    frameId = requestAnimationFrame(run);

    // ── Event Handlers ───────────────────────────────────────
    function isInsideText(x, y) {
      return x >= interactiveLeft && x <= interactiveRight &&
             y >= interactiveTop  && y <= interactiveBottom;
    }

    function onMouseMove(e) {
      if (!o.enableHover) return;
      var rect = canvas.getBoundingClientRect();
      isHovering = isInsideText(e.clientX - rect.left, e.clientY - rect.top);
    }

    function onMouseLeave() { isHovering = false; }

    function onClick() {
      if (!o.clickEffect) return;
      isClicking = true;
      clearTimeout(clickTimerId);
      clickTimerId = setTimeout(function () { isClicking = false; }, 150);
    }

    function onTouchMove(e) {
      if (!o.enableHover || !e.touches.length) return;
      e.preventDefault();
      var rect = canvas.getBoundingClientRect();
      var t = e.touches[0];
      isHovering = isInsideText(t.clientX - rect.left, t.clientY - rect.top);
    }

    function onTouchEnd() { isHovering = false; }

    if (o.enableHover) {
      canvas.addEventListener('mousemove', onMouseMove);
      canvas.addEventListener('mouseleave', onMouseLeave);
      canvas.addEventListener('touchmove', onTouchMove, { passive: false });
      canvas.addEventListener('touchend', onTouchEnd);
    }
    if (o.clickEffect) {
      canvas.addEventListener('click', onClick);
    }

    // ── Cleanup ──────────────────────────────────────────────
    return function cleanup() {
      cancelled = true;
      cancelAnimationFrame(frameId);
      clearTimeout(glitchTimerId);
      clearTimeout(glitchEndTimerId);
      clearTimeout(clickTimerId);
      if (o.enableHover) {
        canvas.removeEventListener('mousemove', onMouseMove);
        canvas.removeEventListener('mouseleave', onMouseLeave);
        canvas.removeEventListener('touchmove', onTouchMove);
        canvas.removeEventListener('touchend', onTouchEnd);
      }
      if (o.clickEffect) {
        canvas.removeEventListener('click', onClick);
      }
    };
  }

  // ── Auto-init: scan DOM for [data-text] canvases ──────────
  function autoInit() {
    var canvases = document.querySelectorAll('canvas.fuzzy-text[data-text]');
    canvases.forEach(function (el) {
      var opts = {
        text:           el.dataset.text           || DEFAULTS.text,
        color:          el.dataset.color          || DEFAULTS.color,
        fontSize:       parseInt(el.dataset.size) || DEFAULTS.fontSize,
        fontWeight:     el.dataset.weight         || DEFAULTS.fontWeight,
        fontFamily:     el.dataset.family         || DEFAULTS.fontFamily,
        baseIntensity:  parseFloat(el.dataset.intensity)      || DEFAULTS.baseIntensity,
        hoverIntensity: parseFloat(el.dataset.hoverIntensity) || DEFAULTS.hoverIntensity,
        fuzzRange:      parseInt(el.dataset.fuzzRange)        || DEFAULTS.fuzzRange,
        glitchMode:     el.dataset.glitch === 'true'
      };
      if (el.dataset.gradient) {
        try { opts.gradient = JSON.parse(el.dataset.gradient); } catch (e) {}
      }
      create(el, opts);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', autoInit);
  } else {
    autoInit();
  }

  return { create: create };
})();
