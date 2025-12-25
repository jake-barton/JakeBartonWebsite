import { Settings } from "./Settings.js";

export class AIRacer {
    constructor(startX, startY, startAngle, waypoints, maskData = null, character = 'mario') {
        this.x = startX;
        this.y = startY;
        this.angle = startAngle;
        this.speed = 0;
        
        this.character = character;

        this.waypoints = waypoints;
        
        // Use AI settings from Settings with randomization for variety
        const speedVar = 1 + (Math.random() - 0.5) * Settings.ai.speedVariation;
        const accuracyVar = 1 + (Math.random() - 0.5) * Settings.ai.accuracyVariation;
        
        this.baseSpeed = Settings.ai.baseSpeed * speedVar;
        this.steerAccuracy = Math.max(0.1, Math.min(0.95, Settings.ai.steerAccuracy * accuracyVar));
        this.mistakeFrequency = Settings.ai.mistakeFrequency;
        this.reactionTime = Settings.ai.reactionTime;
        this.turnSharpnessMultiplier = Settings.ai.turnSharpness;
        this.lateralWander = Settings.ai.lateralWander;
        this.racingLineVariation = Settings.ai.racingLineVariation;
        
        this.targetSpeed = Settings.movement.maxSpeed * this.baseSpeed;
        this.steerStrength = 0.015 * this.steerAccuracy;
        this.racingLinePreference = (Math.random() - 0.5) * this.racingLineVariation;
        
        this.currentWaypointIndex = this.findClosestWaypointIndex(startX, startY);
        
        this.lookAheadDistance = 80;
        
        this.maskData = maskData;
        
        // Reference to other racers for collision avoidance
        this.otherRacers = [];

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
        
        // Item effects
        this.starPowerActive = false;
        this.starPowerTimer = 0;
        this.starPowerDuration = 7.5; // SNES: 450 frames at 60fps = 7.5 seconds
        this.starSpeedMultiplier = 1.5; // Good speed boost without wall clipping (reduced from 1.8)
        
        // Lightning victim
        this.isLightningVictim = false;
        this.lightningTimer = 0;
        this.lightningDuration = 5.0;
        
        // Item storage (for ghost steal)
        this.hasItem = false;
        this.currentItem = null;
        
        // Hit state
        this.isHit = false;
        this.hitTimer = 0;
        this.hitDuration = 2.0; // 2 seconds of hit state
        this.hitSpinRotation = 0; // For 360 spin animation
        this.hitSpinSpeed = 0; // Spin speed during hit
    }
    
    setOtherRacers(racers) {
        this.otherRacers = racers;
    }

