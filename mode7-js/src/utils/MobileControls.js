/**
 * MobileControls.js
 * Full mobile control overlay for the Mario Kart game.
 *
 * – Detects touch vs mouse dynamically (pointer media query + touch events)
 * – Shows/hides controls live based on device type
 * – Joystick (left side) drives steering AND gas/brake:
 *     up   → ArrowUp   (gas)
 *     down → ArrowDown (brake/reverse)
 *     left → ArrowLeft
 *     right→ ArrowRight
 * – Right side action buttons:
 *     A (green)  → ArrowUp  (accelerate, for menus too)
 *     B (red)    → ArrowDown (brake/reverse)
 *     ITEM (yellow) → Shift  (use item)
 *     JUMP (blue)   → Space  (jump / drift)
 *     START         → Enter  (menus + confirm)
 * – Context modes: 'menu' (shows large START, hides ITEM/JUMP)
 *                  'race' (shows joystick + all buttons)
 * – Canvas resizes to leave room for controls on mobile
 * – Portrait warning overlay
 */

export class MobileControls {
    constructor(keys, canvas) {
        this.keys   = keys;
        this.canvas = canvas;
        this.active = false;   // true when touch device
        this.mode   = 'menu';  // 'menu' | 'race'

        // Joystick state
        this.joystickPointerId  = null;
        this.joystickOriginX    = 0;
        this.joystickOriginY    = 0;
        this.joystickX          = 0;  // -1 … 1
        this.joystickY          = 0;  // -1 … 1
        this.JOYSTICK_DEADZONE  = 0.18;
        this.JOYSTICK_RADIUS    = 48; // px from centre to edge of zone

        // Button pointer map: pointerId → buttonId
        this.pointerMap = {};

        // Button → key string
        this.buttonMap = {
            'mkc-btn-a':     'ArrowUp',
            'mkc-btn-b':     'ArrowDown',
            'mkc-btn-item':  'Shift',
            'mkc-btn-jump':  ' ',
            'mkc-btn-start': 'Enter',
        };

        this._buildDOM();
        this._checkDevice();

        // Re-evaluate if pointer environment changes (e.g. docking a keyboard)
        window.addEventListener('pointerover', (e) => {
            if (e.pointerType === 'touch') this._enable();
        }, { once: false });
    }

    /* ── Public API ──────────────────────────────────────── */

    /** Call from RaceScreen when race starts */
    setRaceMode() {
        this.mode = 'race';
        if (this.active) this._applyMode();
    }

    /** Call from any menu screen */
    setMenuMode() {
        this.mode = 'menu';
        if (this.active) this._applyMode();
    }

    /* ── Device detection ────────────────────────────────── */

    _isTouchDevice() {
        return (
            'ontouchstart' in window ||
            navigator.maxTouchPoints > 0 ||
            window.matchMedia('(pointer: coarse)').matches
        );
    }

    _checkDevice() {
        if (this._isTouchDevice()) {
            this._enable();
        } else {
            this._disable();
            // Listen for first touch event to switch on-the-fly
            window.addEventListener('touchstart', () => this._enable(), { once: true });
        }
    }

    _enable() {
        if (this.active) return;
        this.active = true;
        this.overlay.style.display = 'block';
        this._applyMode();
        this._resizeCanvas();
        this._updateOrientation();
        // Prevent scroll/zoom
        document.addEventListener('touchstart', this._preventDefault, { passive: false });
        document.addEventListener('touchmove',  this._preventDefault, { passive: false });
    }

    _disable() {
        this.active = false;
        this.overlay.style.display = 'none';
        this.canvas.style.cssText = '';
    }

    _preventDefault(e) { e.preventDefault(); }

    /* ── DOM construction ────────────────────────────────── */

