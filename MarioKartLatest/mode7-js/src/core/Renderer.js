import { Settings } from './Settings.js';
import { Sprite } from '../utils/Sprite.js';
import { MARIO_SPRITES } from '../data/mario_sprites.js';
import { LUIGI_SPRITES } from '../data/luigi_sprites.js';
import { ITEM_SPRITES } from '../data/ItemSprites.js';
import { PARTICLE_SPRITES } from '../data/particle_sprites.js';

export class Renderer {
    constructor(canvas, trackData, trackWidth, trackHeight, grassTileData, backgroundImage, treesImage) {
        this.ctx = canvas.getContext('2d');

        this.ctx.imageSmoothingEnabled = false;
        this.ctx.mozImageSmoothingEnabled = false;
        this.ctx.webkitImageSmoothingEnabled = false;
        this.ctx.msImageSmoothingEnabled = false;

        this.width = canvas.width;
        this.height = canvas.height;

        this.trackData = trackData;
        this.trackWidth = trackWidth;
        this.trackHeight = trackHeight;
        this.grassTileData = grassTileData;
        this.backgroundImage = backgroundImage;
        this.treesImage = treesImage;

        this.currentBackgroundAngle = 0;
        this.currentTreesAngle = 0;

        this.marioSprite = new Sprite('assets/sprites/characters/mario.png');
        
        // Cache for character sprites (loaded on demand)
        this.characterSpriteCache = new Map();
        this.characterSpriteCache.set('mario', this.marioSprite); // Pre-cache Mario
        
        // Load item sprite for coin animation
        this.itemSprite = new Sprite('assets/sprites/objects/items.png');
        
        this.groundImageData = null;
        this.lastGroundHeight = 0;
    }

    render(camera) {
        if (!this.trackData) return;

        const horizon = Math.floor(this.height / 2 + camera.getPitch());

        this.drawSky(camera, horizon);
        this.drawGround(camera, horizon);
    }

    drawSky(camera, horizon) {
        const backgroundHeight = horizon;
        const treesHeight = Math.floor(horizon * 0.5);
        const treesStartY = horizon - treesHeight;

        this.currentBackgroundAngle = this.drawBackgroundLayer(
            this.backgroundImage,
            camera.getAngle(),
            Settings.rendering.backgroundSpeed,
            0,
            backgroundHeight,
            this.currentBackgroundAngle,
            Settings.rendering.backgroundEasing
        );
        this.currentTreesAngle = this.drawBackgroundLayer(
            this.treesImage,
            camera.getAngle(),
            Settings.rendering.treeSpeed,
            treesStartY,
            treesHeight,
            this.currentTreesAngle,
            Settings.rendering.treesEasing
        );
    }

    drawBackgroundLayer(image, cameraAngle, parallaxSpeed, startY, layerHeight, currentAngle, easingSpeed) {
        if (!image) {
            return;
        }

        const bgWidth = image.width;
        const bgHeight = image.height;

        const parallaxAngle = cameraAngle * parallaxSpeed;
        const targetAngle = parallaxAngle;
        const smoothedAngle = currentAngle + (targetAngle - currentAngle) * easingSpeed;

        const normalizedAngle = ((smoothedAngle % (Math.PI * 2)) + (Math.PI * 2)) % (Math.PI * 2);
        const bgX = Math.floor((normalizedAngle / (Math.PI * 2)) * bgWidth);

        const slice1Width = Math.min(bgWidth - bgX, this.width);

        this.ctx.drawImage(
            image,
            bgX, 0,
            slice1Width, bgHeight,
            0, startY,
            slice1Width, layerHeight
        );

        if (slice1Width < this.width) {
            const slice2Width = this.width - slice1Width;
            this.ctx.drawImage(
                image,
                0, 0,
                slice2Width, bgHeight,
                slice1Width, startY,
                slice2Width, layerHeight
            );
        }
        return smoothedAngle; 
    }

    drawGround(camera, horizon) {
        const groundHeight = this.height - horizon;
        
        
        if (!this.groundImageData || this.lastGroundHeight !== groundHeight) {
            this.groundImageData = this.ctx.createImageData(this.width, groundHeight);
            this.lastGroundHeight = groundHeight;
        }
        
        const screenData = this.groundImageData;
        
        // Pre-calculate trig values ONCE per frame instead of per pixel
        const cosAngle = Math.cos(camera.getAngle());
        const sinAngle = Math.sin(camera.getAngle());
        const cameraX = camera.getX();
        const cameraY = camera.getY();
        const cameraHeight = camera.getHeight();
        const halfWidth = this.width / 2;

        for (let y = horizon; y < this.height; y++) {
            
            const distance = (cameraHeight * this.height) / (y - horizon + 1);
            const scale = distance / this.height;
            
            // Pre-calculate values that don't change per row
            const distanceTimesSin = distance * sinAngle;
            const distanceTimesCos = distance * cosAngle;

            for (let x = 0; x < this.width; x++) {
                const screenOffsetX = (halfWidth - x) * scale;
                
                // Use pre-calculated trig values - MATCH ORIGINAL FORMULA EXACTLY
                const rotatedX = screenOffsetX * cosAngle + distanceTimesSin;
                const rotatedY = -screenOffsetX * sinAngle + distanceTimesCos;

                const trackX = Math.floor(cameraX + rotatedX);
                const trackY = Math.floor(cameraY + rotatedY);

                const color = this.getTrackPixel(trackX, trackY);
                
                const screenY = y - horizon;
                const screenIndex = (screenY * this.width + x) * 4;

                screenData.data[screenIndex] = color.r;
                screenData.data[screenIndex + 1] = color.g;
                screenData.data[screenIndex + 2] = color.b;
                screenData.data[screenIndex + 3] = 255;
    
            }
        }
        this.ctx.putImageData(screenData, 0, horizon);
    }

    render(camera) {
        if (!this.trackData) return;

        const horizon = Math.floor(this.height / 2 + camera.getPitch());

        this.drawSky(camera, horizon);
        this.drawGround(camera, horizon);

        this.drawMario(camera.player, camera);
    }

