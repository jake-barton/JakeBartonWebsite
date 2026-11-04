export class GreenShell {
    constructor(x, y, angle, speed = 1.7, placedBy = null) {
        this.x = x;
        this.y = y;
        this.angle = angle; // Direction the shell is traveling
        this.speed = speed;
        this.height = 0;
        this.active = true;
        this.placedBy = placedBy;
        
        // Immunity timer - don't hit the thrower immediately
        this.immunityTimer = 0.3; // 0.3 seconds of immunity
        
        // Animation state
        this.animationTime = 0;
        this.currentFrame = 0;
        this.frameSpeed = 0.05; // Speed of rotation animation
        
        // Hit state
        this.isHit = false;
        this.hitTimer = 0;
        this.hitDuration = 0.5; // Time to show upside down and disappear
        this.bounceHeight = 0;
        this.bounceSpeed = 0;
        this.maxBounceHeight = 5; // Small bounce
        
        // Wall bounce tracking
        this.bounceCount = 0;
        this.maxBounces = 3; // Disappear after 3 bounces
    }
    
    update(deltaTime) {
        if (this.isHit) {
            // Handle hit animation
            this.hitTimer += deltaTime;
            
            // Small bounce
            this.bounceSpeed -= 20 * deltaTime; // Gravity
            this.bounceHeight += this.bounceSpeed * deltaTime;
            
            if (this.bounceHeight < 0) {
                this.bounceHeight = 0;
                this.bounceSpeed = 0;
            }
            
            // Disappear after hit duration
            if (this.hitTimer >= this.hitDuration) {
                this.active = false;
            }
            return;
        }
        
        // Update immunity timer
        if (this.immunityTimer > 0) {
            this.immunityTimer -= deltaTime;
        }
        
        // Move forward in the direction it was thrown
        this.x += Math.sin(this.angle) * this.speed;
        this.y += Math.cos(this.angle) * this.speed;
        
        // Update rotation animation
        this.animationTime += deltaTime;
        if (this.animationTime >= this.frameSpeed) {
            this.currentFrame = (this.currentFrame + 1) % 3; // 3 frames of rotation
            this.animationTime = 0;
        }
    }
    
    hit() {
        if (!this.isHit) {
            this.isHit = true;
            this.hitTimer = 0;
            this.currentFrame = 0; // First frame
            this.bounceHeight = 0;
            this.bounceSpeed = 8; // Initial upward velocity for small bounce
        }
    }
    
    getCurrentFrame() {
        return this.currentFrame;
    }
    
    // Check for wall collision and bounce
    checkWallBounce(maskData) {
        if (this.isHit) return; // Don't bounce if already hit
        
        // Check position ahead of shell
        const checkDistance = 10;
        const checkX = this.x + Math.sin(this.angle) * checkDistance;
        const checkY = this.y + Math.cos(this.angle) * checkDistance;
        
        // Get pixel type
        const index = (Math.floor(checkY) * maskData.width + Math.floor(checkX)) * 4;
        const r = maskData.data[index];
        const g = maskData.data[index + 1];
        const b = maskData.data[index + 2];
        
        // Check if it's a wall (blue)
        const isWall = (r < 50 && g < 50 && b > 200);
        
        if (isWall) {
            // Bounce by reversing angle (simple 180 degree bounce)
            this.angle = (this.angle + Math.PI) % (Math.PI * 2);
            
            // Move back a bit to prevent getting stuck in wall
            this.x -= Math.sin(this.angle) * 5;
            this.y -= Math.cos(this.angle) * 5;
            
            this.bounceCount++;
            
            // Disappear after max bounces
            if (this.bounceCount >= this.maxBounces) {
                this.active = false;
            }
        }
    }
}
