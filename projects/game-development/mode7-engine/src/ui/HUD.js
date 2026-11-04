import { NUMBER_SPRITES } from '../data/NumberSprites.js';


export class HUD {
    constructor() {
        this.elements = [];
        
        // Create a single reusable temporary canvas for color overlays
        // This prevents memory leaks from creating new canvases every frame
        this.tempCanvas = document.createElement('canvas');
        this.tempCtx = this.tempCanvas.getContext('2d');
    }

    addNumber(id, x, y, text, numberSprite, style = {}) {
        this.elements.push({
            id,
            type: 'number',
            x,
            y,
            text,
            numberSprite,
            style: {
                scale: style.scale || 1,
                spacing: style.spacing || 0,
                align: style.align || 'left',
                ...style
            },
            visible: true,
            animation: {
                offsetX: 0,
                offsetY: 0,
                rotation: 0,
                scale: 1
            }
        });
    }

    addSprite(id, x, y, sprite, image, style = {}) {
        this.elements.push({
            id,
            type: 'sprite',
            x,
            y,
            sprite,
            image,
            style: {
                scale: style.scale || 1,
                ...style
            },
            visible: true,
            animation: {
                offsetX: 0,
                offsetY: 0,
                rotation: 0,
                scale: 1
            }
        });
    }

    updateElement(id, updates) {
        const element = this.elements.find(el => el.id === id);
        if (element) {
            Object.assign(element, updates);
        }
    }

    animateElement(id, property, value) {
        const element = this.elements.find(el => el.id === id);
        if (element && element.animation) {
            element.animation[property] = value;
        }
    }

    update(deltaTime) {
        
    }

    draw(ctx) {
        this.elements.forEach(element => {
            if (!element.visible) return;

            if (element.type === 'number') {
                this.drawNumber(ctx, element);
            }

            if (element.type === 'sprite') {
                this.drawSprite(ctx, element);
            }
        });
    }

    drawNumber(ctx, element) {
        const text = String(element.text);
        const chars = text.split('');

        let totalWidth = 0;
        chars.forEach(char => {
            const sprite = NUMBER_SPRITES.chars[char];
            if (sprite) {
                totalWidth += sprite.width * element.style.scale + element.style.spacing;
            }
        });

        let startX = element.x;
        if (element.style.align === 'center') {
            startX -= totalWidth / 2;
        } else if (element.style.align === 'right') {
            startX = element.x - totalWidth;
        }

        startX = Math.floor(startX);

        let currentX = startX;
        chars.forEach(char => {
            if (char === ' ') {
                currentX += 5;
                return;
            }
            const sprite = NUMBER_SPRITES.chars[char];
            if (sprite) {
                element.numberSprite.draw(
                    ctx,
                    sprite.x, sprite.y, sprite.width, sprite.height,
                    Math.floor(currentX), Math.floor(element.y),
                    Math.floor(sprite.width * element.style.scale),
                    Math.floor(sprite.height * element.style.scale)
                );
                currentX += sprite.width * element.style.scale + element.style.spacing;
            }
        });
    }

    drawSprite(ctx, element) {
        ctx.save();

        const animX = Math.floor(element.x + element.animation.offsetX);
        const animY = Math.floor(element.y + element.animation.offsetY);
        const animScale = element.style.scale * element.animation.scale;

        ctx.translate(animX, animY);
        ctx.rotate(element.animation.rotation);
        
        const drawX = Math.floor(-element.sprite.width * animScale / 2);
        const drawY = Math.floor(-element.sprite.height * animScale / 2);
        const drawW = Math.floor(element.sprite.width * animScale);
        const drawH = Math.floor(element.sprite.height * animScale);
        
        // If we have a color overlay, we need to use a temporary canvas
        if (element.colorOverlay) {
            // Reuse the temporary canvas (resize if needed)
            if (this.tempCanvas.width !== element.sprite.width || 
                this.tempCanvas.height !== element.sprite.height) {
                this.tempCanvas.width = element.sprite.width;
                this.tempCanvas.height = element.sprite.height;
            }
            
            // Clear the temp canvas
            this.tempCtx.clearRect(0, 0, element.sprite.width, element.sprite.height);
            
            // Draw sprite to temp canvas
            element.image.draw(
                this.tempCtx,
                element.sprite.x, element.sprite.y,
                element.sprite.width, element.sprite.height,
                0, 0,
                element.sprite.width, element.sprite.height
            );
            
            // Apply color overlay using multiply blend
            this.tempCtx.globalCompositeOperation = 'source-atop';
            this.tempCtx.fillStyle = element.colorOverlay;
            this.tempCtx.fillRect(0, 0, element.sprite.width, element.sprite.height);
            
            // Reset composite operation for next use
            this.tempCtx.globalCompositeOperation = 'source-over';
            
            // Draw the colored result
            ctx.drawImage(this.tempCanvas, drawX, drawY, drawW, drawH);
        } else {
            // Draw normally without color overlay
            element.image.draw(
                ctx,
                element.sprite.x, element.sprite.y,
                element.sprite.width, element.sprite.height,
                drawX, drawY, drawW, drawH
            );
        }

        ctx.restore();
    }
}