    getTrackPixel(x, y) {
            if (x < 0 || x >= this.trackWidth || y < 0 || y >= this.trackHeight) {
                if (!this.grassTileData) {
                    return {
                        r: Settings.rendering.grassColor.r,
                        g: Settings.rendering.grassColor.g,
                        b: Settings.rendering.grassColor.b,
                        a: 255
                    };
                }
                
            const tileX = ((x % 8) + 8) % 8;
            const tileY = ((y % 8) + 8) % 8;
            const index = (Math.floor(tileY) * 8 + Math.floor(tileX)) * 4;

            return {
                r: this.grassTileData.data[index],
                g: this.grassTileData.data[index + 1],
                b: this.grassTileData.data[index + 2],
                a: this.grassTileData.data[index + 3],
            };
        }

        const index = (Math.floor(y) * this.trackWidth + Math.floor(x)) * 4;

        return {
            r: this.trackData.data[index],
            g: this.trackData.data[index + 1],
            b: this.trackData.data[index + 2],
            a: this.trackData.data[index + 3],
        };
    }
    getSpriteIndex(player, camera) {
        let flipHorizontal = false;

        const spriteIndex = player.getAnimationFrame();

        if (player.getAnimationDirection() < 0) {
            flipHorizontal = true;
        }

        return { spriteIndex, flipHorizontal };
    }
    drawMario(player, camera, yOffset = 0) {
        let sprite;
        let flipHorizontal = false;

        // Get player's character sprites, default to Mario if not set
        const characterSprites = player.characterSprites || MARIO_SPRITES;
        
        // Get the appropriate character sprite, or default to Mario
        let characterSprite = player.characterSprite || this.marioSprite;
        
        // Fallback: if not cached, load and cache it
        if (!player.characterSprite && player.characterData && player.characterData.imagePath) {
            const characterId = player.characterData.id;
            if (!this.characterSpriteCache.has(characterId)) {
                this.characterSpriteCache.set(characterId, new Sprite(player.characterData.imagePath));
            }
            characterSprite = this.characterSpriteCache.get(characterId);
            player.characterSprite = characterSprite;
        }

        // If hit, show 360 spin animation based on hitSpinRotation
        if (player.isHit) {
            // Map spin rotation to sprite index (0-11 for full rotation)
            const normalizedAngle = ((player.hitSpinRotation % (Math.PI * 2)) + (Math.PI * 2)) % (Math.PI * 2);
            let workingAngle = normalizedAngle;
            
            if (normalizedAngle > Math.PI) {
                workingAngle = (Math.PI * 2) - normalizedAngle;
                flipHorizontal = false;
            } else {
                flipHorizontal = true;
            }
            
            const spriteIndex = Math.round((workingAngle / Math.PI) * 11);
            sprite = characterSprites.lod0[spriteIndex];
        }
        // If feather jump, show spin animation
        else if (player.featherJumpActive) {
            const normalizedAngle = ((player.featherSpinRotation % (Math.PI * 2)) + (Math.PI * 2)) % (Math.PI * 2);
            let workingAngle = normalizedAngle;
            
            if (normalizedAngle > Math.PI) {
                workingAngle = (Math.PI * 2) - normalizedAngle;
                flipHorizontal = false;
            } else {
                flipHorizontal = true;
            }
            
            const spriteIndex = Math.round((workingAngle / Math.PI) * 11);
            sprite = characterSprites.lod0[spriteIndex];
        }
        // Check if player is in victory mode
        else if (player.victoryMode) {
            if (player.celebrating) {
                // During celebration phase, flash between celebrate and facing camera sprite
                sprite = characterSprites.celebrate || characterSprites.lod0[11];
                flipHorizontal = false;
            } else {
                // During rotation phase, show sprite based on camera angle relative to player
                // We want to show the player from the camera's perspective
                // Reverse the calculation: player angle - camera angle
                const relativeAngle = player.angle - camera.angle;
                const normalizedAngle = ((relativeAngle % (Math.PI * 2)) + (Math.PI * 2)) % (Math.PI * 2);
                
                // Map angle to sprite index (0-11 for 12 rotation sprites)
                // The sprites cover 0° to 180°, then we flip for 180° to 360°
                let workingAngle = normalizedAngle;
                
                if (normalizedAngle > Math.PI) {
                    // Back side (180° to 360°) - mirror the angle
                    workingAngle = (Math.PI * 2) - normalizedAngle;
                    flipHorizontal = false;
                } else {
                    // Front side (0° to 180°)
                    flipHorizontal = true;
                }
                
                // Map the working angle (0 to π) to sprite index (0 to 11)
                const spriteIndex = Math.round((workingAngle / Math.PI) * 11);
                sprite = characterSprites.lod0[spriteIndex];
            }
        } else {
            const { spriteIndex, flipHorizontal: shouldFlip } = this.getSpriteIndex(player, camera);
            sprite = characterSprites.lod0[spriteIndex];
            flipHorizontal = shouldFlip;
        }

        const driftOffset = player.getDriftOffset();

        const jumpOffset = player.getJumpHeight();
        
        // Add drift wobble to horizontal position
        const wobbleOffset = player.driftWobbleOffset * 15; // Scale up for screen space

        // Apply lightning shrink effect - reduce scale by 50% when player is lightning victim
        const lightningScale = player.isLightningVictim ? 0.5 : 1.0;
        const finalScale = Settings.sprite.scale * lightningScale;

        const centerX = Math.floor(this.width / 2 - (sprite.width * finalScale) / 2 + driftOffset + wobbleOffset);
        const centerY = Math.floor(this.height - (sprite.height * finalScale) / 2 + Settings.sprite.verticalOffset - jumpOffset + yOffset);

        this.ctx.save();
        
        // Apply rainbow color filter for star power
        if (player.starPowerActive) {
            const rainbowColor = player.getRainbowColor();
            this.ctx.filter = `hue-rotate(${(player.starPowerTimer * 360 * 3) % 360}deg) saturate(2)`;
        }

        if (flipHorizontal) {
            this.ctx.translate(centerX + (sprite.width * finalScale) / 2, centerY + (sprite.height * finalScale) / 2);
            this.ctx.scale(-1, 1);

            characterSprite.draw(
                this.ctx,
                sprite.x, sprite.y, sprite.width, sprite.height,
                -(sprite.width * finalScale) / 2,
                -(sprite.height * finalScale) / 2,
                sprite.width * finalScale,
                sprite.height * finalScale
            );
        } else {
            characterSprite.draw(
                this.ctx,
                sprite.x, sprite.y, sprite.width, sprite.height,
                centerX, centerY,
                sprite.width * finalScale,
                sprite.height * finalScale
            );
        }

        this.ctx.restore();
        
        // Draw coin animation above player if active
        if (player.coinAnimationActive) {
            const coinBounceHeight = player.getCoinBounceHeight();
            const coinSpinRotation = player.getCoinSpinRotation();
            
            // Use coin sprite rotation based on spin angle
            // Map rotation to 3 frames: front (0°-60°, 300°-360°), 45° turn (60°-120°, 240°-300°), side (120°-240°)
            const normalizedRotation = ((coinSpinRotation % (Math.PI * 2)) + (Math.PI * 2)) % (Math.PI * 2);
            const rotationDegrees = (normalizedRotation * 180) / Math.PI;
            
            let coinFrame;
            if ((rotationDegrees >= 0 && rotationDegrees < 60) || rotationDegrees >= 300) {
                coinFrame = ITEM_SPRITES.coin_world[0]; // Front
            } else if ((rotationDegrees >= 60 && rotationDegrees < 120) || (rotationDegrees >= 240 && rotationDegrees < 300)) {
                coinFrame = ITEM_SPRITES.coin_world[1]; // 45° turn
            } else {
                coinFrame = ITEM_SPRITES.coin_world[2]; // Side
            }
            
            // Coin position: centered above player, moving up with bounce
            const coinScale = 1; // Smaller: 2x scale (reduced from 3x)
            const coinX = Math.floor(this.width / 2 - (coinFrame.width * coinScale) / 2);
            const coinY = Math.floor(this.height - 50 - coinBounceHeight - (coinFrame.height * coinScale) / 2);
            
            this.itemSprite.draw(
                this.ctx,
                coinFrame.x, coinFrame.y, coinFrame.width, coinFrame.height,
                coinX, coinY,
                coinFrame.width * coinScale,
                coinFrame.height * coinScale
            );
        }
    }

