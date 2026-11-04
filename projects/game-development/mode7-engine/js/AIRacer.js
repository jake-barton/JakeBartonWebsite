import { Settings } from "./Settings.js";

export class AIRacer {
    constructor(startX, startY, startAngle, waypoints, difficulty = 'medium', maskData = null, character = 'mario') {
        this.x = startX;
        this.y = startY;
        this.angle = startAngle;
        this.speed = 0;
        
        this.character = character;
        
        this.difficulty = difficulty;
        this.setPersonality(difficulty);

        this.waypoints = waypoints;
        
        
        this.currentWaypointIndex = this.findClosestWaypointIndex(startX, startY);
        
        this.lookAheadDistance = 80;
        
        this.maskData = maskData;

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
        this.isOffTrack = false;
        
        this.mistakeTimer = 0;
        this.makingMistake = false;
        this.mistakeDuration = 0;
        this.mistakeType = null;
        
        this.lateralOffset = (Math.random() - 0.5) * this.lateralWander * 2;
        this.offsetChangeTimer = 0;
        
        this.stuckTimer = 0;
        this.lastPosition = { x: startX, y: startY };
        this.stuckRecoveryTimer = 0;
    }

    setPersonality(difficulty) {
        switch(difficulty) {
            case 'easy':
                this.baseSpeed = 1.40; 
                this.steerAccuracy = 0.10;
                this.mistakeFrequency = 0.40;
                this.reactionTime = 0.8;
                this.turnSharpnessMultiplier = 0.6;
                this.lateralWander = 20;
                this.racingLineVariation = 10;
                break;
            case 'medium':
                this.baseSpeed = 0.82;
                this.steerAccuracy = 0.80;
                this.mistakeFrequency = 0.08;
                this.reactionTime = 0.15;
                this.turnSharpnessMultiplier = 0.75;
                this.lateralWander = 1; 
                this.racingLineVariation = 5;
                break;
            case 'hard':
                this.baseSpeed = 0.92;
                this.steerAccuracy = 0.95;
                this.mistakeFrequency = 0.03;
                this.reactionTime = 0.05;
                this.turnSharpnessMultiplier = 0.9;
                this.lateralWander = 1; 
                this.racingLineVariation = 3; 
                break;
            default:
                this.baseSpeed = 0.82;
                this.steerAccuracy = 0.85;
                this.mistakeFrequency = 0.08;
                this.reactionTime = 0.15;
                this.turnSharpnessMultiplier = 0.75;
                this.lateralWander = 1;
                this.racingLineVariation = 10;
        }
        
        this.targetSpeed = Settings.movement.maxSpeed * this.baseSpeed;
        this.steerStrength = 0.015 * this.steerAccuracy;
        
        this.racingLinePreference = (Math.random() - 0.5) * this.racingLineVariation;
    }

    update(deltaTime) {
        if (this.stuckRecoveryTimer > 0) {
            this.stuckRecoveryTimer -= deltaTime;
        }
        
        this.updateMistakes(deltaTime);
        this.updateAIInput();
        this.updateSpeed(deltaTime);
        this.updateRotation(deltaTime);
        this.updateTurnAnimation(deltaTime);
        this.updatePosition(deltaTime);
        this.updateJump(deltaTime);
    }

    updateMistakes(deltaTime) {
        if (this.maskData && !this.isOnTrack(this.x, this.y)) {
            this.lateralOffset *= 0.5; 
            this.isOffTrack = true;
        } else {
            this.isOffTrack = false;
        }
        
        this.offsetChangeTimer += deltaTime;
        if (this.offsetChangeTimer > 5.0) { 
            if (!this.isOffTrack) {
                const changeAmount = (Math.random() - 0.5) * (this.lateralWander * 0.2);
                this.lateralOffset = this.lateralOffset * 0.8 + changeAmount * 0.2; 
                this.lateralOffset = Math.max(-this.lateralWander, Math.min(this.lateralWander, this.lateralOffset));
            }
            this.offsetChangeTimer = 0;
        }
        
        if (this.makingMistake) {
            this.mistakeDuration -= deltaTime;
            if (this.mistakeDuration <= 0) {
                this.makingMistake = false;
                this.mistakeType = null;
            }
            return;
        }
        
        this.mistakeTimer += deltaTime;
        if (this.mistakeTimer > 1.0) {
            if (Math.random() < this.mistakeFrequency) {
                this.makingMistake = true;
                this.mistakeDuration = 0.3 + Math.random() * 0.7; 
                
                const rand = Math.random();
                if (rand < 0.4) {
                    this.mistakeType = 'understeer';
                } else if (rand < 0.7) {
                    this.mistakeType = 'oversteer';
                } else {
                    this.mistakeType = 'brake';
                }
            }
            this.mistakeTimer = 0;
        }
    }

