import { Settings } from "../core/Settings.js";

export class CollisionManager {
    constructor(maskData, player) {
        this.maskData = maskData;
        this.player = player;

        this.isOffTrack = false;
        this.offTrackBounceTimer = 0;

        this.currentLap = 1;
        this.totalLaps = 5;
        this.hasPassedCheckpoint = false;
        this.onFinishLine = false;
        this.onCheckpoint = false;

        this.wallCollisionCooldown = 0;
        this.isCollidingWithWall = false;
    }

    getPixelType(x, y) {
        const maskData = this.maskData;
        const index = (Math.floor(y) * maskData.width + Math.floor(x)) * 4;

        const r = maskData.data[index];
        const g = maskData.data[index + 1];
        const b = maskData.data[index + 2];

        if (r > 200 && g < 50 && b < 50) return 'finishLine';
        if (r < 50 && g > 200 && b < 50) return 'checkpoint';
        if (r < 50 && g < 50 && b > 200) return 'wall';
        if (r > 200 && g > 200 && b < 50) return 'itemBox';
        if (r > 200 && g > 200 && b > 200) return 'road';
        if (r < 50 && g < 50 && b < 50) return 'offTrack';

        return 'track';
    }

    getCollisionPoint() {
        const collisionDistance = 27;

        const checkX = this.player.x + Math.sin(this.player.angle) * collisionDistance;
        const checkY = this.player.y + Math.cos(this.player.angle) * collisionDistance;

        return { x: checkX, y: checkY };
    }

    checkLapCrossing(onLapComplete) {
        const collisionPoint = this.getCollisionPoint();
        const pixelType = this.getPixelType(collisionPoint.x, collisionPoint.y);

        if (pixelType === 'finishLine') {
            if (!this.onFinishLine && this.hasPassedCheckpoint) {
                this.currentLap++;
                this.hasPassedCheckpoint = false;

                if (onLapComplete) {
                    onLapComplete(this.currentLap);
                }
            }
            this.onFinishLine = true;
        } else {
            this.onFinishLine = false;
        }

        if (pixelType === 'checkpoint') {
            if (!this.onCheckpoint) {
                this.hasPassedCheckpoint = true;
            }
            this.onCheckpoint = true; 
        } else {
            this.onCheckpoint = false;
        }
    }

    handleCollisions(deltaTime, keys) {
        const collisionPoint = this.getCollisionPoint();
        const pixelType = this.getPixelType(collisionPoint.x, collisionPoint.y);
        
        if (this.wallCollisionCooldown > 0) {
            this.wallCollisionCooldown -= deltaTime;
        }
        if (pixelType === 'wall') {
            if (this.wallCollisionCooldown <= 0) {
                const minBounceForce = 0.8;
                const bounceSpeed = Math.max(minBounceForce, Math.abs(this.player.speed) * 0.5);
                this.player.speed = -bounceSpeed;
                
                this.player.targetSpeed = 0;
                this.player.triggerBounce(2);
                
                this.wallCollisionCooldown = 0.3;
                this.offTrackBounceTimer = -0.5; // Negative timer = cooldown period after wall hit
            }
            this.isCollidingWithWall = true;
            this.isOffTrack = false;

        } else if (pixelType === 'offTrack') {
            const dt60 = deltaTime * 60;
    
            this.player.speed *= Math.pow(Settings.movement.offTrackDrag, dt60);
            this.isOffTrack = true;
    
            // Only bounce if player is pressing forward (ArrowUp) and not already jumping
            const isPressingGo = keys && keys['ArrowUp'];
            const isAlreadyJumping = this.player.isJumping || this.player.jumpHeight > 0;
            
            if (isPressingGo && this.offTrackBounceTimer >= 0 && !isAlreadyJumping) {
                // Only accumulate timer if it's not in cooldown (negative) and not jumping
                this.offTrackBounceTimer += deltaTime;
                
                if (this.offTrackBounceTimer > 0.15) {
                    this.player.triggerBounce(0.5);
                    this.offTrackBounceTimer = 0;
                }
            } else if (isPressingGo && this.offTrackBounceTimer < 0) {
                // In cooldown - count up towards 0
                this.offTrackBounceTimer += deltaTime;
            } else {
                // Reset timer when not pressing go (but not during cooldown)
                if (this.offTrackBounceTimer >= 0) {
                    this.offTrackBounceTimer = 0;
                }
            }
        } else {
            this.isOffTrack = false;
            this.isCollidingWithWall = false;
        }
    }

    getCurrentLap() {
        return this.currentLap;
    }

    getTotalLaps() {
        return this.totalLaps;
    }

    getIsOffTrack() {
        return this.isOffTrack;
    }
}