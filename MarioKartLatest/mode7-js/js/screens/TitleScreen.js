import { Settings } from "../Settings.js";

export class TitleScreen {
    constructor(screenManager) {
        this.screenManager = screenManager;
        this.blinkTimer = 0;
        this.showText = true;
    }

    enter(gameData) {
        console.log('Entered Title Screen');
        this.blinkTimer = 0;
    }

    update(keys) {
        this.blinkTimer++
        if (this.blinkTimer > 30) {
            this.showText = !this.showText;
            this.blinkTimer = 0;
        }

        if (keys['Enter']) {
            import('./ModeSelectionScreen.js').then((module) => {
                this.screenManager.setScreen(new module.ModeSelectionScreen(this.screenManager));
            });
            keys['Enter'] = false;
        }
    }

    draw(ctx) {
        ctx.fillStyle = 'black';
        ctx.fillRect(0, 0, Settings.canvas.width, Settings.canvas.height);

        ctx.fillStyle = 'white';
        ctx.font = '32px Monospace';
        ctx.textAlign = 'center';
        ctx.fillText('My Racing Game', Settings.canvas.width / 2, Settings.canvas.height / 2 - 40);

        if (this.showText) {
            ctx.font = '16px Monospace';
            ctx.fillText('Press Enter to Start', Settings.canvas.width / 2, Settings.canvas.height / 2 + 60);
        }
    }

    exit() {
        console.log('Exiting Title Screen');
    }
}