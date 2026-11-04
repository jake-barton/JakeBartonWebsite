import { Settings } from '../core/Settings.js';
import { CharacterPool } from '../utils/CharacterPool.js';
import { FontRenderer } from '../utils/FontRenderer.js';

export class CharacterSelectionScreen {
    constructor(screenManager) {
        this.screenManager = screenManager;
        this.characterPool = new CharacterPool();
        this.fontRenderer = new FontRenderer();
        
        // Get all available characters
        this.characters = this.characterPool.availableCharacters;
        
        // Currently selected character index
        this.selectedIndex = 0;
        
        // Animation states
        this.bounceTimer = 0;
        this.bounceOffset = 0;
        
        // Character preview rotation animation (cycles through sprite frames)
        this.rotationFrame = 0;
        this.rotationTimer = 0;
        this.rotationSpeed = 5; // Frames per second for rotation
        
        // Character preview zoom animation
        this.zoomPulse = 0;
        this.zoomTimer = 0;
        
        // Scrolling background (same as main menu)
        this.scrollOffset = 0;
        this.scrollSpeed = 20;
        
        // Load scrolling background
        this.scrollBg = new Image();
        this.scrollBg.src = 'MARIO KART SNES/images/scroll.png';
        
        // Load character sprites for preview
        this.characterImages = new Map();
        this.characters.forEach(char => {
            const img = new Image();
            img.src = char.imagePath;
            this.characterImages.set(char.id, img);
        });
        
        // Grid layout (2 rows, 4 columns on the right side)
        this.gridLayout = this.calculateGridLayout();
        
        // Smooth transition for selection
        this.transitionProgress = 0;
        this.isTransitioning = false;
    }

    calculateGridLayout() {
        const canvas = this.screenManager.canvas;
        const cardWidth = 35;
        const cardHeight = 38; // Reduced from 42 to fit better
        const spacing = 6; // Reduced from 8 to be more compact
        const cols = 2;
        const rows = 4;
        
        // Position on the right side, vertically centered with proper margins
        const gridWidth = (cardWidth * cols) + (spacing * (cols - 1));
        const gridHeight = (cardHeight * rows) + (spacing * (rows - 1));
        const startX = canvas.width - gridWidth - 20;
        const startY = (canvas.height - gridHeight) / 2; // Center vertically
        
        const cards = [];
        this.characters.forEach((char, index) => {
            const row = Math.floor(index / cols);
            const col = index % cols;
            
            cards.push({
                character: char,
                x: startX + (col * (cardWidth + spacing)),
                y: startY + (row * (cardHeight + spacing)),
                width: cardWidth,
                height: cardHeight,
                index: index
            });
        });
        
        return cards;
    }

    enter(gameData) {
        console.log('Entered Character Selection Screen');
        // Reset animations
        this.bounceTimer = 0;
        this.rotationTimer = 0;
        this.rotationFrame = 0;
        this.selectedIndex = 0;
        this.scrollOffset = 0;
    }

    update(keys, deltaTime) {
        // Update scroll
        this.scrollOffset -= this.scrollSpeed * deltaTime;
        
        // Update animations
        this.bounceTimer += deltaTime * 3;
        this.bounceOffset = Math.sin(this.bounceTimer) * 5;
        
        // Update rotation animation (cycle through frames for 360° rotation)
        // Sprite sheets have 12 frames (0-11) covering 0° to 180°
        // For full 360°, go 0->11 then 10->1, creating 22 total frames
        this.rotationTimer += deltaTime * this.rotationSpeed;
        if (this.rotationTimer >= 1.0) {
            this.rotationTimer = 0;
            this.rotationFrame = (this.rotationFrame + 1) % 22; // 22 frames for full rotation
        }
        
        this.zoomTimer += deltaTime * 2;
        this.zoomPulse = Math.sin(this.zoomTimer) * 0.05;
        
        // Handle input - navigate grid with arrow keys
        if (keys['ArrowLeft'] && !this.pressedLeft) {
            this.pressedLeft = true;
            this.moveSelection(-1, 0);
        }
        if (!keys['ArrowLeft']) this.pressedLeft = false;
        
        if (keys['ArrowRight'] && !this.pressedRight) {
            this.pressedRight = true;
            this.moveSelection(1, 0);
        }
        if (!keys['ArrowRight']) this.pressedRight = false;
        
        if (keys['ArrowUp'] && !this.pressedUp) {
            this.pressedUp = true;
            this.moveSelection(0, -1);
        }
        if (!keys['ArrowUp']) this.pressedUp = false;
        
        if (keys['ArrowDown'] && !this.pressedDown) {
            this.pressedDown = true;
            this.moveSelection(0, 1);
        }
        if (!keys['ArrowDown']) this.pressedDown = false;
        
        // Enter to confirm selection
        if (keys['Enter'] && !this.pressedEnter) {
            this.pressedEnter = true;
            this.confirmSelection();
        }
        if (!keys['Enter']) this.pressedEnter = false;
        
        // Update transition
        if (this.isTransitioning) {
            this.transitionProgress += deltaTime * 3;
            if (this.transitionProgress >= 1) {
                this.transitionProgress = 1;
            }
        }
    }

