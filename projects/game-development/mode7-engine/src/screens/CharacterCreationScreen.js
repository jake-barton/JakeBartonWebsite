import { Settings } from '../core/Settings.js';


export class CharacterCreationScreen {
    constructor(screenManager) {
        this.screenManager = screenManager;
        this.pixels = [];
        for (let y = 0; y < 32; y++) {
            this.pixels[y] = [];
            for (let x = 0; x < 32; x++) {
                this.pixels[y][x] = 'transparent';
            }
        }
        this.selectedColor = 'black';
        this.currentTool = 'pencil';
    }

    enter(gameData) {
    }

    update(keys) {
        if (keys['Escape']) {
            import('./TrackSelectionScreen.js').then((module) => {
                this.screenManager.setScreen(new module.TrackSelectionScreen(this.screenManager));
            });
            keys['Escape'] = false;
        }
    }

    draw(ctx) {
        ctx.fillStyle = 'darkgray';
        ctx.fillRect(0, 0, Settings.canvas.width, Settings.canvas.height);

        ctx.fillStyle = 'white';
        ctx.font = '24px Monospace';
        ctx.textAlign = 'center';
        ctx.fillText('Character Creation (Placeholder)', Settings.canvas.width / 2, 30);

        const pixelSize = 8;
        const gridSize = 32 * pixelSize;
        const gridX = (Settings.canvas.width - gridSize) / 2;
        const gridY = 60;

        for (let y = 0; y < 32; y++) {
            for (let x = 0; x < 32; x++) {
                const screenX = gridX + x * pixelSize;
                const screenY = gridY + y * pixelSize;

                ctx.fillStyle = this.pixels[y][x];
                ctx.fillRect(screenX, screenY, pixelSize, pixelSize);

                ctx.strokeStyle = 'lightgray';
                ctx.lineWidth = 0.5;
                ctx.strokeRect(screenX, screenY, pixelSize, pixelSize);
            }
        }

        ctx.fillStyle = 'white';
        ctx.font = '16px Monospace';
        ctx.fillText('Press ESC for now.', Settings.canvas.width / 2, Settings.canvas.height - 20);
    }

    exit() {
    }
}