    _buildDOM() {
        // Inject stylesheet
        const style = document.createElement('style');
        style.textContent = `
            /* ═══════════════════════════════════════════════
               Mario Kart Mobile Controls
               Retro SNES pixel-art aesthetic
            ═══════════════════════════════════════════════ */
            #mkc-overlay {
                display: none;
                position: fixed;
                inset: 0;
                pointer-events: none;
                z-index: 200;
                font-family: 'Courier New', monospace;
            }

            /* ── Portrait warning ───────────────────────── */
            #mkc-portrait {
                display: none;
                position: fixed;
                inset: 0;
                background: #0a0a0a;
                color: #fff;
                align-items: center;
                justify-content: center;
                text-align: center;
                z-index: 300;
                flex-direction: column;
                gap: 14px;
                pointer-events: none;
            }
            #mkc-portrait.show { display: flex; pointer-events: all; }
            #mkc-portrait-icon { font-size: 56px; animation: mkc-spin 2s linear infinite; }
            #mkc-portrait-text {
                font-family: 'Courier New', monospace;
                font-size: 16px;
                letter-spacing: 1px;
                color: #f8d030;
                text-shadow: 2px 2px 0 #000;
                line-height: 1.6;
            }
            @keyframes mkc-spin {
                0%   { transform: rotate(0deg);   }
                40%  { transform: rotate(0deg);   }
                60%  { transform: rotate(90deg);  }
                100% { transform: rotate(90deg);  }
            }

            /* ── Joystick zone (left side) ──────────────── */
            #mkc-stick-zone {
                position: fixed;
                bottom: 18px;
                left: 14px;
                width: 130px;
                height: 130px;
                pointer-events: all;
                touch-action: none;
            }
            #mkc-stick-base {
                position: absolute;
                inset: 0;
                border-radius: 50%;
                background: rgba(10,10,10,0.70);
                border: 3px solid rgba(248,208,48,0.55);
                box-shadow: 0 0 0 2px rgba(0,0,0,0.6), inset 0 0 12px rgba(0,0,0,0.5);
            }
            /* Cardinal direction tick marks */
            #mkc-stick-base::before {
                content: '';
                position: absolute;
                inset: 10px;
                border-radius: 50%;
                border: 1px dashed rgba(248,208,48,0.18);
            }
            #mkc-stick-nub {
                position: absolute;
                width: 44px;
                height: 44px;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                border-radius: 50%;
                background: radial-gradient(circle at 38% 35%, #e8c820, #b89010 60%, #806000);
                border: 3px solid #f8d030;
                box-shadow:
                    0 3px 6px rgba(0,0,0,0.7),
                    inset 0 1px 2px rgba(255,255,200,0.4);
                transition: background 0.05s;
                pointer-events: none;
            }
            #mkc-stick-nub.active {
                background: radial-gradient(circle at 38% 35%, #fff080, #f8d030 60%, #c09000);
                box-shadow: 0 3px 10px rgba(248,208,48,0.6), inset 0 1px 2px rgba(255,255,200,0.5);
            }
            /* Directional arrows on base */
            .mkc-stick-arrow {
                position: absolute;
                color: rgba(248,208,48,0.45);
                font-size: 13px;
                pointer-events: none;
                line-height: 1;
            }
            .mkc-arrow-up    { top: 6px;  left: 50%; transform: translateX(-50%); }
            .mkc-arrow-down  { bottom: 6px; left: 50%; transform: translateX(-50%); }
            .mkc-arrow-left  { left: 6px; top: 50%; transform: translateY(-50%); }
            .mkc-arrow-right { right: 6px; top: 50%; transform: translateY(-50%); }

            /* ── Right action buttons ───────────────────── */
            #mkc-actions {
                position: fixed;
                bottom: 18px;
                right: 14px;
                width: 150px;
                height: 150px;
                pointer-events: none;
            }

            /* Base style for all action buttons */
            .mkc-btn {
                position: absolute;
                border-radius: 50%;
                pointer-events: all;
                touch-action: none;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                border: 3px solid;
                -webkit-tap-highlight-color: transparent;
                user-select: none;
                transition: transform 0.06s, filter 0.06s;
                /* Pixel-art shadow */
                box-shadow: 0 4px 0 rgba(0,0,0,0.7), 0 6px 10px rgba(0,0,0,0.5);
            }
            .mkc-btn:active, .mkc-btn.pressed {
                transform: translateY(3px);
                box-shadow: 0 1px 0 rgba(0,0,0,0.7), 0 2px 4px rgba(0,0,0,0.5);
                filter: brightness(1.35);
            }
            .mkc-btn-label {
                font-family: 'Courier New', monospace;
                font-weight: bold;
                font-size: 15px;
                letter-spacing: 0px;
                line-height: 1;
                text-shadow: 1px 1px 0 rgba(0,0,0,0.8);
                pointer-events: none;
            }
            .mkc-btn-sublabel {
                font-family: 'Courier New', monospace;
                font-size: 9px;
                letter-spacing: 0.5px;
                line-height: 1;
                opacity: 0.85;
                text-shadow: 1px 1px 0 rgba(0,0,0,0.8);
                pointer-events: none;
                margin-top: 1px;
            }

            /* A — Accelerate (green, bottom-left of cluster) */
            #mkc-btn-a {
                width: 52px; height: 52px;
                bottom: 54px; right: 56px;
                background: radial-gradient(circle at 40% 35%, #68e050, #28a818 55%, #186010);
                border-color: #80f060;
                color: #e8ffe0;
            }
            /* B — Brake/reverse (red, bottom-right) */
            #mkc-btn-b {
                width: 52px; height: 52px;
                bottom: 54px; right: 0;
                background: radial-gradient(circle at 40% 35%, #f06060, #c02020 55%, #801010);
                border-color: #ff8080;
                color: #ffe8e8;
            }
            /* JUMP / Drift (blue, top-right) */
            #mkc-btn-jump {
                width: 48px; height: 48px;
                top: 0; right: 0;
                background: radial-gradient(circle at 40% 35%, #60a0f8, #2060d0 55%, #103090);
                border-color: #80c0ff;
                color: #e8f4ff;
            }
            /* ITEM — use item (yellow, top-left of cluster) */
            #mkc-btn-item {
                width: 48px; height: 48px;
                top: 0; right: 54px;
                background: radial-gradient(circle at 40% 35%, #ffe060, #d0a000 55%, #806000);
                border-color: #ffe880;
                color: #3a2000;
            }

            /* START button (center bottom, menu mode) */
            #mkc-btn-start {
                width: 64px; height: 28px;
                border-radius: 14px;
                /* centred below canvas */
                bottom: 12px;
                left: 50%;
                transform: translateX(-50%);
                background: radial-gradient(ellipse at 40% 35%, #d8d8d8, #888 60%, #444);
                border-color: #ccc;
                color: #111;
                font-size: 10px;
                letter-spacing: 1px;
                position: fixed;
                box-shadow: 0 3px 0 rgba(0,0,0,0.7), 0 5px 8px rgba(0,0,0,0.5);
            }
            #mkc-btn-start:active, #mkc-btn-start.pressed {
                transform: translateX(-50%) translateY(3px);
                box-shadow: 0 1px 0 rgba(0,0,0,0.7), 0 2px 4px rgba(0,0,0,0.5);
            }

            /* ── Visibility modes ───────────────────────── */
            /* Menu mode: hide joystick + ITEM/JUMP, show big START */
            #mkc-overlay.mode-menu  #mkc-stick-zone { opacity: 0.35; pointer-events: none; }
            #mkc-overlay.mode-menu  #mkc-btn-item   { opacity: 0; pointer-events: none; }
            #mkc-overlay.mode-menu  #mkc-btn-jump   { opacity: 0; pointer-events: none; }
            #mkc-overlay.mode-menu  #mkc-btn-a      { bottom: 54px; right: 56px; }
            #mkc-overlay.mode-menu  #mkc-btn-b      { opacity: 0.35; pointer-events: none; }
            #mkc-overlay.mode-menu  #mkc-btn-start  { opacity: 1;  pointer-events: all; }

            /* Race mode: full controls, smaller START */
            #mkc-overlay.mode-race  #mkc-stick-zone { opacity: 1;   pointer-events: all; }
            #mkc-overlay.mode-race  #mkc-btn-item   { opacity: 1;   pointer-events: all; }
            #mkc-overlay.mode-race  #mkc-btn-jump   { opacity: 1;   pointer-events: all; }
            #mkc-overlay.mode-race  #mkc-btn-b      { opacity: 1;   pointer-events: all; }
            #mkc-overlay.mode-race  #mkc-btn-start  { opacity: 0.4; pointer-events: none; width: 48px; height: 22px; font-size: 9px; }

            /* Smooth mode transitions */
            #mkc-stick-zone, .mkc-btn { transition: opacity 0.25s, transform 0.06s, filter 0.06s; }
        `;
        document.head.appendChild(style);

        // Portrait warning
        const portrait = document.createElement('div');
        portrait.id = 'mkc-portrait';
        portrait.innerHTML = `
            <div id="mkc-portrait-icon">⟳</div>
            <div id="mkc-portrait-text">ROTATE TO<br>LANDSCAPE<br>TO PLAY</div>
        `;
        document.body.appendChild(portrait);
        this.portraitEl = portrait;

        // Main overlay
        const overlay = document.createElement('div');
        overlay.id = 'mkc-overlay';
        overlay.innerHTML = `
            <!-- Left: Joystick -->
            <div id="mkc-stick-zone">
                <div id="mkc-stick-base">
                    <span class="mkc-stick-arrow mkc-arrow-up">▲</span>
                    <span class="mkc-stick-arrow mkc-arrow-down">▼</span>
                    <span class="mkc-stick-arrow mkc-arrow-left">◀</span>
                    <span class="mkc-stick-arrow mkc-arrow-right">▶</span>
                </div>
                <div id="mkc-stick-nub"></div>
            </div>

            <!-- Right: Action buttons -->
            <div id="mkc-actions">
                <div class="mkc-btn" id="mkc-btn-item">
                    <span class="mkc-btn-label">🎁</span>
                    <span class="mkc-btn-sublabel">ITEM</span>
                </div>
                <div class="mkc-btn" id="mkc-btn-jump">
                    <span class="mkc-btn-label">L</span>
                    <span class="mkc-btn-sublabel">JUMP</span>
                </div>
                <div class="mkc-btn" id="mkc-btn-a">
                    <span class="mkc-btn-label">A</span>
                    <span class="mkc-btn-sublabel">GO</span>
                </div>
                <div class="mkc-btn" id="mkc-btn-b">
                    <span class="mkc-btn-label">B</span>
                    <span class="mkc-btn-sublabel">BACK</span>
                </div>
            </div>

            <!-- Centre: START -->
            <div class="mkc-btn" id="mkc-btn-start">
                <span class="mkc-btn-label" style="font-size:10px;letter-spacing:1px">START</span>
            </div>
        `;
        document.body.appendChild(overlay);
        this.overlay = overlay;

        // Cache elements
        this.stickZone = document.getElementById('mkc-stick-zone');
        this.stickNub  = document.getElementById('mkc-stick-nub');

        this._wireJoystick();
        this._wireButtons();
        this._wireResize();
    }