    // Draw player in screen space with position/scale calculated from world space
    // During intro, player appears at their actual world position on screen
    drawPlayerIntro(player, camera) {
        // Calculate where player should appear on screen based on actual world position
        // Offset player position forward (toward camera) by 20 units in world space
        const worldX = player.x - camera.getX();
        const worldY = player.y - camera.getY();
        
        // Calculate direction from camera to player
        const dirLength = Math.sqrt(worldX * worldX + worldY * worldY);
        if (dirLength === 0) return;
        
        // Move player 20 units closer to camera in world space
        const offsetWorldX = worldX - (worldX / dirLength) * 10;
        const offsetWorldY = worldY - (worldY / dirLength) * 10;

        const cos = Math.cos(camera.getAngle());
        const sin = Math.sin(camera.getAngle());
        
        // Project world position to screen coordinates using offset position
        let distance = offsetWorldX * sin + offsetWorldY * cos;
        let screenOffsetX = offsetWorldX * cos - offsetWorldY * sin;

        // Store original distance for blending calculations
        const originalDistance = distance;
        
        // Clamp distance to minimum value to prevent shrinking when camera is close/behind
        // This keeps the sprite at a reasonable size throughout the rotation
        const minDistance = 15; // Minimum distance for consistent size
        const blendStart = 25; // Start blending when distance drops below this
        
        if (distance < minDistance) {
            // When clamping distance, also reduce the horizontal offset proportionally
            // This keeps the sprite more centered instead of sliding to the edge
            const ratio = minDistance / Math.max(distance, 0.1);
            screenOffsetX = screenOffsetX / ratio;
            distance = minDistance;
        }

        const horizon = Math.floor(this.height / 2 + camera.getPitch());
        
        const scale = distance / this.height;
        
        // Keep player centered horizontally during intro - don't use screenOffsetX
        const screenX = Math.floor(this.width / 2);
        
        // Calculate Y position with smooth blending
        const calculatedY = Math.floor(horizon + (camera.getHeight() * this.height) / distance);
        const targetY = this.height * 0.87; // Target position when very close
        
        // Blend between calculated and target Y based on how much we had to clamp
        let screenY;
        if (originalDistance < blendStart) {
            // Blend from calculated to target as distance decreases
            const blendFactor = Math.max(0, Math.min(1, (blendStart - originalDistance) / (blendStart - minDistance)));
            screenY = Math.floor(calculatedY + (targetY - calculatedY) * blendFactor);
        } else {
            screenY = calculatedY;
        }

        // Get player's character sprites
        const characterSprites = player.characterSprites || MARIO_SPRITES;
        let characterSprite = player.characterSprite || this.marioSprite;
        
        if (!player.characterSprite && player.characterData && player.characterData.imagePath) {
            const characterId = player.characterData.id;
            if (!this.characterSpriteCache.has(characterId)) {
                this.characterSpriteCache.set(characterId, new Sprite(player.characterData.imagePath));
            }
            characterSprite = this.characterSpriteCache.get(characterId);
            player.characterSprite = characterSprite;
        }

        // Calculate angle and sprite
        const relativeAngle = player.angle - camera.getAngle();
        const normalizedAngle = ((relativeAngle % (Math.PI * 2)) + (Math.PI * 2)) % (Math.PI * 2);
        let flipHorizontal = false;
        let workingAngle = normalizedAngle;

        // If hit, override with spin animation angle
        if (player.isHit) {
            const hitNormalizedAngle = ((player.hitSpinRotation % (Math.PI * 2)) + (Math.PI * 2)) % (Math.PI * 2);
            workingAngle = hitNormalizedAngle;
            
            if (hitNormalizedAngle > Math.PI) {
                workingAngle = (Math.PI * 2) - hitNormalizedAngle;
                flipHorizontal = false;
            } else {
                flipHorizontal = true;
            }
        } else {
            if (normalizedAngle > Math.PI) {
                workingAngle = (Math.PI * 2) - normalizedAngle;
                flipHorizontal = false;
            } else {
                flipHorizontal = true;
            }
        }

        // Select LOD based on screen depth
        const screenDepth = screenY - horizon;
        const maxDepth = this.height - horizon;
        const depthRatio = Math.max(0, Math.min(1, screenDepth / maxDepth));

        let lodSprites;
        if (depthRatio < 0.05) {
            lodSprites = characterSprites.lod10;
        } else if (depthRatio < 0.10) {
            lodSprites = characterSprites.lod9;
        } else if (depthRatio < 0.15) {
            lodSprites = characterSprites.lod8;
        } else if (depthRatio < 0.20) {
            lodSprites = characterSprites.lod7;
        } else if (depthRatio < 0.25) {
            lodSprites = characterSprites.lod6;
        } else if (depthRatio < 0.30) {
            lodSprites = characterSprites.lod5;
        } else if (depthRatio < 0.35) {
            lodSprites = characterSprites.lod4;
        } else if (depthRatio < 0.40) {
            lodSprites = characterSprites.lod3;
        } else if (depthRatio < 0.50) {
            lodSprites = characterSprites.lod2;
        } else if (depthRatio < 0.65) {
            lodSprites = characterSprites.lod1;
        } else {
            lodSprites = characterSprites.lod0;
        }

        // Calculate sprite index
        let spriteIndex;
        const spriteCount = lodSprites.length;

        if (spriteCount === 12) {
            const segmentSize = Math.PI / 11;
            spriteIndex = Math.round(workingAngle / segmentSize);
            spriteIndex = Math.min(spriteIndex, 11);
        } else if (spriteCount === 8) {
            const segmentSize = Math.PI / 7;
            spriteIndex = Math.round(workingAngle / segmentSize);
            spriteIndex = Math.min(spriteIndex, 7);
        } else if (spriteCount === 4) {
            if (workingAngle < Math.PI * 0.25) spriteIndex = 0;
            else if (workingAngle < Math.PI * 0.60) spriteIndex = 1;
            else if (workingAngle < Math.PI * 0.85) spriteIndex = 2;
            else spriteIndex = 3;
        } else if (spriteCount === 3) {
            if (workingAngle < Math.PI * 0.33) spriteIndex = 0;
            else if (workingAngle < Math.PI * 0.67) spriteIndex = 1;
            else spriteIndex = 2;
        } else if (spriteCount === 2) {
            spriteIndex = workingAngle < Math.PI * 0.5 ? 0 : 1;
        } else {
            spriteIndex = 0;
        }

        const sprite = lodSprites[spriteIndex];
        const jumpOffset = player.getJumpHeight ? player.getJumpHeight() : 0;

        // Scale sprite using Settings.sprite.scale for consistent rendering
        const finalScale = Settings.sprite.scale;
        const scaledWidth = sprite.width * finalScale;
        const scaledHeight = sprite.height * finalScale;

        // Position at calculated screen coordinates
        const drawX = Math.floor(screenX - scaledWidth / 2);
        const drawY = Math.floor(screenY - scaledHeight - jumpOffset);

        this.ctx.save();
        
        // Apply rainbow color filter for star power
        if (player.starPowerActive) {
            this.ctx.filter = `hue-rotate(${(player.starPowerTimer * 360 * 3) % 360}deg) saturate(2)`;
        }

        if (flipHorizontal) {
            this.ctx.translate(Math.floor(screenX), Math.floor(drawY + scaledHeight / 2));
            this.ctx.scale(-1, 1);
            characterSprite.draw(
                this.ctx,
                sprite.x, sprite.y, sprite.width, sprite.height,
                Math.floor(-scaledWidth / 2),
                Math.floor(-scaledHeight / 2),
                scaledWidth,
                scaledHeight
            );
        } else {
            characterSprite.draw(
                this.ctx,
                sprite.x, sprite.y, sprite.width, sprite.height,
                drawX, drawY,
                scaledWidth,
                scaledHeight
            );
        }

        this.ctx.restore();
    }

