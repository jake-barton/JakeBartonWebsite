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
        this.shiftWasPressed = false;
        
        this.currentWaypointIndex = 0;
        this.waypoints = null;
        
        this.driftWobbleTime = 0;
        this.driftWobbleOffset = 0;
        
        this.victoryMode = false;
        this.celebrating = false;
        
        // Item system
        this.hasItem = false;
        this.currentItem = null;
        this.isRouletteActive = false;
        this.rouletteTimer = 0;
        this.rouletteDuration = 1.5; // 1.5 seconds of cycling
        
        // Item effects
        this.starPowerActive = false;
        this.starPowerTimer = 0;
        this.starPowerDuration = 7.5; // SNES: 450 frames at 60fps = 7.5 seconds
        this.starSpeedMultiplier = 1.5; // Good speed boost without wall clipping (reduced from 1.8)
        
        // Mushroom boost
        this.mushroomBoostActive = false;
        this.mushroomBoostTimer = 0;
        this.mushroomBoostDuration = 0.8; // Short burst
        this.mushroomBoostSpeed = 1.8; // Impulse speed (reduced from 2.2 to prevent wall clipping)
        
        // Lightning effect
        this.lightningActive = false;
        this.lightningTimer = 0;
        this.lightningDuration = 5.0; // 5 seconds shrunk and slower
        this.isLightningVictim = false; // Set by external lightning
        this.lightningTriggered = false; // Track if lightning has been triggered in RaceScreen
        
        // Ghost steal
        this.ghostStealActive = false;
        this.ghostStealTimer = 0;
        this.ghostStealDuration = 0.5; // Flash duration
        this.ghostStealTriggered = false; // Track if ghost has been triggered in RaceScreen
        
        // Coin collection
        this.coinCount = 0;
        this.coinAnimationActive = false;
        this.coinAnimationTimer = 0;
        this.coinAnimationDuration = 0.6; // Faster: 0.6 seconds (reduced from 1.0)
        this.coinBounceHeight = 0; // Height above player for bounce animation
        this.coinSpinRotation = 0; // Rotation angle for spin animation
        
        // Feather jump
        this.featherJumpActive = false;
        this.featherSpinRotation = 0;
        this.featherSpinSpeed = 0;
        
        // Hit state
        this.isHit = false;
        this.hitTimer = 0;
        this.hitDuration = 2.0; // 2 seconds of hit state
        this.hitSpinRotation = 0; // For 360 spin animation
        this.hitSpinSpeed = 0; // Spin speed during hit
    }

    update(keys, deltaTime) {
        if (!this.victoryMode) {
            this.handleInput(keys, deltaTime);
        }
        this.updateItemEffects(deltaTime);
        this.updateHitState(deltaTime);
        this.updateSpeed(deltaTime);
        this.updateRotation(keys, deltaTime);
        this.updateTurnAnimation(deltaTime);
        this.updateDriftWobble(deltaTime);
        this.updatePosition(deltaTime);
        this.updateJump(deltaTime);
        this.updateWaypointProgress();
    }

    handleInput(keys) {
        // Don't allow input during hit state
        if (this.isHit) {
            this.targetSpeed = 0;
            return;
        }
        
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
        
        // Use item with Shift key
        if (keys['Shift'] && this.hasItem && !this.shiftWasPressed) {
            this.useItem();
        }
        this.shiftWasPressed = keys['Shift'];
    }

    updateSpeed(deltaTime) {
        // Don't update speed during hit state - let hit state handle deceleration
        if (this.isHit) {
            return;
        }
        
        const dt60 = deltaTime * 60;
        
        // Apply speed boosts
        let speedMultiplier = 1.0;
        let effectiveMaxSpeed = Settings.movement.maxSpeed;
        
        // Star power: 50% speed boost with instant acceleration
        if (this.starPowerActive) {
            speedMultiplier *= this.starSpeedMultiplier;
            effectiveMaxSpeed *= this.starSpeedMultiplier;
            // Give instant boost when star is activated
            if (this.speed < effectiveMaxSpeed * 0.8) {
                this.speed = Math.max(this.speed, effectiveMaxSpeed * 0.8);
            }
        }
        
        // Mushroom boost: Override with boost speed
        if (this.mushroomBoostActive) {
            this.speed = this.mushroomBoostSpeed;
            return; // Skip normal speed updates during mushroom boost
        }
        
        // Lightning victim: 50% speed reduction
        if (this.isLightningVictim) {
            speedMultiplier *= 0.5;
            effectiveMaxSpeed *= 0.5;
        }
        
        // Apply multiplier to target speed
        let adjustedTargetSpeed = this.targetSpeed * speedMultiplier;
        adjustedTargetSpeed = Math.min(adjustedTargetSpeed, effectiveMaxSpeed);
        
        if (this.speed < adjustedTargetSpeed) {
            this.speed += Settings.movement.acceleration * dt60;

            if (this.speed > adjustedTargetSpeed) {
                this.speed = adjustedTargetSpeed;
            }
        }
        else if (this.speed > adjustedTargetSpeed) {
            this.speed -= Settings.movement.deceleration * dt60;

            if (this.speed < adjustedTargetSpeed) {
                this.speed = adjustedTargetSpeed;
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

    updatePosition(deltaTime) {
        const dt60 = deltaTime * 60;
        
        this.x += Math.sin(this.angle) * this.speed * dt60;
        this.y += Math.cos(this.angle) * this.speed * dt60;

        if (this.isDrifting) {
            const slideDirection = this.driftDirection;
            const slideAmount = Settings.movement.driftSlide * Math.abs(this.speed);

            const slideAngle = this.angle + (Math.PI / 2) * slideDirection;
            this.x += Math.sin(slideAngle) * slideAmount * dt60;
            this.y += Math.cos(slideAngle) * slideAmount * dt60;
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

    startItemRoulette() {
        this.isRouletteActive = true;
        this.rouletteTimer = 0;
        this.hasItem = false;
        this.currentItem = null;
        // Pre-select the final item randomly at the start
        this.finalItem = null;
    }

    updateItemRoulette(deltaTime, availableItems, placement = 5) {
        if (!this.isRouletteActive) return null;
        
        this.rouletteTimer += deltaTime;
        
        // Filter items based on placement
        const placementItems = this.getItemsByPlacement(availableItems, placement);
        
        // Cycle through items quickly for visual effect
        const cycleSpeed = 12; // cycles per second
        const itemIndex = Math.floor(this.rouletteTimer * cycleSpeed) % placementItems.length;
        const currentRouletteItem = placementItems[itemIndex];
        
        // End roulette after duration
        if (this.rouletteTimer >= this.rouletteDuration) {
            this.isRouletteActive = false;
            this.hasItem = true;
            // Select truly random item from placement-appropriate items
            const randomIndex = Math.floor(Math.random() * placementItems.length);
            this.currentItem = placementItems[randomIndex];
            return this.currentItem;
        }
        
        return currentRouletteItem;
    }
    
    getItemsByPlacement(availableItems, placement) {
        // 1st place: coins, green_shell, feather, banana
        if (placement === 1) {
            return availableItems.filter(item => 
                ['coin', 'green_shell', 'feather', 'bannana'].includes(item)
            );
        }
        // 2nd-5th place: everything except lightning and star
        else if (placement >= 2 && placement <= 5) {
            return availableItems.filter(item => 
                !['lightning', 'star'].includes(item)
            );
        }
        // 6th-7th place: no lightning (make it rare)
        else if (placement >= 6 && placement <= 7) {
            return availableItems.filter(item => item !== 'lightning');
        }
        // 8th place: can get everything including lightning
        else {
            return availableItems;
        }
    }

    getItemForDisplay() {
        return this.currentItem || 'blank';
    }
    
    useItem() {
        if (!this.hasItem || !this.currentItem) return;
        
        switch(this.currentItem) {
            case 'star':
                this.activateStarPower();
                break;
            case 'mushroom':
                this.activateMushroomBoost();
                break;
            case 'ghost':
                this.activateGhost();
                break;
            case 'lightning':
                this.activateLightning();
                break;
            case 'coin':
                this.activateCoin();
                break;
            case 'feather':
                this.activateFeather();
                break;
            case 'bannana':
                this.placeBanana = true; // Signal to RaceScreen to place banana
                break;
            case 'green_shell':
                this.throwGreenShell = true; // Signal to RaceScreen to throw shell
                break;
            case 'red_shell':
                this.throwRedShell = true; // Signal to RaceScreen to throw homing red shell
                break;
        }
        
        // Clear item after use (except banana/shell - cleared after placement/throwing)
        if (this.currentItem !== 'bannana' && this.currentItem !== 'green_shell' && this.currentItem !== 'red_shell') {
            this.hasItem = false;
            this.currentItem = null;
        }
    }
    
    activateStarPower() {
        this.starPowerActive = true;
        this.starPowerTimer = 0;
    }
    
    activateMushroomBoost() {
        this.mushroomBoostActive = true;
        this.mushroomBoostTimer = 0;
    }
    
    activateGhost() {
        this.ghostStealActive = true;
        this.ghostStealTimer = 0;
        this.ghostStealTriggered = false; // Reset trigger flag
        // Actual stealing happens in RaceScreen
    }
    
    activateLightning() {
        this.lightningActive = true;
        this.lightningTimer = 0;
        this.lightningTriggered = false; // Reset trigger flag
        // Actual lightning effect happens in RaceScreen
    }
    
    activateCoin() {
        this.coinAnimationActive = true;
        this.coinAnimationTimer = 0;
        this.coinBounceHeight = 0;
        this.coinSpinRotation = 0;
        this.coinCount++;
    }
    
    activateFeather() {
        this.featherJumpActive = true;
        this.featherSpinRotation = 0;
        this.featherSpinSpeed = Math.PI * 4; // Two full rotations per second
        this.triggerBounce(2.2); // High jump (reduced from 3.5)
    }
    
    updateItemEffects(deltaTime) {
        // Update star power
        if (this.starPowerActive) {
            this.starPowerTimer += deltaTime;
            if (this.starPowerTimer >= this.starPowerDuration) {
                this.starPowerActive = false;
                this.starPowerTimer = 0;
                // Clear item box when star power ends
                this.hasItem = false;
                this.currentItem = null;
            }
        }
        
        // Update mushroom boost
        if (this.mushroomBoostActive) {
            this.mushroomBoostTimer += deltaTime;
            if (this.mushroomBoostTimer >= this.mushroomBoostDuration) {
                this.mushroomBoostActive = false;
                this.mushroomBoostTimer = 0;
            }
        }
        
        // Update lightning effect on victim
        if (this.isLightningVictim) {
            this.lightningTimer += deltaTime;
            if (this.lightningTimer >= this.lightningDuration) {
                this.isLightningVictim = false;
                this.lightningTimer = 0;
            }
        }
        
        // Update ghost steal flash
        if (this.ghostStealActive) {
            this.ghostStealTimer += deltaTime;
            if (this.ghostStealTimer >= this.ghostStealDuration) {
                this.ghostStealActive = false;
                this.ghostStealTimer = 0;
            }
        }
        
        // Update coin animation
        if (this.coinAnimationActive) {
            this.coinAnimationTimer += deltaTime;
            
            // Bounce animation: parabolic arc up and down
            const progress = this.coinAnimationTimer / this.coinAnimationDuration;
            // Parabola: peaks at 0.5, returns to 0 at 1.0
            this.coinBounceHeight = 30 * Math.sin(progress * Math.PI); // Max height of 30 units (reduced from 40)
            
            // Spin animation: 3 full rotations during the animation (faster)
            this.coinSpinRotation = progress * Math.PI * 6; // 6π = 3 full rotations
            
            if (this.coinAnimationTimer >= this.coinAnimationDuration) {
                this.coinAnimationActive = false;
                this.coinAnimationTimer = 0;
                this.coinBounceHeight = 0;
                this.coinSpinRotation = 0;
            }
        }
        
        // Update feather jump spin
        if (this.featherJumpActive) {
            this.featherSpinRotation += this.featherSpinSpeed * deltaTime;
            // End when jump completes
            if (this.jumpHeight <= 0 && !this.isJumping) {
                this.featherJumpActive = false;
                this.featherSpinRotation = 0;
                this.featherSpinSpeed = 0;
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
        this.triggerBounce(1.5);
        return true;
    }
    
    // Alias for hit state (used by banana collisions)
    hit() {
        return this.triggerHit();
    }
    
    // Lightning hit - smaller bounce than regular hit
    triggerLightningHit() {
        // Don't get hit if star power is active (invincible)
        if (this.starPowerActive) {
            return false;
        }
        
        this.isHit = true;
        this.hitTimer = 0;
        this.hitSpinRotation = 0;
        this.hitSpinSpeed = Math.PI * 2; // One full rotation per second
        this.triggerBounce(0.15); // Minimal bounce for lightning (same as AI)
        return true;
    }
    
    getRainbowColor() {
        // Cycle through rainbow colors based on timer
        // SNES uses: Red -> Orange -> Yellow -> Green -> Blue -> Purple
        const cycleSpeed = 3; // 3 cycles per second
        const hue = (this.starPowerTimer * cycleSpeed * 360) % 360;
        return `hsl(${hue}, 100%, 50%)`;
    }
    
    getCoinBounceHeight() {
        return this.coinBounceHeight;
    }
    
    getCoinSpinRotation() {
        return this.coinSpinRotation;
    }

}