import { Settings } from './src/core/Settings.js';
import { ScreenManager } from './src/core/ScreenManager.js';
import { MainMenuScreen } from './src/screens/MainMenuScreen.js';
import { MobileControls } from './src/utils/MobileControls.js';


const canvas = document.getElementById('gameCanvas');
canvas.width = Settings.canvas.width;
canvas.height = Settings.canvas.height;

const screenManager = new ScreenManager(canvas);

const keys = {};

// Mobile touch controls — injects into the same keys object
const mobileControls = new MobileControls(keys, canvas);
screenManager.mobileControls = mobileControls;

screenManager.setScreen(new MainMenuScreen(screenManager));

const gameKeys = new Set(['Enter', 'Escape', 'ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight', ' ', 'Shift']);

window.addEventListener('keydown', function(e) {
    // Leave Tab, browser shortcuts, and focused buttons available in the embed.
    if (!gameKeys.has(e.key) || e.ctrlKey || e.metaKey || e.altKey || e.target.closest('button, a, input')) return;
    keys[e.key] = true;
    e.preventDefault();
});

window.addEventListener('keyup', function(e) {
    keys[e.key] = false;
});

window.addEventListener('blur', function() {
    Object.keys(keys).forEach(key => { keys[key] = false; });
});

canvas.addEventListener('pointerdown', function() {
    if (document.activeElement instanceof HTMLElement) document.activeElement.blur();
    window.focus();
});

// Frame rate configuration
const TARGET_FPS = 60;
const FRAME_TIME = 1000 / TARGET_FPS;   // ms per logical tick  (~16.667ms)
const FIXED_DT   = FRAME_TIME / 1000;   // seconds per tick     (0.01667s)

let lastTime    = performance.now();
let accumulator = 0;

function gameLoop(currentTime) {
    // Clamp raw elapsed time so a tab-background pause doesn't cause a huge spike
    const elapsed = Math.min(currentTime - lastTime, FRAME_TIME * 5);
    lastTime = currentTime;

    accumulator += elapsed;

    // Consume fixed-size ticks — keeps physics/speed identical regardless of
    // whether the device runs at 60 Hz, 90 Hz, 120 Hz, or ProMotion 144 Hz
    while (accumulator >= FRAME_TIME) {
        screenManager.update(keys, FIXED_DT);
        accumulator -= FRAME_TIME;
    }

    // Always draw once per rAF (interpolation could go here later)
    screenManager.draw();

    requestAnimationFrame(gameLoop);
}

requestAnimationFrame(gameLoop);