    // Special method for drawing player in world space during intro camera
    // Uses the same scale as drawMario to ensure seamless transition
    drawPlayerInWorld(player, camera) {
        const worldX = player.x - camera.getX();
        const worldY = player.y - camera.getY();

        const cos = Math.cos(camera.getAngle());
        const sin = Math.sin(camera.getAngle());
        
        const distance = worldX * sin + worldY * cos;
        const screenOffsetX = worldX * cos - worldY * sin;

        if (distance <= 1) return;

        const horizon = Math.floor(this.height / 2 + camera.getPitch());
        
        const scale = distance / this.height;
        const screenX = Math.floor(this.width / 2 - screenOffsetX / scale);
        const screenY = Math.floor(horizon + (camera.getHeight() * this.height) / distance - 1);

        if (screenX < -100 || screenX > this.width + 100 || screenY < horizon - 50 || screenY > this.height + 50) {
            return;
        }

        // Get player's character sprites
        const characterSprites = player.characterSprites || MARIO_SPRITES;
        let characterSprite = player.characterSprite || this.marioSprite;
        
        if (!player.characterSprite && player.characterData && player.characterData.imagePath) {
            const characterId = player.characterData.id;
            if (!this.characterSpriteCache.has(characterId)) {
                this.characterSpriteCache.set(characterId, new Sprite(player.characterData.imagePath));
            }
            characterSprite = this.characterSpriteCache.get(characterId);
            player.characterSprite = characterSprite;
        }

        // Calculate angle and sprite
        const relativeAngle = player.angle - camera.getAngle();
        const normalizedAngle = ((relativeAngle % (Math.PI * 2)) + (Math.PI * 2)) % (Math.PI * 2);
        let flipHorizontal = false;
        let workingAngle = normalizedAngle;

        if (normalizedAngle > Math.PI) {
            workingAngle = (Math.PI * 2) - normalizedAngle;
            flipHorizontal = false;
        } else {
            flipHorizontal = true;
        }

        // Always use LOD0 (highest quality) for player during intro
        const lodSprites = characterSprites.lod0;
        const segmentSize = Math.PI / 11;
        let spriteIndex = Math.round(workingAngle / segmentSize);
        spriteIndex = Math.min(spriteIndex, 11);
        const sprite = lodSprites[spriteIndex];

        const jumpOffset = player.getJumpHeight ? player.getJumpHeight() : 0;

        // Use the SAME scale as drawMario for consistent size
        const spriteScale = Settings.sprite.scale;
        const scaledWidth = sprite.width * spriteScale;
        const scaledHeight = sprite.height * spriteScale;

        const drawX = Math.floor(screenX - scaledWidth / 2);
        const drawY = Math.floor(screenY - scaledHeight - jumpOffset);

        this.ctx.save();

        if (flipHorizontal) {
            this.ctx.translate(Math.floor(screenX), Math.floor(drawY + scaledHeight / 2));
            this.ctx.scale(-1, 1);
            characterSprite.draw(
                this.ctx,
                sprite.x, sprite.y, sprite.width, sprite.height,
                Math.floor(-scaledWidth / 2),
                Math.floor(-scaledHeight / 2),
                scaledWidth,
                scaledHeight
            );
        } else {
            characterSprite.draw(
                this.ctx,
                sprite.x, sprite.y, sprite.width, sprite.height,
                drawX, drawY,
                scaledWidth,
                scaledHeight
            );
        }

        this.ctx.restore();
    }

