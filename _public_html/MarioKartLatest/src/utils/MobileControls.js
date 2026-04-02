/**
 * MobileControls.js
 * Minimal mobile control overlay — joystick + 2 buttons.
 *
 * Joystick (left sidebar):
 *   up    → ArrowUp   (gas / menu up)
 *   down  → ArrowDown (brake / menu down)
 *   left  → ArrowLeft (steer / menu left)
 *   right → ArrowRight(steer / menu right)
 *
 * Button A (right sidebar, top):
 *   menu  → Enter  (confirm / start)
 *   race  → Space  (jump / drift)
 *
 * Button B (right sidebar, bottom):
 *   race  → Shift  (use item)
 *   menu  → Enter  (also confirm)
 *
 * Controls sit inside the black pillarbox sidebars beside the canvas.
 * Visual style matches the game's dark SNES navy/black aesthetic.
 */

export class MobileControls {
    constructor(keys, canvas) {
        this.keys   = keys;
        this.canvas = canvas;
        this.active = false;
        this.mode   = 'menu'; // 'menu' | 'race'

        this.joystickPointerId = null;
        this.joystickOriginX   = 0;
        this.joystickOriginY   = 0;
        this.joystickX         = 0;
        this.joystickY         = 0;
        this.JOYSTICK_DEADZONE = 0.20;
        this.JOYSTICK_RADIUS   = 48;

        this.pointerMap = {};

        // Per-mode key bindings for A and B
        this.buttonKeys = {
            menu: { 'mkc-btn-a': 'Enter', 'mkc-btn-b': 'Enter' },
            race: { 'mkc-btn-a': ' ',     'mkc-btn-b': 'Shift'  },
        };

        this._buildDOM();
        this._checkDevice();

        window.addEventListener('pointerover', (e) => {
            if (e.pointerType === 'touch') this._enable();
        });
    }

    /* ── Public API ──────────────────────────────────────── */

    setRaceMode() {
        this.mode = 'race';
        if (this.active) this._applyMode();
    }

    setMenuMode() {
        this.mode = 'menu';
        if (this.active) this._applyMode();
    }

    /* ── Device detection ────────────────────────────────── */

    _isTouchDevice() {
        return (
            'ontouchstart' in window ||
            navigator.maxTouchPoints > 0 ||
            navigator.msMaxTouchPoints > 0 ||
            window.matchMedia('(pointer: coarse)').matches ||
            window.matchMedia('(hover: none)').matches
        );
    }

    _checkDevice() {
        const forceEnable = new URLSearchParams(window.location.search).get('mobile') === '1';
        if (forceEnable || this._isTouchDevice()) {
            this._enable();
        } else {
            this._disable();
            const onFirstTouch = () => {
                this._enable();
                window.removeEventListener('touchstart', onFirstTouch);
                window.removeEventListener('pointerdown', onFirstPointer);
            };
            const onFirstPointer = (e) => {
                if (e.pointerType === 'touch') {
                    this._enable();
                    window.removeEventListener('touchstart', onFirstTouch);
                    window.removeEventListener('pointerdown', onFirstPointer);
                }
            };
            window.addEventListener('touchstart', onFirstTouch, { passive: true });
            window.addEventListener('pointerdown', onFirstPointer, { passive: true });
        }
    }

    _enable() {
        if (this.active) return;
        this.active = true;
        this.overlay.style.display = 'block';
        this._applyMode();
        this._resizeCanvas();
        this._updateOrientation();
        document.addEventListener('touchstart', this._preventDefault, { passive: false });
        document.addEventListener('touchmove',  this._preventDefault, { passive: false });
    }

    _disable() {
        this.active = false;
        this.overlay.style.display = 'none';
    }

    _preventDefault(e) { e.preventDefault(); }

    /* ── DOM ─────────────────────────────────────────────── */

