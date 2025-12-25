import { FONT_SPRITES } from '../data/font_sprites.js';
import { Sprite } from './Sprite.js';

export class FontRenderer {
    constructor() {
        this.fontSprite = new Sprite('assets/sprites/ui/font.png');
        this.sprites = FONT_SPRITES;
        
        // Create a reusable canvas for processing characters
        this.tempCanvas = document.createElement('canvas');
        this.tempCanvas.width = 64; // Max character width
        this.tempCanvas.height = 64; // Max character height
        this.tempCtx = this.tempCanvas.getContext('2d', { willReadFrequently: true });
        
        // Cache for processed characters (no black pixels)
        this.processedCharCache = new Map();
        
        // Cache for colored characters (key: "char_color_scale")
        this.coloredCharCache = new Map();
        
        // Reusable canvas for color tinting
        this.tintCanvas = document.createElement('canvas');
        this.tintCanvas.width = 64;
        this.tintCanvas.height = 64;
        this.tintCtx = this.tintCanvas.getContext('2d');
    }

    /**
     * Draw text using sprite font
     * @param {CanvasRenderingContext2D} ctx - Canvas context
     * @param {string} text - Text to draw
     * @param {number} x - X position (center if centered)
     * @param {number} y - Y position
     * @param {number} scale - Scale factor (default 1)
     * @param {boolean} centered - Center the text (default false)
     * @param {string} color - Optional color tint (default null)
     */
    drawText(ctx, text, x, y, scale = 1, centered = false, color = null) {
        if (!this.fontSprite.image.complete) {
            return; // Font not loaded yet
        }

        const chars = text.toUpperCase().split('');
        const spacing = Math.floor(2 * scale); // Floor spacing for pixel-perfect
        
        // Calculate total width if centering
        let totalWidth = 0;
        if (centered) {
            chars.forEach(char => {
                const sprite = this.getCharSprite(char);
                if (sprite) {
                    totalWidth += Math.floor(sprite.width * scale) + spacing;
                }
            });
            totalWidth -= spacing; // Remove last spacing
        }

        let currentX = Math.floor(centered ? x - totalWidth / 2 : x);
        
        ctx.save();
        ctx.imageSmoothingEnabled = false;

        chars.forEach(char => {
            const sprite = this.getCharSprite(char);
            if (sprite) {
                let charCanvas;
                
                // If color is specified, check colored cache first
                if (color) {
                    const cacheKey = `${char}_${color}`;  // Remove scale from cache key
                    charCanvas = this.coloredCharCache.get(cacheKey);
                    
                    if (!charCanvas) {
                        // Get the base processed character
                        let processedCanvas = this.processedCharCache.get(char);
                        
                        if (!processedCanvas) {
                            // Create and cache the processed character
                            processedCanvas = document.createElement('canvas');
                            processedCanvas.width = sprite.width;
                            processedCanvas.height = sprite.height;
                            const processedCtx = processedCanvas.getContext('2d', { willReadFrequently: true });
                            
                            // Draw sprite to canvas
                            processedCtx.drawImage(
                                this.fontSprite.image,
                                sprite.x, sprite.y, sprite.width, sprite.height,
                                0, 0, sprite.width, sprite.height
                            );
                            
                            // Get pixel data and make black pixels transparent
                            const imageData = processedCtx.getImageData(0, 0, sprite.width, sprite.height);
                            const data = imageData.data;
                            
                            for (let i = 0; i < data.length; i += 4) {
                                // Check if pixel is black (or very close to black)
                                if (data[i] < 5 && data[i + 1] < 5 && data[i + 2] < 5) {
                                    data[i + 3] = 0; // Make it transparent
                                }
                            }
                            
                            processedCtx.putImageData(imageData, 0, 0);
                            this.processedCharCache.set(char, processedCanvas);
                        }
                        
                        // Create colored version at ORIGINAL size
                        charCanvas = document.createElement('canvas');
                        charCanvas.width = sprite.width;
                        charCanvas.height = sprite.height;
                        const colorCtx = charCanvas.getContext('2d', { willReadFrequently: true });
                        colorCtx.imageSmoothingEnabled = false;
                        
                        // Draw the processed character at original size
                        colorCtx.drawImage(
                            processedCanvas,
                            0, 0, sprite.width, sprite.height,
                            0, 0, sprite.width, sprite.height
                        );
                        
                        // Replace specific blue color (#0068f8 / rgb(0, 104, 248)) with the target color
                        const imageData = colorCtx.getImageData(0, 0, sprite.width, sprite.height);
                        const data = imageData.data;
                        
                        // Parse target color (hex to RGB)
                        const targetR = parseInt(color.slice(1, 3), 16);
                        const targetG = parseInt(color.slice(3, 5), 16);
                        const targetB = parseInt(color.slice(5, 7), 16);
                        
                        for (let i = 0; i < data.length; i += 4) {
                            const r = data[i];
                            const g = data[i + 1];
                            const b = data[i + 2];
                            
                            // Check if pixel is the blue color (#0068f8 = rgb(0, 104, 248))
                            // Allow slight tolerance for compression artifacts
                            if (Math.abs(r - 0) < 10 && Math.abs(g - 104) < 10 && Math.abs(b - 248) < 10) {
                                data[i] = targetR;
                                data[i + 1] = targetG;
                                data[i + 2] = targetB;
                                // Keep alpha as-is
                            }
                        }
                        
                        colorCtx.putImageData(imageData, 0, 0);
                        
                        // Cache the colored character
                        this.coloredCharCache.set(cacheKey, charCanvas);
                    }
                    
                    // Draw the cached colored character with scaling applied here
                    ctx.drawImage(
                        charCanvas,
                        0, 0, sprite.width, sprite.height,
                        Math.floor(currentX), Math.floor(y),
                        Math.floor(sprite.width * scale), Math.floor(sprite.height * scale)
                    );
                } else {
                    // No color - use base processed character
                    let processedCanvas = this.processedCharCache.get(char);
                    
                    if (!processedCanvas) {
                        // Create and cache the processed character
                        processedCanvas = document.createElement('canvas');
                        processedCanvas.width = sprite.width;
                        processedCanvas.height = sprite.height;
                        const processedCtx = processedCanvas.getContext('2d', { willReadFrequently: true });
                        
                        // Draw sprite to canvas
                        processedCtx.drawImage(
                            this.fontSprite.image,
                            sprite.x, sprite.y, sprite.width, sprite.height,
                            0, 0, sprite.width, sprite.height
                        );
                        
                        // Get pixel data and make black pixels transparent
                        const imageData = processedCtx.getImageData(0, 0, sprite.width, sprite.height);
                        const data = imageData.data;
                        
                        for (let i = 0; i < data.length; i += 4) {
                            // Check if pixel is black (or very close to black)
                            if (data[i] < 5 && data[i + 1] < 5 && data[i + 2] < 5) {
                                data[i + 3] = 0; // Make it transparent
                            }
                        }
                        
                        processedCtx.putImageData(imageData, 0, 0);
                        this.processedCharCache.set(char, processedCanvas);
                    }
                    
                    // Draw the processed character
                    ctx.drawImage(
                        processedCanvas,
                        0, 0, sprite.width, sprite.height,
                        Math.floor(currentX), Math.floor(y),
                        Math.floor(sprite.width * scale), Math.floor(sprite.height * scale)
                    );
                }
                
                currentX += Math.floor(sprite.width * scale) + spacing;
            }
        });

        ctx.restore();
    }

    /**
     * Get sprite data for a character
     */
    getCharSprite(char) {
        if (char === ' ') return this.sprites.space;
        if (char === '.') return this.sprites.period;
        
        // Handle numbers
        const numberMap = {
            '1': 'one', '2': 'two', '3': 'three', '4': 'four', '5': 'five',
            '6': 'six', '7': 'seven', '8': 'eight', '9': 'nine', '0': 'zero'
        };
        
        if (numberMap[char]) {
            return this.sprites[numberMap[char]];
        }
        
        // Handle letters
        return this.sprites[char];
    }

    /**
     * Calculate text width
     */
    measureText(text, scale = 1) {
        const chars = text.toUpperCase().split('');
        const spacing = 2 * scale;
        let totalWidth = 0;
        
        chars.forEach(char => {
            const sprite = this.getCharSprite(char);
            if (sprite) {
                totalWidth += sprite.width * scale + spacing;
            }
        });
        
        return totalWidth - spacing;
    }
}
