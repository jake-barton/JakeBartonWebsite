import { CharacterSelectionScreen } from './CharacterSelectionScreen.js';
import { ModeSelectionScreen } from './ModeSelectionScreen.js';
import { FontRenderer } from '../utils/FontRenderer.js';
import { CharacterPool } from '../utils/CharacterPool.js';

export class MainMenuScreen {
    constructor(screenManager) {
        this.screenManager = screenManager;
        this.fontRenderer = new FontRenderer();
        
        // Menu state
        this.showingStartScreen = true;
        this.selectedIndex = 0;
        this.menuItems = [
            { text: 'GRAND PRIX', action: 'grand-prix' },
            { text: 'TIME TRIAL', action: 'time-trial' },
            { text: 'VS MODE', action: 'vs-mode' },
            { text: 'BATTLE MODE', action: 'battle' }
        ];
        
        // Animation timers
        this.blinkTimer = 0;
        this.scrollOffset = 0;
        this.scrollSpeed = 20; // pixels per second
        this.transitionProgress = 0;
        this.isTransitioning = false;
        
        // Animated logo
        this.logoFrames = [];
        this.logoFrameCount = 35;
        this.currentLogoFrame = 0;
        this.logoAnimationTimer = 0;
        this.logoFrameRate = 12; // frames per second
        
        // Logo slide-in animation
        this.logoSlideProgress = 0; // 0 to 1
        this.logoSlideSpeed = 2.5; // Speed of slide animation
        this.logoBounceTimer = 0; // For bounce effect after slide
        this.logoIsSliding = true;
        
        // Animated karts driving across screen
        this.karts = [];
        this.kartSpawnTimer = 0;
        this.kartSpawnInterval = 1.5; // Spawn a kart every 1.5 seconds (closer together)
        
        // Create character pool instance to get character data
        const characterPool = new CharacterPool();
        this.characters = characterPool.getAvailableCharacters();
        this.characterImages = new Map();
        
        // Load character images using the imagePath from CharacterPool
        this.characters.forEach(char => {
            const img = new Image();
            img.src = char.imagePath; // Use the imagePath property which has the correct filename
            this.characterImages.set(char.id, img);
        });
        
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
        console.log('Entered Main Menu Screen');
        this.showingStartScreen = true;
        this.selectedIndex = 0;
        this.scrollOffset = 0;
        this.transitionProgress = 0;
        this.isTransitioning = false;
        this.currentLogoFrame = 0;
        this.logoAnimationTimer = 0;
        this.logoSlideProgress = 0;
        this.logoIsSliding = true;
        this.logoBounceTimer = 0;
        this.karts = [];
        this.kartSpawnTimer = 0;
    }

