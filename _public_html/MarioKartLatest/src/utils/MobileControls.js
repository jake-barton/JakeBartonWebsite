/**
 * MobileControls.js
 * Injects touch-button states into the shared `keys` object so the rest of
 * the game engine never needs to know about touch input.
 *
 * Button → key mapping:
 *   D-pad up    → ArrowUp
 *   D-pad down  → ArrowDown
 *   D-pad left  → ArrowLeft
 *   D-pad right → ArrowRight
 *   A button    → ArrowUp   (also accelerate — duplicate of up for ease)
 *   B button    → ArrowDown (brake / reverse)
 *   L button    →  (space)  jump / drift
 *   START       → Enter
 */

export class MobileControls {
    /**
     * @param {Object} keys  - The shared keys object from game.js
     * @param {HTMLCanvasElement} canvas
     */
    constructor(keys, canvas) {
        this.keys = keys;
        this.canvas = canvas;
        this.isTouch = false;

        // Map button id → key string
        this.buttonMap = {
            'btn-up':    'ArrowUp',
            'btn-down':  'ArrowDown',
            'btn-left':  'ArrowLeft',
            'btn-right': 'ArrowRight',
            'btn-a':     'ArrowUp',
            'btn-b':     'ArrowDown',
            'btn-l':     ' ',
            'btn-start': 'Enter',
        };

        // Track which pointer IDs are held on which button
        this.pointerMap = {}; // pointerId → buttonId

        this._init();
    }

    _isTouchDevice() {
        return (
            'ontouchstart' in window ||
            navigator.maxTouchPoints > 0 ||
            window.matchMedia('(pointer: coarse)').matches
        );
    }

    _init() {
        if (!this._isTouchDevice()) return;

        this.isTouch = true;
        document.getElementById('dpad').style.display = 'block';
        document.getElementById('action-btns').style.display = 'block';

        // Show / hide portrait warning
        this._updateOrientation();
        window.addEventListener('resize', () => this._updateOrientation());
        window.addEventListener('orientationchange', () => {
            setTimeout(() => this._updateOrientation(), 200);
        });

        // Resize canvas to fit landscape mobile with room for controls
        this._resizeCanvas();
        window.addEventListener('resize', () => this._resizeCanvas());

        // Wire all dpad + action buttons
        Object.keys(this.buttonMap).forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            el.addEventListener('pointerdown',  e => this._onDown(e, id), { passive: false });
            el.addEventListener('pointermove',  e => e.preventDefault(),  { passive: false });
            el.addEventListener('pointerup',    e => this._onUp(e),        { passive: false });
            el.addEventListener('pointercancel',e => this._onUp(e),        { passive: false });
            // Capture so drag off-button still releases properly
            el.addEventListener('pointerdown', e => el.setPointerCapture(e.pointerId));
        });

        // Prevent default touch scrolling / zoom on the whole page
        document.addEventListener('touchstart', e => e.preventDefault(), { passive: false });
        document.addEventListener('touchmove',  e => e.preventDefault(), { passive: false });
    }

    _onDown(e, id) {
        e.preventDefault();
        this.pointerMap[e.pointerId] = id;
        const keyName = this.buttonMap[id];
        if (keyName) this.keys[keyName] = true;
        const el = document.getElementById(id);
        if (el) el.classList.add('pressed');
    }

    _onUp(e) {
        e.preventDefault();
        const id = this.pointerMap[e.pointerId];
        if (!id) return;
        delete this.pointerMap[e.pointerId];

        // Only release the key if no other pointer is still holding this button
        const stillHeld = Object.values(this.pointerMap).includes(id);
        if (!stillHeld) {
            const keyName = this.buttonMap[id];
            if (keyName) {
                // Also check if another button maps to the same key (e.g. btn-a and btn-up → ArrowUp)
                const otherHolding = Object.entries(this.pointerMap).some(([pid, bid]) => {
                    return this.buttonMap[bid] === keyName;
                });
                if (!otherHolding) {
                    this.keys[keyName] = false;
                }
            }
            const el = document.getElementById(id);
            if (el) el.classList.remove('pressed');
        }
    }

    _resizeCanvas() {
        if (!this.isTouch) return;

        const controlsH = 170; // px reserved for controls at bottom
        const availW = window.innerWidth;
        const availH = window.innerHeight - controlsH;

        // Maintain the SNES aspect ratio (255:224 ≈ 1.138)
        const gameW = 255;
        const gameH = 224;
        const scale = Math.min(availW / gameW, availH / gameH);
        const displayW = Math.floor(gameW * scale);
        const displayH = Math.floor(gameH * scale);

        this.canvas.style.width  = displayW + 'px';
        this.canvas.style.height = displayH + 'px';
        // Position canvas at top-center, leaving space for controls
        this.canvas.style.position = 'fixed';
        this.canvas.style.top  = Math.max(4, (availH - displayH) / 2) + 'px';
        this.canvas.style.left = ((availW - displayW) / 2) + 'px';
    }

    _updateOrientation() {
        const warn = document.getElementById('portrait-warn');
        if (!warn) return;
        // Portrait: innerWidth < innerHeight
        const isPortrait = window.innerWidth < window.innerHeight;
        warn.classList.toggle('visible', isPortrait);
    }
}
