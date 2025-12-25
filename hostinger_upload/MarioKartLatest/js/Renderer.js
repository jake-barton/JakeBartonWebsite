import { Settings } from './Settings.js';
import { Sprite } from './Sprite.js';
import { MARIO_SPRITES } from '../racers/mario_sprites.js';

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

        this.marioSprite = new Sprite('racers/mario.png');
        
        
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

        for (let y = horizon; y < this.height; y++) {
            
            const distance = (camera.getHeight() * this.height) / (y - horizon + 1);
            const scale = distance / this.height;

            for (let x = 0; x < this.width; x++) {
                let screenOffsetX = (this.width / 2 - x) * scale;
                let screenOffsetY = distance;

                let rotatedX = screenOffsetX * Math.cos(camera.getAngle()) + screenOffsetY * Math.sin(camera.getAngle());
                let rotatedY = -screenOffsetX * Math.sin(camera.getAngle()) + screenOffsetY * Math.cos(camera.getAngle());

                let trackX = Math.floor(camera.getX() + rotatedX);
                let trackY = Math.floor(camera.getY() + rotatedY);

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

        // Check if player is in victory mode
        if (player.victoryMode) {
            if (player.celebrating) {
                // During celebration phase, flash between celebrate and facing camera sprite
                sprite = MARIO_SPRITES.celebrate;
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
                sprite = MARIO_SPRITES.lod0[spriteIndex];
            }
        } else {
            const { spriteIndex, flipHorizontal: shouldFlip } = this.getSpriteIndex(player, camera);
            sprite = MARIO_SPRITES.lod0[spriteIndex];
            flipHorizontal = shouldFlip;
        }

        const driftOffset = player.getDriftOffset();

        const jumpOffset = player.getJumpHeight();
        
        // Add drift wobble to horizontal position
        const wobbleOffset = player.driftWobbleOffset * 15; // Scale up for screen space

        const centerX = Math.floor(this.width / 2 - (sprite.width * Settings.sprite.scale) / 2 + driftOffset + wobbleOffset);
        const centerY = Math.floor(this.height - (sprite.height * Settings.sprite.scale) / 2 + Settings.sprite.verticalOffset - jumpOffset + yOffset);

        this.ctx.save();

        if (flipHorizontal) {
            this.ctx.translate(centerX + (sprite.width * Settings.sprite.scale) / 2, centerY + (sprite.height * Settings.sprite.scale) / 2);
            this.ctx.scale(-1, 1);

            this.marioSprite.draw(
                this.ctx,
                sprite.x, sprite.y, sprite.width, sprite.height,
                -(sprite.width * Settings.sprite.scale) / 2,
                -(sprite.height * Settings.sprite.scale) / 2,
                sprite.width * Settings.sprite.scale,
                sprite.height * Settings.sprite.scale
            );
        } else {
            this.marioSprite.draw(
                this.ctx,
                sprite.x, sprite.y, sprite.width, sprite.height,
                centerX, centerY,
                sprite.width * Settings.sprite.scale,
                sprite.height * Settings.sprite.scale
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

        // The sprites show right turns by default (0° to 180°)
        // When the racer is on the left side (angle > π), we need to flip
        if (normalizedAngle > Math.PI) {
            workingAngle = (Math.PI * 2) - normalizedAngle;
            flipHorizontal = false; // Changed from true to false
        } else {
            flipHorizontal = true; // Added else case - flip when on right side
        }

        // Don't use animationDirection for AI - the viewing angle already determines the correct sprite
        // The animationDirection is only used for the player sprite at the bottom of the screen

        const screenDepth = screenY - horizon;
        const maxDepth = this.height - horizon;
        const depthRatio = screenDepth / maxDepth;

        let lodSprites;
        if (depthRatio < 0.05) {
            lodSprites = MARIO_SPRITES.lod10;
        } else if (depthRatio < 0.10) {
            lodSprites = MARIO_SPRITES.lod9;
        } else if (depthRatio < 0.15) {
            lodSprites = MARIO_SPRITES.lod8;
        } else if (depthRatio < 0.20) {
            lodSprites = MARIO_SPRITES.lod7;
        } else if (depthRatio < 0.25) {
            lodSprites = MARIO_SPRITES.lod6;
        } else if (depthRatio < 0.30) {
            lodSprites = MARIO_SPRITES.lod5;
        } else if (depthRatio < 0.35) {
            lodSprites = MARIO_SPRITES.lod4;
        } else if (depthRatio < 0.40) {
            lodSprites = MARIO_SPRITES.lod3;
        } else if (depthRatio < 0.50) {
            lodSprites = MARIO_SPRITES.lod2;
        } else if (depthRatio < 0.65) {
            lodSprites = MARIO_SPRITES.lod1;
        } else {
            lodSprites = MARIO_SPRITES.lod0;
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

        this.ctx.save();

        if (flipHorizontal) {
            this.ctx.translate(Math.floor(screenX), Math.floor(drawY + sprite.height / 2));
            this.ctx.scale(-1, 1);
            this.marioSprite.draw(
                this.ctx,
                sprite.x, sprite.y, sprite.width, sprite.height,
                Math.floor(-sprite.width / 2),
                Math.floor(-sprite.height / 2),
                sprite.width,
                sprite.height
            );
        } else {
            this.marioSprite.draw(
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
}