    update(keys, deltaTime) {
        // Update scroll
        this.scrollOffset -= this.scrollSpeed * deltaTime;
        
        // Update blink animation
        this.blinkTimer += deltaTime;
        
        // Update logo animation
        this.logoAnimationTimer += deltaTime;
        if (this.logoAnimationTimer >= 1.0 / this.logoFrameRate) {
            this.logoAnimationTimer = 0;
            this.currentLogoFrame = (this.currentLogoFrame + 1) % this.logoFrameCount;
        }
        
        // Update logo slide-in animation
        if (this.logoIsSliding) {
            this.logoSlideProgress += deltaTime * this.logoSlideSpeed;
            if (this.logoSlideProgress >= 1) {
                this.logoSlideProgress = 1;
                this.logoIsSliding = false;
            }
        } else {
            // After sliding, add subtle bounce
            this.logoBounceTimer += deltaTime * 3;
        }
        
        // Update kart spawning and movement
        this.kartSpawnTimer += deltaTime;
        if (this.kartSpawnTimer >= this.kartSpawnInterval) {
            this.kartSpawnTimer = 0;
            this.spawnKart();
        }
        
        // Update existing karts
        this.karts = this.karts.filter(kart => {
            kart.x += kart.speed * deltaTime;
            kart.bounceTimer += deltaTime * 12; // Quicker bounce (was 8)
            
            // Remove karts that have gone off screen
            return kart.x < this.screenManager.canvas.width + 50;
        });
        
        // Start screen input - Enter to start
        if (this.showingStartScreen) {
            if (keys['Enter'] && !this.pressedKeys['start']) {
                this.pressedKeys['start'] = true;
                this.startTransition();
            }
            if (!keys['Enter']) {
                this.pressedKeys['start'] = false;
            }
            
            // Update transition animation
            if (this.isTransitioning) {
                this.transitionProgress += deltaTime * 2;
                if (this.transitionProgress >= 1) {
                    this.transitionProgress = 1;
                    this.showingStartScreen = false;
                    this.isTransitioning = false;
                    this.transitionProgress = 0;
                }
            }
            return;
        }
        
        // Menu navigation
        if (keys['ArrowUp'] && !this.pressedKeys['up']) {
            this.pressedKeys['up'] = true;
            this.selectedIndex = (this.selectedIndex - 1 + this.menuItems.length) % this.menuItems.length;
        }
        if (!keys['ArrowUp']) this.pressedKeys['up'] = false;
        
        if (keys['ArrowDown'] && !this.pressedKeys['down']) {
            this.pressedKeys['down'] = true;
            this.selectedIndex = (this.selectedIndex + 1) % this.menuItems.length;
        }
        if (!keys['ArrowDown']) this.pressedKeys['down'] = false;
        
        if (keys['Enter'] && !this.pressedKeys['enter']) {
            this.pressedKeys['enter'] = true;
            this.selectMenuItem();
        }
        if (!keys['Enter']) this.pressedKeys['enter'] = false;
        
        // Update transition
        if (this.isTransitioning) {
            this.transitionProgress += deltaTime * 2;
            if (this.transitionProgress >= 1) {
                this.transitionProgress = 1;
            }
        }
    }

    startTransition() {
        this.isTransitioning = true;
        this.transitionProgress = 0;
    }

    spawnKart() {
        const canvas = this.screenManager.canvas;
        
        // Get characters that are not currently visible on screen (within 200px from left edge)
        const recentKarts = this.karts.filter(k => k.x < 200);
        const recentCharacterIds = new Set(recentKarts.map(k => k.character.id));
        
        // Filter out recently used characters
        const availableCharacters = this.characters.filter(char => !recentCharacterIds.has(char.id));
        
        // If all characters are recent (shouldn't happen often), use all characters
        const characterPool = availableCharacters.length > 0 ? availableCharacters : this.characters;
        
        // Pick a random character from available pool
        const character = characterPool[Math.floor(Math.random() * characterPool.length)];
        
        // Random Y position (bottom half of screen only)
        const minY = canvas.height / 2 + 20;
        const maxY = canvas.height - 50;
        const y = minY + Math.random() * (maxY - minY);
        
        // Random speed
        const speed = 80 + Math.random() * 40; // 80-120 pixels per second
        
        // Random bounce phase offset for variety
        const bouncePhase = Math.random() * Math.PI * 2;
        
        this.karts.push({
            character: character,
            x: -50, // Start off screen to the left
            y: y,
            speed: speed,
            bounceTimer: bouncePhase // Start at random phase for variety
        });
    }

    selectMenuItem() {
        const selectedItem = this.menuItems[this.selectedIndex];
        console.log('Selected:', selectedItem.text);
        
        // Start transition
        this.isTransitioning = true;
        this.transitionProgress = 0;
        
        // Wait for transition then switch screens
        setTimeout(() => {
            if (selectedItem.action === 'grand-prix') {
                // Go to character selection for Grand Prix
                this.screenManager.setScreen(new CharacterSelectionScreen(this.screenManager));
            } else if (selectedItem.action === 'time-trial') {
                this.screenManager.setScreen(new CharacterSelectionScreen(this.screenManager));
            } else if (selectedItem.action === 'vs-mode') {
                this.screenManager.setScreen(new ModeSelectionScreen(this.screenManager));
            } else if (selectedItem.action === 'battle') {
                this.screenManager.setScreen(new ModeSelectionScreen(this.screenManager));
            }
        }, 500);
    }