    moveSelection(deltaX, deltaY) {
        const cols = 2;
        const currentRow = Math.floor(this.selectedIndex / cols);
        const currentCol = this.selectedIndex % cols;
        
        const newRow = currentRow + deltaY;
        const newCol = currentCol + deltaX;
        
        // Check bounds
        if (newRow >= 0 && newRow < 4 && newCol >= 0 && newCol < cols) {
            const newIndex = newRow * cols + newCol;
            if (newIndex < this.characters.length) {
                this.selectedIndex = newIndex;
                this.bounceTimer = 0; // Reset bounce for emphasis
                // DON'T reset rotation - let it continue spinning
            }
        }
    }

    confirmSelection() {
        const selectedCharacter = this.characters[this.selectedIndex];
        console.log('Selected character:', selectedCharacter.name);
        
        // Start transition animation
        this.isTransitioning = true;
        this.transitionProgress = 0;
        
        // Wait for animation to complete before switching screens
        setTimeout(() => {
            import('./TrackSelectionScreen.js').then((module) => {
                this.screenManager.setScreen(
                    new module.TrackSelectionScreen(this.screenManager),
                    { selectedCharacter: selectedCharacter.id }
                );
            });
        }, 800); // Increased from 500 to 800 to ensure transition completes
    }

    draw() {
        const ctx = this.screenManager.ctx;
        const canvas = this.screenManager.canvas;
        
        // Draw scrolling background (same as main menu)
        this.drawScrollingBackground(ctx, canvas);
        
        // Draw title
        this.drawTitle(ctx, canvas);
        
        // Large character preview on the left (spinning)
        this.drawLargeCharacterPreview(ctx, canvas);
        
        // Character grid on the right
        this.drawCharacterGrid(ctx, canvas);
        
        // Transition effect
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

    drawTitle(ctx, canvas) {
        ctx.save();
        ctx.imageSmoothingEnabled = false;
        
        // Shorter title text
        const titleText = 'PICK RACER';
        
        // Calculate title width based on font
        const titleScale = 1;
        const titleWidth = this.fontRenderer.measureText(titleText, titleScale) + 16;
        const titleHeight = 16;
        const titleX = (canvas.width - titleWidth) / 2;
        const titleY = 8;
        
        // Glass background
        ctx.fillStyle = 'rgba(255, 255, 255, 0.2)';
        ctx.fillRect(titleX, titleY, titleWidth, titleHeight);
        
        // Border
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.3)';
        ctx.lineWidth = 1;
        ctx.strokeRect(titleX + 0.5, titleY + 0.5, titleWidth - 1, titleHeight - 1);
        
        // Title text using pixel font
        this.fontRenderer.drawText(
            ctx,
            titleText,
            canvas.width / 2,
            titleY + 5,
            titleScale,
            true,
            null // Default blue color
        );
        
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

    drawLargeCharacterPreview(ctx, canvas) {
        const selectedChar = this.characters[this.selectedIndex];
        const charImg = this.characterImages.get(selectedChar.id);
        
        if (!charImg || !charImg.complete) return;
        
        ctx.save();
        
        // Position on the left side of the screen
        const previewX = canvas.width * 0.28;
        const previewY = canvas.height / 2 + 10;
        
        // Glass panel behind character
        const panelWidth = 100;
        const panelHeight = 120;
        const panelX = previewX - panelWidth / 2;
        const panelY = previewY - panelHeight / 2 - 10;
        
        ctx.fillStyle = 'rgba(255, 255, 255, 0.15)';
        ctx.fillRect(panelX, panelY, panelWidth, panelHeight);
        
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.25)';
        ctx.lineWidth = 1;
        ctx.strokeRect(panelX + 0.5, panelY + 0.5, panelWidth - 1, panelHeight - 1);
        
        // Oval shadow below character (changes with bounce)
        const shadowOffsetY = 55; // Distance below character
        const shadowY = previewY + shadowOffsetY;
        
        const shadowScale = 1 + (this.bounceOffset / 15);
        const shadowBaseWidth = 28;
        const shadowBaseHeight = 6;
        const shadowWidth = shadowBaseWidth * shadowScale;
        const shadowHeight = shadowBaseHeight * shadowScale;
        const shadowOpacity = 0.45 + (this.bounceOffset / 20);
        const pixelSize = 4;
        
        // Disable smoothing for pixel-perfect shadow
        ctx.imageSmoothingEnabled = false;
        
        ctx.save();
        ctx.translate(previewX, shadowY);
        
        // Draw pixelated oval shadow
        ctx.fillStyle = `rgba(0, 0, 0, ${shadowOpacity})`;
        
        const sw = Math.floor(shadowWidth);
        const sh = Math.floor(shadowHeight);
        
        // Center lines (widest)
        for (let i = 0; i < pixelSize; i++) {
            ctx.fillRect(Math.floor(-sw), -pixelSize/2 + i, Math.floor(sw * 2), 1);
        }
        
        // Upper and lower lines (narrower)
        const w1 = Math.floor(sw * 0.85);
        for (let i = 0; i < pixelSize; i++) {
            ctx.fillRect(Math.floor(-w1), -pixelSize/2 - pixelSize + i, Math.floor(w1 * 2), 1);
            ctx.fillRect(Math.floor(-w1), pixelSize/2 + i, Math.floor(w1 * 2), 1);
        }
        
        if (sh > pixelSize) {
            const w2 = Math.floor(sw * 0.6);
            for (let i = 0; i < pixelSize; i++) {
                ctx.fillRect(Math.floor(-w2), -pixelSize/2 - pixelSize * 2 + i, Math.floor(w2 * 2), 1);
                ctx.fillRect(Math.floor(-w2), pixelSize/2 + pixelSize + i, Math.floor(w2 * 2), 1);
            }
        }
        
        ctx.restore();
        
        // Disable image smoothing for crisp pixels
        ctx.imageSmoothingEnabled = false;
        
        // Draw spinning character using different sprite frames
        ctx.translate(previewX, previewY + this.bounceOffset);
        
        // Map rotation frame (0-21) to sprite index (0-11)
        // 0-11: forward (0->11), 12-21: flipped backward (10->1)
        let spriteIndex;
        let shouldFlip = false;
        
        if (this.rotationFrame <= 11) {
            spriteIndex = this.rotationFrame;
            shouldFlip = false;
        } else {
            spriteIndex = 22 - this.rotationFrame; // Goes 10, 9, 8... 1
            shouldFlip = true; // Flip horizontally for the other side
        }
        
        const sprite = selectedChar.sprites.lod1[spriteIndex];
        const scale = 2.0 + this.zoomPulse;
        
        // Calculate dimensions to maintain aspect ratio
        const drawWidth = sprite.width * scale;
        const drawHeight = sprite.height * scale;
        
        if (shouldFlip) {
            ctx.scale(-1, 1); // Flip horizontally
        }
        
        ctx.drawImage(
            charImg,
            sprite.x, sprite.y, sprite.width, sprite.height,
            Math.floor(-drawWidth / 2), Math.floor(-drawHeight / 2),
            Math.floor(drawWidth), Math.floor(drawHeight)
        );
        
        ctx.restore();
        
        // Draw character name above the sprite
        ctx.save();
        ctx.imageSmoothingEnabled = false;
        
        const nameY = Math.floor(previewY - drawHeight / 2 - 20); // Position above the character, floored
        const nameScale = 1; // Use integer scale for pixel-perfect rendering
        
        // Use null for original blue font color
        this.fontRenderer.drawText(
            ctx,
            selectedChar.name,
            Math.floor(previewX), // Floor the X position too
            nameY,
            nameScale,
            true, // centered
            null // Use original blue color
        );
        
        ctx.restore();
    }

