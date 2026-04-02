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

let lastTime = performance.now();

function gameLoop(currentTime) {
    const deltaTime = (currentTime - lastTime) / 1000;
    lastTime = currentTime;

    const clampedDeltaTime = Math.min(deltaTime, 0.1);
    
    if (clampedDeltaTime > 0) {
        screenManager.update(keys, clampedDeltaTime);
        screenManager.draw();
    }
    
    requestAnimationFrame(gameLoop);
}

requestAnimationFrame(gameLoop);