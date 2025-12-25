import { Settings } from "../Settings.js";
import { MARIO_SPRITES } from "../../racers/mario_sprites.js";

export class MinimapRenderer {
    constructor(renderer, mapWidth, mapHeight) {
        this.renderer = renderer;
        this.mapWidth = mapWidth;
        this.mapHeight = mapHeight;

        this.minimapCanvas = null;
        this.minimapCtx = null;
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

        
        if (waypoints.length > 0) {
            ctx.save();
            for (let i = 0; i < waypoints.length; i++) {
                const wp = waypoints[i];
                const persp = this.worldToPerspective(
                    wp.x + 100,
                    wp.y + 100,
                    trackWidth + 200,
                    trackHeight + 200
                );
                const wpX = mapX + (persp.x / (trackWidth + 200)) * this.mapWidth;
                const wpY = mapY + (persp.y / (trackHeight + 200)) * this.mapHeight;
                
                ctx.fillStyle = 'rgba(255, 0, 255, 0)';
                ctx.beginPath();
                ctx.arc(wpX, wpY, 1.5, 0, Math.PI * 2);
                ctx.fill();
            }
            ctx.restore();
        }

        
        for (const ai of aiRacers) {
            
            if (ai.waypoints && ai.currentWaypointIndex !== undefined) {
                const targetWP = ai.waypoints[ai.currentWaypointIndex];
                const aiPersp = this.worldToPerspective(
                    ai.x + 100,
                    ai.y + 100,
                    trackWidth + 200,
                    trackHeight + 200
                );
                const wpPersp = this.worldToPerspective(
                    targetWP.x + 100,
                    targetWP.y + 100,
                    trackWidth + 200,
                    trackHeight + 200
                );
                
                const aiMapX = mapX + (aiPersp.x / (trackWidth + 200)) * this.mapWidth;
                const aiMapY = mapY + (aiPersp.y / (trackHeight + 200)) * this.mapHeight;
                const wpMapX = mapX + (wpPersp.x / (trackWidth + 200)) * this.mapWidth;
                const wpMapY = mapY + (wpPersp.y / (trackHeight + 200)) * this.mapHeight;
                
                ctx.save();
                ctx.strokeStyle = 'none';
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.moveTo(aiMapX, aiMapY);
                ctx.lineTo(wpMapX, wpMapY);
                ctx.stroke();
                ctx.restore();
            }
            
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

        
        const normalizedAngle = (racer.angle % (Math.PI * 2) + (Math.PI * 2)) % (Math.PI * 2);
        let flipHorizontal = false;
        let workingAngle = normalizedAngle;

        if (normalizedAngle > Math.PI) {
            workingAngle = (Math.PI * 2) - normalizedAngle;
            flipHorizontal = true;
        }

        const segmentSize = Math.PI / 8;
        let spriteIndex = Math.floor(workingAngle / segmentSize);
        spriteIndex = 7 - spriteIndex; 
        spriteIndex = Math.min(spriteIndex, 7);

        const sprite = MARIO_SPRITES.lod3[spriteIndex];

        ctx.save();
        
        
        if (colorTint === 'red') {
            ctx.globalCompositeOperation = 'source-over';
            ctx.filter = 'hue-rotate(180deg) saturate(1.5)';
        }
        
        if (flipHorizontal) {
            ctx.translate(racerMapX, racerMapY);
            ctx.scale(-1, 1);
            this.renderer.marioSprite.draw(
                ctx,
                sprite.x, sprite.y, sprite.width, sprite.height,
                -sprite.width / 2, -sprite.height / 1.4,
                sprite.width,
                sprite.height
            );
        } else {
            this.renderer.marioSprite.draw(
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