    _buildDOM() {
        const style = document.createElement('style');
        style.textContent = `
            #mkc-overlay {
                display: none;
                position: fixed;
                inset: 0;
                pointer-events: none;
                z-index: 200;
            }

            /* ── Portrait warning ─────────────────────── */
            #mkc-portrait {
                display: none;
                position: fixed;
                inset: 0;
                background: #0b2240;
                align-items: center;
                justify-content: center;
                text-align: center;
                z-index: 300;
                flex-direction: column;
                gap: 14px;
                pointer-events: none;
            }
            #mkc-portrait.show { display: flex; pointer-events: all; }
            #mkc-portrait-text {
                font-family: 'Courier New', monospace;
                font-size: 15px;
                letter-spacing: 2px;
                color: #ffd700;
                text-shadow: 2px 2px 0 #000;
                line-height: 1.7;
            }

            /* ── Joystick zone ────────────────────────── */
            #mkc-stick-zone {
                position: fixed;
                pointer-events: all;
                touch-action: none;
            }

            #mkc-stick-base {
                position: absolute;
                inset: 0;
                border-radius: 50%;
                background-image:
                    repeating-linear-gradient(
                        -45deg,
                        #0b2240 0px,
                        #0b2240 5px,
                        #0e2d55 5px,
                        #0e2d55 10px
                    );
                border: 2px solid rgba(255,255,255,0.20);
                box-shadow:
                    0 0 0 1px rgba(0,0,0,0.6),
                    inset 0 1px 0 rgba(255,255,255,0.06),
                    0 4px 16px rgba(0,0,0,0.65);
            }

            #mkc-stick-nub {
                position: absolute;
                top: 50%; left: 50%;
                transform: translate(-50%,-50%);
                border-radius: 50%;
                background-image:
                    repeating-linear-gradient(
                        -45deg,
                        #0d2a4e 0px,
                        #0d2a4e 4px,
                        #112f58 4px,
                        #112f58 8px
                    );
                border: 2px solid rgba(255,255,255,0.25);
                box-shadow:
                    0 3px 8px rgba(0,0,0,0.75),
                    inset 0 1px 0 rgba(255,255,255,0.10);
                pointer-events: none;
                transition: border-color 0.10s, box-shadow 0.10s;
            }
            #mkc-stick-nub.active {
                border-color: rgba(255,215,0,0.70);
                box-shadow:
                    0 3px 14px rgba(255,215,0,0.28),
                    inset 0 1px 0 rgba(255,240,160,0.18);
            }

            /* ── Action buttons container ─────────────── */
            #mkc-actions {
                position: fixed;
                pointer-events: none;
            }

            .mkc-btn {
                position: absolute;
                border-radius: 50%;
                pointer-events: all;
                touch-action: none;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                -webkit-tap-highlight-color: transparent;
                user-select: none;
                background-image:
                    repeating-linear-gradient(
                        -45deg,
                        #0b2240 0px,
                        #0b2240 5px,
                        #0e2d55 5px,
                        #0e2d55 10px
                    );
                transition: transform 0.07s, filter 0.07s, box-shadow 0.07s;
            }

            .mkc-btn-label {
                pointer-events: none;
                font-family: 'Courier New', monospace;
                font-weight: 900;
                line-height: 1;
                text-shadow: 1px 1px 0 rgba(0,0,0,0.9), -1px -1px 0 rgba(0,0,0,0.5);
            }

            #mkc-btn-a {
                border: 2px solid rgba(255,255,255,0.25);
                box-shadow:
                    0 4px 0 rgba(0,0,0,0.70),
                    0 6px 14px rgba(0,0,0,0.50),
                    inset 0 1px 0 rgba(255,255,255,0.08);
                color: #ffd700;
            }
            #mkc-btn-a:active, #mkc-btn-a.pressed {
                transform: translateY(3px) !important;
                box-shadow: 0 1px 0 rgba(0,0,0,0.70), inset 0 2px 6px rgba(0,0,0,0.55);
                filter: brightness(1.5);
                border-color: rgba(255,215,0,0.65);
            }

            #mkc-btn-b {
                border: 2px solid rgba(214,40,40,0.50);
                box-shadow:
                    0 4px 0 rgba(0,0,0,0.70),
                    0 6px 14px rgba(0,0,0,0.50),
                    inset 0 1px 0 rgba(214,40,40,0.10);
                color: #d62828;
            }
            #mkc-btn-b:active, #mkc-btn-b.pressed {
                transform: translateY(3px) !important;
                box-shadow: 0 1px 0 rgba(0,0,0,0.70), inset 0 2px 6px rgba(0,0,0,0.55);
                filter: brightness(1.5);
                border-color: rgba(214,40,40,0.80);
            }
        `;
        document.head.appendChild(style);

        /* Portrait warning */
        const portrait = document.createElement('div');
        portrait.id = 'mkc-portrait';
        portrait.innerHTML = `
            <div id="mkc-portrait-text">ROTATE TO<br>LANDSCAPE<br>TO PLAY</div>
        `;
        document.body.appendChild(portrait);
        this.portraitEl = portrait;

        /* Main overlay */
        const overlay = document.createElement('div');
        overlay.id = 'mkc-overlay';
        overlay.innerHTML = `
            <div id="mkc-stick-zone">
                <div id="mkc-stick-base"></div>
                <div id="mkc-stick-nub"></div>
            </div>
            <div id="mkc-actions">
                <div class="mkc-btn" id="mkc-btn-a">
                    <span class="mkc-btn-label">A</span>
                </div>
                <div class="mkc-btn" id="mkc-btn-b">
                    <span class="mkc-btn-label">B</span>
                </div>
            </div>
        `;
        document.body.appendChild(overlay);
        this.overlay = overlay;

        this.stickZone = document.getElementById('mkc-stick-zone');
        this.stickNub  = document.getElementById('mkc-stick-nub');

        this._wireJoystick();
        this._wireButtons();
        this._wireResize();
    }