    drawRacerInWorld(racer, camera) {
        const worldX = racer.x - camera.getX();
        const worldY = racer.y - camera.getY();

        const cos = Math.cos(camera.getAngle());
        const sin = Math.sin(camera.getAngle());
        
        const distance = worldX * sin + worldY * cos;
        const screenOffsetX = worldX * cos - worldY * sin;

        if (distance <= 1) return;

        const horizon = Math.floor(this.height / 2 + camera.getPitch());
        
        const scale = distance / this.height;
        const screenX = Math.floor(this.width / 2 - screenOffsetX / scale);
        const screenY = Math.floor(horizon + (camera.getHeight() * this.height) / distance - 1);

        if (screenX < -100 || screenX > this.width + 100 || screenY < horizon - 50 || screenY > this.height + 50) {
            return;
        }

        const relativeAngle = racer.angle - camera.getAngle();
        const normalizedAngle = ((relativeAngle % (Math.PI * 2)) + (Math.PI * 2)) % (Math.PI * 2);
        let flipHorizontal = false;
        let workingAngle = normalizedAngle;

        // If hit, override with spin animation angle
        if (racer.isHit) {
            const hitNormalizedAngle = ((racer.hitSpinRotation % (Math.PI * 2)) + (Math.PI * 2)) % (Math.PI * 2);
            workingAngle = hitNormalizedAngle;
            
            if (hitNormalizedAngle > Math.PI) {
                workingAngle = (Math.PI * 2) - hitNormalizedAngle;
                flipHorizontal = false;
            } else {
                flipHorizontal = true;
            }
        } else {
            // The sprites show right turns by default (0° to 180°)
            // When the racer is on the left side (angle > π), we need to flip
            if (normalizedAngle > Math.PI) {
                workingAngle = (Math.PI * 2) - normalizedAngle;
                flipHorizontal = false; // Changed from true to false
            } else {
                flipHorizontal = true; // Added else case - flip when on right side
            }
        }

        // Don't use animationDirection for AI - the viewing angle already determines the correct sprite
        // The animationDirection is only used for the player sprite at the bottom of the screen

        const screenDepth = screenY - horizon;
        const maxDepth = this.height - horizon;
        let depthRatio = screenDepth / maxDepth;
        
        // Lightning effect: force higher LOD (smaller sprites) by increasing depth ratio
        if (racer.isLightningVictim) {
            depthRatio = Math.max(0, depthRatio - 0.35); // Shift down ~3-4 LOD levels
        }

        // Use character-specific sprites if available, otherwise default to MARIO_SPRITES
        // Cache the sprite data reference to avoid repeated property lookups
        const characterSprites = racer.characterSprites || MARIO_SPRITES;

        let lodSprites;
        if (depthRatio < 0.05) {
            lodSprites = characterSprites.lod10;
        } else if (depthRatio < 0.10) {
            lodSprites = characterSprites.lod9;
        } else if (depthRatio < 0.15) {
            lodSprites = characterSprites.lod8;
        } else if (depthRatio < 0.20) {
            lodSprites = characterSprites.lod7;
        } else if (depthRatio < 0.25) {
            lodSprites = characterSprites.lod6;
        } else if (depthRatio < 0.30) {
            lodSprites = characterSprites.lod5;
        } else if (depthRatio < 0.35) {
            lodSprites = characterSprites.lod4;
        } else if (depthRatio < 0.40) {
            lodSprites = characterSprites.lod3;
        } else if (depthRatio < 0.50) {
            lodSprites = characterSprites.lod2;
        } else if (depthRatio < 0.65) {
            lodSprites = characterSprites.lod1;
        } else {
            lodSprites = characterSprites.lod0;
        }
        
        let spriteIndex;
        const spriteCount = lodSprites.length;

        if (spriteCount === 12) {
            const segmentSize = Math.PI / 11;
            spriteIndex = Math.round(workingAngle / segmentSize);
            spriteIndex = Math.min(spriteIndex, 11);
        } else if (spriteCount === 8) {
            const segmentSize = Math.PI / 7;
            spriteIndex = Math.round(workingAngle / segmentSize);
            spriteIndex = Math.min(spriteIndex, 7);
        } else if (spriteCount === 4) {
            if (workingAngle < Math.PI * 0.25) {
                spriteIndex = 0;
            } else if (workingAngle < Math.PI * 0.60) {
                spriteIndex = 1;
            } else if (workingAngle < Math.PI * 0.85) {
                spriteIndex = 2;
            } else {
                spriteIndex = 3;
            }
        } else if (spriteCount === 3) {
            if (workingAngle < Math.PI * 0.33) {
                spriteIndex = 0;
            } else if (workingAngle < Math.PI * 0.67) {
                spriteIndex = 1;
            } else {
                spriteIndex = 2;
            }
        } else if (spriteCount === 2) {
            if (workingAngle < Math.PI * 0.5) {
                spriteIndex = 0;
            } else {
                spriteIndex = 1;
            }
        }

        const sprite = lodSprites[spriteIndex];

        const jumpOffset = racer.getJumpHeight ? racer.getJumpHeight() : 0;

        const drawX = Math.floor(screenX - sprite.width / 2);
        const drawY = Math.floor(screenY - sprite.height - jumpOffset);

        // Get the appropriate character sprite, or default to Mario
        // Use cached sprite reference for performance
        let characterSprite = racer.characterSprite || this.marioSprite;
        
        // Fallback: if not cached, load and cache it
        if (!racer.characterSprite && racer.characterData && racer.characterData.imagePath) {
            const characterId = racer.characterData.id;
            if (!this.characterSpriteCache.has(characterId)) {
                // Load and cache the character sprite if not already loaded
                this.characterSpriteCache.set(characterId, new Sprite(racer.characterData.imagePath));
            }
            characterSprite = this.characterSpriteCache.get(characterId);
            // Cache it on the racer for next frame
            racer.characterSprite = characterSprite;
        }

        this.ctx.save();
        
        // Apply rainbow color filter for star power
        if (racer.starPowerActive) {
            this.ctx.filter = `hue-rotate(${(racer.starPowerTimer * 360 * 3) % 360}deg) saturate(2)`;
        }

        if (flipHorizontal) {
            this.ctx.translate(Math.floor(screenX), Math.floor(drawY + sprite.height / 2));
            this.ctx.scale(-1, 1);
            characterSprite.draw(
                this.ctx,
                sprite.x, sprite.y, sprite.width, sprite.height,
                Math.floor(-sprite.width / 2),
                Math.floor(-sprite.height / 2),
                sprite.width,
                sprite.height
            );
        } else {
            characterSprite.draw(
                this.ctx,
                sprite.x, sprite.y, sprite.width, sprite.height,
                drawX, drawY,
                sprite.width,
                sprite.height
            );
        }

        this.ctx.restore();
    }

