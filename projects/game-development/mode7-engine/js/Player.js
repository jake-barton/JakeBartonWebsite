import { Settings } from "./Settings.js";

export class Player {

    constructor(startX, startY, startAngle) {
        this.x = startX;
        this.y = startY;
        this.angle = Settings.player.startAngle;
        this.speed = 0;
        this.targetSpeed = 0;
        this.currentTurnSpeed = 0;
        this.turnTime = 0;
        this.isDrifting = false;
        this.driftDirection = 0;
        this.lastTurnDirection = 0;
        this.animationFrame = 0;
        this.animationDirection = 0;
        this.jumpHeight = 0;
        this.jumpVelocity = 0;
        this.isJumping = false;
        this.spaceWasPressed = false;
        
        this.currentWaypointIndex = 0;
        this.waypoints = null;
        
        this.driftWobbleTime = 0;
        this.driftWobbleOffset = 0;
        
        this.victoryMode = false;
        this.celebrating = false;
    }

    update(keys, deltaTime) {
        if (!this.victoryMode) {
            this.handleInput(keys, deltaTime);
        }
        this.updateSpeed(deltaTime);
        this.updateRotation(keys, deltaTime);
        this.updateTurnAnimation(deltaTime);
        this.updateDriftWobble(deltaTime);
        this.updatePosition(deltaTime);
        this.updateJump(deltaTime);
        this.updateWaypointProgress();
    }

    handleInput(keys) {
        if (keys['ArrowUp']) {
            this.targetSpeed = Settings.movement.maxSpeed;
            
        } else if (keys['ArrowDown']) {
            this.targetSpeed = -Settings.movement.maxReverseSpeed;
        } else {
            this.targetSpeed = 0;
        }

        if (keys[' '] && !this.spaceWasPressed && !this.isJumping && this.jumpHeight === 0) {
            this.triggerBounce(1.6);
        }

        this.spaceWasPressed = keys[' '];
    }

    updateSpeed(deltaTime) {
        const dt60 = deltaTime * 60;
        if (this.speed < this.targetSpeed) {
            this.speed += Settings.movement.acceleration * dt60;

            if (this.speed > this.targetSpeed) {
                this.speed = this.targetSpeed;
            }
        }
        else if (this.speed > this.targetSpeed) {
            this.speed -= Settings.movement.deceleration * dt60;

            if (this.speed < this.targetSpeed) {
                this.speed = this.targetSpeed;
            }
        }
        if (this.targetSpeed === 0) {
            this.speed *= Math.pow(Settings.movement.friction, dt60);

            if (Math.abs(this.speed) < 0.01) {
                this.speed = 0;
            }
        }
        
    }

    updateRotation(keys, deltaTime) {
        const dt60 = deltaTime * 60;
        let isTurning = false;
        let turnDirection = 0;

        const minTurnSpeed = this.isOffTrack ? Settings.movement.offTrackMinSpeedToTurn : Settings.movement.minSpeedToTurn;
        if ((keys['ArrowLeft'] || keys['ArrowRight']) && Math.abs(this.speed) > minTurnSpeed) {
            if (keys['ArrowLeft']) {
                turnDirection = -1;
            }
            if (keys['ArrowRight']) {
                turnDirection = 1;
            }

            if (this.lastTurnDirection !== 0 && this.lastTurnDirection !== turnDirection) {
                this.currentTurnSpeed = 0;
                this.turnTime = 0;
                this.isDrifting = false;
                this.driftDirection = 0;
            }

            this.lastTurnDirection = turnDirection;

            this.currentTurnSpeed += Settings.movement.turnAcceleration * dt60;
            if (this.currentTurnSpeed > Settings.movement.baseTurnSpeed) {
                this.currentTurnSpeed = Settings.movement.baseTurnSpeed;
            }
            isTurning = true;

            this.turnTime += deltaTime;

            // Start drift if space is pressed
            if (keys[' '] && !this.isJumping) {
                this.isDrifting = true;
                this.driftDirection = turnDirection;
            }
            // Stop drift if space is released
            else if (!keys[' ']) {
                this.isDrifting = false;
                this.driftDirection = 0;
            }
        } else {
            this.currentTurnSpeed *= Math.pow(Settings.movement.turnSpeedEasing, dt60);

            if (this.currentTurnSpeed < 0.01) {
                this.currentTurnSpeed = 0;
                this.lastTurnDirection = 0;
            }
            this.turnTime = 0;
            this.isDrifting = false;
            this.driftDirection = 0;
        }

        if (this.currentTurnSpeed > 0 && this.lastTurnDirection !== 0) {
            const rotationAmount = this.currentTurnSpeed * (this.isDrifting ? Settings.movement.driftTurnBoost : 1);

            if (this.lastTurnDirection < 0) {
                this.angle += rotationAmount;
            } else {
                this.angle -= rotationAmount;
            }

            isTurning = true;
        }
        if (isTurning) {
            const dragValue = this.isDrifting ? Settings.movement.driftDrag : Settings.movement.turnDrag;
            this.speed *= Math.pow(dragValue, dt60);
        }
    }

