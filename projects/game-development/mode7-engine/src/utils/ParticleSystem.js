import { PARTICLE_SPRITES } from '../data/particle_sprites.js';

export class Particle {
    constructor(x, y, velocityX, velocityY, sprites, lifetime = 0.3, animationSpeed = 0.1, flipHorizontal = false, screenSpaceVelocity = false) {
        this.x = x;
        this.y = y;
        this.z = 0; // Height/bounce (separate from physics-based height)
        this.velocityX = velocityX;
        this.velocityY = velocityY;
        this.sprites = Array.isArray(sprites) ? sprites : [sprites]; // Support multiple sprites for animation
        this.lifetime = lifetime;
        this.maxLifetime = lifetime;
        this.height = 0;
        this.verticalVelocity = 0;
        this.active = true;
        this.flipHorizontal = flipHorizontal; // Whether to flip sprite horizontally
        this.screenSpaceVelocity = screenSpaceVelocity; // Whether velocity is in screen space (not world space)
        
        // Screen space offset (accumulated over time)
        this.screenOffsetX = 0;
        this.screenOffsetY = 0;
        
        // Animation properties
        this.animationSpeed = animationSpeed;
        this.animationTimer = 0;
        this.currentFrame = 0;
    }

    update(deltaTime) {
        // Move particle
        if (!this.screenSpaceVelocity) {
            // World space velocity
            this.x += this.velocityX * deltaTime * 60;
            this.y += this.velocityY * deltaTime * 60;
        } else {
            // Screen space velocity - accumulate offset
            this.screenOffsetX += this.velocityX * deltaTime * 60;
            this.screenOffsetY += this.velocityY * deltaTime * 60;
        }

        // Apply gravity if particle has height
        if (this.height > 0 || this.verticalVelocity !== 0) {
            this.verticalVelocity -= 15 * deltaTime * 60;
            this.height += this.verticalVelocity * deltaTime * 60;
            
            if (this.height < 0) {
                this.height = 0;
                this.verticalVelocity = 0;
            }
        }

        // Animate between frames
        if (this.sprites.length > 1) {
            this.animationTimer += deltaTime;
            if (this.animationTimer >= this.animationSpeed) {
                this.currentFrame = (this.currentFrame + 1) % this.sprites.length;
                this.animationTimer = 0;
            }
        }

        // Decay lifetime
        this.lifetime -= deltaTime;
        if (this.lifetime <= 0) {
            this.active = false;
        }
    }

    getCurrentSprite() {
        return this.sprites[this.currentFrame];
    }

    getAlpha() {
        return 1.0; // No fade for SNES style
    }
}

export class ParticleSystem {
    constructor() {
        this.particles = [];
        this.maxParticles = 100;
        
        // Track persistent drift spark particles (one per wheel)
        this.leftDriftSpark = null;
        this.rightDriftSpark = null;
        
        // Track persistent dirt particles (one per wheel + 2 trailing per wheel = 6 total)
        this.leftDirtParticle = null;
        this.rightDirtParticle = null;
        this.leftDirtTrail1 = null;
        this.leftDirtTrail2 = null;
        this.rightDirtTrail1 = null;
        this.rightDirtTrail2 = null;
    }

    update(deltaTime) {
        // Update all particles
        for (let i = this.particles.length - 1; i >= 0; i--) {
            const particle = this.particles[i];
            particle.update(deltaTime);
            
            if (!particle.active) {
                // Clean up particle references
                if (particle === this.leftDriftSpark) {
                    this.leftDriftSpark = null;
                }
                if (particle === this.rightDriftSpark) {
                    this.rightDriftSpark = null;
                }
                if (particle === this.leftDirtParticle) {
                    this.leftDirtParticle = null;
                }
                if (particle === this.rightDirtParticle) {
                    this.rightDirtParticle = null;
                }
                if (particle === this.leftDirtTrail1) {
                    this.leftDirtTrail1 = null;
                }
                if (particle === this.leftDirtTrail2) {
                    this.leftDirtTrail2 = null;
                }
                if (particle === this.rightDirtTrail1) {
                    this.rightDirtTrail1 = null;
                }
                if (particle === this.rightDirtTrail2) {
                    this.rightDirtTrail2 = null;
                }
                
                this.particles.splice(i, 1);
            }
        }
    }