    updateAIInput() {
        // Check for walls ahead and apply emergency steering
        let wallAvoidanceAngle = 0;
        let hasWallAhead = false;
        
        if (this.maskData) {
            // Check multiple points ahead
            for (let checkDist = 15; checkDist <= 40; checkDist += 10) {
                const checkX = this.x + Math.sin(this.angle) * checkDist;
                const checkY = this.y + Math.cos(this.angle) * checkDist;
                const pixelType = this.getPixelType(checkX, checkY);
                
                if (pixelType === 'wall') {
                    hasWallAhead = true;
                    
                    // Check left and right to find escape direction
                    const leftCheckX = this.x + Math.sin(this.angle - Math.PI / 4) * 30;
                    const leftCheckY = this.y + Math.cos(this.angle - Math.PI / 4) * 30;
                    const rightCheckX = this.x + Math.sin(this.angle + Math.PI / 4) * 30;
                    const rightCheckY = this.y + Math.cos(this.angle + Math.PI / 4) * 30;
                    
                    const leftClear = this.getPixelType(leftCheckX, leftCheckY) !== 'wall';
                    const rightClear = this.getPixelType(rightCheckX, rightCheckY) !== 'wall';
                    
                    if (leftClear && !rightClear) {
                        wallAvoidanceAngle = 0.3; // Steer left
                    } else if (rightClear && !leftClear) {
                        wallAvoidanceAngle = -0.3; // Steer right
                    } else if (leftClear && rightClear) {
                        // Both clear, pick one randomly (but consistently per AI)
                        wallAvoidanceAngle = (this.x % 2 === 0) ? 0.3 : -0.3;
                    }
                    break;
                }
            }
        }
        
        let targetWaypointIndex = this.currentWaypointIndex;
        let lookAheadCount = 1;
        
        
        const currentWP = this.waypoints[this.currentWaypointIndex];
        const dxCurrent = currentWP.x - this.x;
        const dyCurrent = currentWP.y - this.y;
        const distanceToCurrent = Math.sqrt(dxCurrent * dxCurrent + dyCurrent * dyCurrent);
        
        if (distanceToCurrent < 60) {
            lookAheadCount = 2;
        }
        
        targetWaypointIndex = (this.currentWaypointIndex + lookAheadCount - 1) % this.waypoints.length;
        const targetWaypoint = this.waypoints[targetWaypointIndex];

        const offsetAngle = Math.atan2(dxCurrent, dyCurrent) + Math.PI / 2;
        
        let targetX, targetY;
        
        if (this.isOffTrack) {
            targetX = targetWaypoint.x;
            targetY = targetWaypoint.y;
            this.lateralOffset *= 0.1;
        } else {
            const totalOffset = this.lateralOffset + this.racingLinePreference;
            
            targetX = targetWaypoint.x + Math.sin(offsetAngle) * totalOffset;
            targetY = targetWaypoint.y + Math.cos(offsetAngle) * totalOffset;
            
            let shouldAvoidPath = false;
            for (let checkDist = 10; checkDist <= 25; checkDist += 5) {
                const checkX = this.x + Math.sin(this.angle) * checkDist;
                const checkY = this.y + Math.cos(this.angle) * checkDist;
                
                if (this.maskData && !this.isOnTrack(checkX, checkY)) {
                    shouldAvoidPath = true;
                    break;
                }
            }
            
            if (shouldAvoidPath) {
                this.lateralOffset *= 0.2;
                targetX = targetWaypoint.x;
                targetY = targetWaypoint.y;
            }
            else if (this.maskData && !this.isOnTrack(targetX, targetY)) {
                targetX = targetWaypoint.x;
                targetY = targetWaypoint.y;
                this.lateralOffset *= 0.1;
            }
        }

        const dx = targetX - this.x;
        const dy = targetY - this.y;
        const distanceToTarget = Math.sqrt(dx * dx + dy * dy);
        
        
        let targetAngle = Math.atan2(dx, dy);
        
        if (hasWallAhead && this.stuckRecoveryTimer <= 0) {
            targetAngle += wallAvoidanceAngle;
        }
        
        const steeringNoise = (Math.random() - 0.5) * (1.0 - this.steerAccuracy) * 0.5;
        targetAngle += steeringNoise;

        
        let angleDiff = targetAngle - this.angle;
        while (angleDiff > Math.PI) angleDiff -= 2 * Math.PI;
        while (angleDiff < -Math.PI) angleDiff += 2 * Math.PI;

        
        angleDiff *= this.turnSharpnessMultiplier;

        
        if (this.makingMistake) {
            if (this.mistakeType === 'understeer') {
                angleDiff *= 0.3;
            } else if (this.mistakeType === 'oversteer') {
                angleDiff *= 1.8;
            }
        }

        
        const turnSharpness = Math.abs(angleDiff);
    const speedVariation = 0.9 + Math.random() * 0.2;
        
        if (this.makingMistake && this.mistakeType === 'brake') {
            this.targetSpeed = Settings.movement.maxSpeed * this.baseSpeed * 0.4;
        } else if (turnSharpness > 1.2) {
            this.targetSpeed = Settings.movement.maxSpeed * this.baseSpeed * 0.6 * speedVariation;
        } else if (turnSharpness > 0.8) {
            this.targetSpeed = Settings.movement.maxSpeed * this.baseSpeed * 0.75 * speedVariation;
        } else if (turnSharpness > 0.4) {
            this.targetSpeed = Settings.movement.maxSpeed * this.baseSpeed * 0.88 * speedVariation;
        } else {
            this.targetSpeed = Settings.movement.maxSpeed * this.baseSpeed * speedVariation;
        }

        
        const turnThreshold = 0.05 + (1.0 - this.steerAccuracy) * 0.05;
        if (Math.abs(angleDiff) > turnThreshold) {
            if (angleDiff > 0) {
                this.aiTurnDirection = -1;
            } else {
                this.aiTurnDirection = 1;
            }
            
            
            this.aiTurnIntensity = Math.min(Math.abs(angleDiff) / (Math.PI / 2), 1.0);
        } else {
            this.aiTurnDirection = 0;
            this.aiTurnIntensity = 0;
        }

        
        const currentAngle = Math.atan2(dxCurrent, dyCurrent);
        let currentAngleDiff = currentAngle - this.angle;
        while (currentAngleDiff > Math.PI) currentAngleDiff -= 2 * Math.PI;
        while (currentAngleDiff < -Math.PI) currentAngleDiff += 2 * Math.PI;
        
        const waypointIsBehind = Math.abs(currentAngleDiff) > Math.PI / 2;
        
        if (distanceToCurrent < 40 || (waypointIsBehind && distanceToCurrent < 80)) {
            this.currentWaypointIndex = (this.currentWaypointIndex + 1) % this.waypoints.length;
        }
    }