    drawMysteryBox(mysteryBox, baseSprite, shaderSprite, baseFrames, shaderFrames, camera) {
        const worldX = mysteryBox.x - camera.getX();
        const worldY = mysteryBox.y - camera.getY();

        const cos = Math.cos(camera.getAngle());
        const sin = Math.sin(camera.getAngle());
        const distance = worldX * sin + worldY * cos;
        const screenOffsetX = worldX * cos - worldY * sin;

        if (distance <= 1 || distance > 800) return;

        const horizon = Math.floor(this.height / 2 + camera.getPitch());
        const scale = distance / this.height;
        const screenX = Math.floor(this.width / 2 - screenOffsetX / scale);
        const screenY = Math.floor(horizon + ((camera.getHeight() - mysteryBox.height) * this.height) / distance - 1);

        const frameIndex = mysteryBox.getCurrentFrame();
        const baseFrame = baseFrames[frameIndex];
        const shaderFrame = shaderFrames[frameIndex];

        const spriteWidth = Math.floor((baseFrame.width / scale) * mysteryBox.scale);
        const spriteHeight = Math.floor((baseFrame.height / scale) * mysteryBox.scale);

        const drawX = Math.floor(screenX - spriteWidth / 2);
        const drawY = Math.floor(screenY - spriteHeight);

        this.ctx.drawImage(
            baseSprite.image,
            baseFrame.x, baseFrame.y,
            baseFrame.width, baseFrame.height,
            drawX, drawY,
            spriteWidth, spriteHeight
        );

        this.ctx.drawImage(
            shaderSprite.image,
            shaderFrame.x, shaderFrame.y,
            shaderFrame.width, shaderFrame.height,
            drawX, drawY,
            spriteWidth, spriteHeight
        )
    }

    drawCoin(coin, itemSprite, camera) {
        const worldX = coin.x - camera.getX();
        const worldY = coin.y - camera.getY();

        const cos = Math.cos(camera.getAngle());
        const sin = Math.sin(camera.getAngle());
        const distance = worldX * sin + worldY * cos;
        const screenOffsetX = worldX * cos - worldY * sin;

        if (distance <= 1 || distance > 800) return;

        const horizon = Math.floor(this.height / 2 + camera.getPitch());
        const scale = distance / this.height;
        const screenX = Math.floor(this.width / 2 - screenOffsetX / scale);
        const screenY = Math.floor(horizon + ((camera.getHeight() - coin.height) * this.height) / distance - 1);

        const frameIndex = coin.getCurrentFrame();
        const coinFrame = ITEM_SPRITES.coin_world[frameIndex];

        const spriteWidth = Math.floor((coinFrame.width / scale) * coin.scale);
        const spriteHeight = Math.floor((coinFrame.height / scale) * coin.scale);

        const drawX = Math.floor(screenX - spriteWidth / 2);
        const drawY = Math.floor(screenY - spriteHeight);

        this.ctx.drawImage(
            itemSprite.image,
            coinFrame.x, coinFrame.y,
            coinFrame.width, coinFrame.height,
            drawX, drawY,
            spriteWidth, spriteHeight
        );
    }
    
    drawBanana(banana, itemSprite, camera) {
        const worldX = banana.x - camera.getX();
        const worldY = banana.y - camera.getY();

        const cos = Math.cos(camera.getAngle());
        const sin = Math.sin(camera.getAngle());
        const distance = worldX * sin + worldY * cos;
        const screenOffsetX = worldX * cos - worldY * sin;

        if (distance <= 1 || distance > 800) return;

        const horizon = Math.floor(this.height / 2 + camera.getPitch());
        const scale = distance / this.height;
        const screenX = Math.floor(this.width / 2 - screenOffsetX / scale);
        const screenY = Math.floor(horizon + ((camera.getHeight() - banana.height) * this.height) / distance - 1);

        // Calculate LOD based on screen depth (matching racer system)
        const screenDepth = screenY - horizon;
        const maxDepth = this.height - horizon;
        const depthRatio = screenDepth / maxDepth;

        // Banana has 8 LODs (lod0-lod7), map to racer depth ratios
        // Racers have 11 LODs with these thresholds, we'll map banana LODs appropriately
        let lodSprites;
        if (depthRatio < 0.10) {
            lodSprites = ITEM_SPRITES.bannana_world.lod7; // Farthest
        } else if (depthRatio < 0.15) {
            lodSprites = ITEM_SPRITES.bannana_world.lod7;
        } else if (depthRatio < 0.20) {
            lodSprites = ITEM_SPRITES.bannana_world.lod6;
        } else if (depthRatio < 0.25) {
            lodSprites = ITEM_SPRITES.bannana_world.lod5;
        } else if (depthRatio < 0.30) {
            lodSprites = ITEM_SPRITES.bannana_world.lod5;
        } else if (depthRatio < 0.35) {
            lodSprites = ITEM_SPRITES.bannana_world.lod4;
        } else if (depthRatio < 0.40) {
            lodSprites = ITEM_SPRITES.bannana_world.lod3;
        } else if (depthRatio < 0.50) {
            lodSprites = ITEM_SPRITES.bannana_world.lod2;
        } else if (depthRatio < 0.65) {
            lodSprites = ITEM_SPRITES.bannana_world.lod1;
        } else {
            lodSprites = ITEM_SPRITES.bannana_world.lod0; // Closest
        }

        // Safety check
        if (!lodSprites || lodSprites.length === 0) return;

        const bananaFrame = lodSprites[0]; // Bananas don't animate, just use first frame
        
        // Safety check for frame
        if (!bananaFrame) return;

        // Draw at native sprite size (like racers do)
        const drawX = Math.floor(screenX - bananaFrame.width / 2);
        const drawY = Math.floor(screenY - bananaFrame.height);

        this.ctx.drawImage(
            itemSprite.image,
            bananaFrame.x, bananaFrame.y,
            bananaFrame.width, bananaFrame.height,
            drawX, drawY,
            bananaFrame.width,
            bananaFrame.height
        );
    }
    