    // Drift sparks - ONE particle per wheel that persists during drift
    updateDriftSparks(x, y, angle, isDrifting, driftDirection) {
        if (!isDrifting) {
            // Remove drift sparks when not drifting
            if (this.leftDriftSpark) {
                this.leftDriftSpark.active = false;
                this.leftDriftSpark = null;
            }
            if (this.rightDriftSpark) {
                this.rightDriftSpark.active = false;
                this.rightDriftSpark = null;
            }
            return;
        }

        // Wheel positions on the sprite (both at same Y level - the bottom of the sprite)
        // For Mario sprite: left wheel around x:-10, right wheel around x:10, both at y:15 (bottom)
        const baseWheelYOffset = 8; // Base distance from center toward back of sprite
        const baseLeftWheelXOffset = -7; // Left wheel X offset from center
        const baseRightWheelXOffset = 7; // Right wheel X offset from center
        
        // Adjust based on drift direction
        // Outer wheel (the one being drifted on) moves back and inward
        const driftYAdjustment = 2; // How much to push outer wheel back
        const driftXAdjustment = 2; // How much to move outer wheel inward (toward center)
        
        let leftWheelYOffset = baseWheelYOffset;
        let rightWheelYOffset = baseWheelYOffset;
        let leftWheelXOffset = baseLeftWheelXOffset;
        let rightWheelXOffset = baseRightWheelXOffset;
        
        if (driftDirection < 0) {
            // Drifting left - left wheel moves back and inward (toward center = more positive X)
            leftWheelYOffset += driftYAdjustment;
            leftWheelXOffset += driftXAdjustment; // -7 + 2 = -5 (closer to center)
        } else if (driftDirection > 0) {
            // Drifting right - right wheel moves back and inward (toward center = more negative X)
            rightWheelYOffset += driftYAdjustment;
            rightWheelXOffset -= driftXAdjustment; // 7 - 2 = 5 (closer to center)
        }

        const cos = Math.cos(angle);
        const sin = Math.sin(angle);
        
        // Transform sprite-space wheel positions to world space
        // Both wheels at same Y (bottom), different X (left/right)
        const leftWheelX = x + (leftWheelXOffset * cos - leftWheelYOffset * sin);
        const leftWheelY = y + (leftWheelXOffset * sin + leftWheelYOffset * cos);
        const rightWheelX = x + (rightWheelXOffset * cos - rightWheelYOffset * sin);
        const rightWheelY = y + (rightWheelXOffset * sin + rightWheelYOffset * cos);

        // Spark sprites (animate between spark frames)
        const sparkSprites = [
            PARTICLE_SPRITES.spark[0],
            PARTICLE_SPRITES.spark[1]
        ];

        // Create or update left drift spark (FLIPPED)
        if (!this.leftDriftSpark) {
            this.leftDriftSpark = new Particle(
                leftWheelX, 
                leftWheelY, 
                0, 0, // No velocity - stays at wheel
                sparkSprites,
                999, // Long lifetime (will be manually removed)
                0.1, // Animation speed
                true // Flip horizontally for left wheel
            );
            this.particles.push(this.leftDriftSpark);
        } else {
            // Update position to follow wheel
            this.leftDriftSpark.x = leftWheelX;
            this.leftDriftSpark.y = leftWheelY;
            this.leftDriftSpark.lifetime = 999; // Keep alive
        }

        // Create or update right drift spark (NOT FLIPPED)
        if (!this.rightDriftSpark) {
            this.rightDriftSpark = new Particle(
                rightWheelX, 
                rightWheelY, 
                0, 0,
                sparkSprites,
                999,
                0.1,
                false // No flip for right wheel
            );
            this.particles.push(this.rightDriftSpark);
        } else {
            // Update position to follow wheel
            this.rightDriftSpark.x = rightWheelX;
            this.rightDriftSpark.y = rightWheelY;
            this.rightDriftSpark.lifetime = 999;
        }
    }