    updateSpeed(deltaTime) {
        const dt60 = deltaTime * 60;
        
        if (this.isOffTrack) {
            this.speed *= Math.pow(Settings.movement.offTrackDrag, dt60);
        }
        
        if (this.speed < this.targetSpeed) {
            this.speed += Settings.movement.acceleration * dt60;
            if(this.speed > this.targetSpeed) {
                this.speed = this.targetSpeed;
            }
        } else if (this.speed > this.targetSpeed) {
            this.speed -= Settings.movement.deceleration * dt60;
            if(this.speed < this.targetSpeed) {
                this.speed = this.targetSpeed;
            }
        }
    }

    updateRotation(deltaTime) {
        const dt60 = deltaTime * 60;

        if (this.aiTurnDirection !== 0 && Math.abs(this.speed) > Settings.movement.minSpeedToTurn) {
            
            const intensityMultiplier = 0.5 + (this.aiTurnIntensity || 1.0) * 0.5;
            
            if (this.lastTurnDirection !== 0 && this.lastTurnDirection !== this.aiTurnDirection) {
                this.currentTurnSpeed = 0;
                this.turnTime = 0;
                this.isDrifting = false;
                this.driftDirection = 0;
            }

            this.lastTurnDirection = this.aiTurnDirection;

            this.currentTurnSpeed += Settings.movement.turnAcceleration * dt60;
            if (this.currentTurnSpeed > Settings.movement.baseTurnSpeed * intensityMultiplier) {
                this.currentTurnSpeed = Settings.movement.baseTurnSpeed * intensityMultiplier;
            }

            this.turnTime += deltaTime;

            if (this.turnTime >= Settings.movement.driftThreshold) {
                this.isDrifting = true;
                this.driftDirection = this.aiTurnDirection;
            }

            if (this.aiTurnDirection < 0) {
                this.angle += this.currentTurnSpeed;
            } else {
                this.angle -= this.currentTurnSpeed;
            }

            this.speed *= Math.pow(Settings.movement.turnDrag, dt60);
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
    }

    updateTurnAnimation(deltaTime) {
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

    updatePosition() {
        const prevX = this.x;
        const prevY = this.y;
        
        this.x += Math.sin(this.angle) * this.speed;
        this.y += Math.cos(this.angle) * this.speed;
        
        if (this.maskData) {
            const pixelType = this.getPixelType(this.x, this.y);
            
            if (pixelType === 'wall' || pixelType === 'outOfBounds') {
                this.x = prevX;
                this.y = prevY;
                this.speed = -Math.abs(this.speed) * 0.5; 
                this.triggerBounce(2);
                
                if (this.stuckTimer > 0.3) {
                    this.stuckRecoveryTimer = 0.5;
                    this.angle += (Math.random() > 0.5 ? 0.3 : -0.3);
                }
            }
            else if (pixelType === 'offTrack') {
                this.isOffTrack = true;
            } else {
                this.isOffTrack = false;
            }
        }
        
        if (Math.abs(this.speed) > 0.3) {
            const distMoved = Math.sqrt((this.x - this.lastPosition.x) ** 2 + (this.y - this.lastPosition.y) ** 2);
            if (distMoved < 0.5) {
                this.stuckTimer += 0.016;
                if (this.stuckTimer > 1.5) {
                    this.angle += Math.PI / 2;
                    this.speed = this.baseSpeed;
                    this.stuckTimer = 0;
                    this.stuckRecoveryTimer = 1.0;
                }
            } else {
                this.stuckTimer = Math.max(0, this.stuckTimer - 0.016);
            }
        } else {
            this.stuckTimer = 0;
        }
        
        this.lastPosition.x = this.x;
        this.lastPosition.y = this.y;
    }

    updateJump(deltaTime) {
        if (this.isJumping || this.jumpHeight > 0) {
            const dt60 = deltaTime * 60;
            const gravity = 0.03;
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

    getAnimationFrame() {
        return Math.floor(this.animationFrame);
    }

    getAnimationDirection() {
        return this.animationDirection;
    }
    
    isOnTrack(x, y) {
        if (!this.maskData) return true;
        
        const maskX = Math.floor(x);
        const maskY = Math.floor(y);
        
        if (maskX < 0 || maskX >= this.maskData.width || 
            maskY < 0 || maskY >= this.maskData.height) {
            return false;
        }
        
        const index = (maskY * this.maskData.width + maskX) * 4;
        const r = this.maskData.data[index];
        const g = this.maskData.data[index + 1];
        const b = this.maskData.data[index + 2];
        
        
        return r > 200 && g > 200 && b > 200;
    }
    
    getPixelType(x, y) {
        if (!this.maskData) return 'road';
        
        const maskX = Math.floor(x);
        const maskY = Math.floor(y);
        
        if (maskX < 0 || maskX >= this.maskData.width || 
            maskY < 0 || maskY >= this.maskData.height) {
            return 'outOfBounds';
        }
        
        const index = (maskY * this.maskData.width + maskX) * 4;
        const r = this.maskData.data[index];
        const g = this.maskData.data[index + 1];
        const b = this.maskData.data[index + 2];
        
        if (r < 50 && g < 50 && b > 200) return 'wall'; 
        if (r > 200 && g > 200 && b > 200) return 'road'; 
        if (r < 50 && g < 50 && b < 50) return 'offTrack'; 
        
        return 'track'; 
    }
    
    findClosestWaypointIndex(x, y) {
        let closestIndex = 0;
        let closestDistance = Infinity;
        
        for (let i = 0; i < this.waypoints.length; i++) {
            const wp = this.waypoints[i];
            const dx = wp.x - x;
            const dy = wp.y - y;
            const distance = Math.sqrt(dx * dx + dy * dy);
            
            const angleToWaypoint = Math.atan2(dx, dy);
            let angleDiff = angleToWaypoint - this.angle;
            while (angleDiff > Math.PI) angleDiff -= 2 * Math.PI;
            while (angleDiff < -Math.PI) angleDiff += 2 * Math.PI;
            
            const isAhead = Math.abs(angleDiff) < Math.PI / 2;
            
            if (isAhead && distance < closestDistance) {
                closestDistance = distance;
                closestIndex = i;
            }
        }
        
        return closestIndex;
    }
}