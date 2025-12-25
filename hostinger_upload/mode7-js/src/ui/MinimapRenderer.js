import { Settings } from "../core/Settings.js";
import { MARIO_SPRITES } from "../data/mario_sprites.js";
import { LUIGI_SPRITES } from "../data/luigi_sprites.js";
import { Sprite } from "../utils/Sprite.js";

export class MinimapRenderer {
    constructor(renderer, mapWidth, mapHeight) {
        this.renderer = renderer;
        this.mapWidth = mapWidth;
        this.mapHeight = mapHeight;

        this.minimapCanvas = null;
        this.minimapCtx = null;
        
        // Cache for character sprites on minimap
        this.characterSpriteCache = new Map();
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

    generateCache() {
        const trackWidth = this.renderer.trackWidth;
        const trackHeight = this.renderer.trackHeight;
        const mapWidth = this.mapWidth;
        const mapHeight = this.mapHeight;

        this.minimapCanvas = document.createElement('canvas');
        this.minimapCanvas.width = mapWidth;
        this.minimapCanvas.height = mapHeight;
        this.minimapCtx = this.minimapCanvas.getContext('2d');

        const grassBgSampleRate = 1;
        for (let y = 0; y < mapHeight; y += grassBgSampleRate) {
            for (let x = 0; x < mapWidth; x += grassBgSampleRate) {
                const grassX = -100 + (x % 8);
                const grassY = -100 + (y % 8);

                const grassColor = this.renderer.getTrackPixel(grassX, grassY);
                this.minimapCtx.fillStyle = `rgb(${grassColor.r}, ${grassColor.g}, ${grassColor.b})`;
                this.minimapCtx.fillRect(x, y, grassBgSampleRate, grassBgSampleRate);
            }
        }

        const sampleRate = 4;
        for (let ty = 0; ty < trackHeight; ty+= sampleRate) {
            for (let tx = 0; tx < trackWidth; tx+= sampleRate) {
                const color = this.renderer.getTrackPixel(tx, ty);
                
                const persp = this.worldToPerspective(tx + 100, ty + 100, trackWidth + 200, trackHeight + 200);

                const screenX = (persp.x / (trackWidth + 200)) * mapWidth;
                const screenY = (persp.y / (trackHeight + 200)) * mapHeight;

                const rectSize = Math.ceil(sampleRate * persp.scale * 1.0);

                const rectX = Math.floor(screenX);
                const rectY = Math.floor(screenY);

                this.minimapCtx.fillStyle = `rgb(${color.r}, ${color.g}, ${color.b})`;
                this.minimapCtx.fillRect(rectX, rectY, rectSize, rectSize);
            }
        }
    }

    draw(ctx, player, hudHeight, viewportHeight, dividerHeight, aiRacers = [], waypoints = []) {
        const mapX = 0;
        const mapY = hudHeight + viewportHeight + dividerHeight;

        if (this.minimapCanvas) {
            ctx.drawImage(this.minimapCanvas, mapX, mapY);
        }

        const trackWidth = this.renderer.trackWidth;
        const trackHeight = this.renderer.trackHeight;

        // Draw AI racers
        for (const ai of aiRacers) {
            this.drawRacerOnMinimap(ctx, ai, mapX, mapY, trackWidth, trackHeight, 'normal');
        }

        // Draw player
        this.drawRacerOnMinimap(ctx, player, mapX, mapY, trackWidth, trackHeight, 'normal');
    }

    drawRacerOnMinimap(ctx, racer, mapX, mapY, trackWidth, trackHeight, colorTint = 'normal') {
        const persp = this.worldToPerspective(
            racer.x + 100,
            racer.y + 100,
            trackWidth + 200,
            trackHeight + 200
        )

        const racerMapX = mapX + (persp.x / (trackWidth + 200)) * this.mapWidth;
        const racerMapY = mapY + (persp.y / (trackHeight + 200)) * this.mapHeight;

        
        let normalizedAngle = (racer.angle % (Math.PI * 2) + (Math.PI * 2)) % (Math.PI * 2);
        let flipHorizontal = false;
        let workingAngle = normalizedAngle;

        // If hit, override with spin animation angle
        if (racer.isHit && racer.hitSpinRotation !== undefined) {
            const hitNormalizedAngle = ((racer.hitSpinRotation % (Math.PI * 2)) + (Math.PI * 2)) % (Math.PI * 2);
            workingAngle = hitNormalizedAngle;
            
            if (hitNormalizedAngle > Math.PI) {
                workingAngle = (Math.PI * 2) - hitNormalizedAngle;
                flipHorizontal = true;
            } else {
                flipHorizontal = false;
            }
        } else {
            if (normalizedAngle > Math.PI) {
                workingAngle = (Math.PI * 2) - normalizedAngle;
                flipHorizontal = true;
            }
        }

        const segmentSize = Math.PI / 8;
        let spriteIndex = Math.floor(workingAngle / segmentSize);
        spriteIndex = 7 - spriteIndex; 
        spriteIndex = Math.max(0, Math.min(spriteIndex, 7));

        // Use character-specific sprites if available, otherwise default to MARIO_SPRITES
        // Use cached reference to avoid property lookups every frame
        const characterSprites = racer.characterSprites || MARIO_SPRITES;
        
        // Safety check for valid LOD and sprite index
        if (!characterSprites.lod3 || !characterSprites.lod3[spriteIndex]) {
            return; // Skip rendering if sprite data is invalid
        }
        
        const sprite = characterSprites.lod3[spriteIndex];

        // Get character sprite image (load and cache if needed)
        // Use cached sprite reference for performance
        let characterSprite = racer.minimapCharacterSprite;
        
        if (!characterSprite) {
            if (racer.characterData && racer.characterData.imagePath) {
                const characterId = racer.characterData.id;
                if (!this.characterSpriteCache.has(characterId)) {
                    // Load and cache the character sprite
                    this.characterSpriteCache.set(
                        characterId,
                        new Sprite(racer.characterData.imagePath)
                    );
                }
                characterSprite = this.characterSpriteCache.get(characterId);
                // Cache it on the racer for next frame
                racer.minimapCharacterSprite = characterSprite;
            } else {
                // Default to Mario sprite from renderer
                characterSprite = this.renderer.marioSprite;
                racer.minimapCharacterSprite = characterSprite;
            }
        }

        ctx.save();
        
        
        if (colorTint === 'red') {
            ctx.globalCompositeOperation = 'source-over';
            ctx.filter = 'hue-rotate(180deg) saturate(1.5)';
        }
        
        if (flipHorizontal) {
            ctx.translate(racerMapX, racerMapY);
            ctx.scale(-1, 1);
            characterSprite.draw(
                ctx,
                sprite.x, sprite.y, sprite.width, sprite.height,
                -sprite.width / 2, -sprite.height / 1.4,
                sprite.width,
                sprite.height
            );
        } else {
            characterSprite.draw(
                ctx,
                sprite.x, sprite.y, sprite.width, sprite.height,
                racerMapX - sprite.width / 2, racerMapY - sprite.height / 1.4,
                sprite.width,
                sprite.height
            );
        }

        ctx.restore();
    }
}