    // Off-road dirt particles - ONE particle per wheel that persists + trailing particles
    updateDirtParticles(x, y, angle, isOffRoad, isJumping = false, jumpHeight = 0) {
        if (!isOffRoad || isJumping) {
            // Sequential disappearance - back to front (trail2 -> trail1 -> main)
            if (!this.dirtDisappearTimer) {
                this.dirtDisappearTimer = 0;
            }
            this.dirtDisappearTimer += 0.016; // Approximate frame time
            
            // Remove trail2 particles first (after 0.05s)
            if (this.dirtDisappearTimer > 0.05) {
                if (this.leftDirtTrail2) {
                    this.leftDirtTrail2.active = false;
                    this.leftDirtTrail2 = null;
                }
                if (this.rightDirtTrail2) {
                    this.rightDirtTrail2.active = false;
                    this.rightDirtTrail2 = null;
                }
            }
            
            // Remove trail1 particles next (after 0.1s)
            if (this.dirtDisappearTimer > 0.1) {
                if (this.leftDirtTrail1) {
                    this.leftDirtTrail1.active = false;
                    this.leftDirtTrail1 = null;
                }
                if (this.rightDirtTrail1) {
                    this.rightDirtTrail1.active = false;
                    this.rightDirtTrail1 = null;
                }
            }
            
            // Remove main particles last (after 0.15s)
            if (this.dirtDisappearTimer > 0.15) {
                if (this.leftDirtParticle) {
                    this.leftDirtParticle.active = false;
                    this.leftDirtParticle = null;
                }
                if (this.rightDirtParticle) {
                    this.rightDirtParticle.active = false;
                    this.rightDirtParticle = null;
                }
                // Reset timers after full disappearance
                this.dirtParticleTimer = 0;
                this.dirtDisappearTimer = 0;
            }
            return;
        }
        
        // Reset disappear timer when off-road
        this.dirtDisappearTimer = 0;

        // Use same wheel positioning logic as drift sparks
        const baseWheelYOffset = 8;
        const leftWheelXOffset = -7;
        const rightWheelXOffset = 7;
        
        const cos = Math.cos(angle);
        const sin = Math.sin(angle);
        
        // Main wheel positions
        const leftWheelX = x + (leftWheelXOffset * cos - baseWheelYOffset * sin);
        const leftWheelY = y + (leftWheelXOffset * sin + baseWheelYOffset * cos);
        const rightWheelX = x + (rightWheelXOffset * cos - baseWheelYOffset * sin);
        const rightWheelY = y + (rightWheelXOffset * sin + baseWheelYOffset * cos);
        
        // Trailing positions (behind wheels) - bunched up closer
        const trail1Offset = baseWheelYOffset + 2;  // 2 pixels behind
        const trail2Offset = baseWheelYOffset + 4;  // 4 pixels behind
        
        const leftTrail1X = x + (leftWheelXOffset * cos - trail1Offset * sin);
        const leftTrail1Y = y + (leftWheelXOffset * sin + trail1Offset * cos);
        const leftTrail2X = x + (leftWheelXOffset * cos - trail2Offset * sin);
        const leftTrail2Y = y + (leftWheelXOffset * sin + trail2Offset * cos);
        
        const rightTrail1X = x + (rightWheelXOffset * cos - trail1Offset * sin);
        const rightTrail1Y = y + (rightWheelXOffset * sin + trail1Offset * cos);
        const rightTrail2X = x + (rightWheelXOffset * cos - trail2Offset * sin);
        const rightTrail2Y = y + (rightWheelXOffset * sin + trail2Offset * cos);

        // Dirt sprites (animate between dirt1 and dirt2)
        const dirtSprites = [
            PARTICLE_SPRITES.dirt[0],
            PARTICLE_SPRITES.dirt[1]
        ];

        // Sequential appearance delay (in seconds)
        if (!this.dirtParticleTimer) {
            this.dirtParticleTimer = 0;
        }
        this.dirtParticleTimer += 0.016; // Approximate frame time
        
        // Bounce offset for particles based on jump height
        // Main particles and trail1 bounce more, trail2 bounces less
        const mainBounceAmount = jumpHeight * 0.8; // Main particles bounce 80% with player
        const trail1BounceAmount = jumpHeight * 0.5; // Trail1 bounces 50% with player
        const trail2BounceAmount = 0; // Trail2 doesn't bounce

        // Create or update left dirt particle (main) - appears immediately
        if (!this.leftDirtParticle) {
            this.leftDirtParticle = new Particle(
                leftWheelX, 
                leftWheelY, 
                0, 0,
                dirtSprites,
                999,
                0.05 // Much faster animation
            );
            this.particles.push(this.leftDirtParticle);
        } else {
            this.leftDirtParticle.x = leftWheelX;
            this.leftDirtParticle.y = leftWheelY;
            this.leftDirtParticle.z = mainBounceAmount; // Add bounce
            this.leftDirtParticle.lifetime = 999;
        }

        // Create or update right dirt particle (main) - appears immediately
        if (!this.rightDirtParticle) {
            this.rightDirtParticle = new Particle(
                rightWheelX, 
                rightWheelY, 
                0, 0,
                dirtSprites,
                999,
                0.05 // Much faster animation
            );
            this.particles.push(this.rightDirtParticle);
        } else {
            this.rightDirtParticle.x = rightWheelX;
            this.rightDirtParticle.y = rightWheelY;
            this.rightDirtParticle.z = mainBounceAmount; // Add bounce
            this.rightDirtParticle.lifetime = 999;
        }

        // Create or update left trail 1 - appears after 0.05 seconds
        if (this.dirtParticleTimer > 0.05) {
            if (!this.leftDirtTrail1) {
                this.leftDirtTrail1 = new Particle(
                    leftTrail1X, 
                    leftTrail1Y, 
                    0, 0,
                    dirtSprites,
                    999,
                    0.06 // Fast animation with variation
                );
                this.particles.push(this.leftDirtTrail1);
            } else {
                this.leftDirtTrail1.x = leftTrail1X;
                this.leftDirtTrail1.y = leftTrail1Y;
                this.leftDirtTrail1.z = trail1BounceAmount; // Add bounce (less than main)
                this.leftDirtTrail1.lifetime = 999;
            }
        }

        // Create or update left trail 2 - appears after 0.1 seconds
        if (this.dirtParticleTimer > 0.1) {
            if (!this.leftDirtTrail2) {
                this.leftDirtTrail2 = new Particle(
                    leftTrail2X, 
                    leftTrail2Y, 
                    0, 0,
                    dirtSprites,
                    999,
                    0.04 // Fast animation with variation
                );
                this.particles.push(this.leftDirtTrail2);
            } else {
                this.leftDirtTrail2.x = leftTrail2X;
                this.leftDirtTrail2.y = leftTrail2Y;
                this.leftDirtTrail2.z = trail2BounceAmount; // No bounce
                this.leftDirtTrail2.lifetime = 999;
            }
        }

        // Create or update right trail 1 - appears after 0.05 seconds
        if (this.dirtParticleTimer > 0.05) {
            if (!this.rightDirtTrail1) {
                this.rightDirtTrail1 = new Particle(
                    rightTrail1X, 
                    rightTrail1Y, 
                    0, 0,
                    dirtSprites,
                    999,
                    0.055 // Fast animation with variation
                );
                this.particles.push(this.rightDirtTrail1);
            } else {
                this.rightDirtTrail1.x = rightTrail1X;
                this.rightDirtTrail1.y = rightTrail1Y;
                this.rightDirtTrail1.z = trail1BounceAmount; // Add bounce (less than main)
                this.rightDirtTrail1.lifetime = 999;
            }
        }

        // Create or update right trail 2 - appears after 0.1 seconds
        if (this.dirtParticleTimer > 0.1) {
            if (!this.rightDirtTrail2) {
                this.rightDirtTrail2 = new Particle(
                    rightTrail2X, 
                    rightTrail2Y, 
                    0, 0,
                    dirtSprites,
                    999,
                    0.045 // Fast animation with variation
                );
                this.particles.push(this.rightDirtTrail2);
            } else {
                this.rightDirtTrail2.x = rightTrail2X;
                this.rightDirtTrail2.y = rightTrail2Y;
                this.rightDirtTrail2.z = trail2BounceAmount; // No bounce
                this.rightDirtTrail2.lifetime = 999;
            }
        }
    }

    addParticle(particle) {
        this.particles.push(particle);
        
        // Remove oldest particles if we exceed max
        while (this.particles.length > this.maxParticles) {
            const removed = this.particles.shift();
            if (removed === this.leftDriftSpark) {
                this.leftDriftSpark = null;
            }
            if (removed === this.rightDriftSpark) {
                this.rightDriftSpark = null;
            }
            if (removed === this.leftDirtParticle) {
                this.leftDirtParticle = null;
            }
            if (removed === this.rightDirtParticle) {
                this.rightDirtParticle = null;
            }
        }
    }

    getParticles() {
        return this.particles;
    }

    clear() {
        this.particles = [];
        this.leftDriftSpark = null;
        this.rightDriftSpark = null;
        this.leftDirtParticle = null;
        this.rightDirtParticle = null;
    }
}