    /* ── Joystick ────────────────────────────────────────── */

    _wireJoystick() {
        const z = this.stickZone;
        z.addEventListener('pointerdown',   e => this._stickDown(e),  { passive: false });
        z.addEventListener('pointermove',   e => this._stickMove(e),  { passive: false });
        z.addEventListener('pointerup',     e => this._stickUp(e),    { passive: false });
        z.addEventListener('pointercancel', e => this._stickUp(e),    { passive: false });
    }

    _stickDown(e) {
        e.preventDefault();
        if (this.joystickPointerId !== null) return;
        this.joystickPointerId = e.pointerId;
        this.stickZone.setPointerCapture(e.pointerId);
        const r = this.stickZone.getBoundingClientRect();
        this.joystickOriginX = r.left + r.width  / 2;
        this.joystickOriginY = r.top  + r.height / 2;
        this._stickMove(e);
        this.stickNub.classList.add('active');
    }

    _stickMove(e) {
        if (e.pointerId !== this.joystickPointerId) return;
        e.preventDefault();
        const dx = e.clientX - this.joystickOriginX;
        const dy = e.clientY - this.joystickOriginY;
        const dist  = Math.sqrt(dx * dx + dy * dy);
        const angle = Math.atan2(dy, dx);
        const clamped = Math.min(dist, this.JOYSTICK_RADIUS);
        const nx = clamped * Math.cos(angle);
        const ny = clamped * Math.sin(angle);
        this.joystickX = (clamped / this.JOYSTICK_RADIUS) * Math.cos(angle);
        this.joystickY = (clamped / this.JOYSTICK_RADIUS) * Math.sin(angle);
        const hw = this.stickZone.offsetWidth  / 2;
        const hh = this.stickZone.offsetHeight / 2;
        this.stickNub.style.left = (hw + nx) + 'px';
        this.stickNub.style.top  = (hh + ny) + 'px';
        this.stickNub.style.transform = 'translate(-50%,-50%)';
        this._updateStickKeys();
    }

