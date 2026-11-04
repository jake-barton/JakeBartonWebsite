import { Settings } from "../core/Settings.js";
import { FontRenderer } from '../utils/FontRenderer.js';

export class ModeSelectionScreen {
    constructor(screenManager) {
        this.screenManager = screenManager;
        this.fontRenderer = new FontRenderer();
        this.selectedMode = 0;
        this.modes = ['Single Player', 'Multiplayer'];
        
        // Animation properties
        this.slideProgress = 0;
        this.slideSpeed = 3;
        this.isSliding = true;
        this.scrollOffset = 0;
        this.scrollSpeed = 20;
        
        // Animated logo
        this.logoFrames = [];
        this.logoFrameCount = 35;
        this.currentLogoFrame = 0;
        this.logoAnimationTimer = 0;
        this.logoFrameRate = 12;
        
        // Load animated logo frames
        for (let i = 1; i <= this.logoFrameCount; i++) {
            const img = new Image();
            img.src = `assets/sprites/ui/logo/logo${i}.png`;
            this.logoFrames.push(img);
        }
        
        this.scrollBg = new Image();
        this.scrollBg.src = 'MARIO KART SNES/images/scroll.png';
        
        // Input tracking
        this.pressedKeys = {};
    }

    enter(gameData) {
        console.log('Entered Mode Selection Screen');
        this.selectedMode = 0;
        this.slideProgress = 0;
        this.isSliding = true;
        this.scrollOffset = 0;
        this.currentLogoFrame = 0;
        this.logoAnimationTimer = 0;
    }

    update(keys, deltaTime) {
        // Update scroll
        this.scrollOffset -= this.scrollSpeed * deltaTime;
        
        // Update logo animation
        this.logoAnimationTimer += deltaTime;
        if (this.logoAnimationTimer >= 1.0 / this.logoFrameRate) {
            this.logoAnimationTimer = 0;
            this.currentLogoFrame = (this.currentLogoFrame + 1) % this.logoFrameCount;
        }
        
        // Update slide-in animation
        if (this.isSliding) {
            this.slideProgress += deltaTime * this.slideSpeed;
            if (this.slideProgress >= 1) {
                this.slideProgress = 1;
                this.isSliding = false;
            }
        }
        
        // Menu navigation
        if (keys['ArrowUp'] && !this.pressedKeys['up']) {
            this.pressedKeys['up'] = true;
            this.selectedMode--;
            if (this.selectedMode < 0) {
                this.selectedMode = this.modes.length - 1;
            }
        }
        if (!keys['ArrowUp']) this.pressedKeys['up'] = false;

        if (keys['ArrowDown'] && !this.pressedKeys['down']) {
            this.pressedKeys['down'] = true;
            this.selectedMode++;
            if (this.selectedMode >= this.modes.length) {
                this.selectedMode = 0;
            }
        }
        if (!keys['ArrowDown']) this.pressedKeys['down'] = false;

        if (keys['Enter'] && !this.pressedKeys['enter']) {
            this.pressedKeys['enter'] = true;
            this.screenManager.setGameData('mode', this.modes[this.selectedMode]);
            console.log('Selected mode:', this.modes[this.selectedMode]);

            import('./TrackSelectionScreen.js').then((module) => {
                this.screenManager.setScreen(new module.TrackSelectionScreen(this.screenManager));
            });
        }
        if (!keys['Enter']) this.pressedKeys['enter'] = false;
    }

    draw() {
        const ctx = this.screenManager.ctx;
        const canvas = this.screenManager.canvas;
        
        // Draw scrolling background
        this.drawScrollingBackground(ctx, canvas);
        
        // Draw animated logo
        this.drawLogo(ctx, canvas);
        
        // Draw title
        this.drawTitle(ctx, canvas);
        
        // Draw mode options with slide-in animation
        this.drawModeOptions(ctx, canvas);
        
        // Draw scanlines effect
        this.drawScanlines(ctx, canvas);
    }

    drawScrollingBackground(ctx, canvas) {
        if (!this.scrollBg.complete) {
            ctx.fillStyle = '#0b2240';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            return;
        }
        
        ctx.save();
        ctx.imageSmoothingEnabled = false;
        
        const bgHeight = canvas.height;
        const bgWidth = this.scrollBg.width * (bgHeight / this.scrollBg.height);
        
        // Wrap scroll
        const wrappedScroll = this.scrollOffset % bgWidth;
        
        // Draw multiple tiles to fill screen
        const tilesNeeded = Math.ceil(canvas.width / bgWidth) + 2;
        
        for (let i = 0; i < tilesNeeded; i++) {
            const x = wrappedScroll + (i * bgWidth);
            ctx.drawImage(this.scrollBg, x, 0, bgWidth, bgHeight);
        }
        
        ctx.restore();
    }

