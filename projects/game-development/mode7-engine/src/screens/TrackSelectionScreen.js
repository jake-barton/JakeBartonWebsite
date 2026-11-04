import { Settings } from '../core/Settings.js';
import { FontRenderer } from '../utils/FontRenderer.js';

export class TrackSelectionScreen {
    constructor(screenManager) {
        this.screenManager = screenManager;
        this.fontRenderer = new FontRenderer();
        
        // Four tracks to display
        this.tracks = [
            { name: 'MARIO CIRCUIT', key: 'MarioCircuit', preview: 'assets/tracks/MarioCircuit/track.png', tiles: 'assets/tracks/MarioCircuit/mariocircuit_tiles.png' },
            { name: 'MARIO CIRCUIT', key: 'MarioCircuit', preview: 'assets/tracks/MarioCircuit/track.png', tiles: 'assets/tracks/MarioCircuit/mariocircuit_tiles.png' },
            { name: 'MARIO CIRCUIT', key: 'MarioCircuit', preview: 'assets/tracks/MarioCircuit/track.png', tiles: 'assets/tracks/MarioCircuit/mariocircuit_tiles.png' },
            { name: 'MARIO CIRCUIT', key: 'MarioCircuit', preview: 'assets/tracks/MarioCircuit/track.png', tiles: 'assets/tracks/MarioCircuit/mariocircuit_tiles.png' }
        ];
        
        // Selected track index
        this.selectedIndex = 0;
        
        // Load assets for all tracks
        this.trackPreviews = [];
        this.grassTileData = [];
        this.mode7PreviewCanvases = [];
        
        this.tracks.forEach((track, index) => {
            // Load track image
            const trackImg = new Image();
            trackImg.src = track.preview;
            trackImg.onload = () => {
                this.generateMode7Preview(index);
            };
            this.trackPreviews[index] = trackImg;
            
            // Load grass tile
            const tilesImg = new Image();
            tilesImg.src = track.tiles;
            tilesImg.onload = () => {
                const tileCanvas = document.createElement('canvas');
                tileCanvas.width = 8;
                tileCanvas.height = 8;
                const tileCtx = tileCanvas.getContext('2d');
                tileCtx.drawImage(tilesImg, 0, 0, 8, 8, 0, 0, 8, 8);
                this.grassTileData[index] = tileCtx.getImageData(0, 0, 8, 8);
                
                // Regenerate preview with grass texture
                if (this.trackPreviews[index].complete) {
                    this.generateMode7Preview(index);
                }
            };
            
            this.mode7PreviewCanvases[index] = null;
        });
        
        // Load scrolling background
        this.scrollBg = new Image();
        this.scrollBg.src = 'MARIO KART SNES/images/scroll.png';
        
        // Animation
        this.scrollOffset = 0;
        this.scrollSpeed = 20;
        this.cardBounce = 0;
        
        // Input tracking
        this.pressedKeys = {};
    }

    enter(gameData) {
        this.scrollOffset = 0;
        this.cardBounce = 0;
        
        // Generate mode-7 previews if not already done
        this.tracks.forEach((track, index) => {
            if (!this.mode7PreviewCanvases[index] && this.trackPreviews[index].complete && this.grassTileData[index]) {
                this.generateMode7Preview(index);
            }
        });
    }
    
    worldToPerspective(worldX, worldY, trackWidth, trackHeight) {
        const normX = worldX / trackWidth;
        const normY = worldY / trackHeight;

        const perspectiveFactor = 0.7 + (normY * 0.3);
        const perspX = (normX - 0.5) * perspectiveFactor + 0.5;
        const perspY = (normY * 0.95);

        return {
            x: perspX * trackWidth,
            y: perspY * trackHeight,
            scale: perspectiveFactor
        }
    }
    
    getTrackPixel(x, y, trackImageData, grassTileData, trackWidth, trackHeight) {
        if (x < 0 || x >= trackWidth || y < 0 || y >= trackHeight) {
            // Use grass tile texture
            if (!grassTileData) {
                return { r: 0, g: 128, b: 0 }; // Fallback green
            }
            
            const tileX = ((x % 8) + 8) % 8;
            const tileY = ((y % 8) + 8) % 8;
            const index = (Math.floor(tileY) * 8 + Math.floor(tileX)) * 4;

            return {
                r: grassTileData.data[index],
                g: grassTileData.data[index + 1],
                b: grassTileData.data[index + 2]
            };
        }
        
        const index = (Math.floor(y) * trackWidth + Math.floor(x)) * 4;
        return {
            r: trackImageData.data[index],
            g: trackImageData.data[index + 1],
            b: trackImageData.data[index + 2]
        };
    }
    
