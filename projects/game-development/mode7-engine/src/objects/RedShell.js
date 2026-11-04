export class RedShell {
    constructor(x, y, angle, waypoints, maskData, targetRacer, placedBy = null) {
        this.x = x;
        this.y = y;
        this.angle = angle; // Initial direction
        this.speed = 2.0; // Slightly faster than green shell
        this.height = 0;
        this.active = true;
        this.placedBy = placedBy;
        
        // Waypoint tracking
        this.waypoints = waypoints;
        this.maskData = maskData;
        this.currentWaypointIndex = this.findClosestWaypointIndex(x, y, angle);
        this.targetRacer = targetRacer; // The racer ahead to target
        
        // Homing behavior
        this.homingRange = 80; // Distance at which to switch from waypoint to direct homing
        this.isHoming = false; // True when close enough to go directly at target
        
        // Immunity timer - don't hit the thrower immediately
        this.immunityTimer = 0.3; // 0.3 seconds of immunity
        
        // Animation state
        this.animationTime = 0;
        this.currentFrame = 0;
        this.frameSpeed = 0.05; // Speed of rotation animation
        
        // Hit/broken state
        this.isBroken = false;
        this.brokenTimer = 0;
        this.brokenDuration = 0.5; // Time to show upside down and disappear
        this.bounceHeight = 0;
        this.bounceSpeed = 0;
        this.flipRotation = 0; // Rotation for flipping animation
    }
    
    update(deltaTime) {
        if (this.isBroken) {
            // Handle broken animation
            this.brokenTimer += deltaTime;
            
            // Flip and bounce
            this.flipRotation += Math.PI * 4 * deltaTime; // Spin while falling
            this.bounceSpeed -= 20 * deltaTime; // Gravity
            this.bounceHeight += this.bounceSpeed * deltaTime;
            
            if (this.bounceHeight < 0) {
                this.bounceHeight = 0;
                this.bounceSpeed = 0;
            }
            
            // Disappear after broken duration
            if (this.brokenTimer >= this.brokenDuration) {
                this.active = false;
            }
            return;
        }
        
        // Update immunity timer
        if (this.immunityTimer > 0) {
            this.immunityTimer -= deltaTime;
        }
        
        // Check distance to target
        if (this.targetRacer && this.targetRacer.active) {
            const dx = this.targetRacer.x - this.x;
            const dy = this.targetRacer.y - this.y;
            const distanceToTarget = Math.sqrt(dx * dx + dy * dy);
            
            // Switch to homing mode when close enough
            if (distanceToTarget < this.homingRange) {
                this.isHoming = true;
            }
        }
        
        // Determine target angle
        let targetAngle;
        
        if (this.isHoming && this.targetRacer && this.targetRacer.active) {
            // Direct homing - go straight at target
            const dx = this.targetRacer.x - this.x;
            const dy = this.targetRacer.y - this.y;
            targetAngle = Math.atan2(dx, dy);
        } else {
            // Waypoint following
            const targetWaypoint = this.getTargetWaypoint();
            const dx = targetWaypoint.x - this.x;
            const dy = targetWaypoint.y - this.y;
            targetAngle = Math.atan2(dx, dy);
            
            // Update waypoint index if close enough
            const distanceToWaypoint = Math.sqrt(dx * dx + dy * dy);
            if (distanceToWaypoint < 30) {
                this.currentWaypointIndex = (this.currentWaypointIndex + 1) % this.waypoints.length;
            }
        }
        
        // Smoothly turn towards target angle
        let angleDiff = targetAngle - this.angle;
        while (angleDiff > Math.PI) angleDiff -= 2 * Math.PI;
        while (angleDiff < -Math.PI) angleDiff += 2 * Math.PI;
        
        // Turn rate - red shells turn pretty quickly
        const maxTurnRate = Math.PI * 2 * deltaTime; // Can turn 360° per second
        if (Math.abs(angleDiff) < maxTurnRate) {
            this.angle = targetAngle;
        } else {
            this.angle += Math.sign(angleDiff) * maxTurnRate;
        }
        
        // Normalize angle
        while (this.angle > Math.PI * 2) this.angle -= Math.PI * 2;
        while (this.angle < 0) this.angle += Math.PI * 2;
        
        // Move forward in the current direction
        this.x += Math.sin(this.angle) * this.speed;
        this.y += Math.cos(this.angle) * this.speed;
        
        // Update rotation animation
        this.animationTime += deltaTime;
        if (this.animationTime >= this.frameSpeed) {
            this.currentFrame = (this.currentFrame + 1) % 3; // 3 frames of rotation
            this.animationTime = 0;
        }
    }
    
    getTargetWaypoint() {
        // Look ahead a few waypoints for smoother tracking
        const lookAhead = 3;
        const targetIndex = (this.currentWaypointIndex + lookAhead) % this.waypoints.length;
        return this.waypoints[targetIndex];
    }
    
    findClosestWaypointIndex(x, y, angle) {
        let closestIndex = 0;
        let closestDistance = Infinity;
        
        for (let i = 0; i < this.waypoints.length; i++) {
            const wp = this.waypoints[i];
            const dx = wp.x - x;
            const dy = wp.y - y;
            const distance = Math.sqrt(dx * dx + dy * dy);
            
            const angleToWaypoint = Math.atan2(dx, dy);
            let angleDiff = angleToWaypoint - angle;
            while (angleDiff > Math.PI) angleDiff -= 2 * Math.PI;
            while (angleDiff < -Math.PI) angleDiff += 2 * Math.PI;
            
            // Prefer waypoints ahead
            const isAhead = Math.abs(angleDiff) < Math.PI / 2;
            
            if (isAhead && distance < closestDistance) {
                closestDistance = distance;
                closestIndex = i;
            }
        }
        
        return closestIndex;
    }
    
    break() {
        if (!this.isBroken) {
            this.isBroken = true;
            this.brokenTimer = 0;
            this.currentFrame = 0; // First frame
            this.bounceHeight = 0;
            this.bounceSpeed = 8; // Initial upward velocity for bounce
            this.flipRotation = 0;
        }
    }
    
    getCurrentFrame() {
        return this.currentFrame;
    }
    
    getFlipRotation() {
        return this.flipRotation;
    }
    
    // Check for wall collision - red shells break on walls
    checkWallCollision() {
        if (this.isBroken) return false;
        
        // Check position ahead of shell
        const checkDistance = 10;
        const checkX = this.x + Math.sin(this.angle) * checkDistance;
        const checkY = this.y + Math.cos(this.angle) * checkDistance;
        
        // Bounds check
        if (checkX < 0 || checkX >= this.maskData.width || 
            checkY < 0 || checkY >= this.maskData.height) {
            this.break();
            return true;
        }
        
        // Get pixel type
        const index = (Math.floor(checkY) * this.maskData.width + Math.floor(checkX)) * 4;
        const r = this.maskData.data[index];
        const g = this.maskData.data[index + 1];
        const b = this.maskData.data[index + 2];
        
        // Check if it's a wall (blue)
        const isWall = (r < 50 && g < 50 && b > 200);
        
        if (isWall) {
            this.break();
            return true;
        }
        
        return false;
    }
}