    draw() {
        const ctx = this.screenManager.ctx;
        const canvas = this.screenManager.canvas;
        
        // Draw scrolling background
        this.drawScrollingBackground(ctx, canvas);
        
        // Draw animated karts
        this.drawKarts(ctx, canvas);
        
        if (this.showingStartScreen) {
            this.drawStartScreen(ctx, canvas);
        } else {
            this.drawMenu(ctx, canvas);
        }
        
        // Draw transition overlay
        if (this.isTransitioning) {
            this.drawTransition(ctx, canvas);
        }
        
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

    drawKarts(ctx, canvas) {
        ctx.save();
        ctx.imageSmoothingEnabled = false;
        
        this.karts.forEach(kart => {
            const charImg = this.characterImages.get(kart.character.id);
            if (!charImg || !charImg.complete) return;
            
            // Use lod0 frame 6 (90-degree side view, facing right)
            const sprite = kart.character.sprites.lod0[6];
            
            // Original size (no scaling)
            const drawWidth = sprite.width;
            const drawHeight = sprite.height;
            
            // Calculate bounce offset (smaller and faster bounce)
            const bounceAmount = Math.sin(kart.bounceTimer) * 1; // 1 pixel bounce (was 2)
            const kartY = kart.y + bounceAmount;
            
            // Draw the kart at original size with bounce (no shadow)
            ctx.drawImage(
                charImg,
                sprite.x, sprite.y, sprite.width, sprite.height,
                Math.floor(kart.x - drawWidth / 2),
                Math.floor(kartY - drawHeight / 2),
                drawWidth,
                drawHeight
            );
        });
        
        ctx.restore();
    }

    drawStartScreen(ctx, canvas) {
        ctx.save();
        
        // Draw animated logo with slide-in and bounce effect
        const currentFrame = this.logoFrames[this.currentLogoFrame];
        if (currentFrame && currentFrame.complete) {
            ctx.imageSmoothingEnabled = false;
            
            const logoMaxWidth = canvas.width * 0.9;
            const logoMaxHeight = 50;
            const logoScale = Math.min(logoMaxWidth / currentFrame.width, logoMaxHeight / currentFrame.height);
            const logoWidth = currentFrame.width * logoScale;
            const logoHeight = currentFrame.height * logoScale;
            
            const logoX = (canvas.width - logoWidth) / 2;
            const logoFinalY = 20;
            
            // Calculate logo Y position with slide-in and bounce
            let logoY;
            if (this.logoIsSliding) {
                // Ease-out cubic for smooth deceleration
                const progress = this.logoSlideProgress;
                const easeOut = 1 - Math.pow(1 - progress, 3);
                
                // Slide from above the screen
                const startY = -logoHeight - 20;
                logoY = startY + (logoFinalY - startY) * easeOut;
                
                // Add overshoot at the end for bounce effect
                if (progress > 0.7) {
                    const bounceProgress = (progress - 0.7) / 0.3;
                    const overshoot = Math.sin(bounceProgress * Math.PI) * 8;
                    logoY += overshoot;
                }
            } else {
                // Subtle continuous bounce after slide completes
                const bounce = Math.sin(this.logoBounceTimer) * 2;
                logoY = logoFinalY + bounce;
            }
            
            ctx.drawImage(currentFrame, logoX, logoY, logoWidth, logoHeight);
        }
        
        // Draw "PRESS ENTER" text (blinking)
        const blinkVisible = Math.floor(this.blinkTimer * 2) % 2 === 0;
        
        if (blinkVisible) {
            ctx.imageSmoothingEnabled = false;
            this.fontRenderer.drawText(
                ctx,
                'PRESS ENTER TO START',
                canvas.width / 2,
                canvas.height - 20,
                1,
                true,
                null // Use default blue color
            );
        }
        
        ctx.restore();
    }

    drawMenu(ctx, canvas) {
        ctx.save();
        
        // Draw animated logo at top
        const currentFrame = this.logoFrames[this.currentLogoFrame];
        if (currentFrame && currentFrame.complete) {
            ctx.imageSmoothingEnabled = false;
            
            const logoMaxWidth = canvas.width * 0.9;
            const logoMaxHeight = 40;
            const logoScale = Math.min(logoMaxWidth / currentFrame.width, logoMaxHeight / currentFrame.height);
            const logoWidth = currentFrame.width * logoScale;
            const logoHeight = currentFrame.height * logoScale;
            
            const logoX = (canvas.width - logoWidth) / 2;
            const logoY = 10;
            
            ctx.drawImage(currentFrame, logoX, logoY, logoWidth, logoHeight);
        }
        
        // Draw menu window
        const menuWidth = canvas.width - 24;
        const menuHeight = 130;
        const menuX = 12;
        const menuY = 60;
        
        // Glass-like background
        ctx.fillStyle = 'rgba(255, 255, 255, 0.2)';
        ctx.fillRect(menuX, menuY, menuWidth, menuHeight);
        
        // Border
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.3)';
        ctx.lineWidth = 1;
        ctx.strokeRect(menuX + 0.5, menuY + 0.5, menuWidth - 1, menuHeight - 1);
        
        // Menu title
        ctx.imageSmoothingEnabled = false;
        this.fontRenderer.drawText(
            ctx,
            'SELECT MODE',
            menuX + 8,
            menuY + 8,
            1,
            false,
            null
        );
        
        // Draw menu items
        const itemStartY = menuY + 24;
        const itemHeight = 18;
        const itemPadding = 4;
        
        this.menuItems.forEach((item, index) => {
            const isSelected = index === this.selectedIndex;
            const itemY = itemStartY + (index * (itemHeight + itemPadding));
            const itemX = menuX + 6;
            const itemWidth = menuWidth - 12;
            
            // Item background
            if (isSelected) {
                const gradient = ctx.createLinearGradient(itemX, itemY, itemX, itemY + itemHeight);
                gradient.addColorStop(0, '#fff6e6');
                gradient.addColorStop(1, '#ffe6e6');
                ctx.fillStyle = gradient;
            } else {
                const gradient = ctx.createLinearGradient(itemX, itemY, itemX, itemY + itemHeight);
                gradient.addColorStop(0, '#ffffff');
                gradient.addColorStop(1, '#e6eefc');
                ctx.fillStyle = gradient;
            }
            
            ctx.fillRect(itemX, itemY, itemWidth, itemHeight);
            
            // Item shadow
            if (isSelected) {
                ctx.strokeStyle = 'rgba(0, 0, 0, 0.12)';
                ctx.lineWidth = 2;
                ctx.strokeRect(itemX + 2, itemY + 2, itemWidth, itemHeight);
            }
            
            // Arrow for selected item
            if (isSelected) {
                ctx.fillStyle = '#d62828';
                ctx.beginPath();
                ctx.moveTo(itemX + 8, itemY + itemHeight / 2);
                ctx.lineTo(itemX + 14, itemY + itemHeight / 2 - 3);
                ctx.lineTo(itemX + 14, itemY + itemHeight / 2 + 3);
                ctx.closePath();
                ctx.fill();
            }
            
            // Item text
            ctx.imageSmoothingEnabled = false;
            this.fontRenderer.drawText(
                ctx,
                item.text,
                itemX + 20,
                itemY + 6,
                1,
                false,
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

    drawTransition(ctx, canvas) {
        ctx.save();
        
        // Navy circle growing from center
        const maxRadius = Math.sqrt(canvas.width ** 2 + canvas.height ** 2);
        const currentRadius = maxRadius * this.transitionProgress;
        
        ctx.fillStyle = '#000030';
        ctx.beginPath();
        ctx.arc(canvas.width / 2, canvas.height / 2, currentRadius, 0, Math.PI * 2);
        ctx.fill();
        
        ctx.restore();
    }

    exit() {
        console.log('Exited Main Menu Screen');
    }
}