    generateMode7Preview(trackIndex) {
        const trackPreview = this.trackPreviews[trackIndex];
        const grassTileData = this.grassTileData[trackIndex];
        
        if (!trackPreview || !trackPreview.complete) return;
        
        // Tiny preview to fit in small cards
        const previewWidth = 50;
        const previewHeight = 50;
        const canvas = document.createElement('canvas');
        canvas.width = previewWidth;
        canvas.height = previewHeight;
        const ctx = canvas.getContext('2d');
        ctx.imageSmoothingEnabled = false;
        
        // Load track image data
        const tempCanvas = document.createElement('canvas');
        tempCanvas.width = trackPreview.width;
        tempCanvas.height = trackPreview.height;
        const tempCtx = tempCanvas.getContext('2d');
        tempCtx.imageSmoothingEnabled = false;
        tempCtx.drawImage(trackPreview, 0, 0);
        const trackImageData = tempCtx.getImageData(0, 0, trackPreview.width, trackPreview.height);
        
        const trackWidth = trackPreview.width;
        const trackHeight = trackPreview.height;
        const mapWidth = previewWidth;
        const mapHeight = previewHeight;
        
        // Draw grass background using same algorithm as minimap
        if (grassTileData) {
            const grassBgSampleRate = 1;
            for (let y = 0; y < mapHeight; y += grassBgSampleRate) {
                for (let x = 0; x < mapWidth; x += grassBgSampleRate) {
                    const grassX = -100 + (x % 8);
                    const grassY = -100 + (y % 8);

                    const grassColor = this.getTrackPixel(grassX, grassY, trackImageData, grassTileData, trackWidth, trackHeight);
                    ctx.fillStyle = `rgb(${grassColor.r}, ${grassColor.g}, ${grassColor.b})`;
                    ctx.fillRect(x, y, grassBgSampleRate, grassBgSampleRate);
                }
            }
        } else {
            // Fallback if grass not loaded yet
            ctx.fillStyle = '#00AA00';
            ctx.fillRect(0, 0, mapWidth, mapHeight);
        }
        
        // Draw track with perspective transformation - EXACTLY like minimap
        const sampleRate = 5;
        for (let ty = 0; ty < trackHeight; ty += sampleRate) {
            for (let tx = 0; tx < trackWidth; tx += sampleRate) {
                const color = this.getTrackPixel(tx, ty, trackImageData, grassTileData, trackWidth, trackHeight);
                
                const persp = this.worldToPerspective(tx + 100, ty + 100, trackWidth + 200, trackHeight + 200);

                const screenX = (persp.x / (trackWidth + 200)) * mapWidth;
                const screenY = (persp.y / (trackHeight + 200)) * mapHeight;

                const rectSize = Math.max(1, Math.ceil(sampleRate * persp.scale * 0.8));

                const rectX = Math.floor(screenX);
                const rectY = Math.floor(screenY);

                // Bounds check to prevent glitches
                if (rectX >= 0 && rectX < mapWidth && rectY >= 0 && rectY < mapHeight) {
                    ctx.fillStyle = `rgb(${color.r}, ${color.g}, ${color.b})`;
                    ctx.fillRect(rectX, rectY, rectSize, rectSize);
                }
            }
        }
        
        this.mode7PreviewCanvases[trackIndex] = canvas;
    }

    update(keys, deltaTime) {
        // Update scroll
        this.scrollOffset -= this.scrollSpeed * deltaTime;
        
        // Update card bounce
        this.cardBounce += deltaTime * 4;
        
        // Input handling - left/right to select track
        if (keys['ArrowLeft'] && !this.pressedKeys['left']) {
            this.pressedKeys['left'] = true;
            this.selectedIndex = (this.selectedIndex - 1 + this.tracks.length) % this.tracks.length;
        }
        if (!keys['ArrowLeft']) this.pressedKeys['left'] = false;
        
        if (keys['ArrowRight'] && !this.pressedKeys['right']) {
            this.pressedKeys['right'] = true;
            this.selectedIndex = (this.selectedIndex + 1) % this.tracks.length;
        }
        if (!keys['ArrowRight']) this.pressedKeys['right'] = false;
        
        // Input handling - enter to start race
        if (keys['Enter'] && !this.pressedKeys['enter']) {
            this.pressedKeys['enter'] = true;
            this.screenManager.setGameData('track', this.tracks[this.selectedIndex].key);
            
            import('./RaceScreen.js').then((module) => {
                this.screenManager.setScreen(new module.RaceScreen(this.screenManager));
            });
        }
        if (!keys['Enter']) this.pressedKeys['enter'] = false;
        
        if (keys['Escape'] && !this.pressedKeys['escape']) {
            this.pressedKeys['escape'] = true;
            import('./CharacterSelectionScreen.js').then((module) => {
                this.screenManager.setScreen(new module.CharacterSelectionScreen(this.screenManager));
            });
        }
        if (!keys['Escape']) this.pressedKeys['escape'] = false;
    }