    updateTurnAnimation() {
        let targetFrame = 0;

        if (this.isDrifting) {
            targetFrame = 4;
            this.animationDirection = this.driftDirection;
        } else if (this.lastTurnDirection !== 0) {
            const turnRatio = this.currentTurnSpeed / Settings.movement.baseTurnSpeed;
            const driftProgress = this.turnTime / Settings.movement.driftThreshold;

            if (driftProgress > 0.7) {
                targetFrame = 3;
            } else {
                targetFrame = 1 + turnRatio * 1;
            }
            this.animationDirection = this.lastTurnDirection;
        } else {
            targetFrame = 0;
            if (Math.abs(this.animationFrame) < 0.1) {
                this.animationDirection = 0;
            }
        }

        const easeSpeed = Settings.rendering.turnAnimationEaseSpeed;
        this.animationFrame += (targetFrame - this.animationFrame) * easeSpeed;
    }

    getDriftOffset() {
        if (!this.isDrifting) return 0;

        return this.driftDirection;
    }

    getAnimationFrame() {
        return Math.floor(this.animationFrame);
    }

    getAnimationDirection() {
        return this.animationDirection;
    }

    updateDriftWobble(deltaTime) {
        if (this.isDrifting) {
            // Oscillate side to side while drifting
            const wobbleSpeed = 24.0; // How fast the wobble cycles
            const wobbleAmount = 0.11; // How far to wobble (units)
            
            this.driftWobbleTime += deltaTime * wobbleSpeed;
            this.driftWobbleOffset = Math.sin(this.driftWobbleTime) * wobbleAmount;
        } else {
            // Smoothly return to center when not drifting
            this.driftWobbleTime = 0;
            this.driftWobbleOffset *= 0.8;
            if (Math.abs(this.driftWobbleOffset) < 0.01) {
                this.driftWobbleOffset = 0;
            }
        }
    }

    updatePosition() {
        this.x += Math.sin(this.angle) * this.speed;
        this.y += Math.cos(this.angle) * this.speed;

        if (this.isDrifting) {
            const slideDirection = this.driftDirection;
            const slideAmount = Settings.movement.driftSlide * Math.abs(this.speed);

            const slideAngle = this.angle + (Math.PI / 2) * slideDirection;
            this.x += Math.sin(slideAngle) * slideAmount;
            this.y += Math.cos(slideAngle) * slideAmount;
        }
    }
    
    // Get visual rendering position (includes wobble offset)
    getVisualPosition() {
        if (this.driftWobbleOffset === 0) {
            return { x: this.x, y: this.y };
        }
        
        const wobbleAngle = this.angle + Math.PI / 2; // Perpendicular to direction
        return {
            x: this.x + Math.sin(wobbleAngle) * this.driftWobbleOffset,
            y: this.y + Math.cos(wobbleAngle) * this.driftWobbleOffset
        };
    }

    updateJump(deltaTime) {
        if (this.isJumping || this.jumpHeight > 0) {
            const dt60 = deltaTime * 60;

            const gravity = 0.3;
            this.jumpVelocity -= gravity * dt60;

            this.jumpHeight += this.jumpVelocity * dt60;

            if (this.jumpHeight <= 0) {
                this.jumpHeight = 0;
                this.jumpVelocity = 0;
                this.isJumping = false;
            }
        }
    }

    triggerBounce(strength = 1.0) {
        this.jumpVelocity = 2.5 * strength;
        this.isJumping = true;
    }

    getJumpHeight() {
        return this.jumpHeight;
    }

    updateWaypointProgress() {
        if (!this.waypoints || this.waypoints.length === 0) return;

        const currentWP = this.waypoints[this.currentWaypointIndex];
        const dxCurrent = currentWP.x - this.x;
        const dyCurrent = currentWP.y - this.y;
        const distanceToCurrent = Math.sqrt(dxCurrent * dxCurrent + dyCurrent * dyCurrent);

        const currentAngle = Math.atan2(dxCurrent, dyCurrent);
        let currentAngleDiff = currentAngle - this.angle;
        while (currentAngleDiff > Math.PI) currentAngleDiff -= 2 * Math.PI;
        while (currentAngleDiff < -Math.PI) currentAngleDiff += 2 * Math.PI;
        
        const waypointIsBehind = Math.abs(currentAngleDiff) > Math.PI / 2;

        // Check for forward checkpoint advancement
        if (distanceToCurrent < 80 || (waypointIsBehind && distanceToCurrent < 150)) {
            this.currentWaypointIndex = (this.currentWaypointIndex + 1) % this.waypoints.length;
        }
        
        // Check for backward checkpoint loss (when reversing)
        // Look at the previous checkpoint
        const prevWaypointIndex = (this.currentWaypointIndex - 1 + this.waypoints.length) % this.waypoints.length;
        const prevWP = this.waypoints[prevWaypointIndex];
        const dxPrev = prevWP.x - this.x;
        const dyPrev = prevWP.y - this.y;
        const distanceToPrev = Math.sqrt(dxPrev * dxPrev + dyPrev * dyPrev);
        
        const prevAngle = Math.atan2(dxPrev, dyPrev);
        let prevAngleDiff = prevAngle - this.angle;
        while (prevAngleDiff > Math.PI) prevAngleDiff -= 2 * Math.PI;
        while (prevAngleDiff < -Math.PI) prevAngleDiff += 2 * Math.PI;
        
        const prevWaypointIsAhead = Math.abs(prevAngleDiff) < Math.PI / 2;
        
        // If going backwards and previous checkpoint is ahead and close, lose a checkpoint
        if (this.speed < -0.5 && prevWaypointIsAhead && distanceToPrev < 60) {
            this.currentWaypointIndex = prevWaypointIndex;
        }
    }

}