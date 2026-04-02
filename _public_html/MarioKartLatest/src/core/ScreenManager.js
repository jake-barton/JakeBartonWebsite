export class ScreenManager {
    constructor(canvas) {
        this.canvas = canvas;
        this.ctx = canvas.getContext('2d');

        this.currentScreen = null;
        this.gameData = {};

        /** Set by game.js after MobileControls is created */
        this.mobileControls = null;
    }

    setScreen(screen, gameData = {}) {
        if (this.currentScreen && this.currentScreen.exit) {
                this.currentScreen.exit();
        }

        this.currentScreen = screen;

        if (this.currentScreen && this.currentScreen.enter) {
                this.currentScreen.enter(gameData);
        }
    }

    update(keys, deltaTime) {
        if (this.currentScreen && this.currentScreen.update) {
            this.currentScreen.update(keys, deltaTime);
        }
    }

    draw() {
        if (this.currentScreen && this.currentScreen.draw) {
            this.currentScreen.draw(this.ctx);
        }
    }

    setGameData(key, value) {
        this.gameData[key] = value;
    }

    getGameData(key) {
        return this.gameData[key];
    }

    /** Convenience helpers called by individual screens */
    setMobileRaceMode()  { if (this.mobileControls) this.mobileControls.setRaceMode();  }
    setMobileMenuMode()  { if (this.mobileControls) this.mobileControls.setMenuMode();  }
}