    drawGreenShell(shell, itemSprite, camera) {
        const worldX = shell.x - camera.getX();
        const worldY = shell.y - camera.getY();

        const cos = Math.cos(camera.getAngle());
        const sin = Math.sin(camera.getAngle());
        const distance = worldX * sin + worldY * cos;
        const screenOffsetX = worldX * cos - worldY * sin;

        if (distance <= 1 || distance > 800) return;

        const horizon = Math.floor(this.height / 2 + camera.getPitch());
        const scale = distance / this.height;
        const screenX = Math.floor(this.width / 2 - screenOffsetX / scale);
        const screenY = Math.floor(horizon + ((camera.getHeight() - shell.height + shell.bounceHeight) * this.height) / distance - 1);

        // Calculate LOD based on screen depth (matching banana/racer system)
        const screenDepth = screenY - horizon;
        const maxDepth = this.height - horizon;
        const depthRatio = screenDepth / maxDepth;

        // Green shell has 8 LODs (lod0-lod7), same thresholds as banana
        let lodSprites;
        if (depthRatio < 0.10) {
            lodSprites = ITEM_SPRITES.green_shell_world.lod7; // Farthest
        } else if (depthRatio < 0.15) {
            lodSprites = ITEM_SPRITES.green_shell_world.lod7;
        } else if (depthRatio < 0.20) {
            lodSprites = ITEM_SPRITES.green_shell_world.lod6;
        } else if (depthRatio < 0.25) {
            lodSprites = ITEM_SPRITES.green_shell_world.lod5;
        } else if (depthRatio < 0.30) {
            lodSprites = ITEM_SPRITES.green_shell_world.lod5;
        } else if (depthRatio < 0.35) {
            lodSprites = ITEM_SPRITES.green_shell_world.lod4;
        } else if (depthRatio < 0.40) {
            lodSprites = ITEM_SPRITES.green_shell_world.lod3;
        } else if (depthRatio < 0.50) {
            lodSprites = ITEM_SPRITES.green_shell_world.lod2;
        } else if (depthRatio < 0.65) {
            lodSprites = ITEM_SPRITES.green_shell_world.lod1;
        } else {
            lodSprites = ITEM_SPRITES.green_shell_world.lod0; // Closest
        }

        // Safety check for valid LOD sprites
        if (!lodSprites || lodSprites.length === 0) {
            return;
        }

        // Select frame based on shell state
        let frameIndex = shell.currentFrame;
        
        // Clamp frame index to available frames
        frameIndex = Math.min(frameIndex, lodSprites.length - 1);
        frameIndex = Math.max(0, frameIndex);
        
        const shellFrame = lodSprites[frameIndex];
        
        // Safety check for valid frame
        if (!shellFrame || !shellFrame.width || !shellFrame.height) {
            return;
        }

        // Draw at native sprite size (like racers and bananas)
        const drawX = Math.floor(screenX - shellFrame.width / 2);
        const drawY = Math.floor(screenY - shellFrame.height);

        // If hit, flip upside down
        if (shell.isHit) {
            this.ctx.save();
            this.ctx.translate(screenX, screenY);
            this.ctx.scale(1, -1);
            this.ctx.drawImage(
                itemSprite.image,
                shellFrame.x, shellFrame.y,
                shellFrame.width, shellFrame.height,
                -shellFrame.width / 2, -shellFrame.height,
                shellFrame.width, shellFrame.height
            );
            this.ctx.restore();
        } else {
            this.ctx.drawImage(
                itemSprite.image,
                shellFrame.x, shellFrame.y,
                shellFrame.width, shellFrame.height,
                drawX, drawY,
                shellFrame.width, shellFrame.height
            );
        }
    }
    