    _stickUp(e) {
        if (e.pointerId !== this.joystickPointerId) return;
        e.preventDefault();
        this.joystickPointerId = null;
        this.joystickX = 0;
        this.joystickY = 0;
        this.stickNub.style.left = '50%';
        this.stickNub.style.top  = '50%';
        this.stickNub.style.transform = 'translate(-50%,-50%)';
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

    /* ── Buttons ─────────────────────────────────────────── */

    _currentKey(id) {
        return this.buttonKeys[this.mode][id];
    }

    _wireButtons() {
        ['mkc-btn-a', 'mkc-btn-b'].forEach(id => {
            const el = document.getElementById(id);
            el.addEventListener('pointerdown',   e => this._btnDown(e, id), { passive: false });
            el.addEventListener('pointerup',     e => this._btnUp(e),       { passive: false });
            el.addEventListener('pointercancel', e => this._btnUp(e),       { passive: false });
            el.addEventListener('pointerdown',   e => el.setPointerCapture(e.pointerId));
        });
    }

    _btnDown(e, id) {
        e.preventDefault();
        this.pointerMap[e.pointerId] = id;
        this.keys[this._currentKey(id)] = true;
        document.getElementById(id).classList.add('pressed');
    }

    _btnUp(e) {
        e.preventDefault();
        const id = this.pointerMap[e.pointerId];
        if (!id) return;
        delete this.pointerMap[e.pointerId];
        const key = this._currentKey(id);
        const stillHeld = Object.values(this.pointerMap).some(bid => this._currentKey(bid) === key);
        if (!stillHeld) this.keys[key] = false;
        document.getElementById(id).classList.remove('pressed');
    }

    /* ── Layout ──────────────────────────────────────────── */

    _applyMode() {
        this.overlay.classList.remove('mode-menu', 'mode-race');
        this.overlay.classList.add('mode-' + this.mode);
    }

    _wireResize() {
        window.addEventListener('resize', () => {
            if (this.active) { this._resizeCanvas(); this._updateOrientation(); }
        });
        window.addEventListener('orientationchange', () => {
            setTimeout(() => {
                if (this.active) { this._resizeCanvas(); this._updateOrientation(); }
            }, 250);
        });
    }

    _resizeCanvas() {
        // CSS sizes the canvas (height:100dvh, auto width → natural pillarbox).
        // After layout settle, measure canvas bounds and place controls in sidebars.
        requestAnimationFrame(() => this._repositionControls());
    }

    _repositionControls() {
        const vw  = window.innerWidth;
        const vh  = window.innerHeight;
        const pad = 10;

        const rect  = this.canvas.getBoundingClientRect();
        const leftW  = rect.left;       // width of left black sidebar
        const rightW = vw - rect.right; // width of right black sidebar

        /* Joystick — centred in left sidebar */
        const stickSize = Math.max(56, Math.min(leftW - pad * 2, 130));
        const nubSize   = Math.round(stickSize * 0.38);
        const stickL    = Math.max(pad, Math.floor((leftW - stickSize) / 2));
        const stickT    = Math.floor((vh - stickSize) / 2);

        const sz = this.stickZone;
        sz.style.width  = stickSize + 'px';
        sz.style.height = stickSize + 'px';
        sz.style.left   = stickL + 'px';
        sz.style.top    = stickT + 'px';

        this.JOYSTICK_RADIUS = stickSize / 2 - 4;

        const nub = this.stickNub;
        nub.style.width  = nubSize + 'px';
        nub.style.height = nubSize + 'px';

        /* A + B buttons — stacked vertically, centred in right sidebar */
        const btnSize = Math.max(44, Math.min(rightW - pad * 2, 66));
        const gap     = Math.round(btnSize * 0.30);
        const totalH  = btnSize * 2 + gap;
        const bT      = Math.floor((vh - totalH) / 2);
        const bL      = Math.floor(rect.right + (rightW - btnSize) / 2);

        const act = document.getElementById('mkc-actions');
        act.style.left   = bL + 'px';
        act.style.top    = bT + 'px';
        act.style.width  = btnSize + 'px';
        act.style.height = totalH + 'px';

        const btnA = document.getElementById('mkc-btn-a');
        btnA.style.width  = btnSize + 'px';
        btnA.style.height = btnSize + 'px';
        btnA.style.left   = '0';
        btnA.style.top    = '0';

        const btnB = document.getElementById('mkc-btn-b');
        btnB.style.width  = btnSize + 'px';
        btnB.style.height = btnSize + 'px';
        btnB.style.left   = '0';
        btnB.style.top    = (btnSize + gap) + 'px';

        /* Scale label text to button size */
        const lblPx = Math.round(btnSize * 0.38);
        document.querySelectorAll('.mkc-btn-label').forEach(el => el.style.fontSize = lblPx + 'px');
    }

    _updateOrientation() {
        const isPortrait = window.innerWidth < window.innerHeight;
        this.portraitEl.classList.toggle('show', isPortrait);
        this.overlay.style.visibility = isPortrait ? 'hidden' : 'visible';
    }
}