    /* ── Joystick ────────────────────────────────────────── */

    _wireJoystick() {
        const zone = this.stickZone;
        zone.addEventListener('pointerdown',  e => this._stickDown(e),  { passive: false });
        zone.addEventListener('pointermove',  e => this._stickMove(e),  { passive: false });
        zone.addEventListener('pointerup',    e => this._stickUp(e),    { passive: false });
        zone.addEventListener('pointercancel',e => this._stickUp(e),    { passive: false });
    }

    _stickDown(e) {
        e.preventDefault();
        if (this.joystickPointerId !== null) return; // already tracking
        this.joystickPointerId = e.pointerId;
        this.stickZone.setPointerCapture(e.pointerId);
        const rect = this.stickZone.getBoundingClientRect();
        this.joystickOriginX = rect.left + rect.width  / 2;
        this.joystickOriginY = rect.top  + rect.height / 2;
        this._stickMove(e);
        this.stickNub.classList.add('active');
    }

    _stickMove(e) {
        if (e.pointerId !== this.joystickPointerId) return;
        e.preventDefault();

        const dx = e.clientX - this.joystickOriginX;
        const dy = e.clientY - this.joystickOriginY;
        const dist = Math.sqrt(dx * dx + dy * dy);
        const maxDist = this.JOYSTICK_RADIUS;

        // Clamp to circle
        const clampDist = Math.min(dist, maxDist);
        const angle = Math.atan2(dy, dx);
        const nx = clampDist * Math.cos(angle);
        const ny = clampDist * Math.sin(angle);

        this.joystickX = clampDist / maxDist * Math.cos(angle);
        this.joystickY = clampDist / maxDist * Math.sin(angle);

        // Move nub visual
        const halfW = this.stickZone.offsetWidth  / 2;
        const halfH = this.stickZone.offsetHeight / 2;
        this.stickNub.style.left = (halfW + nx) + 'px';
        this.stickNub.style.top  = (halfH + ny) + 'px';
        this.stickNub.style.transform = 'translate(-50%, -50%)';

        this._updateStickKeys();
    }