    drawCharacterGrid(ctx, canvas) {
        // Disable image smoothing for crisp pixels
        ctx.imageSmoothingEnabled = false;
        
        this.gridLayout.forEach((card, index) => {
            const isSelected = index === this.selectedIndex;
            
            ctx.save();
            
            // Smooth opacity transition for non-selected cards
            const targetOpacity = isSelected ? 1.0 : 0.7;
            if (!card.currentOpacity) card.currentOpacity = targetOpacity;
            card.currentOpacity += (targetOpacity - card.currentOpacity) * 0.1;
            
            ctx.globalAlpha = card.currentOpacity;
            ctx.translate(card.x + card.width / 2, card.y + card.height / 2);
            
            // Add subtle bounce to selected card
            if (isSelected) {
                ctx.translate(0, Math.sin(this.bounceTimer) * 3);
            }
            
            // Glass-morphism background (matching main menu style)
            ctx.fillStyle = isSelected ? 'rgba(255, 255, 255, 0.3)' : 'rgba(255, 255, 255, 0.15)';
            ctx.fillRect(
                Math.floor(-card.width / 2), 
                Math.floor(-card.height / 2), 
                card.width, 
                card.height
            );
            
            // Border (brighter for selected)
            ctx.strokeStyle = isSelected ? 'rgba(255, 215, 0, 0.6)' : 'rgba(255, 255, 255, 0.25)';
            ctx.lineWidth = isSelected ? 2 : 1;
            ctx.strokeRect(
                Math.floor(-card.width / 2) + 0.5, 
                Math.floor(-card.height / 2) + 0.5, 
                card.width - 1, 
                card.height - 1
            );
            
            // Character sprite using lod2 frame 6 (side view)
            const charImg = this.characterImages.get(card.character.id);
            if (charImg && charImg.complete) {
                const sprite = card.character.sprites.lod2[6];
                
                // Use original sprite dimensions - no scaling - pixel-perfect positioning
                ctx.drawImage(
                    charImg,
                    sprite.x, sprite.y, sprite.width, sprite.height,
                    Math.floor(-sprite.width / 2),
                    Math.floor(-sprite.height / 2 - 2),
                    sprite.width,
                    sprite.height
                );
            }
            
            ctx.restore();
        });
    }

    roundRect(ctx, x, y, width, height, radius) {
        ctx.beginPath();
        ctx.moveTo(x + radius, y);
        ctx.lineTo(x + width - radius, y);
        ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
        ctx.lineTo(x + width, y + height - radius);
        ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
        ctx.lineTo(x + radius, y + height);
        ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
        ctx.lineTo(x, y + radius);
        ctx.quadraticCurveTo(x, y, x + radius, y);
        ctx.closePath();
    }

    drawTransition(ctx, canvas) {
        ctx.save();
        
        // Start with content visible, then cover it with the navy circle growing from center
        const maxRadius = Math.sqrt(canvas.width ** 2 + canvas.height ** 2);
        const currentRadius = maxRadius * this.transitionProgress; // Growing circle
        
        // Draw navy circle that grows to cover the screen
        ctx.fillStyle = '#000030';
        ctx.beginPath();
        ctx.arc(canvas.width / 2, canvas.height / 2, currentRadius, 0, Math.PI * 2);
        ctx.fill();
        
        ctx.restore();
    }

    exit() {
        console.log('Exited Character Selection Screen');
    }
}