    drawRedShell(shell, itemSprite, camera) {
        const worldX = shell.x - camera.getX();
        const worldY = shell.y - camera.getY();

        const cos = Math.cos(camera.getAngle());
        const sin = Math.sin(camera.getAngle());
        const distance = worldX * sin + worldY * cos;
        const screenOffsetX = worldX * cos - worldY * sin;

        if (distance <= 1 || distance > 800) return;

        const horizon = Math.floor(this.height / 2 + camera.getPitch());
        const scale = distance / this.height;
        const screenX = Math.floor(this.width / 2 - screenOffsetX / scale);
        const screenY = Math.floor(horizon + ((camera.getHeight() - shell.height + shell.bounceHeight) * this.height) / distance - 1);

        // Calculate LOD based on screen depth (matching banana/racer system)
        const screenDepth = screenY - horizon;
        const maxDepth = this.height - horizon;
        const depthRatio = screenDepth / maxDepth;

        // Red shell uses same LOD system as green shell
        let lodSprites;
        if (depthRatio < 0.10) {
            lodSprites = ITEM_SPRITES.green_shell_world.lod7;
        } else if (depthRatio < 0.15) {
            lodSprites = ITEM_SPRITES.green_shell_world.lod7;
        } else if (depthRatio < 0.20) {
            lodSprites = ITEM_SPRITES.green_shell_world.lod6;
        } else if (depthRatio < 0.25) {
            lodSprites = ITEM_SPRITES.green_shell_world.lod5;
        } else if (depthRatio < 0.30) {
            lodSprites = ITEM_SPRITES.green_shell_world.lod5;
        } else if (depthRatio < 0.35) {
            lodSprites = ITEM_SPRITES.green_shell_world.lod4;
        } else if (depthRatio < 0.40) {
            lodSprites = ITEM_SPRITES.green_shell_world.lod3;
        } else if (depthRatio < 0.50) {
            lodSprites = ITEM_SPRITES.green_shell_world.lod2;
        } else if (depthRatio < 0.65) {
            lodSprites = ITEM_SPRITES.green_shell_world.lod1;
        } else {
            lodSprites = ITEM_SPRITES.green_shell_world.lod0;
        }

        if (!lodSprites || lodSprites.length === 0) {
            return;
        }

        let frameIndex = shell.currentFrame;
        frameIndex = Math.min(frameIndex, lodSprites.length - 1);
        frameIndex = Math.max(0, frameIndex);
        
        const shellFrame = lodSprites[frameIndex];
        
        if (!shellFrame || !shellFrame.width || !shellFrame.height) {
            return;
        }

        const drawX = Math.floor(screenX - shellFrame.width / 2);
        const drawY = Math.floor(screenY - shellFrame.height);

        this.ctx.save();
        
        // Apply color filter to convert green shell to red shell
        // Green 008800 -> Red a00000
        // Green 40e040 -> Red f84848
        this.ctx.filter = 'hue-rotate(220deg) saturate(1.2)';
        
        // If broken, flip and rotate
        if (shell.isBroken) {
            this.ctx.translate(screenX, screenY);
            this.ctx.rotate(shell.getFlipRotation());
            this.ctx.scale(1, -1);
            this.ctx.drawImage(
                itemSprite.image,
                shellFrame.x, shellFrame.y,
                shellFrame.width, shellFrame.height,
                -shellFrame.width / 2, -shellFrame.height,
                shellFrame.width, shellFrame.height
            );
        } else {
            this.ctx.drawImage(
                itemSprite.image,
                shellFrame.x, shellFrame.y,
                shellFrame.width, shellFrame.height,
                drawX, drawY,
                shellFrame.width, shellFrame.height
            );
        }
        
        this.ctx.restore();
    }

    drawParticle(particle, particleSprite, camera) {
        if (!particleSprite || !particleSprite.image) {
            console.error('Particle sprite not loaded!');
            return;
        }

        const worldX = particle.x - camera.getX();
        const worldY = particle.y - camera.getY();

        const cos = Math.cos(camera.getAngle());
        const sin = Math.sin(camera.getAngle());
        const distance = worldX * sin + worldY * cos;
        const screenOffsetX = worldX * cos - worldY * sin;

        // Changed: Allow particles closer than 1 unit (was too strict)
        if (distance <= 0.1 || distance > 800) {
            return;
        }

        const horizon = Math.floor(this.height / 2 + camera.getPitch());
        const scale = distance / this.height;
        const screenX = Math.floor(this.width / 2 - screenOffsetX / scale);
        const screenY = Math.floor(horizon + ((camera.getHeight() - particle.height) * this.height) / distance - 1);

        const sprite = particle.sprite;
        if (!sprite || !sprite.width || !sprite.height) {
            console.error('Particle sprite data invalid:', sprite);
            return;
        }

        // Draw at native sprite size
        const drawX = Math.floor(screenX - sprite.width / 2);
        const drawY = Math.floor(screenY - sprite.height);

        // Apply alpha based on lifetime
        const alpha = particle.getAlpha();
        this.ctx.save();
        this.ctx.globalAlpha = alpha;

        this.ctx.drawImage(
            particleSprite.image,
            sprite.x, sprite.y,
            sprite.width, sprite.height,
            drawX, drawY,
            sprite.width, sprite.height
        );

        this.ctx.restore();
    }

    // Draw particle in screen space (viewport) relative to player
    drawParticleInViewport(particle, particleSprite, player) {
        if (!particleSprite || !particleSprite.image) {
            return;
        }

        const sprite = particle.getCurrentSprite();
        if (!sprite || !sprite.width || !sprite.height) {
            return;
        }

        // Calculate particle position relative to player
        const dx = particle.x - player.x;
        const dy = particle.y - player.y;

        // Rotate to player's perspective
        const cos = Math.cos(-player.angle);
        const sin = Math.sin(-player.angle);
        const rotatedX = dx * cos - dy * sin;
        const rotatedY = dx * sin + dy * cos;

        // Screen position (player is at bottom center)
        const screenCenterX = this.width / 2;
        const screenCenterY = this.height - 30; // Player is drawn at bottom
        
        // Scale based on distance (simple perspective)
        const distance = Math.sqrt(dx * dx + dy * dy);
        const scale = Math.max(0.3, 1 - distance / 100); // Particles get smaller with distance
        
        // Don't draw if too far
        if (distance > 150) return;
        
        let screenX = screenCenterX + rotatedX * 2; // Scale factor for screen space
        let screenY = screenCenterY + rotatedY * 2 - particle.height * 3; // Account for height
        
        // Handle screen space velocity (for dirt particles)
        if (particle.screenSpaceVelocity) {
            // Add accumulated screen space offset
            screenX += particle.screenOffsetX;
            screenY += particle.screenOffsetY;
        }

        const drawWidth = sprite.width * scale;
        const drawHeight = sprite.height * scale;
        const drawX = Math.floor(screenX - drawWidth / 2);
        const drawY = Math.floor(screenY - drawHeight);

        // Apply alpha based on lifetime
        const alpha = particle.getAlpha();
        this.ctx.save();
        this.ctx.globalAlpha = alpha;

        // Handle horizontal flipping for left wheel particles
        if (particle.flipHorizontal) {
            this.ctx.translate(drawX + drawWidth, drawY);
            this.ctx.scale(-1, 1);
            this.ctx.drawImage(
                particleSprite.image,
                sprite.x, sprite.y,
                sprite.width, sprite.height,
                0, 0,
                drawWidth, drawHeight
            );
        } else {
            // Draw without flip - particles stay screen-aligned
            this.ctx.drawImage(
                particleSprite.image,
                sprite.x, sprite.y,
                sprite.width, sprite.height,
                drawX, drawY,
                drawWidth, drawHeight
            );
        }

        this.ctx.restore();
    }
}