    _stickUp(e) {
        if (e.pointerId !== this.joystickPointerId) return;
        e.preventDefault();
        this.joystickPointerId = null;
        this.joystickX = 0;
        this.joystickY = 0;

        // Re-centre nub
        this.stickNub.style.left = '50%';
        this.stickNub.style.top  = '50%';
        this.stickNub.style.transform = 'translate(-50%, -50%)';
        this.stickNub.classList.remove('active');

        this._updateStickKeys();
    }

    _updateStickKeys() {
        const dz = this.JOYSTICK_DEADZONE;
        this.keys['ArrowUp']    = this.joystickY < -dz;
        this.keys['ArrowDown']  = this.joystickY >  dz;
        this.keys['ArrowLeft']  = this.joystickX < -dz;
        this.keys['ArrowRight'] = this.joystickX >  dz;
    }

    /* ── Action buttons ──────────────────────────────────── */

    _wireButtons() {
        Object.keys(this.buttonMap).forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            el.addEventListener('pointerdown',   e => this._btnDown(e, id), { passive: false });
            el.addEventListener('pointerup',     e => this._btnUp(e),       { passive: false });
            el.addEventListener('pointercancel', e => this._btnUp(e),       { passive: false });
            el.addEventListener('pointerdown',   e => el.setPointerCapture(e.pointerId));
        });
    }

    _btnDown(e, id) {
        e.preventDefault();
        this.pointerMap[e.pointerId] = id;
        this.keys[this.buttonMap[id]] = true;
        document.getElementById(id).classList.add('pressed');
    }

    _btnUp(e) {
        e.preventDefault();
        const id = this.pointerMap[e.pointerId];
        if (!id) return;
        delete this.pointerMap[e.pointerId];

        // Only release key if no other pointer still holds this button's key
        const key = this.buttonMap[id];
        const stillHeld = Object.entries(this.pointerMap).some(
            ([, bid]) => this.buttonMap[bid] === key
        );
        if (!stillHeld) this.keys[key] = false;
        document.getElementById(id).classList.remove('pressed');
    }

    /* ── Layout helpers ──────────────────────────────────── */

    _applyMode() {
        this.overlay.classList.remove('mode-menu', 'mode-race');
        this.overlay.classList.add('mode-' + this.mode);
    }

    _wireResize() {
        window.addEventListener('resize', () => {
            if (this.active) {
                this._resizeCanvas();
                this._updateOrientation();
            }
        });
        window.addEventListener('orientationchange', () => {
            setTimeout(() => {
                if (this.active) {
                    this._resizeCanvas();
                    this._updateOrientation();
                }
            }, 250);
        });
    }

    _resizeCanvas() {
        // Reserve space at the bottom for controls (joystick + buttons)
        const ctrlH  = 168;
        const availW = window.innerWidth;
        const availH = window.innerHeight - ctrlH;

        const gameW = 255;
        const gameH = 224;
        const scale = Math.min(availW / gameW, availH / gameH);
        const displayW = Math.floor(gameW * scale);
        const displayH = Math.floor(gameH * scale);

        const topPad = Math.max(4, (availH - displayH) / 2);

        this.canvas.style.position  = 'fixed';
        this.canvas.style.width     = displayW + 'px';
        this.canvas.style.height    = displayH + 'px';
        this.canvas.style.top       = topPad + 'px';
        this.canvas.style.left      = ((availW - displayW) / 2) + 'px';
        this.canvas.style.border    = '2px solid #555';
    }

    _updateOrientation() {
        const isPortrait = window.innerWidth < window.innerHeight;
        this.portraitEl.classList.toggle('show', isPortrait);
        this.overlay.style.visibility = isPortrait ? 'hidden' : 'visible';
    }
}