    draw(ctx) {
        const canvas = this.screenManager.canvas;
        
        // Draw scrolling background
        this.drawScrollingBackground(ctx, canvas);
        
        // Draw four track cards in bottom half
        this.drawTrackCards(ctx, canvas);
    }

    drawScrollingBackground(ctx, canvas) {
        if (!this.scrollBg.complete) {
            ctx.fillStyle = '#1a0a2e';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            return;
        }
        
        const bgWidth = this.scrollBg.width;
        const bgHeight = this.scrollBg.height;
        const scale = canvas.height / bgHeight;
        const scaledWidth = bgWidth * scale;
        
        const offsetX = this.scrollOffset % scaledWidth;
        
        for (let x = offsetX; x < canvas.width; x += scaledWidth) {
            ctx.drawImage(this.scrollBg, x, 0, scaledWidth, canvas.height);
        }
        for (let x = offsetX - scaledWidth; x < canvas.width && x + scaledWidth >= 0; x += scaledWidth) {
            ctx.drawImage(this.scrollBg, x, 0, scaledWidth, canvas.height);
        }
    }

    drawTrackCards(ctx, canvas) {
        // Card dimensions - MUCH smaller to fit 255px wide canvas
        const cardWidth = 55;
        const cardHeight = 70;
        const cardSpacing = 8;
        const totalWidth = (cardWidth * 4) + (cardSpacing * 3);
        
        // Start X position to center all 4 cards
        const startX = (canvas.width - totalWidth) / 2;
        const cardY = canvas.height - cardHeight - 30; // Room for text below
        
        // Subtle bounce
        const bounceOffset = Math.sin(this.cardBounce) * 2;
        
        // Draw all 4 cards
        for (let i = 0; i < this.tracks.length; i++) {
            const cardX = startX + (i * (cardWidth + cardSpacing));
            const finalY = cardY + bounceOffset;
            const isSelected = (i === this.selectedIndex);
            
            // Card background
            ctx.fillStyle = isSelected ? 'rgba(255, 255, 255, 0.3)' : 'rgba(255, 255, 255, 0.2)';
            ctx.fillRect(cardX, finalY, cardWidth, cardHeight);
            
            // Border - golden if selected, white if not
            ctx.strokeStyle = isSelected ? 'rgba(255, 215, 0, 0.9)' : 'rgba(255, 255, 255, 0.5)';
            ctx.lineWidth = isSelected ? 2 : 1;
            ctx.strokeRect(cardX, finalY, cardWidth, cardHeight);
            
            // Draw mode-7 track preview - tiny to fit
            const previewWidth = 50;
            const previewHeight = 50;
            const previewX = cardX + (cardWidth - previewWidth) / 2;
            const previewY = finalY + 3;
            
            // Preview border
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.3)';
            ctx.lineWidth = 1;
            ctx.strokeRect(previewX, previewY, previewWidth, previewHeight);
            
            // Draw mode-7 preview
            if (this.mode7PreviewCanvases[i]) {
                ctx.save();
                ctx.imageSmoothingEnabled = false;
                ctx.drawImage(this.mode7PreviewCanvases[i], previewX, previewY, previewWidth, previewHeight);
                ctx.restore();
            } else {
                // Loading placeholder
                ctx.fillStyle = 'rgba(0, 0, 0, 0.6)';
                ctx.fillRect(previewX, previewY, previewWidth, previewHeight);
            }
        }
        
        // Draw selected track name centered below all cards
        const selectedTrack = this.tracks[this.selectedIndex];
        const textY = cardY + bounceOffset + cardHeight + 10;
        const textX = canvas.width / 2; // Center X position
        this.fontRenderer.drawText(ctx, selectedTrack.name, textX, textY, 1, true, '#FFD700');
    }

    drawInstructions(ctx, canvas) {
        // Instructions removed per user request
    }

    exit() {
    }
}