    update(deltaTime) {
        if (this.stuckRecoveryTimer > 0) {
            this.stuckRecoveryTimer -= deltaTime;
        }
        
        this.updateMistakes(deltaTime);
        this.updateItemEffects(deltaTime);
        this.updateHitState(deltaTime);
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
        // Don't allow input during hit state
        if (this.isHit) {
            this.targetSpeed = 0;
            return;
        }
        
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
        
        // AI collision avoidance - steer away from nearby racers
        let racerAvoidanceAngle = 0;
        for (const otherRacer of this.otherRacers) {
            if (otherRacer === this) continue;
            
            const dxRacer = otherRacer.x - this.x;
            const dyRacer = otherRacer.y - this.y;
            const distanceToRacer = Math.sqrt(dxRacer * dxRacer + dyRacer * dyRacer);
            
            // Check if racer is close enough to avoid
            if (distanceToRacer < Settings.ai.avoidanceDistance && distanceToRacer > 0.1) {
                // Check if racer is ahead of us
                const angleToRacer = Math.atan2(dxRacer, dyRacer);
                let racerAngleDiff = angleToRacer - this.angle;
                while (racerAngleDiff > Math.PI) racerAngleDiff -= 2 * Math.PI;
                while (racerAngleDiff < -Math.PI) racerAngleDiff += 2 * Math.PI;
                
                // Only avoid if racer is in front (within 120 degrees)
                if (Math.abs(racerAngleDiff) < Math.PI * 0.66) {
                    // Steer away from the racer
                    const avoidanceStrength = Settings.ai.avoidanceStrength * 
                                             (1 - distanceToRacer / Settings.ai.avoidanceDistance);
                    
                    // Steer perpendicular to the direction of the other racer
                    racerAvoidanceAngle += (racerAngleDiff > 0 ? -avoidanceStrength : avoidanceStrength);
                    
                    // Slow down if very close
                    if (distanceToRacer < Settings.ai.minimumSeparation) {
                        this.targetSpeed *= 0.7;
                    }
                }
            }
        }
        
        targetAngle += racerAvoidanceAngle;
        
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
        // Don't update speed during hit state - let hit state handle deceleration
        if (this.isHit) {
            return;
        }
        
        const dt60 = deltaTime * 60;
        
        if (this.isOffTrack) {
            this.speed *= Math.pow(Settings.movement.offTrackDrag, dt60);
        }
        
        // Apply speed multipliers
        let speedMultiplier = 1.0;
        
        // Star power: 33% speed boost
        if (this.starPowerActive) {
            speedMultiplier *= this.starSpeedMultiplier;
        }
        
        // Lightning victim: 50% speed reduction
        if (this.isLightningVictim) {
            speedMultiplier *= 0.5;
        }
        
        let effectiveTargetSpeed = this.targetSpeed * speedMultiplier;
        
        if (this.speed < effectiveTargetSpeed) {
            this.speed += Settings.movement.acceleration * dt60;
            if(this.speed > effectiveTargetSpeed) {
                this.speed = effectiveTargetSpeed;
            }
        } else if (this.speed > effectiveTargetSpeed) {
            this.speed -= Settings.movement.deceleration * dt60;
            if(this.speed < effectiveTargetSpeed) {
                this.speed = effectiveTargetSpeed;
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

    updatePosition(deltaTime) {
        const dt60 = deltaTime * 60;
        const prevX = this.x;
        const prevY = this.y;
        
        this.x += Math.sin(this.angle) * this.speed * dt60;
        this.y += Math.cos(this.angle) * this.speed * dt60;
        
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
                this.stuckTimer += deltaTime;
                if (this.stuckTimer > 1.5) {
                    this.angle += Math.PI / 2;
                    this.speed = this.baseSpeed;
                    this.stuckTimer = 0;
                    this.stuckRecoveryTimer = 1.0;
                }
            } else {
                this.stuckTimer = Math.max(0, this.stuckTimer - deltaTime);
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
    updateItemEffects(deltaTime) {
        // Update star power
        if (this.starPowerActive) {
            this.starPowerTimer += deltaTime;
            if (this.starPowerTimer >= this.starPowerDuration) {
                this.starPowerActive = false;
                this.starPowerTimer = 0;
            }
        }
        
        // Update lightning victim effect
        if (this.isLightningVictim) {
            this.lightningTimer += deltaTime;
            if (this.lightningTimer >= this.lightningDuration) {
                this.isLightningVictim = false;
                this.lightningTimer = 0;
            }
        }
    }
    
    updateHitState(deltaTime) {
        if (this.isHit) {
            this.hitTimer += deltaTime;
            
            // Update spin rotation (complete 360 degree spin)
            this.hitSpinRotation += this.hitSpinSpeed * deltaTime;
            
            // Stop spinning after one full rotation (2π radians)
            if (this.hitSpinRotation >= Math.PI * 2) {
                this.hitSpinRotation = Math.PI * 2;
                this.hitSpinSpeed = 0;
            }
            
            // Decelerate faster during hit
            const dt60 = deltaTime * 60;
            this.speed *= Math.pow(0.85, dt60); // Faster deceleration than normal friction
            
            if (Math.abs(this.speed) < 0.01) {
                this.speed = 0;
            }
            
            // End hit state after duration
            if (this.hitTimer >= this.hitDuration) {
                this.isHit = false;
                this.hitTimer = 0;
                this.hitSpinRotation = 0;
                this.hitSpinSpeed = 0;
            }
        }
    }
    
    triggerHit() {
        // Don't get hit if star power is active (invincible)
        if (this.starPowerActive) {
            return false;
        }
        
        this.isHit = true;
        this.hitTimer = 0;
        this.hitSpinRotation = 0;
        this.hitSpinSpeed = Math.PI * 2; // One full rotation per second
        // No bounce - just spin in place
        return true;
    }
    
    triggerLightningHit() {
        // Lightning hit - very small bounce
        if (this.starPowerActive) {
            return false;
        }
        
        this.isHit = true;
        this.hitTimer = 0;
        this.hitSpinRotation = 0;
        this.hitSpinSpeed = Math.PI * 2; // One full rotation per second
        this.triggerBounce(0.15); // Minimal bounce for lightning (reduced from 0.3)
        return true;
    }
    
    receiveItem(item) {
        this.hasItem = true;
        this.currentItem = item;
    }
    
    useItem() {
        if (!this.hasItem || !this.currentItem) return null;
        
        const itemUsed = this.currentItem;
        
        // Activate item effects
        switch(this.currentItem) {
            case 'star':
                this.starPowerActive = true;
                this.starPowerTimer = 0;
                break;
            case 'mushroom':
                // AI mushroom boost (simple speed increase)
                this.speed = Math.min(this.speed * 1.5, this.targetSpeed * 1.8);
                break;
            case 'coin':
                // AI coin - just adds to coin count (visual effect handled by RaceScreen if needed)
                // For now, just consume the item
                break;
            case 'feather':
                // AI feather - disabled (AIs don't jump)
                // Just consume the item without jumping
                break;
            case 'lightning':
                // Lightning will be handled by RaceScreen, just mark item as used
                break;
            case 'bannana':
                // Banana will be placed by RaceScreen, just mark item as used
                break;
            case 'green_shell':
                // Green shell will be thrown by RaceScreen, just mark item as used
                break;
            case 'red_shell':
                // Red shell will be thrown by RaceScreen
                break;
        }
        
        // Clear item after use (banana/shell/lightning clears after activation in RaceScreen)
        if (!['bannana', 'green_shell', 'red_shell', 'lightning'].includes(this.currentItem)) {
            this.hasItem = false;
            this.currentItem = null;
        }
        
        return itemUsed;
    }
    
}