    drawLogo(ctx, canvas) {
        const currentFrame = this.logoFrames[this.currentLogoFrame];
        if (!currentFrame || !currentFrame.complete) return;
        
        ctx.save();
        ctx.imageSmoothingEnabled = false;
        
        // Fade in logo
        const progress = this.slideProgress;
        const easeOut = 1 - Math.pow(1 - progress, 3);
        ctx.globalAlpha = easeOut;
        
        const logoMaxWidth = canvas.width * 0.9;
        const logoMaxHeight = 35;
        const logoScale = Math.min(logoMaxWidth / currentFrame.width, logoMaxHeight / currentFrame.height);
        const logoWidth = currentFrame.width * logoScale;
        const logoHeight = currentFrame.height * logoScale;
        
        const logoX = (canvas.width - logoWidth) / 2;
        const logoY = 10;
        
        ctx.drawImage(currentFrame, logoX, logoY, logoWidth, logoHeight);
        
        ctx.restore();
    }

    drawTitle(ctx, canvas) {
        ctx.save();
        ctx.imageSmoothingEnabled = false;
        
        // Slide in from top with ease-out
        const progress = this.slideProgress;
        const easeOut = 1 - Math.pow(1 - progress, 3);
        
        const titleText = 'SELECT MODE';
        const titleScale = 1;
        const titleWidth = this.fontRenderer.measureText(titleText, titleScale) + 16;
        const titleHeight = 16;
        const titleX = (canvas.width - titleWidth) / 2;
        const titleFinalY = 55;
        
        // Slide in from above
        const titleY = titleFinalY - (1 - easeOut) * 80;
        
        // Fade in opacity
        ctx.globalAlpha = easeOut;
        
        // Glass background
        ctx.fillStyle = 'rgba(255, 255, 255, 0.2)';
        ctx.fillRect(titleX, titleY, titleWidth, titleHeight);
        
        // Border
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.3)';
        ctx.lineWidth = 1;
        ctx.strokeRect(titleX + 0.5, titleY + 0.5, titleWidth - 1, titleHeight - 1);
        
        // Title text
        this.fontRenderer.drawText(
            ctx,
            titleText,
            canvas.width / 2,
            titleY + 5,
            titleScale,
            true,
            null
        );
        
        ctx.restore();
    }

    drawModeOptions(ctx, canvas) {
        ctx.save();
        ctx.imageSmoothingEnabled = false;
        
        // Calculate slide animation with ease-out
        const progress = this.slideProgress;
        const easeOut = 1 - Math.pow(1 - progress, 3);
        
        const startY = canvas.height / 2 - 10;
        const spacing = 50;
        
        this.modes.forEach((mode, index) => {
            const isSelected = index === this.selectedMode;
            
            // Slide in from right with staggered timing
            const staggerDelay = index * 0.2;
            const itemProgress = Math.max(0, Math.min(1, (progress - staggerDelay) / (1 - staggerDelay)));
            const itemEaseOut = 1 - Math.pow(1 - itemProgress, 3);
            
            const slideOffset = (1 - itemEaseOut) * canvas.width;
            
            const y = startY + (index * spacing);
            const panelWidth = 180;
            const panelHeight = 35;
            const panelX = (canvas.width - panelWidth) / 2 + slideOffset;
            const panelY = y - panelHeight / 2;
            
            // Glass panel
            ctx.fillStyle = isSelected ? 'rgba(255, 255, 255, 0.3)' : 'rgba(255, 255, 255, 0.15)';
            ctx.fillRect(panelX, panelY, panelWidth, panelHeight);
            
            // Border
            ctx.strokeStyle = isSelected ? 'rgba(255, 215, 0, 0.6)' : 'rgba(255, 255, 255, 0.25)';
            ctx.lineWidth = isSelected ? 2 : 1;
            ctx.strokeRect(panelX + 0.5, panelY + 0.5, panelWidth - 1, panelHeight - 1);
            
            // Mode text
            this.fontRenderer.drawText(
                ctx,
                mode.toUpperCase(),
                panelX + panelWidth / 2,
                panelY + 12,
                1,
                true,
                null
            );
        });
        
        ctx.restore();
    }

    drawScanlines(ctx, canvas) {
        ctx.save();
        ctx.globalAlpha = 0.06;
        
        for (let y = 0; y < canvas.height; y += 2) {
            ctx.fillStyle = '#000000';
            ctx.fillRect(0, y, canvas.width, 1);
        }
        
        ctx.restore();
    }

    exit() {
        console.log('Exited Mode Selection Screen');
    }
}