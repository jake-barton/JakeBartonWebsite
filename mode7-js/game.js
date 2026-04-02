import { Settings } from './src/core/Settings.js';
import { ScreenManager } from './src/core/ScreenManager.js';
import { MainMenuScreen } from './src/screens/MainMenuScreen.js';
import { MobileControls } from './src/utils/MobileControls.js';


const canvas = document.getElementById('gameCanvas');
canvas.width = Settings.canvas.width;
canvas.height = Settings.canvas.height;

const screenManager = new ScreenManager(canvas);
screenManager.setScreen(new MainMenuScreen(screenManager));

const keys = {};

// Mobile touch controls — injects into the same keys object
new MobileControls(keys, canvas);

window.addEventListener('keydown', function(e) {
    keys[e.key] = true;
    e.preventDefault();
});

window.addEventListener('keyup', function(e) {
    keys[e.key] = false;
});

// Frame rate configuration
const TARGET_FPS = 60; // Set your target frame rate here (60 is recommended)
const FRAME_TIME = 1000 / TARGET_FPS; // Time per frame in milliseconds
const MAX_DELTA_TIME = 0.1; // Maximum deltaTime to prevent spiral of death

let lastTime = performance.now();
let accumulator = 0;

function gameLoop(currentTime) {
    const deltaTime = currentTime - lastTime;
    lastTime = currentTime;
    
    // Accumulate time
    accumulator += deltaTime;
    
    // Only update if enough time has passed for the next frame
    if (accumulator >= FRAME_TIME) {
        // Calculate actual deltaTime in seconds
        const dt = accumulator / 1000;
        
        // Clamp deltaTime to prevent huge jumps
        const clampedDeltaTime = Math.min(dt, MAX_DELTA_TIME);
        
        if (clampedDeltaTime > 0) {
            screenManager.update(keys, clampedDeltaTime);
            screenManager.draw();
        }
        
        // Reset accumulator (subtract frame time, not set to 0, to maintain accuracy)
        accumulator -= FRAME_TIME;
        
        // If we've fallen too far behind, reset accumulator to prevent spiral
        if (accumulator > FRAME_TIME * 5) {
            accumulator = 0;
        }
    }
    
    requestAnimationFrame(gameLoop);
}

requestAnimationFrame(gameLoop);